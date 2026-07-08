<?php
/**
 * Search results template.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="alostora-container alostora-section">
	<header class="alostora-section__head">
		<h1 class="alostora-section__title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Search results for: %s', 'alostora' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			?>
		</h1>
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
get_footer();
