/**
 * report-charts: a Field Report's "Poll results" with three or more charts
 * shows one chart at a time behind a row of pills. The server renders every
 * chart stacked, so without this script (and in print) they all still read.
 */
( function () {
	'use strict';

	document.querySelectorAll( '[data-chart-switcher]' ).forEach( function ( root ) {
		var tabs = root.querySelector( '.chart-tabs' );
		var buttons = Array.prototype.slice.call( root.querySelectorAll( '[data-chart-target]' ) );
		var panels = root.querySelectorAll( '.report-chart' );

		if ( ! tabs || ! buttons.length ) {
			return;
		}

		function open( id ) {
			panels.forEach( function ( panel ) {
				panel.hidden = panel.id !== id;
			} );
			buttons.forEach( function ( button ) {
				var active = button.getAttribute( 'data-chart-target' ) === id;
				button.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
				// On phones the pills swipe sideways; keep the chosen one in view.
				if ( active && tabs.scrollWidth > tabs.clientWidth ) {
					tabs.scrollLeft = Math.max( 0, button.offsetLeft - tabs.offsetLeft - ( tabs.clientWidth - button.offsetWidth ) / 2 );
				}
			} );
		}

		buttons.forEach( function ( button, index ) {
			button.addEventListener( 'click', function () {
				open( button.getAttribute( 'data-chart-target' ) );
			} );

			// Left and right arrows step through the charts.
			button.addEventListener( 'keydown', function ( event ) {
				var step = { ArrowRight: 1, ArrowLeft: -1 }[ event.key ];
				if ( ! step ) {
					return;
				}
				event.preventDefault();
				var next = buttons[ ( index + step + buttons.length ) % buttons.length ];
				next.focus();
				open( next.getAttribute( 'data-chart-target' ) );
			} );
		} );

		tabs.hidden = false;
		open( buttons[ 0 ].getAttribute( 'data-chart-target' ) );
	} );
}() );
