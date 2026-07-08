<?php
/**
 * Elementor Pro integration.
 *
 * The theme follows the Hello Elementor philosophy: it registers Theme Builder
 * locations and hands full layout control to Elementor Pro. The homepage and all
 * templates are composed in the Theme Builder — never in PHP. Exportable kits and
 * template JSON live in /elementor and can be imported on a fresh install.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Elementor Theme Builder locations. This lets Elementor Pro own the
 * header, footer, single, archive and full-page (single-page) regions.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Locations manager.
 * @return void
 */
function alostora_register_elementor_locations( $manager ) {
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'alostora_register_elementor_locations' );

/**
 * Register the theme as an Elementor-compatible theme and declare the container
 * width so Elementor global settings inherit the design system.
 *
 * @return void
 */
function alostora_elementor_settings() {
	// Match Elementor's default breakpoints to the SCSS breakpoints.
	add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
}
add_action( 'init', 'alostora_elementor_settings' );

/**
 * Register the theme's Elementor font families so editors can select the brand
 * fonts from Elementor's typography controls without loading Google Fonts.
 *
 * @param array $fonts Existing Elementor fonts, keyed by family.
 * @return array
 */
function alostora_register_elementor_fonts( $fonts ) {
	$fonts['IBM Plex Sans Arabic'] = 'system';
	$fonts['Tajawal']              = 'system';

	return $fonts;
}
add_filter( 'elementor/fonts/additional_fonts', 'alostora_register_elementor_fonts' );

/**
 * Path to the bundled Elementor exports (kits + theme-builder templates).
 *
 * @return string
 */
function alostora_elementor_exports_dir() {
	return ALOSTORA_DIR . 'elementor/';
}

/**
 * Surface a one-click admin notice pointing editors to the bundled Elementor kit
 * so the approved homepage blueprint can be imported after activation.
 *
 * @return void
 */
function alostora_elementor_import_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'themes' !== $screen->id ) {
		return;
	}

	if ( get_option( 'alostora_kit_imported' ) ) {
		return;
	}

	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	$kit = alostora_elementor_exports_dir() . 'kits/alostora-kit.json';

	if ( ! is_readable( $kit ) ) {
		return;
	}

	printf(
		'<div class="notice notice-info is-dismissible"><p>%s</p><p><code>%s</code></p></div>',
		esc_html__( 'Alostora: import the bundled Elementor kit (Elementor → Tools → Import Kit) to load the homepage blueprint and Theme Builder templates.', 'alostora' ),
		esc_html( str_replace( ABSPATH, '', $kit ) )
	);
}
add_action( 'admin_notices', 'alostora_elementor_import_notice' );

/**
 * Register the bundled Theme Builder templates directory with Elementor's
 * template library so they appear under "My Templates" import sources.
 *
 * @param array $sources Registered template sources.
 * @return array
 */
function alostora_register_template_sources( $sources ) {
	// Elementor reads JSON exports from the import UI; this keeps the bundled
	// path discoverable for tooling that iterates registered sources.
	if ( is_array( $sources ) ) {
		$sources['alostora_theme_builder'] = alostora_elementor_exports_dir() . 'theme-builder/';
	}

	return $sources;
}
add_filter( 'elementor/template_library/sources', 'alostora_register_template_sources' );
