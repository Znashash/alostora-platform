<?php
/**
 * 404 template.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="alostora-container alostora-section u-text-center">
	<header class="alostora-section__head">
		<h1 class="alostora-section__title"><?php esc_html_e( '404', 'alostora' ); ?></h1>
		<p class="alostora-section__subtitle"><?php esc_html_e( 'The page you are looking for could not be found.', 'alostora' ); ?></p>
	</header>
	<div class="alostora-cluster" style="justify-content:center;">
		<?php
		alostora_component( 'buttons', array(
			'label' => esc_html__( 'Back to home', 'alostora' ),
			'url'   => home_url( '/' ),
			'style' => 'primary',
		) );
		?>
	</div>
	<div style="max-inline-size:520px;margin-inline:auto;margin-block-start:2rem;">
		<?php get_search_form(); ?>
	</div>
</div>
<?php
get_footer();
