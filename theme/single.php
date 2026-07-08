<?php
/**
 * Template for displaying single posts.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'alostora-container alostora-container--narrow alostora-section' ); ?>>
			<header class="alostora-entry__header">
				<?php the_title( '<h1 class="alostora-entry__title">', '</h1>' ); ?>
				<div class="alostora-entry__meta">
					<?php echo esc_html( get_the_date() ); ?>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="alostora-entry__thumb">
					<?php the_post_thumbnail( 'alostora-hero' ); ?>
				</figure>
			<?php endif; ?>

			<div class="alostora-entry__content entry-content">
				<?php
				the_content();
				wp_link_pages( array(
					'before' => '<div class="alostora-page-links">' . esc_html__( 'Pages:', 'alostora' ),
					'after'  => '</div>',
				) );
				?>
			</div>
		</article>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
endif;

get_footer();
