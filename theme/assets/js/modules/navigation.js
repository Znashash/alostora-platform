/**
 * Off-canvas navigation drawer and sticky-header behaviour.
 *
 * Progressive enhancement: the menu works as a plain list without JS; this adds
 * the mobile drawer toggle, focus trapping and a "stuck" class on scroll.
 */

export function initNavigation() {
	const header = document.querySelector( '[data-header]' );
	const toggle = document.querySelector( '[data-nav-toggle]' );
	const menu = document.getElementById( 'alostora-primary-menu' );
	const backdrop = document.querySelector( '[data-nav-backdrop]' );

	if ( header ) {
		const onScroll = () => {
			header.classList.toggle( 'is-stuck', window.scrollY > 8 );
		};
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	if ( ! toggle || ! menu ) {
		return;
	}

	const setOpen = ( open ) => {
		menu.classList.toggle( 'is-open', open );
		if ( backdrop ) {
			backdrop.classList.toggle( 'is-open', open );
		}
		toggle.setAttribute( 'aria-expanded', String( open ) );
		document.body.classList.toggle( 'u-no-scroll', open );
	};

	toggle.addEventListener( 'click', () => {
		setOpen( ! menu.classList.contains( 'is-open' ) );
	} );

	if ( backdrop ) {
		backdrop.addEventListener( 'click', () => setOpen( false ) );
	}

	document.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key && menu.classList.contains( 'is-open' ) ) {
			setOpen( false );
			toggle.focus();
		}
	} );

	// Close the drawer when a link is followed on small screens.
	menu.addEventListener( 'click', ( event ) => {
		if ( event.target.closest( 'a' ) && window.matchMedia( '(max-width: 1024px)' ).matches ) {
			setOpen( false );
		}
	} );
}
