<?php
/**
 * Customizer options: brand, header CTAs, social links and behaviour toggles.
 *
 * These control theme-level chrome that is not part of an Elementor-composed
 * page (e.g. the fallback header CTA URLs and social links) plus performance and
 * motion toggles. Page content itself stays editable in Elementor.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Customizer settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function alostora_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'alostora_panel', array(
		'title'    => esc_html__( 'Alostora Theme', 'alostora' ),
		'priority' => 20,
	) );

	/* -------- Brand & CTAs -------- */
	$wp_customize->add_section( 'alostora_brand', array(
		'title' => esc_html__( 'Brand & Header CTAs', 'alostora' ),
		'panel' => 'alostora_panel',
	) );

	$ctas = array(
		'alostora_cta_primary_label' => array(
			'default' => 'سجّل الآن',
			'label'   => esc_html__( 'Primary CTA label', 'alostora' ),
			'type'    => 'text',
		),
		'alostora_cta_primary_url'   => array(
			'default' => '',
			'label'   => esc_html__( 'Primary CTA URL (optional override)', 'alostora' ),
			'type'    => 'url',
		),
	);

	foreach ( $ctas as $id => $args ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $args['default'],
			'sanitize_callback' => 'url' === $args['type'] ? 'esc_url_raw' : 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $args['label'],
			'section' => 'alostora_brand',
			'type'    => 'text',
		) );
	}

	/* -------- Social links -------- */
	$wp_customize->add_section( 'alostora_social', array(
		'title' => esc_html__( 'Social Links', 'alostora' ),
		'panel' => 'alostora_panel',
	) );

	foreach ( array( 'facebook', 'instagram', 'youtube', 'telegram', 'x' ) as $network ) {
		$setting = 'alostora_social_' . $network;
		$wp_customize->add_setting( $setting, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $setting, array(
			/* translators: %s: social network name. */
			'label'   => sprintf( esc_html__( '%s URL', 'alostora' ), ucfirst( $network ) ),
			'section' => 'alostora_social',
			'type'    => 'url',
		) );
	}

	/* -------- Behaviour toggles -------- */
	$wp_customize->add_section( 'alostora_behaviour', array(
		'title' => esc_html__( 'Performance & Motion', 'alostora' ),
		'panel' => 'alostora_panel',
	) );

	$toggles = array(
		'alostora_enable_animations' => array(
			'default' => true,
			'label'   => esc_html__( 'Enable scroll & hover animations', 'alostora' ),
		),
		'alostora_reduce_motion'     => array(
			'default' => false,
			'label'   => esc_html__( 'Force reduced motion', 'alostora' ),
		),
		'alostora_inline_critical'   => array(
			'default' => true,
			'label'   => esc_html__( 'Inline critical CSS', 'alostora' ),
		),
	);

	foreach ( $toggles as $id => $args ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $args['default'],
			'sanitize_callback' => 'alostora_sanitize_bool',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $args['label'],
			'section' => 'alostora_behaviour',
			'type'    => 'checkbox',
		) );
	}
}
add_action( 'customize_register', 'alostora_customize_register' );

/**
 * Sanitize a checkbox boolean.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function alostora_sanitize_bool( $value ) {
	return (bool) $value;
}

/**
 * Retrieve configured social links as a network => url map (only non-empty).
 *
 * @return array
 */
function alostora_get_social_links() {
	$links = array();

	foreach ( array( 'facebook', 'instagram', 'youtube', 'telegram', 'x' ) as $network ) {
		$url = get_theme_mod( 'alostora_social_' . $network, '' );
		if ( $url ) {
			$links[ $network ] = $url;
		}
	}

	return $links;
}
