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

/* -------------------------------------------------------------------------
 * Student Dashboard / frontend account links.
 *
 * Students must never be sent to wp-login.php or wp-admin. Frontend "login"
 * and "my account" CTAs always point at the LifterLMS Student Dashboard page,
 * which shows the login form when logged out and the dashboard when logged in.
 * Administrators keep normal wp-admin access via WordPress core — these helpers
 * only affect theme chrome links.
 * ---------------------------------------------------------------------- */

/**
 * Whether a URL targets WordPress auth/admin screens that students must not use.
 *
 * @param string $url Candidate URL.
 * @return bool
 */
function alostora_is_forbidden_student_auth_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return true;
	}

	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	$path = strtolower( untrailingslashit( $path ) );

	if ( '' === $path ) {
		return false;
	}

	if ( false !== strpos( $path, 'wp-login.php' ) ) {
		return true;
	}

	if ( preg_match( '#(?:^|/)wp-admin(?:/|$)#', $path ) ) {
		return true;
	}

	return false;
}

/**
 * Find a published page that embeds the LifterLMS my-account shortcode.
 *
 * @return int Page ID or 0.
 */
function alostora_find_lifterlms_my_account_page_id() {
	static $cached = null;

	if ( null !== $cached ) {
		return $cached;
	}

	global $wpdb;

	$page_id = (int) $wpdb->get_var(
		"SELECT ID FROM {$wpdb->posts}
		WHERE post_type = 'page'
			AND post_status = 'publish'
			AND post_content LIKE '%[lifterlms_my_account%'
		ORDER BY ID ASC
		LIMIT 1"
	);

	$cached = $page_id > 0 ? $page_id : 0;

	return $cached;
}

/**
 * Resolve the LifterLMS Student Dashboard (My Account) page ID.
 *
 * Priority:
 * 1. LifterLMS configured `myaccount` page (`llms_get_page_id` / option).
 * 2. Published page containing `[lifterlms_my_account]`.
 *
 * @return int Page ID or 0 when unknown.
 */
function alostora_get_student_dashboard_page_id() {
	$page_id = 0;

	if ( function_exists( 'llms_get_page_id' ) ) {
		$page_id = (int) llms_get_page_id( 'myaccount' );
	}

	if ( $page_id <= 0 ) {
		$page_id = absint( get_option( 'lifterlms_myaccount_page_id', 0 ) );
	}

	if ( $page_id > 0 && 'publish' === get_post_status( $page_id ) ) {
		return $page_id;
	}

	return alostora_find_lifterlms_my_account_page_id();
}

/**
 * Permalink for the LifterLMS Student Dashboard / login page.
 *
 * Never returns wp-login.php or wp-admin URLs. Falls back to
 * home_url( '/student-dashboard/' ) when no dashboard page can be resolved.
 *
 * @return string
 */
function alostora_get_student_dashboard_url() {
	static $cached = null;

	if ( null !== $cached ) {
		return $cached;
	}

	$url = '';

	if ( function_exists( 'llms_get_page_url' ) ) {
		$candidate = llms_get_page_url( 'myaccount' );
		if ( is_string( $candidate ) && $candidate && ! alostora_is_forbidden_student_auth_url( $candidate ) ) {
			$url = $candidate;
		}
	}

	if ( ! $url ) {
		$page_id = alostora_get_student_dashboard_page_id();
		if ( $page_id > 0 ) {
			$permalink = get_permalink( $page_id );
			if ( is_string( $permalink ) && $permalink && ! alostora_is_forbidden_student_auth_url( $permalink ) ) {
				$url = $permalink;
			}
		}
	}

	if ( ! $url ) {
		$url = home_url( '/student-dashboard/' );
	}

	if ( alostora_is_forbidden_student_auth_url( $url ) ) {
		$url = home_url( '/student-dashboard/' );
	}

	$cached = $url;

	return $cached;
}

/**
 * Frontend account link label based on authentication state.
 *
 * Logged out: تسجيل الدخول (Student Dashboard shows the login form).
 * Logged in: حسابي (same dashboard URL).
 *
 * @return string
 */
function alostora_get_account_link_label() {
	if ( is_user_logged_in() ) {
		return 'حسابي';
	}

	return 'تسجيل الدخول';
}

/**
 * Account link data used by header, drawer, footer and CTAs.
 *
 * @return array{url:string,label:string}
 */
function alostora_get_account_link() {
	return array(
		'url'   => alostora_get_student_dashboard_url(),
		'label' => alostora_get_account_link_label(),
	);
}
