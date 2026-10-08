/**
 * article-hero: the share row's "Copy link" button shows "Copied" for 1.4s.
 */
( function () {
	document.querySelectorAll( '[data-copy-link]' ).forEach( function ( button ) {
		var label = button.querySelector( 'span' );
		var original = label ? label.textContent : '';
		var timer;

		button.addEventListener( 'click', function () {
			var url = button.getAttribute( 'data-copy-link' ) || window.location.href;
			var done = function () {
				if ( ! label ) {
					return;
				}
				label.textContent = button.getAttribute( 'data-copied-label' ) || 'Copied';
				button.classList.add( 'is-copied' );
				clearTimeout( timer );
				timer = setTimeout( function () {
					label.textContent = original;
					button.classList.remove( 'is-copied' );
				}, 1400 );
			};

			// Older copy path: plain-http hosts, and browsers that refuse the Clipboard API.
			var fallback = function () {
				var field = document.createElement( 'textarea' );
				field.value = url;
				field.setAttribute( 'readonly', '' );
				field.style.position = 'absolute';
				field.style.left = '-9999px';
				document.body.appendChild( field );
				field.select();
				try {
					if ( document.execCommand( 'copy' ) ) {
						done();
					}
				} catch ( e ) {}
				document.body.removeChild( field );
			};

			if ( navigator.clipboard && window.isSecureContext ) {
				navigator.clipboard.writeText( url ).then( done, fallback );
			} else {
				fallback();
			}
		} );
	} );
}() );
