<?php
/**
 * LifterLMS integration and VdoCipher secure-video support.
 *
 * The theme only overrides *presentation*. Enrolment, access control, quizzes
 * and DRM remain owned by LifterLMS and VdoCipher. Template overrides live in
 * /lifterlms and are auto-loaded by LifterLMS from the theme.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Declare LifterLMS theme support and adjust the default wrappers so LMS pages
 * inherit the Alostora container/layout instead of LifterLMS' generic markup.
 *
 * @return void
 */
function alostora_lifterlms_setup() {
	add_theme_support( 'lifterlms' );
	add_theme_support( 'lifterlms-sidebars' );
	add_theme_support( 'lifterlms-quizzes' );

	// Replace the default LifterLMS content wrappers with theme-owned wrappers.
	remove_action( 'lifterlms_before_main_content', 'lifterlms_output_content_wrapper', 10 );
	remove_action( 'lifterlms_after_main_content', 'lifterlms_output_content_wrapper_end', 10 );

	add_action( 'lifterlms_before_main_content', 'alostora_lifterlms_wrapper_start', 10 );
	add_action( 'lifterlms_after_main_content', 'alostora_lifterlms_wrapper_end', 10 );
}
add_action( 'after_setup_theme', 'alostora_lifterlms_setup' );

/**
 * Open the theme content wrapper around LifterLMS content.
 *
 * @return void
 */
function alostora_lifterlms_wrapper_start() {
	echo '<div class="alostora-llms wp-block-group alignwide"><main id="primary" class="alostora-llms__main">';
}

/**
 * Close the theme content wrapper around LifterLMS content.
 *
 * @return void
 */
function alostora_lifterlms_wrapper_end() {
	echo '</main></div>';
}

/**
 * Point LifterLMS at the theme's template override directory.
 *
 * @return string
 */
function alostora_lifterlms_template_path() {
	return 'lifterlms/';
}
add_filter( 'lifterlms_template_path', 'alostora_lifterlms_template_path' );

/**
 * Tune the courses-per-page and columns on catalog/loop views to match the
 * homepage course grid design.
 *
 * @param int $columns Default column count.
 * @return int
 */
function alostora_lifterlms_loop_columns( $columns ) {
	return 3;
}
add_filter( 'lifterlms_loop_columns', 'alostora_lifterlms_loop_columns' );

/* -------------------------------------------------------------------------
 * VdoCipher secure video integration.
 *
 * VdoCipher owns DRM and OTP generation via its own plugin/shortcode. The theme
 * only guarantees a responsive, brand-styled container and a lightweight
 * shortcode passthrough so lessons can embed a video by ID without exposing the
 * raw iframe. We never rebuild the player.
 * ---------------------------------------------------------------------- */

/**
 * Render a responsive, brand-styled wrapper around a VdoCipher embed.
 *
 * Usage: [alostora_vdocipher id="VIDEO_ID"] — delegates to the official
 * [vdo_video_embed] shortcode when the VdoCipher plugin is active, otherwise
 * renders an accessible placeholder so editors see the slot in the builder.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function alostora_vdocipher_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'id'    => '',
			'ratio' => '16x9',
			'title' => '',
		),
		$atts,
		'alostora_vdocipher'
	);

	$video_id = sanitize_text_field( $atts['id'] );
	$ratio    = in_array( $atts['ratio'], array( '16x9', '4x3', '1x1' ), true ) ? $atts['ratio'] : '16x9';
	$title    = sanitize_text_field( $atts['title'] );

	ob_start();
	?>
	<div class="alostora-video alostora-video--<?php echo esc_attr( $ratio ); ?>">
		<div class="alostora-video__frame">
			<?php
			if ( $video_id && shortcode_exists( 'vdo_video_embed' ) ) {
				echo do_shortcode( sprintf( '[vdo_video_embed id="%s"]', esc_attr( $video_id ) ) );
			} elseif ( $video_id && shortcode_exists( 'vdocipher' ) ) {
				echo do_shortcode( sprintf( '[vdocipher id="%s"]', esc_attr( $video_id ) ) );
			} else {
				printf(
					'<div class="alostora-video__placeholder" role="img" aria-label="%1$s"><span class="alostora-video__icon" aria-hidden="true"></span><span class="alostora-video__label">%2$s</span></div>',
					esc_attr( $title ? $title : esc_html__( 'Secure video', 'alostora' ) ),
					esc_html( $title ? $title : esc_html__( 'VdoCipher video will appear here', 'alostora' ) )
				);
			}
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'alostora_vdocipher', 'alostora_vdocipher_shortcode' );

/**
 * Whether the VdoCipher plugin is active (either known shortcode is registered).
 *
 * @return bool
 */
function alostora_has_vdocipher() {
	return shortcode_exists( 'vdo_video_embed' ) || shortcode_exists( 'vdocipher' );
}
