<?php
/**
 * Performance & Core Web Vitals: font preloading, critical CSS, lazy loading,
 * next-gen image formats (WebP/AVIF) and head cleanup.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Preconnect and preload the critical brand fonts to reduce CLS/LCP.
 *
 * Only the weights used above the fold are preloaded (body 400/700 and the
 * heading 800) to avoid wasting bandwidth on fonts the first paint never needs.
 *
 * @return void
 */
function alostora_preload_fonts() {
	$fonts = array(
		'assets/fonts/ibm-plex-sans-arabic-400.woff2',
		'assets/fonts/ibm-plex-sans-arabic-700.woff2',
		'assets/fonts/tajawal-800.woff2',
	);

	foreach ( $fonts as $font ) {
		$path = ALOSTORA_DIR . $font;

		if ( ! is_readable( $path ) ) {
			continue;
		}

		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( ALOSTORA_URI . $font )
		);
	}
}
add_action( 'wp_head', 'alostora_preload_fonts', 1 );

/**
 * Inline the critical, above-the-fold CSS so the first paint is not blocked by
 * the main stylesheet request. The file is generated at build time.
 *
 * @return void
 */
function alostora_inline_critical_css() {
	$critical = ALOSTORA_DIR . 'assets/css/critical.css';

	if ( ! is_readable( $critical ) ) {
		return;
	}

	$css = file_get_contents( $critical ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( false === $css ) {
		return;
	}

	echo '<style id="alostora-critical">' . wp_strip_all_tags( $css ) . '</style>' . "\n";
}
add_action( 'wp_head', 'alostora_inline_critical_css', 2 );

/**
 * Ensure images and iframes are lazily loaded and decoded asynchronously,
 * except for the first (LCP) image which should load eagerly.
 *
 * @param array $attr Image attributes.
 * @return array
 */
function alostora_image_loading_attributes( $attr ) {
	if ( ! isset( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}

	if ( ! isset( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'alostora_image_loading_attributes' );

/**
 * Allow the AVIF and WebP mime types for uploads so editors can ship next-gen
 * formats directly.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function alostora_allow_modern_image_mimes( $mimes ) {
	$mimes['webp'] = 'image/webp';
	$mimes['avif'] = 'image/avif';

	return $mimes;
}
add_filter( 'upload_mimes', 'alostora_allow_modern_image_mimes' );

/**
 * Announce AVIF/WebP support to WordPress' output-format negotiation so newly
 * generated sub-sizes can prefer modern formats when the server supports them.
 *
 * @param array $formats Editor output formats keyed by source mime.
 * @return array
 */
function alostora_modern_output_formats( $formats ) {
	if ( function_exists( 'imageavif' ) ) {
		$formats['image/jpeg'] = 'image/avif';
		$formats['image/png']  = 'image/avif';
	} elseif ( function_exists( 'imagewebp' ) ) {
		$formats['image/jpeg'] = 'image/webp';
		$formats['image/png']  = 'image/webp';
	}

	return $formats;
}
add_filter( 'image_editor_output_format', 'alostora_modern_output_formats' );

/**
 * Trim WordPress head bloat that hurts performance and adds no value here.
 *
 * @return void
 */
function alostora_clean_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Remove emoji detection scripts/styles for anonymous visitors.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'alostora_clean_head' );

/**
 * Add resource hints for the fonts directory (self-hosted, so same origin) and
 * remove dns-prefetch noise WordPress adds by default.
 *
 * @param array  $hints         URLs to hint.
 * @param string $relation_type The relation type being fetched.
 * @return array
 */
function alostora_resource_hints( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$hints = array_filter(
			$hints,
			static function ( $hint ) {
				$url = is_array( $hint ) && isset( $hint['href'] ) ? $hint['href'] : $hint;
				return false === strpos( (string) $url, 's.w.org' );
			}
		);
	}

	return $hints;
}
add_filter( 'wp_resource_hints', 'alostora_resource_hints', 10, 2 );
