<?php
/**
 * The main template file.
 *
 * Per the Hello Elementor philosophy, Elementor Pro Theme Builder owns the
 * archive/single layouts via registered locations. This template only provides a
 * clean, accessible fallback when no Elementor template is assigned.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Elementor archive/single locations take over when assigned.
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) :
	?>
	<div class="alostora-container alostora-section">
		<?php if ( have_posts() ) : ?>
			<div class="alostora-grid alostora-grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'templates/content', get_post_type() );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => esc_html__( 'Previous', 'alostora' ),
				'next_text' => esc_html__( 'Next', 'alostora' ),
			) ); ?>
		<?php else : ?>
			<?php get_template_part( 'templates/content', 'none' ); ?>
		<?php endif; ?>
	</div>
	<?php
endif;

get_footer();
