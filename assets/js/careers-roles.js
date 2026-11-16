/**
 * Careers: ADP's open-roles widget sets its own design tokens inside its shadow
 * DOM, so the values in careers-roles.css never reach the buttons and links.
 * Copy them into the widget's shadow root once it has rendered (Kristen,
 * 2026-11-13). If ADP changes its markup this does nothing and the widget keeps
 * ADP's own colours.
 */
( function () {
	'use strict';

	var TAG = 'recruitment-current-openings';

	function tokenNames() {
		var names = [];
		Array.prototype.forEach.call( document.styleSheets, function ( sheet ) {
			var rules;
			try {
				rules = sheet.cssRules;
			} catch ( e ) {
				return; // Another origin's stylesheet.
			}
			Array.prototype.forEach.call( rules || [], function ( rule ) {
				if ( rule.selectorText === TAG ) {
					Array.prototype.forEach.call( rule.style, function ( name ) {
						if ( 0 === name.indexOf( '--' ) ) {
							names.push( name );
						}
					} );
				}
			} );
		} );
		return names;
	}

	function apply( widget, tries ) {
		var root = widget.shadowRoot;
		if ( ! root || ! ( 'adoptedStyleSheets' in root ) || typeof CSSStyleSheet === 'undefined' ) {
			if ( tries < 40 ) {
				window.setTimeout( function () {
					apply( widget, tries + 1 );
				}, 250 );
			}
			return;
		}

		var computed = window.getComputedStyle( widget );
		var css = tokenNames().map( function ( name ) {
			return name + ':' + computed.getPropertyValue( name ).trim() + ' !important';
		} ).join( ';' );
		if ( ! css ) {
			return;
		}

		try {
			var sheet = new CSSStyleSheet();
			sheet.replaceSync( '*{' + css + '}' );
			root.adoptedStyleSheets = root.adoptedStyleSheets.concat( [ sheet ] );
		} catch ( e ) {
			// Constructable stylesheets unsupported: ADP's colours stay.
		}
	}

	if ( ! window.customElements ) {
		return;
	}
	window.customElements.whenDefined( TAG ).then( function () {
		Array.prototype.forEach.call( document.querySelectorAll( TAG ), function ( widget ) {
			apply( widget, 0 );
		} );
	} );
}() );
