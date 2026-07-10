/**
 * Sticky header + accessible off-canvas mobile navigation drawer.
 *
 * Progressive enhancement: the menu works as plain lists without JS. This adds
 * the mobile drawer (open/close via button, backdrop, Escape, link click),
 * body-scroll locking, focus management and correct ARIA state. RTL-aware via
 * CSS (the drawer slides from the inline-start edge = right in RTL).
 */

export function initNavigation() {
	const header = document.querySelector( '[data-header]' );
	const toggle = document.querySelector( '[data-nav-toggle]' );
	const drawer = document.querySelector( '[data-drawer]' );
	const backdrop = document.querySelector( '[data-drawer-backdrop]' );
	const closeBtn = drawer ? drawer.querySelector( '[data-drawer-close]' ) : null;

	// Sticky "stuck" state on scroll (used by the transparent-home header too).
	if ( header ) {
		const onScroll = () => header.classList.toggle( 'is-stuck', window.scrollY > 8 );
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	if ( ! toggle || ! drawer ) {
		return;
	}

	let lastFocused = null;

	const focusable = () =>
		Array.prototype.slice.call(
			drawer.querySelectorAll( 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])' )
		).filter( ( el ) => el.offsetParent !== null );

	const open = () => {
		lastFocused = document.activeElement;
		drawer.classList.add( 'is-open' );
		if ( backdrop ) {
			backdrop.classList.add( 'is-open' );
		}
		drawer.setAttribute( 'aria-hidden', 'false' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		document.body.classList.add( 'u-no-scroll' );
		const first = closeBtn || focusable()[ 0 ];
		if ( first ) {
			first.focus();
		}
	};

	const close = () => {
		drawer.classList.remove( 'is-open' );
		if ( backdrop ) {
			backdrop.classList.remove( 'is-open' );
		}
		drawer.setAttribute( 'aria-hidden', 'true' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		document.body.classList.remove( 'u-no-scroll' );
		if ( lastFocused && typeof lastFocused.focus === 'function' ) {
			lastFocused.focus();
		}
	};

	const isOpen = () => drawer.classList.contains( 'is-open' );

	toggle.addEventListener( 'click', () => ( isOpen() ? close() : open() ) );

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', close );
	}
	if ( backdrop ) {
		backdrop.addEventListener( 'click', close );
	}

	// Close when a navigation link is activated.
	drawer.addEventListener( 'click', ( event ) => {
		if ( event.target.closest( 'a' ) ) {
			close();
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( ! isOpen() ) {
			return;
		}
		if ( 'Escape' === event.key ) {
			close();
			return;
		}
		// Simple focus trap.
		if ( 'Tab' === event.key ) {
			const items = focusable();
			if ( ! items.length ) {
				return;
			}
			const firstEl = items[ 0 ];
			const lastEl = items[ items.length - 1 ];
			if ( event.shiftKey && document.activeElement === firstEl ) {
				event.preventDefault();
				lastEl.focus();
			} else if ( ! event.shiftKey && document.activeElement === lastEl ) {
				event.preventDefault();
				firstEl.focus();
			}
		}
	} );

	// Close the drawer if the viewport grows to desktop.
	const mq = window.matchMedia( '(min-width: 1025px)' );
	const onChange = ( e ) => {
		if ( e.matches && isOpen() ) {
			close();
		}
	};
	if ( mq.addEventListener ) {
		mq.addEventListener( 'change', onChange );
	} else if ( mq.addListener ) {
		mq.addListener( onChange );
	}
}
