<?php
/**
 * Offline verification for Alostora frontend Arabic gettext fallback.
 *
 * Simulates WordPress gettext filters without a full WP install.
 *
 * Usage: php scripts/verify-frontend-translations.php
 */

declare(strict_types=1);

$failures = 0;

function fail( string $msg ): void {
	global $failures;
	$failures++;
	fwrite( STDERR, "FAIL: {$msg}\n" );
}

function pass( string $msg ): void {
	fwrite( STDOUT, "PASS: {$msg}\n" );
}

// Minimal WordPress stubs.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', '/tmp/' );
}

$GLOBALS['alostora_filters'] = array();
$GLOBALS['alostora_locale']  = 'ar';
$GLOBALS['alostora_is_admin'] = false;

function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	$GLOBALS['alostora_filters'][ $hook ][ $priority ][] = array(
		'callback' => $callback,
		'args'     => $accepted_args,
	);
}

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	add_filter( $hook, $callback, $priority, $accepted_args );
}

function apply_filters( $hook, $value, ...$args ) {
	if ( empty( $GLOBALS['alostora_filters'][ $hook ] ) ) {
		return $value;
	}
	ksort( $GLOBALS['alostora_filters'][ $hook ] );
	foreach ( $GLOBALS['alostora_filters'][ $hook ] as $callbacks ) {
		foreach ( $callbacks as $cb ) {
			$params = array_merge( array( $value ), $args );
			$params = array_slice( $params, 0, (int) $cb['args'] );
			$value  = call_user_func_array( $cb['callback'], $params );
		}
	}
	return $value;
}

function is_admin() {
	return ! empty( $GLOBALS['alostora_is_admin'] );
}

function wp_doing_ajax() {
	return false;
}

function determine_locale() {
	return (string) $GLOBALS['alostora_locale'];
}

function get_locale() {
	return determine_locale();
}

function is_user_logged_in() {
	return false;
}

function is_page( $id = 0 ) {
	return false;
}

function home_url( $path = '' ) {
	return 'https://example.test' . $path;
}

function esc_html( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
}

function esc_html__( $text, $domain = 'default' ) {
	unset( $domain );
	return (string) $text;
}

function esc_url( $url ) {
	return (string) $url;
}

require_once dirname( __DIR__ ) . '/theme/inc/frontend-translations.php';

$required = array(
	'Dashboard'                              => 'لوحة التحكم',
	'My Courses'                             => 'دوراتي',
	'My Grades'                              => 'علاماتي',
	'Edit Account'                           => 'تعديل الحساب',
	'Sign Out'                               => 'تسجيل الخروج',
	'You are not enrolled in any courses.'   => 'أنت غير مسجل في أي دورة حاليًا.',
	'Login'                                  => 'تسجيل الدخول',
	'Username or Email Address'              => 'اسم المستخدم أو البريد الإلكتروني',
	'Password'                               => 'كلمة المرور',
	'Remember me'                            => 'تذكرني',
	'Lost your password?'                    => 'نسيت كلمة المرور؟',
	'First Name'                             => 'الاسم الأول',
	'Serial Number'                          => 'الرقم التسلسلي',
	'Register'                               => 'إنشاء الحساب',
);

foreach ( $required as $english => $arabic ) {
	// Domain deliberately wrong / unexpected — fallback must still translate.
	$out = apply_filters( 'gettext', $english, $english, 'some-unexpected-domain' );
	if ( $out !== $arabic ) {
		fail( "gettext '{$english}' => '{$out}' (expected '{$arabic}')" );
	} else {
		pass( "gettext {$english}" );
	}
}

// with_context (3 accepted args as registered).
$ctx = apply_filters( 'gettext_with_context', 'Dashboard', 'Dashboard', 'menu' );
if ( 'لوحة التحكم' !== $ctx ) {
	fail( "gettext_with_context Dashboard => {$ctx}" );
} else {
	pass( 'gettext_with_context Dashboard' );
}

// English locale must leave strings alone.
$GLOBALS['alostora_locale'] = 'en_US';
$en = apply_filters( 'gettext', 'Dashboard', 'Dashboard', 'lifterlms' );
if ( 'Dashboard' !== $en ) {
	fail( "en_US locale unexpectedly translated Dashboard => {$en}" );
} else {
	pass( 'en_US locale leaves Dashboard English' );
}

// Admin must not translate.
$GLOBALS['alostora_locale']  = 'ar';
$GLOBALS['alostora_is_admin'] = true;
$admin = apply_filters( 'gettext', 'Dashboard', 'Dashboard', 'lifterlms' );
if ( 'Dashboard' !== $admin ) {
	fail( "admin unexpectedly translated Dashboard => {$admin}" );
} else {
	pass( 'admin leaves Dashboard English' );
}

// Simulate dashboard tabs after gettext (titles already English if MO missing).
$GLOBALS['alostora_is_admin'] = false;
$tabs = array(
	'dashboard'     => array( 'title' => 'Dashboard' ),
	'view-courses'  => array( 'title' => 'My Courses' ),
	'my-grades'     => array( 'title' => 'My Grades' ),
	'edit-account'  => array( 'title' => 'Edit Account' ),
	'signout'       => array( 'title' => 'Sign Out' ),
);
$tabs = apply_filters( 'llms_get_student_dashboard_tabs', $tabs );
foreach ( array(
	'dashboard'    => 'لوحة التحكم',
	'view-courses' => 'دوراتي',
	'my-grades'    => 'علاماتي',
	'edit-account' => 'تعديل الحساب',
	'signout'      => 'تسجيل الخروج',
) as $key => $want ) {
	$got = $tabs[ $key ]['title'];
	if ( $got !== $want ) {
		fail( "dashboard tab {$key} => {$got}" );
	} else {
		pass( "dashboard tab {$key}" );
	}
}

// Simulate login fields.
$fields = array(
	array(
		'id'      => 'llms_login',
		'label'   => 'Username or Email Address',
		'type'    => 'text',
		'columns' => 6,
	),
	array(
		'id'      => 'llms_password',
		'label'   => 'Password',
		'type'    => 'password',
		'columns' => 6,
	),
	array(
		'id'    => 'llms_login_button',
		'value' => 'Login',
		'type'  => 'submit',
	),
	array(
		'id'    => 'llms_remember',
		'label' => 'Remember me',
		'type'  => 'checkbox',
	),
	array(
		'id'          => 'llms_lost_password',
		'description' => '<a href="#">Lost your password?</a>',
		'type'        => 'html',
	),
);
$fields = apply_filters( 'lifterlms_person_login_fields', $fields );

$labels = array();
foreach ( $fields as $field ) {
	if ( ! empty( $field['label'] ) ) {
		$labels[] = $field['label'];
	}
	if ( ! empty( $field['value'] ) ) {
		$labels[] = $field['value'];
	}
	if ( ! empty( $field['description'] ) ) {
		$labels[] = $field['description'];
	}
}
$blob = implode( "\n", $labels );

foreach ( array( 'اسم المستخدم أو البريد الإلكتروني', 'كلمة المرور', 'تسجيل الدخول', 'تذكرني', 'نسيت كلمة المرور؟' ) as $needle ) {
	if ( false === strpos( $blob, $needle ) ) {
		fail( "login fields missing {$needle}" );
	} else {
		pass( "login fields contain {$needle}" );
	}
}

// functions.php must load the new file.
$functions = file_get_contents( dirname( __DIR__ ) . '/theme/functions.php' );
if ( false === strpos( $functions, 'inc/frontend-translations.php' ) ) {
	fail( 'functions.php does not require frontend-translations.php' );
} else {
	pass( 'functions.php loads frontend-translations.php' );
}

$template = dirname( __DIR__ ) . '/theme/lifterlms/global/form-login.php';
if ( ! is_readable( $template ) ) {
	fail( 'missing lifterlms/global/form-login.php override' );
} else {
	$html = file_get_contents( $template );
	foreach ( array( 'تسجيل الدخول', 'أدخل بيانات حسابك للوصول إلى دوراتك التعليمية.' ) as $needle ) {
		if ( false === strpos( $html, $needle ) ) {
			fail( "login template missing {$needle}" );
		} else {
			pass( "login template contains {$needle}" );
		}
	}
}

if ( $failures > 0 ) {
	fwrite( STDERR, "\n{$failures} failure(s)\n" );
	exit( 1 );
}

fwrite( STDOUT, "\nAll frontend translation checks passed.\n" );
exit( 0 );
