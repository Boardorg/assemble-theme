/**
 * Module: public-masthead. Opens and closes the phone/tablet drawer.
 * Escape closes it and returns focus to the button; widening past 980px closes it.
 * The signed-in Account menu (a <details>) closes on Escape or a click outside it.
 */
( function () {
	const menu = document.querySelector( '.account-menu' );
	if ( menu ) {
		document.addEventListener( 'click', ( event ) => {
			if ( menu.open && ! menu.contains( event.target ) ) {
				menu.open = false;
			}
		} );
		document.addEventListener( 'keydown', ( event ) => {
			if ( event.key === 'Escape' && menu.open ) {
				menu.open = false;
				menu.querySelector( 'summary' ).focus();
			}
		} );
	}

	const toggle = document.querySelector( '.menu-toggle' );
	const drawer = document.getElementById( 'site-drawer' );
	if ( ! toggle || ! drawer ) {
		return;
	}

	const setOpen = ( open ) => {
		toggle.setAttribute( 'aria-expanded', String( open ) );
		drawer.hidden = ! open;
	};

	toggle.addEventListener( 'click', () => {
		setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'Escape' && ! drawer.hidden ) {
			setOpen( false );
			toggle.focus();
		}
	} );

	window.matchMedia( '(min-width: 981px)' ).addEventListener( 'change', ( event ) => {
		if ( event.matches ) {
			setOpen( false );
		}
	} );
} )();
