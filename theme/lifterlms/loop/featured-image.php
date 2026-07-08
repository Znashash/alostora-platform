<?php
/**
 * LifterLMS override: course/membership loop featured image.
 *
 * Overrides LifterLMS `loop/featured-image.php`. Renders the catalog card media
 * with brand classes and lazy-loaded, next-gen-friendly thumbnails.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

if ( ! has_post_thumbnail() ) {
	return;
}
?>
<a href="<?php the_permalink(); ?>" class="alostora-course__media llms-featured-image-wrapper" tabindex="-1" aria-hidden="true">
	<?php
	the_post_thumbnail(
		'alostora-course-card',
		array(
			'class'    => 'llms-featured-image',
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);
	?>
</a>
