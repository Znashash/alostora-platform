/**
 * Accessible video lightbox for hero/video triggers.
 *
 * Opens a modal dialog containing the secure VdoCipher embed (or a linked video
 * URL). The theme never handles DRM — it delegates to the VdoCipher shortcode
 * markup that the server already rendered, or opens the provided URL.
 */

let dialog = null;

function ensureDialog() {
	if ( dialog ) {
		return dialog;
	}

	dialog = document.createElement( 'div' );
	dialog.className = 'alostora-lightbox';
	dialog.setAttribute( 'role', 'dialog' );
	dialog.setAttribute( 'aria-modal', 'true' );
	dialog.hidden = true;
	dialog.innerHTML =
		'<div class="alostora-lightbox__backdrop" data-close></div>' +
		'<div class="alostora-lightbox__dialog">' +
		'<button type="button" class="alostora-lightbox__close" data-close aria-label="' +
		( window.alostoraData?.i18n?.close || 'Close' ) +
		'">\u00d7</button>' +
		'<div class="alostora-lightbox__body" data-lightbox-body></div>' +
		'</div>';

	document.body.appendChild( dialog );

	dialog.addEventListener( 'click', ( event ) => {
		if ( event.target.hasAttribute( 'data-close' ) ) {
			closeLightbox();
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key && ! dialog.hidden ) {
			closeLightbox();
		}
	} );

	return dialog;
}

function openLightbox( content ) {
	const el = ensureDialog();
	el.querySelector( '[data-lightbox-body]' ).innerHTML = content;
	el.hidden = false;
	document.body.classList.add( 'u-no-scroll' );
	const close = el.querySelector( '.alostora-lightbox__close' );
	if ( close ) {
		close.focus();
	}
}

function closeLightbox() {
	if ( ! dialog ) {
		return;
	}
	dialog.hidden = true;
	dialog.querySelector( '[data-lightbox-body]' ).innerHTML = '';
	document.body.classList.remove( 'u-no-scroll' );
}

function buildEmbed( trigger ) {
	const videoId = trigger.getAttribute( 'data-video-id' );
	const href = trigger.getAttribute( 'href' );

	if ( videoId ) {
		return (
			'<div class="alostora-video alostora-video--16x9"><div class="alostora-video__frame" data-vdocipher-id="' +
			videoId +
			'"></div></div>'
		);
	}

	if ( href && href !== '#' ) {
		const safe = href.replace( /"/g, '%22' );
		return (
			'<div class="alostora-video alostora-video--16x9"><div class="alostora-video__frame">' +
			'<iframe src="' +
			safe +
			'" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>' +
			'</div></div>'
		);
	}

	return '';
}

export function initVideoLightbox() {
	const triggers = document.querySelectorAll( '[data-video-trigger]' );
	if ( ! triggers.length ) {
		return;
	}

	triggers.forEach( ( trigger ) => {
		trigger.addEventListener( 'click', ( event ) => {
			const content = buildEmbed( trigger );
			if ( content ) {
				event.preventDefault();
				openLightbox( content );
			}
		} );
	} );
}
