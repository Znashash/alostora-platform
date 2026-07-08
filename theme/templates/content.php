<?php
/**
 * Content partial: a post card used in archive/search grids.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'alostora-course' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="alostora-course__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'alostora-course-card', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="alostora-course__body">
		<h3 class="alostora-course__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<p class="alostora-course__instructor">
			<span><?php echo esc_html( get_the_date() ); ?></span>
		</p>
		<div class="alostora-course__excerpt"><?php the_excerpt(); ?></div>
	</div>
</article>
