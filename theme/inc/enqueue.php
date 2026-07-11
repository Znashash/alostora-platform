<?php
/**
 * Asset loading: compiled CSS, JavaScript, fonts and preloading.
 *
 * Styles are authored in assets/scss/ and compiled to assets/css/. Scripts are
 * vanilla ES modules with no build step required. Loading is conditional so that
 * the front page only ships what it needs, keeping Core Web Vitals healthy.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return the file modification time for cache-busting, falling back to the
 * theme version when the file is missing.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string
 */
function alostora_asset_version( $relative_path ) {
	$file = ALOSTORA_DIR . ltrim( $relative_path, '/' );

	if ( is_readable( $file ) ) {
		return (string) filemtime( $file );
	}

	return ALOSTORA_VERSION;
}

/**
 * Enqueue front-end styles and scripts.
 *
 * @return void
 */
function alostora_enqueue_assets() {
	// Core design-system stylesheet (compiled from SCSS).
	wp_enqueue_style(
		'alostora-main',
		ALOSTORA_URI . 'assets/css/main.css',
		array(),
		alostora_asset_version( 'assets/css/main.css' )
	);

	// The WordPress-generated RTL companion loads automatically via wp_style_add_data.
	wp_style_add_data( 'alostora-main', 'rtl', 'replace' );

	// Font-face declarations (kept separate so they can be preloaded up-front).
	wp_enqueue_style(
		'alostora-fonts',
		ALOSTORA_URI . 'assets/css/fonts.css',
		array(),
		alostora_asset_version( 'assets/css/fonts.css' )
	);

	// Interaction layer. Loaded as a module and deferred for a fast first paint.
	wp_enqueue_script(
		'alostora-app',
		ALOSTORA_URI . 'assets/js/app.js',
		array(),
		alostora_asset_version( 'assets/js/app.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script( 'alostora-app', 'alostoraData', array(
		'ajaxUrl'      => esc_url( admin_url( 'admin-ajax.php' ) ),
		'restUrl'      => esc_url_raw( rest_url() ),
		'isRtl'        => is_rtl(),
		'reduceMotion' => (bool) get_theme_mod( 'alostora_reduce_motion', false ),
		'i18n'         => array(
			'menu'  => esc_html__( 'Menu', 'alostora' ),
			'close' => esc_html__( 'Close', 'alostora' ),
			'next'  => esc_html__( 'Next', 'alostora' ),
			'prev'  => esc_html__( 'Previous', 'alostora' ),
		),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'alostora_enqueue_assets' );

/**
 * Load the block-editor stylesheet so the editor mirrors the front end.
 *
 * @return void
 */
function alostora_enqueue_editor_assets() {
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'alostora_enqueue_editor_assets' );
