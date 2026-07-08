<?php
/**
 * LifterLMS override: course/membership loop featured image.
 *
 * Overrides LifterLMS `loop/featured-image.php`. The loop item is already wrapped
 * in a link by `lifterlms_loop_link_start`, so this only outputs the image (no
 * extra anchor) with a brand-sized, lazy-loaded thumbnail.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

global $post;

// Respect the featured-video tile option, matching LifterLMS default behaviour.
if ( function_exists( 'llms_get_post' ) && in_array( $post->post_type, array( 'course', 'llms_membership' ), true ) ) {
	$product = llms_get_post( $post );
	if ( $product && 'yes' === $product->get( 'tile_featured_video' ) && $product->get( 'video_embed' ) ) {
		return;
	}
}

if ( has_post_thumbnail( $post->ID ) ) {
	the_post_thumbnail(
		'alostora-course-card',
		array(
			'class'    => 'alostora-course__img llms-featured-image',
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);
} elseif ( function_exists( 'llms_placeholder_img' ) ) {
	echo llms_placeholder_img(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped inside function.
}
