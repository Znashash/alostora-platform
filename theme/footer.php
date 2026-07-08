<?php
/**
 * The footer: renders the Elementor footer location, falling back to the theme's
 * footer component, then closes the document.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;
?>
	</div><!-- #content -->

	<?php
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
		alostora_component( 'footer' );
	}
	?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
