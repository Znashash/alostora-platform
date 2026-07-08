/**
 * Alostora front-end entry.
 *
 * Loaded as a deferred ES module (see inc/enqueue.php). Each concern lives in its
 * own module and is initialised after the DOM is ready.
 */

import { initNavigation } from './modules/navigation.js';
import { initAnimations } from './modules/animations.js';
import { initCarousels } from './modules/carousel.js';
import { initVideoLightbox } from './modules/video-lightbox.js';

const ready = ( fn ) => {
	if ( document.readyState !== 'loading' ) {
		fn();
	} else {
		document.addEventListener( 'DOMContentLoaded', fn );
	}
};

ready( () => {
	initNavigation();
	initAnimations();
	initCarousels();
	initVideoLightbox();
} );
