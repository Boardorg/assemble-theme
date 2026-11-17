/**
 * [assemble_logo_strip limit="N"]: hide the logos past N and let the button
 * under the list show them all, or fewer again (Our Product, Cale 2026-11-17).
 * The button ships hidden, so without this script every logo shows.
 */
( function () {
	'use strict';

	document.querySelectorAll( '.logo-strip-toggle' ).forEach( function ( button ) {
		var list = document.getElementById( button.getAttribute( 'aria-controls' ) );
		var limit = list ? parseInt( list.getAttribute( 'data-limit' ), 10 ) : 0;
		if ( ! list || ! limit ) {
			return;
		}
		var extra = Array.prototype.slice.call( list.children, limit );

		function set( open ) {
			extra.forEach( function ( item ) {
				item.hidden = ! open;
			} );
			button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			button.textContent = button.getAttribute( open ? 'data-close' : 'data-open' );
		}

		set( false );
		button.hidden = false;
		button.addEventListener( 'click', function () {
			var open = 'true' !== button.getAttribute( 'aria-expanded' );
			set( open );
			if ( open && extra[ 0 ] ) {
				extra[ 0 ].querySelector( 'a' ).focus( { preventScroll: true } );
			}
		} );
	} );
}() );
