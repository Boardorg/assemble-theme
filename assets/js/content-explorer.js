/**
 * Module: content-explorer. Practice-area pills show that area's Community row
 * and open its first panel; Community pills open their panel. The server
 * renders the opening state, so without this script the page still reads.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-content-explorer]' ).forEach( function ( root ) {
		var areaButtons = root.querySelectorAll( '[data-explorer-area]' );
		var groups = root.querySelectorAll( '[data-explorer-group]' );
		var communityButtons = root.querySelectorAll( '.topic-row-secondary [data-explorer-target]' );
		var panels = root.querySelectorAll( '.tab-panel' );

		function press( buttons, active ) {
			buttons.forEach( function ( button ) {
				button.setAttribute( 'aria-pressed', button === active ? 'true' : 'false' );
			} );
		}

		function openPanel( id ) {
			panels.forEach( function ( panel ) {
				panel.hidden = panel.id !== id;
			} );
			press( communityButtons, root.querySelector( '.topic-row-secondary [data-explorer-target="' + id + '"]' ) );
		}

		areaButtons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var area = button.getAttribute( 'data-explorer-area' );
				press( areaButtons, button );
				groups.forEach( function ( group ) {
					group.hidden = group.getAttribute( 'data-explorer-group' ) !== area;
				} );
				openPanel( button.getAttribute( 'data-explorer-target' ) );
			} );
		} );

		communityButtons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				openPanel( button.getAttribute( 'data-explorer-target' ) );
			} );
		} );

		// Deep links, only where the root asks for them: #aeo opens panel <prefix>aeo
		// (the Communities index; data-explorer-hash="find-").
		var prefix = root.getAttribute( 'data-explorer-hash' );
		function openFromHash() {
			var slug = window.location.hash.slice( 1 );
			var panel = slug && document.getElementById( prefix + slug );
			if ( ! panel || ! root.contains( panel ) || ! panel.classList.contains( 'tab-panel' ) ) {
				return;
			}
			var area = panel.getAttribute( 'data-area' );
			var areaButton = root.querySelector( '[data-explorer-area="' + area + '"]' );
			if ( areaButton ) {
				areaButton.click();
			}
			openPanel( panel.id );
			root.scrollIntoView();
		}
		if ( prefix ) {
			openFromHash();
			window.addEventListener( 'hashchange', openFromHash );
		}
	} );
}() );
