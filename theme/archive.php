<?php
/**
 * Archive template.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) :
	?>
	<div class="alostora-container alostora-section">
		<header class="alostora-section__head">
			<?php the_archive_title( '<h1 class="alostora-section__title">', '</h1>' ); ?>
			<?php the_archive_description( '<p class="alostora-section__subtitle">', '</p>' ); ?>
		</header>

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
