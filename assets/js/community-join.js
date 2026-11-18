/**
 * Community pages: the apply-to-join form (template-parts/community/join-form.php).
 *
 * Three steps in the site's own markup: the community (this Board comes
 * checked), the membership criteria, then contact details. Each Next checks
 * its step. Submit posts the answers as JSON to HubSpot's public submissions
 * endpoint for board.org's "Apply to join form (new)" (data-endpoint), so the
 * entry lands in that form as before. Field names are "<objectTypeId>/<name>".
 * The form is hidden until this script runs; without it the <noscript> button
 * shows instead.
 */
( function () {
	'use strict';

	var EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

	function cookie( name ) {
		var match = document.cookie.match( new RegExp( '(?:^|; )' + name + '=([^;]*)' ) );
		return match ? decodeURIComponent( match[ 1 ] ) : '';
	}

	function setup( form ) {
		var steps    = form.querySelectorAll( '[data-join-step]' );
		var number   = form.querySelector( '[data-join-step-number]' );
		var progress = form.querySelector( '.join-form-progress' );
		var done     = form.querySelector( '[data-join-done]' );
		var formErr  = form.querySelector( '[data-join-error="form"]' );
		var submit   = form.querySelector( '[data-join-submit]' );
		var current  = 0;

		function errorFor( name ) {
			return form.querySelector( '[data-join-error="' + name + '"]' );
		}

		function show( index, focus ) {
			Array.prototype.forEach.call( steps, function ( step, i ) {
				step.hidden = i !== index;
			} );
			current = index;
			number.textContent = String( index + 1 );
			if ( focus ) {
				var heading = steps[ index ].querySelector( '.join-step-heading' );
				heading.focus( { preventScroll: true } );
				form.scrollIntoView( { block: 'nearest', behavior: 'smooth' } );
			}
		}

		function setError( name, message, input ) {
			var el = errorFor( name );
			if ( el ) {
				el.textContent = message || el.textContent;
				el.hidden = ! message;
			}
			if ( input ) {
				input.setAttribute( 'aria-invalid', message ? 'true' : 'false' );
				input.closest( '.join-field' ).classList.toggle( 'has-error', !! message );
				if ( message && el ) {
					input.setAttribute( 'aria-describedby', el.id );
				} else {
					input.removeAttribute( 'aria-describedby' );
				}
			}
		}

		function fieldMessage( input ) {
			var value = input.value.trim();
			if ( input.required && ! value ) {
				return 'Please fill in this field.';
			}
			if ( 'email' === input.type && value && ! EMAIL.test( value ) ) {
				return 'Enter an email address like name@company.com.';
			}
			if ( 'tel' === input.type && value ) {
				var digits = value.replace( /\D/g, '' ).length;
				if ( digits < 7 || digits > 20 ) {
					return 'Enter a phone number with 7 to 20 digits.';
				}
			}
			return '';
		}

		// Check one step; show its errors and focus the first one. True if it passes.
		function check( step ) {
			var first = null;

			Array.prototype.forEach.call( step.querySelectorAll( '[data-join-required]' ), function ( group ) {
				var name   = group.getAttribute( 'data-join-required' );
				var picked = group.querySelector( 'input:checked' );
				setError( name, picked ? '' : errorFor( name ).textContent );
				if ( ! picked && ! first ) {
					first = group.querySelector( 'input' );
				}
			} );

			Array.prototype.forEach.call( step.querySelectorAll( '.join-field .field-input' ), function ( input ) {
				var message = fieldMessage( input );
				setError( input.name, message, input );
				if ( message && ! first ) {
					first = input;
				}
			} );

			if ( first ) {
				first.focus();
			}
			return ! first;
		}

		form.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '[data-join-next]' ) ) {
				if ( check( steps[ current ] ) ) {
					show( current + 1, true );
				}
			} else if ( event.target.closest( '[data-join-back]' ) ) {
				show( current - 1, true );
			}
		} );

		// Clear a radio group's error as soon as something is picked.
		form.addEventListener( 'change', function ( event ) {
			var group = event.target.closest( '[data-join-required]' );
			if ( group ) {
				setError( group.getAttribute( 'data-join-required' ), '' );
			}
		} );

		// Re-check a field on blur once it has been flagged.
		form.addEventListener( 'focusout', function ( event ) {
			var input = event.target;
			if ( input.classList && input.classList.contains( 'field-input' ) && 'true' === input.getAttribute( 'aria-invalid' ) ) {
				setError( input.name, fieldMessage( input ), input );
			}
		} );

		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();
			formErr.hidden = true;

			if ( current !== steps.length - 1 ) {
				if ( check( steps[ current ] ) ) {
					show( current + 1, true );
				}
				return;
			}
			if ( ! check( steps[ current ] ) ) {
				return;
			}

			var fields = [];
			Array.prototype.forEach.call( form.elements, function ( el ) {
				if ( ! el.name || ( 'radio' === el.type && ! el.checked ) ) {
					return;
				}
				var value = el.value.trim();
				if ( '' === value ) {
					return;
				}
				var parts = el.name.split( '/' );
				fields.push( { objectTypeId: parts[ 0 ], name: parts[ 1 ], value: value } );
			} );

			var context = { pageUri: window.location.href, pageName: document.title };
			var hutk    = cookie( 'hubspotutk' );
			if ( hutk ) {
				context.hutk = hutk;
			}

			submit.disabled = true;
			submit.textContent = 'Sending…';

			fetch( form.getAttribute( 'data-endpoint' ), {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify( { fields: fields, context: context } ),
			} )
				.then( function ( response ) {
					if ( response.ok ) {
						return null;
					}
					return response.json().catch( function () {
						return {};
					} );
				} )
				.then( function ( failure ) {
					if ( ! failure ) {
						Array.prototype.forEach.call( steps, function ( step ) {
							step.hidden = true;
						} );
						progress.hidden = true;
						done.hidden = false;
						done.focus( { preventScroll: true } );
						form.scrollIntoView( { block: 'nearest', behavior: 'smooth' } );
						return;
					}

					var errors = ( failure.errors || [] ).map( function ( e ) {
						return e.errorType || '';
					} );
					if ( errors.indexOf( 'INVALID_EMAIL' ) !== -1 || errors.indexOf( 'BLOCKED_EMAIL' ) !== -1 ) {
						var email = form.querySelector( '[name="0-1/email"]' );
						setError( email.name, 'Please use your work email address.', email );
						email.focus();
					} else {
						throw new Error( 'rejected' );
					}
				} )
				.catch( function () {
					var fallback = form.getAttribute( 'data-fallback' );
					formErr.innerHTML = '';
					formErr.appendChild( document.createTextNode( 'Something went wrong sending the form. Please try again, or ' ) );
					var link = document.createElement( 'a' );
					link.href = fallback || '/contact-us/';
					link.textContent = 'contact us';
					formErr.appendChild( link );
					formErr.appendChild( document.createTextNode( '.' ) );
					formErr.hidden = false;
				} )
				.then( function () {
					submit.disabled = false;
					submit.textContent = 'Submit';
				} );
		} );

		form.hidden = false;
		show( 0, false );
	}

	Array.prototype.forEach.call( document.querySelectorAll( 'form.join-form[data-endpoint]' ), setup );
}() );
