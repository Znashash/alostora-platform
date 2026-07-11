/**
 * Alostora front-end interactions.
 *
 * Ships as a single CLASSIC script (no ES modules) so it survives asset
 * concatenation/minification and Elementor's asset handling — the previous
 * ES-module build silently failed on optimized/cached production sites, which is
 * why the mobile menu "did nothing". All UI is wired with delegated listeners on
 * `document`, so it also works when Elementor injects the header after load and
 * can never bind duplicate handlers.
 *
 * @package Alostora
 */
( function () {
	'use strict';

	var data = window.alostoraData || {};
	var reduceMotion =
		!! data.reduceMotion ||
		( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	function closest( el, selector ) {
		return el && el.closest ? el.closest( selector ) : null;
	}

	/* ---------------------------------------------------------------------
	 * Sticky header
	 * ------------------------------------------------------------------ */
	function initSticky() {
		var header = document.querySelector( '[data-header]' );
		if ( ! header ) {
			return;
		}
		var onScroll = function () {
			header.classList.toggle( 'is-stuck', window.scrollY > 8 );
		};
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	/* ---------------------------------------------------------------------
	 * Mobile navigation drawer
	 * ------------------------------------------------------------------ */
	var lastFocused = null;

	function drawerEl() { return document.querySelector( '[data-drawer]' ); }
	function toggleEl() { return document.querySelector( '[data-nav-toggle]' ); }
	function backdropEl() { return document.querySelector( '[data-drawer-backdrop]' ); }
	function drawerOpen() { var d = drawerEl(); return !! d && d.classList.contains( 'is-open' ); }

	function focusableIn( el ) {
		if ( ! el ) { return []; }
		return Array.prototype.slice
			.call( el.querySelectorAll( 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])' ) )
			.filter( function ( n ) { return n.offsetParent !== null; } );
	}

	function openDrawer() {
		var d = drawerEl(), t = toggleEl(), b = backdropEl();
		if ( ! d ) { return; }
		lastFocused = document.activeElement;
		d.classList.add( 'is-open' );
		if ( b ) { b.classList.add( 'is-open' ); }
		d.setAttribute( 'aria-hidden', 'false' );
		if ( t ) { t.setAttribute( 'aria-expanded', 'true' ); }
		document.body.classList.add( 'u-no-scroll' );
		var first = d.querySelector( '[data-drawer-close]' ) || focusableIn( d )[ 0 ];
		if ( first ) { try { first.focus(); } catch ( e ) {} }
	}

	function closeDrawer() {
		var d = drawerEl(), t = toggleEl(), b = backdropEl();
		if ( ! d ) { return; }
		d.classList.remove( 'is-open' );
		if ( b ) { b.classList.remove( 'is-open' ); }
		d.setAttribute( 'aria-hidden', 'true' );
		if ( t ) { t.setAttribute( 'aria-expanded', 'false' ); }
		document.body.classList.remove( 'u-no-scroll' );
		if ( lastFocused && lastFocused.focus ) { try { lastFocused.focus(); } catch ( e ) {} }
	}

	/* ---------------------------------------------------------------------
	 * Video gallery — swap the featured video from a thumbnail
	 * ------------------------------------------------------------------ */
	function selectThumb( thumb ) {
		var gallery = closest( thumb, '[data-video-gallery]' );
		if ( ! gallery ) { return; }

		var image = gallery.querySelector( '[data-gallery-image]' );
		var caption = gallery.querySelector( '[data-gallery-caption]' );
		var play = gallery.querySelector( '[data-gallery-play]' );
		var newImage = thumb.getAttribute( 'data-image' );
		var id = thumb.getAttribute( 'data-video-id' ) || '';
		var title = thumb.getAttribute( 'data-title' ) || '';

		var thumbs = gallery.querySelectorAll( '[data-gallery-thumb]' );
		Array.prototype.forEach.call( thumbs, function ( t ) {
			var active = t === thumb;
			t.classList.toggle( 'is-active', active );
			t.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
		} );

		if ( image && newImage ) {
			var apply = function () {
				image.src = newImage;
				if ( title ) { image.alt = title; }
				image.classList.remove( 'is-swapping' );
			};
			if ( reduceMotion ) {
				apply();
			} else {
				image.classList.add( 'is-swapping' );
				window.setTimeout( apply, 180 );
			}
		}

		if ( caption && title ) { caption.textContent = title; }
		if ( play ) {
			play.setAttribute( 'data-video-id', id );
			if ( title ) { play.setAttribute( 'aria-label', 'تشغيل: ' + title ); }
		}
	}

	/* ---------------------------------------------------------------------
	 * Video lightbox (opens the featured / secure video)
	 * ------------------------------------------------------------------ */
	var lightbox = null;

	function ensureLightbox() {
		if ( lightbox ) { return lightbox; }
		lightbox = document.createElement( 'div' );
		lightbox.className = 'alostora-lightbox';
		lightbox.setAttribute( 'role', 'dialog' );
		lightbox.setAttribute( 'aria-modal', 'true' );
		lightbox.hidden = true;
		lightbox.innerHTML =
			'<div class="alostora-lightbox__backdrop" data-close></div>' +
			'<div class="alostora-lightbox__dialog">' +
			'<button type="button" class="alostora-lightbox__close" data-close aria-label="' +
			( ( data.i18n && data.i18n.close ) || 'إغلاق' ) +
			'">\u00d7</button>' +
			'<div class="alostora-lightbox__body" data-lightbox-body></div>' +
			'</div>';
		document.body.appendChild( lightbox );
		lightbox.addEventListener( 'click', function ( e ) {
			if ( e.target.hasAttribute( 'data-close' ) ) { closeLightbox(); }
		} );
		return lightbox;
	}

	function lightboxHidden() { return ! lightbox || lightbox.hidden; }

	function buildEmbed( trigger ) {
		var id = trigger.getAttribute( 'data-video-id' );
		var href = trigger.getAttribute( 'href' );
		if ( id ) {
			return '<div class="alostora-video alostora-video--16x9"><div class="alostora-video__frame" data-vdocipher-id="' + id + '"></div></div>';
		}
		if ( href && href !== '#' ) {
			var safe = href.replace( /"/g, '%22' );
			return '<div class="alostora-video alostora-video--16x9"><div class="alostora-video__frame"><iframe src="' + safe + '" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe></div></div>';
		}
		return '<div class="alostora-video alostora-video--16x9"><div class="alostora-video__frame"><div class="alostora-video__placeholder"><span class="alostora-video__icon"></span><span class="alostora-video__label">' +
			( ( data.i18n && data.i18n.videoSoon ) || 'الفيديو الآمن سيظهر هنا (VdoCipher).' ) +
			'</span></div></div></div>';
	}

	function openLightbox( trigger ) {
		var el = ensureLightbox();
		el.querySelector( '[data-lightbox-body]' ).innerHTML = buildEmbed( trigger );
		el.hidden = false;
		document.body.classList.add( 'u-no-scroll' );
		var close = el.querySelector( '.alostora-lightbox__close' );
		if ( close ) { try { close.focus(); } catch ( e ) {} }
	}

	function closeLightbox() {
		if ( ! lightbox ) { return; }
		lightbox.hidden = true;
		lightbox.querySelector( '[data-lightbox-body]' ).innerHTML = '';
		if ( ! drawerOpen() ) { document.body.classList.remove( 'u-no-scroll' ); }
	}

	/* ---------------------------------------------------------------------
	 * Delegated event handling (single listeners for the whole document)
	 * ------------------------------------------------------------------ */
	document.addEventListener( 'click', function ( e ) {
		if ( closest( e.target, '[data-nav-toggle]' ) ) {
			e.preventDefault();
			drawerOpen() ? closeDrawer() : openDrawer();
			return;
		}
		if ( closest( e.target, '[data-drawer-close]' ) || closest( e.target, '[data-drawer-backdrop]' ) ) {
			e.preventDefault();
			closeDrawer();
			return;
		}
		var drawer = drawerEl();
		if ( drawer && drawer.contains( e.target ) && closest( e.target, 'a' ) ) {
			closeDrawer(); // then let the link navigate
			return;
		}
		var thumb = closest( e.target, '[data-gallery-thumb]' );
		if ( thumb ) {
			e.preventDefault();
			selectThumb( thumb );
			return;
		}
		var trigger = closest( e.target, '[data-video-trigger]' );
		if ( trigger ) {
			openLightbox( trigger );
			if ( trigger.tagName === 'A' ) { e.preventDefault(); }
		}
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' || e.keyCode === 27 ) {
			if ( ! lightboxHidden() ) { closeLightbox(); return; }
			if ( drawerOpen() ) { closeDrawer(); return; }
		}
		// Focus trap inside the open drawer.
		if ( ( e.key === 'Tab' || e.keyCode === 9 ) && drawerOpen() ) {
			var items = focusableIn( drawerEl() );
			if ( ! items.length ) { return; }
			var firstEl = items[ 0 ];
			var lastEl = items[ items.length - 1 ];
			if ( e.shiftKey && document.activeElement === firstEl ) {
				e.preventDefault();
				lastEl.focus();
			} else if ( ! e.shiftKey && document.activeElement === lastEl ) {
				e.preventDefault();
				firstEl.focus();
			}
		}
	} );

	// Close the drawer when the viewport grows to desktop.
	if ( window.matchMedia ) {
		var mq = window.matchMedia( '(min-width: 1025px)' );
		var onMq = function ( ev ) { if ( ev.matches && drawerOpen() ) { closeDrawer(); } };
		if ( mq.addEventListener ) { mq.addEventListener( 'change', onMq ); }
		else if ( mq.addListener ) { mq.addListener( onMq ); }
	}

	/* ---------------------------------------------------------------------
	 * Scroll-reveal + count-up
	 * ------------------------------------------------------------------ */
	function showAllReveals() {
		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-reveal]' ),
			function ( el ) { el.classList.add( 'is-visible' ); }
		);
	}

	function initReveal() {
		var items = document.querySelectorAll( '[data-reveal]' );
		if ( ! items.length ) { return; }
		if ( ! document.body.classList.contains( 'has-animations' ) || reduceMotion || ! ( 'IntersectionObserver' in window ) ) {
			showAllReveals();
			return;
		}
		var obs = new IntersectionObserver( function ( entries, o ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					o.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' } );
		Array.prototype.forEach.call( items, function ( el ) { obs.observe( el ); } );
	}

	function animateCount( el ) {
		var raw = el.textContent.trim();
		var match = raw.match( /([\d.,]+)/ );
		if ( ! match ) { return; }
		var prefix = raw.slice( 0, match.index );
		var suffix = raw.slice( match.index + match[ 0 ].length );
		var target = parseFloat( match[ 0 ].replace( /,/g, '' ) );
		if ( isNaN( target ) ) { return; }
		var duration = 1400;
		var start = performance.now();
		var step = function ( now ) {
			var p = Math.min( ( now - start ) / duration, 1 );
			var eased = 1 - Math.pow( 1 - p, 3 );
			el.textContent = prefix + Math.floor( eased * target ).toLocaleString() + suffix;
			if ( p < 1 ) { requestAnimationFrame( step ); } else { el.textContent = raw; }
		};
		requestAnimationFrame( step );
	}

	function initCountUp() {
		var counters = document.querySelectorAll( '[data-countup]' );
		if ( ! counters.length || reduceMotion || ! ( 'IntersectionObserver' in window ) ) { return; }
		var obs = new IntersectionObserver( function ( entries, o ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					animateCount( entry.target );
					o.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.6 } );
		Array.prototype.forEach.call( counters, function ( el ) { obs.observe( el ); } );
	}

	/* ---------------------------------------------------------------------
	 * Carousels (course slider) — arrow controls, RTL aware
	 * ------------------------------------------------------------------ */
	function initCarousels() {
		Array.prototype.forEach.call( document.querySelectorAll( '[data-carousel]' ), function ( root ) {
			var viewport = root.querySelector( '[data-carousel-viewport]' );
			if ( ! viewport ) { return; }
			var prev = root.querySelector( '[data-carousel-prev]' );
			var next = root.querySelector( '[data-carousel-next]' );
			var page = function () { return Math.max( viewport.clientWidth * 0.9, 240 ); };
			var update = function () {
				var max = viewport.scrollWidth - viewport.clientWidth - 1;
				var pos = Math.abs( viewport.scrollLeft );
				if ( prev ) { prev.disabled = pos <= 1; }
				if ( next ) { next.disabled = pos >= max; }
			};
			var go = function ( dir ) {
				var rtl = getComputedStyle( root ).direction === 'rtl';
				viewport.scrollBy( { left: page() * dir * ( rtl ? -1 : 1 ), behavior: 'smooth' } );
			};
			if ( next ) { next.addEventListener( 'click', function () { go( 1 ); } ); }
			if ( prev ) { prev.addEventListener( 'click', function () { go( -1 ); } ); }
			viewport.addEventListener( 'scroll', update, { passive: true } );
			window.addEventListener( 'resize', update, { passive: true } );
			update();
		} );
	}

	ready( function () {
		initSticky();
		initReveal();
		initCountUp();
		initCarousels();
	} );
}() );
