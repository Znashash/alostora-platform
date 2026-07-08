/**
 * Scroll-reveal and count-up animations.
 *
 * Uses IntersectionObserver so work only happens when elements enter the
 * viewport. Everything is gated by the `.has-animations` body class and the
 * reduced-motion preference so it can be disabled centrally.
 */

const prefersReduced = () =>
	window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ||
	( window.alostoraData && window.alostoraData.reduceMotion );

function initReveal() {
	const items = document.querySelectorAll( '[data-reveal]' );
	if ( ! items.length ) {
		return;
	}

	if ( prefersReduced() || ! ( 'IntersectionObserver' in window ) ) {
		items.forEach( ( el ) => el.classList.add( 'is-visible' ) );
		return;
	}

	const observer = new IntersectionObserver(
		( entries, obs ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					obs.unobserve( entry.target );
				}
			} );
		},
		{ threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
	);

	items.forEach( ( el ) => observer.observe( el ) );
}

function animateCount( el ) {
	const raw = el.textContent.trim();
	const match = raw.match( /([\d.,]+)/ );
	if ( ! match ) {
		return;
	}

	const suffix = raw.slice( match.index + match[ 0 ].length );
	const prefix = raw.slice( 0, match.index );
	const target = parseFloat( match[ 0 ].replace( /,/g, '' ) );
	if ( Number.isNaN( target ) ) {
		return;
	}

	const duration = 1400;
	const start = performance.now();

	const step = ( now ) => {
		const progress = Math.min( ( now - start ) / duration, 1 );
		const eased = 1 - Math.pow( 1 - progress, 3 );
		const value = Math.floor( eased * target );
		el.textContent = prefix + value.toLocaleString() + suffix;
		if ( progress < 1 ) {
			requestAnimationFrame( step );
		} else {
			el.textContent = raw;
		}
	};

	requestAnimationFrame( step );
}

function initCountUp() {
	const counters = document.querySelectorAll( '[data-countup]' );
	if ( ! counters.length || prefersReduced() || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	const observer = new IntersectionObserver(
		( entries, obs ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					animateCount( entry.target );
					obs.unobserve( entry.target );
				}
			} );
		},
		{ threshold: 0.6 }
	);

	counters.forEach( ( el ) => observer.observe( el ) );
}

export function initAnimations() {
	if ( ! document.body.classList.contains( 'has-animations' ) ) {
		document.querySelectorAll( '[data-reveal]' ).forEach( ( el ) => el.classList.add( 'is-visible' ) );
		return;
	}
	initReveal();
	initCountUp();
}
