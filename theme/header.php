<?php
/**
 * The header: opens the document and renders the Elementor header location,
 * falling back to the theme's header component when none is assigned.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'alostora' ); ?></a>

<div id="page" class="alostora-site">
	<?php
	// Let Elementor Pro own the header if a template is assigned to the location.
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
		alostora_component( 'header' );
	}
	?>
	<div id="content" class="alostora-site__content">
