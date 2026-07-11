<?php
/**
 * Alostora theme bootstrap.
 *
 * Loads the modular includes that make up the theme. Each module in inc/ has a
 * single responsibility and is intentionally small. No presentational markup
 * lives here — the homepage and all layouts are composed with the Elementor Pro
 * Theme Builder, while this file only wires up the design system and integrations.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme version. Bump on release; also used to version enqueued assets so that
 * browser caches invalidate on deploy.
 */
if ( ! defined( 'ALOSTORA_VERSION' ) ) {
	$alostora_theme = wp_get_theme();
	define( 'ALOSTORA_VERSION', $alostora_theme->get( 'Version' ) ? $alostora_theme->get( 'Version' ) : '1.0.0' );
}

/** Absolute path to the theme directory, with trailing slash. */
define( 'ALOSTORA_DIR', trailingslashit( get_template_directory() ) );

/** URL to the theme directory, with trailing slash. */
define( 'ALOSTORA_URI', trailingslashit( get_template_directory_uri() ) );

/**
 * Require a theme include file if it exists.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return void
 */
function alostora_require( $relative_path ) {
	$file = ALOSTORA_DIR . ltrim( $relative_path, '/' );

	if ( is_readable( $file ) ) {
		require_once $file;
	}
}

/*
 * Load core modules. Order matters: setup declares theme support before other
 * modules hook into it.
 */
$alostora_modules = array(
	'inc/setup.php',                 // Theme supports, menus, image sizes, i18n.
	'inc/enqueue.php',               // Styles, scripts, fonts, preloading.
	'inc/template-functions.php',    // Shared render helpers used by components.
	'inc/components.php',            // Reusable component loader + registry.
	'inc/sections.php',              // Homepage section shortcodes.
	'inc/theme-options.php',         // Customizer: brand, socials, CTAs, toggles.
	'inc/performance.php',           // Core Web Vitals: lazyload, cleanup, WebP/AVIF.
	'inc/elementor.php',             // Elementor locations + Theme Builder support.
	'inc/lifterlms.php',             // LifterLMS support + VdoCipher integration.
	'inc/frontend-translations.php', // Frontend Arabic gettext fallback (LifterLMS / serial).
	'inc/i18n-frontend.php',         // Additional LifterLMS form/dashboard Arabic helpers.
);

foreach ( $alostora_modules as $alostora_module ) {
	alostora_require( $alostora_module );
}
unset( $alostora_module );
