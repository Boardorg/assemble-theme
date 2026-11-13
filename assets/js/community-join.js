/**
 * Community pages: preselect this Board in the apply-to-join form.
 *
 * The apply band (#apply) embeds board.org's own join form, a HubSpot V4 form
 * (.hs-form-frame). Its first question is a radio, "Please select the group
 * you're most interested in"; the band names the field and this Board's option
 * in data-assemble-join-field / -value. When HubSpot reports the form ready,
 * set the radio, so the visitor starts on the Board whose page they're on and
 * can still pick another. This script loads before HubSpot's loader, so it
 * never misses the event. If HubSpot's API isn't there, the form works as is.
 */
( function () {
	'use strict';

	window.addEventListener( 'hs-form-event:on-ready', function ( event ) {
		var api = window.HubSpotFormsV4;
		if ( ! api || typeof api.getFormFromEvent !== 'function' ) {
			return;
		}

		var form = api.getFormFromEvent( event );
		if ( ! form || typeof form.setFieldValue !== 'function' ) {
			return;
		}

		var frames = document.querySelectorAll( '.hs-form-frame[data-assemble-join-value]' );
		Array.prototype.forEach.call( frames, function ( frame ) {
			var field = frame.getAttribute( 'data-assemble-join-field' );
			var value = frame.getAttribute( 'data-assemble-join-value' );
			var id    = frame.getAttribute( 'data-form-id' );

			if ( field && value && ( ! form.getFormId || form.getFormId() === id ) ) {
				form.setFieldValue( '0-1/' + field, value );
			}
		} );
	} );
}() );
