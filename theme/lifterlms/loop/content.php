<?php
/**
 * LifterLMS override: catalog loop item (course / membership card).
 *
 * Overrides LifterLMS `loop/content.php`. Reproduces the LifterLMS structure with
 * the brand course-card markup while preserving every LifterLMS action hook, so
 * thumbnails, progress, author, difficulty, lesson counts and third-party
 * extensions all continue to work.
 *
 * @package Alostora
 *
 * @since LifterLMS 1.0.0 (structure), Alostora 1.0.0 (re-skin)
 */

defined( 'ABSPATH' ) || exit;
?>
<li <?php post_class( 'llms-loop-item alostora-course' ); ?>>
	<div class="llms-loop-item-content alostora-course__inner">

		<?php
		/**
		 * @hooked lifterlms_loop_featured_video - 8
		 * @hooked lifterlms_loop_link_start - 10
		 */
		do_action( 'lifterlms_before_loop_item' );
		?>

		<div class="alostora-course__media">
			<?php
			/**
			 * @hooked lifterlms_template_loop_thumbnail - 10
			 * @hooked lifterlms_template_loop_progress - 15
			 */
			do_action( 'lifterlms_before_loop_item_title' );
			?>
		</div>

		<div class="alostora-course__body">
			<h3 class="alostora-course__title llms-loop-title"><?php the_title(); ?></h3>

			<footer class="llms-loop-item-footer alostora-course__meta">
				<?php
				/**
				 * @hooked lifterlms_template_loop_author - 10
				 * @hooked lifterlms_template_loop_length - 15
				 * @hooked lifterlms_template_loop_difficulty - 20
				 * @hooked lifterlms_template_loop_lesson_count - 22
				 * @hooked lifterlms_template_loop_enroll_status - 25
				 * @hooked lifterlms_template_loop_enroll_date - 30
				 */
				do_action( 'lifterlms_after_loop_item_title' );
				?>
			</footer>
		</div>

		<?php
		/**
		 * @hooked lifterlms_loop_link_end - 5
		 */
		do_action( 'lifterlms_after_loop_item' );
		?>

	</div><!-- .llms-loop-item-content -->
</li><!-- .llms-loop-item -->
