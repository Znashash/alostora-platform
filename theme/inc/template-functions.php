<?php
/**
 * Shared render helpers used by components and template overrides.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Output the site logo: the custom logo when set, otherwise the bundled brand
 * SVG, wrapped in a home link.
 *
 * @param array $args Optional. { 'class' => string }.
 * @return void
 */
function alostora_brand_logo( $args = array() ) {
	$class = isset( $args['class'] ) ? $args['class'] : 'alostora-brand';

	echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( home_url( '/' ) ) . '" rel="home" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">';

	if ( has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image( $logo_id, 'full', false, array(
			'class'    => 'alostora-brand__img',
			'loading'  => 'eager',
			'decoding' => 'async',
			'alt'      => get_bloginfo( 'name' ),
		) );
	} else {
		echo alostora_get_svg( 'logo', array( 'class' => 'alostora-brand__img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG markup is trusted/escaped inside helper.
	}

	echo '</a>';
}

/**
 * Return an inline SVG from assets/images/icons, with an optional wrapper class.
 *
 * SVGs are trusted theme assets. Output is limited to files that exist inside
 * the icons directory to prevent path traversal.
 *
 * @param string $name SVG file name without extension.
 * @param array  $args Optional. { 'class' => string }.
 * @return string
 */
function alostora_get_svg( $name, $args = array() ) {
	$name = sanitize_file_name( $name );
	$file = ALOSTORA_DIR . 'assets/images/icons/' . $name . '.svg';

	if ( 'logo' === $name ) {
		$file = ALOSTORA_DIR . 'assets/images/logo.svg';
	}

	if ( ! is_readable( $file ) ) {
		return '';
	}

	$svg = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( false === $svg ) {
		return '';
	}

	if ( ! empty( $args['class'] ) ) {
		$svg = preg_replace( '/<svg /', '<svg class="' . esc_attr( $args['class'] ) . '" ', $svg, 1 );
	}

	return $svg;
}

/**
 * Echo the inline SVG helper output.
 *
 * @param string $name SVG file name without extension.
 * @param array  $args Optional wrapper args.
 * @return void
 */
function alostora_svg( $name, $args = array() ) {
	echo alostora_get_svg( $name, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted theme SVG.
}

/**
 * Render an accessible star-rating element.
 *
 * @param float $rating Rating out of 5.
 * @param int   $count  Optional review count.
 * @return string
 */
function alostora_get_rating( $rating, $count = 0 ) {
	$rating  = max( 0, min( 5, (float) $rating ) );
	$percent = ( $rating / 5 ) * 100;

	$label = $count
		/* translators: 1: rating value, 2: review count. */
		? sprintf( esc_html__( 'Rated %1$s out of 5 based on %2$s reviews', 'alostora' ), number_format_i18n( $rating, 1 ), number_format_i18n( $count ) )
		/* translators: %s: rating value. */
		: sprintf( esc_html__( 'Rated %s out of 5', 'alostora' ), number_format_i18n( $rating, 1 ) );

	ob_start();
	?>
	<span class="alostora-rating" role="img" aria-label="<?php echo esc_attr( $label ); ?>">
		<span class="alostora-rating__track">
			<span class="alostora-rating__fill" style="width: <?php echo esc_attr( $percent ); ?>%;"></span>
		</span>
		<span class="alostora-rating__value"><?php echo esc_html( number_format_i18n( $rating, 1 ) ); ?></span>
	</span>
	<?php
	return trim( ob_get_clean() );
}

/**
 * Whether front-end animations should run, respecting the reduce-motion toggle.
 *
 * @return bool
 */
function alostora_animations_enabled() {
	if ( get_theme_mod( 'alostora_reduce_motion', false ) ) {
		return false;
	}

	return (bool) get_theme_mod( 'alostora_enable_animations', true );
}

/**
 * Body classes that flag theme capabilities to the CSS layer.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function alostora_body_classes( $classes ) {
	$classes[] = 'alostora';
	$classes[] = is_rtl() ? 'is-rtl' : 'is-ltr';

	if ( alostora_animations_enabled() ) {
		$classes[] = 'has-animations';
	}

	// Dark-mode hook point for the future: a data attribute is added in JS, but
	// the class keeps server-rendered defaults explicit.
	$classes[] = 'theme-light';

	return $classes;
}
add_filter( 'body_class', 'alostora_body_classes' );
