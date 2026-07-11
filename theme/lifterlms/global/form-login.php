<?php
/**
 * LifterLMS Login Form — Alostora presentation override.
 *
 * Authentication logic stays in LifterLMS. This template only controls markup
 * structure and Alostora chrome (heading, subtitle, stacked layout wrapper).
 *
 * @package Alostora
 * @version 1.0.1
 *
 * @param string $message  Optional messages before the form.
 * @param string $redirect Optional redirect URL after login.
 * @param string $layout   Form layout [columns|stacked] — forced to stacked.
 */

defined( 'ABSPATH' ) || exit;

$layout = 'stacked';

if ( ! isset( $redirect ) ) {
	$redirect = '';
}
?>

<?php llms_print_notices(); ?>

<?php
/**
 * Fire an action prior to the output of the login form.
 *
 * @since Unknown
 */
do_action( 'llms_before_person_login_form' );
?>

<div class="alostora-login-page__card llms-person-login-form-wrapper">

	<header class="alostora-login-page__header">
		<h1 class="alostora-login-page__title llms-form-heading"><?php echo esc_html__( 'تسجيل الدخول', 'alostora' ); ?></h1>
		<p class="alostora-login-page__subtitle"><?php echo esc_html__( 'أدخل بيانات حسابك للوصول إلى دوراتك التعليمية.', 'alostora' ); ?></p>
	</header>

	<form action="" class="llms-login llms-form" method="POST">

		<div class="llms-form-fields">

			<?php
			/**
			 * Fire an action prior to the output of the login form fields.
			 *
			 * @since Unknown
			 */
			do_action( 'lifterlms_login_form_start' );
			?>

			<?php foreach ( LLMS_Person_Handler::get_login_fields( $layout ) as $field ) : ?>
				<?php llms_form_field( $field ); ?>
			<?php endforeach; ?>

			<?php wp_nonce_field( 'llms_login_user', '_llms_login_user_nonce' ); ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
			<input type="hidden" name="action" value="llms_login_user" />

			<?php
			/**
			 * Fire an action after the output of the login form fields.
			 *
			 * @since Unknown
			 */
			do_action( 'lifterlms_login_form_end' );
			?>

		</div>

	</form>

</div>

<?php
/**
 * Fire an action after the output of the login form.
 *
 * @since Unknown
 */
do_action( 'llms_after_person_login_form' );
?>
