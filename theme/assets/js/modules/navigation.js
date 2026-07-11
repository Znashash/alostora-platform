/**
 * Off-canvas navigation drawer and sticky-header behaviour.
 *
 * Progressive enhancement: the desktop menu works as a plain list without JS;
 * this adds the mobile drawer toggle, focus trapping and a "stuck" class on scroll.
 */

export function initNavigation() {
	const header = document.querySelector( '[data-header]' );
	const toggle = document.querySelector( '[data-nav-toggle]' );
	const drawer = document.querySelector( '[data-mobile-drawer]' );
	const backdrop = document.querySelector( '[data-nav-backdrop]' );
	const closeBtn = document.querySelector( '[data-nav-close]' );

	if ( header ) {
		const onScroll = () => {
			header.classList.toggle( 'is-stuck', window.scrollY > 8 );
		};
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	if ( ! toggle || ! drawer ) {
		return;
	}

	const setOpen = ( open ) => {
		drawer.classList.toggle( 'is-open', open );
		drawer.setAttribute( 'aria-hidden', String( ! open ) );
		if ( backdrop ) {
			backdrop.classList.toggle( 'is-open', open );
		}
		toggle.setAttribute( 'aria-expanded', String( open ) );
		document.body.classList.toggle( 'u-no-scroll', open );

		if ( open && closeBtn ) {
			closeBtn.focus();
		}
	};

	toggle.addEventListener( 'click', () => {
		setOpen( ! drawer.classList.contains( 'is-open' ) );
	} );

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', () => {
			setOpen( false );
			toggle.focus();
		} );
	}

	if ( backdrop ) {
		backdrop.addEventListener( 'click', () => setOpen( false ) );
	}

	document.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key && drawer.classList.contains( 'is-open' ) ) {
			setOpen( false );
			toggle.focus();
		}
	} );

	// Close the drawer when a link is followed on small screens.
	drawer.addEventListener( 'click', ( event ) => {
		if ( event.target.closest( 'a' ) && window.matchMedia( '(max-width: 1024px)' ).matches ) {
			setOpen( false );
		}
	} );
}
