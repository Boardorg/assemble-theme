/**
 * For You: "Load more" appends the next page's feed rows in place instead of
 * leaving the page (Kristen, 2026-11-13). Without JS the button is a plain link
 * to the next page.
 */
( function () {
	'use strict';

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '.feed-load-more' );
		if ( ! button || ! window.fetch || ! window.DOMParser ) {
			return;
		}

		var lists = document.querySelectorAll( '.feed-list' );
		var list = lists[ lists.length - 1 ];
		if ( ! list ) {
			return;
		}

		event.preventDefault();
		button.setAttribute( 'aria-busy', 'true' );

		fetch( button.href, { credentials: 'same-origin' } )
			.then( function ( response ) {
				if ( ! response.ok ) {
					throw new Error( response.status );
				}
				return response.text();
			} )
			.then( function ( html ) {
				var doc = new DOMParser().parseFromString( html, 'text/html' );
				var rows = doc.querySelectorAll( '.feed-list .feed-row' );
				var first = null;

				rows.forEach( function ( row ) {
					var node = document.importNode( row, true );
					list.appendChild( node );
					first = first || node;
				} );

				var next = doc.querySelector( '.feed-load-more' );
				if ( next ) {
					button.href = next.href;
					button.removeAttribute( 'aria-busy' );
				} else {
					button.remove();
				}

				// Move focus to the first new row's link for keyboard and screen-reader users.
				var link = first && first.querySelector( 'h3 a' );
				if ( link ) {
					link.focus( { preventScroll: true } );
				}
			} )
			.catch( function () {
				// Fall back to the next page itself.
				window.location.href = button.href;
			} );
	} );
}() );
