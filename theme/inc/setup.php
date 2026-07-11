<?php
/**
 * Theme setup: supports, navigation menus, image sizes and localisation.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register core theme supports.
 *
 * @return void
 */
function alostora_setup() {
	load_theme_textdomain( 'alostora', ALOSTORA_DIR . 'languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 320,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'navigation-widgets',
	) );

	// Elementor and LifterLMS integration points (also declared in their modules).
	add_theme_support( 'elementor' );

	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary Menu', 'alostora' ),
		'footer'    => esc_html__( 'Footer Menu', 'alostora' ),
		'account'   => esc_html__( 'Account Menu', 'alostora' ),
	) );

	// Content widths tuned for the Elementor 1200px container.
	if ( ! isset( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = 1200;
	}
}
add_action( 'after_setup_theme', 'alostora_setup' );

/**
 * Register responsive image sizes used by course cards and hero media.
 *
 * @return void
 */
function alostora_image_sizes() {
	add_image_size( 'alostora-course-card', 640, 420, true );
	add_image_size( 'alostora-video-tile', 480, 300, true );
	add_image_size( 'alostora-hero', 1600, 900, true );
}
add_action( 'after_setup_theme', 'alostora_image_sizes' );

/**
 * Expose the custom image sizes to the media library UI.
 *
 * @param array $sizes Existing selectable sizes.
 * @return array
 */
function alostora_custom_image_size_names( $sizes ) {
	return array_merge( $sizes, array(
		'alostora-course-card' => esc_html__( 'Course Card', 'alostora' ),
		'alostora-video-tile'  => esc_html__( 'Video Tile', 'alostora' ),
		'alostora-hero'        => esc_html__( 'Hero', 'alostora' ),
	) );
}
add_filter( 'image_size_names_choose', 'alostora_custom_image_size_names' );

/**
 * Register widget areas (footer columns + a generic sidebar).
 *
 * @return void
 */
function alostora_widgets_init() {
	$defaults = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget__title">',
		'after_title'   => '</h3>',
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array_merge( $defaults, array(
			/* translators: %d: footer column number. */
			'name'        => sprintf( esc_html__( 'Footer Column %d', 'alostora' ), $i ),
			'id'          => 'footer-' . $i,
			'description' => esc_html__( 'Widgets for the site footer.', 'alostora' ),
		) ) );
	}
}
add_action( 'widgets_init', 'alostora_widgets_init' );
