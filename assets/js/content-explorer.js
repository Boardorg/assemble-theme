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
	} );
}() );
