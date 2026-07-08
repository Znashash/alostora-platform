/**
 * Lightweight, dependency-free, RTL-aware carousel.
 *
 * Enhances any `[data-carousel]` containing a `[data-carousel-viewport]` track.
 * Arrow buttons scroll by one "page" using logical scrolling so it behaves
 * correctly in both LTR and RTL without special-casing.
 */

function initOne( root ) {
	const viewport = root.querySelector( '[data-carousel-viewport]' );
	const prev = root.querySelector( '[data-carousel-prev]' );
	const next = root.querySelector( '[data-carousel-next]' );

	if ( ! viewport ) {
		return;
	}

	const page = () => Math.max( viewport.clientWidth * 0.9, 240 );

	const update = () => {
		const max = viewport.scrollWidth - viewport.clientWidth - 1;
		// scrollLeft is negative in RTL; use absolute value for bounds.
		const pos = Math.abs( viewport.scrollLeft );
		if ( prev ) {
			prev.disabled = pos <= 1;
		}
		if ( next ) {
			next.disabled = pos >= max;
		}
	};

	const scrollByDir = ( dir ) => {
		const isRtl = getComputedStyle( root ).direction === 'rtl';
		const delta = page() * dir * ( isRtl ? -1 : 1 );
		viewport.scrollBy( { left: delta, behavior: 'smooth' } );
	};

	if ( next ) {
		next.addEventListener( 'click', () => scrollByDir( 1 ) );
	}
	if ( prev ) {
		prev.addEventListener( 'click', () => scrollByDir( -1 ) );
	}

	viewport.addEventListener( 'scroll', update, { passive: true } );
	window.addEventListener( 'resize', update, { passive: true } );
	update();
}

export function initCarousels() {
	document.querySelectorAll( '[data-carousel]' ).forEach( initOne );
}
