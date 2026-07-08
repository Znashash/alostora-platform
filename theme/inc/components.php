<?php
/**
 * Reusable components architecture.
 *
 * Each component lives in /components/<slug>/<slug>.php and renders a single
 * design-system building block from a normalised array of arguments. Components
 * are the single source of truth for markup + BEM class names; the matching
 * styles live in assets/scss/components/_<slug>.scss.
 *
 * Structural, content-driven components are also exposed as shortcodes so they
 * can be dropped into Elementor while keeping their content fully editable.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a component template with the given arguments.
 *
 * @param string $slug Component directory/file slug.
 * @param array  $args Arguments made available to the template as $args.
 * @return void
 */
function alostora_component( $slug, $args = array() ) {
	$slug = sanitize_key( str_replace( '_', '-', $slug ) );
	$file = ALOSTORA_DIR . 'components/' . $slug . '/' . $slug . '.php';

	if ( ! is_readable( $file ) ) {
		return;
	}

	// $args is referenced inside the included template.
	include $file;
}

/**
 * Return a component's rendered markup as a string.
 *
 * @param string $slug Component slug.
 * @param array  $args Component arguments.
 * @return string
 */
function alostora_get_component( $slug, $args = array() ) {
	ob_start();
	alostora_component( $slug, $args );
	return ob_get_clean();
}

/**
 * Register component shortcodes so Elementor editors can embed them with
 * editable attributes. Layout stays in Elementor; content stays in the widget.
 *
 * @return void
 */
function alostora_register_component_shortcodes() {
	add_shortcode( 'alostora_hero', 'alostora_shortcode_hero' );
	add_shortcode( 'alostora_statistics', 'alostora_shortcode_statistics' );
	add_shortcode( 'alostora_step_card', 'alostora_shortcode_step_card' );
	add_shortcode( 'alostora_video_card', 'alostora_shortcode_video_card' );
	add_shortcode( 'alostora_testimonial', 'alostora_shortcode_testimonial' );
	add_shortcode( 'alostora_button', 'alostora_shortcode_button' );
}
add_action( 'init', 'alostora_register_component_shortcodes' );

/**
 * [alostora_button label="" url="" style="primary|secondary|ghost" icon=""]
 *
 * @param array $atts Attributes.
 * @return string
 */
function alostora_shortcode_button( $atts ) {
	$atts = shortcode_atts(
		array(
			'label'  => esc_html__( 'Learn more', 'alostora' ),
			'url'    => '#',
			'style'  => 'primary',
			'icon'   => '',
			'target' => '_self',
		),
		$atts,
		'alostora_button'
	);

	return alostora_get_component( 'buttons', $atts );
}

/**
 * [alostora_hero title="" subtitle="" ...]
 *
 * @param array  $atts    Attributes.
 * @param string $content Enclosed content (used as description).
 * @return string
 */
function alostora_shortcode_hero( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'eyebrow'       => '',
			'title'         => '',
			'title_accent'  => '',
			'primary_label' => esc_html__( 'Start learning', 'alostora' ),
			'primary_url'   => '#',
			'video_url'     => '',
			'video_label'   => esc_html__( 'Watch a lesson', 'alostora' ),
			'image'         => '',
		),
		$atts,
		'alostora_hero'
	);

	$atts['description'] = $content ? wp_kses_post( $content ) : '';

	return alostora_get_component( 'hero', $atts );
}

/**
 * [alostora_statistics items="24500+|Students,1200+|Lessons"]
 *
 * @param array $atts Attributes.
 * @return string
 */
function alostora_shortcode_statistics( $atts ) {
	$atts = shortcode_atts( array( 'items' => '' ), $atts, 'alostora_statistics' );

	$items = array();
	foreach ( array_filter( array_map( 'trim', explode( ',', $atts['items'] ) ) ) as $pair ) {
		$parts   = array_map( 'trim', explode( '|', $pair ) );
		$items[] = array(
			'value' => isset( $parts[0] ) ? $parts[0] : '',
			'label' => isset( $parts[1] ) ? $parts[1] : '',
			'icon'  => isset( $parts[2] ) ? $parts[2] : 'chart',
		);
	}

	return alostora_get_component( 'statistics', array( 'items' => $items ) );
}

/**
 * [alostora_step_card number="1" title="" icon="book"]desc[/alostora_step_card]
 *
 * @param array  $atts    Attributes.
 * @param string $content Description.
 * @return string
 */
function alostora_shortcode_step_card( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'number' => '',
			'title'  => '',
			'icon'   => 'book',
		),
		$atts,
		'alostora_step_card'
	);

	$atts['description'] = $content ? wp_kses_post( $content ) : '';

	return alostora_get_component( 'step-card', $atts );
}

/**
 * [alostora_video_card id="VDOCIPHER_ID" title="" image=""]
 *
 * @param array $atts Attributes.
 * @return string
 */
function alostora_shortcode_video_card( $atts ) {
	$atts = shortcode_atts(
		array(
			'id'    => '',
			'title' => '',
			'image' => '',
		),
		$atts,
		'alostora_video_card'
	);

	return alostora_get_component( 'video-card', $atts );
}

/**
 * [alostora_testimonial name="" role="" avatar="" rating="5"]quote[/...]
 *
 * @param array  $atts    Attributes.
 * @param string $content Quote.
 * @return string
 */
function alostora_shortcode_testimonial( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'name'   => '',
			'role'   => '',
			'avatar' => '',
			'rating' => '5',
		),
		$atts,
		'alostora_testimonial'
	);

	$atts['quote'] = $content ? wp_kses_post( $content ) : '';

	return alostora_get_component( 'testimonial', $atts );
}
