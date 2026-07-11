<?php
/**
 * Frontend Arabic translation fallback (gettext).
 *
 * Runs on the frontend only when the active locale starts with `ar`. Does not
 * edit LifterLMS or Academy Serial Enrollment core files. Intentionally does
 * not restrict by text domain — production installs have shown LifterLMS /
 * serial-form strings arriving under unexpected domains, which made a domain
 * allow-list drop Arabic replacements.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * English → Arabic map for LifterLMS account UI and serial enrollment forms.
 *
 * @return array<string,string>
 */
function alostora_get_frontend_translation_map() {
	static $map = null;

	if ( null !== $map ) {
		return $map;
	}

	$map = array(
		// Student dashboard.
		'Dashboard'                              => 'لوحة التحكم',
		'My Courses'                             => 'دوراتي',
		'My Grades'                              => 'علاماتي',
		'Edit Account'                           => 'تعديل الحساب',
		'Sign Out'                               => 'تسجيل الخروج',
		'View All My Courses'                    => 'عرض جميع دوراتي',
		'You are not enrolled in any courses.'   => 'أنت غير مسجل في أي دورة حاليًا.',
		'My Achievements'                        => 'إنجازاتي',
		'View All My Achievements'               => 'عرض جميع إنجازاتي',
		'My Certificates'                        => 'شهاداتي',
		'View All My Certificates'               => 'عرض جميع شهاداتي',
		'My Memberships'                         => 'عضوياتي',
		'View All My Memberships'                => 'عرض جميع عضوياتي',
		'You are not enrolled in any memberships.' => 'أنت غير مسجل في أي عضوية حاليًا.',
		'My Orders'                              => 'طلباتي',
		'Order History'                          => 'طلباتي',
		'Notifications'                          => 'الإشعارات',
		'View Notifications'                     => 'عرض الإشعارات',
		'My Favorites'                           => 'مفضلاتي',
		'Redeem a Voucher'                       => 'استبدال قسيمة',

		// Login / account forms.
		'Login'                                  => 'تسجيل الدخول',
		'Log In'                                 => 'تسجيل الدخول',
		'Username or Email Address'              => 'اسم المستخدم أو البريد الإلكتروني',
		'Username'                               => 'اسم المستخدم',
		'Email Address'                          => 'البريد الإلكتروني',
		'Password'                               => 'كلمة المرور',
		'Remember Me'                            => 'تذكرني',
		'Remember me'                            => 'تذكرني',
		'Lost your password?'                    => 'نسيت كلمة المرور؟',
		'Forgot Password?'                       => 'هل نسيت كلمة المرور؟',
		'Lost your password? Enter your email address and we will send you a link to reset it.' => 'نسيت كلمة المرور؟ أدخل بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيينها.',
		'Lost your password? Enter your username or email address and we will send you a link to reset it.' => 'نسيت كلمة المرور؟ أدخل اسم المستخدم أو بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيينها.',
		'Reset Password'                         => 'إعادة تعيين كلمة المرور',
		'Get New Password'                       => 'إرسال رابط إعادة التعيين',
		'New Password'                           => 'كلمة المرور الجديدة',
		'Confirm New Password'                   => 'تأكيد كلمة المرور الجديدة',
		'Save Password'                          => 'حفظ كلمة المرور',
		'Not a member yet?'                      => 'ليس لديك حساب؟',
		'Already have an account?'               => 'لديك حساب بالفعل؟',
		'Create Account'                         => 'إنشاء حساب جديد',

		// Serial enrollment / registration.
		'First Name'                             => 'الاسم الأول',
		'Last Name'                              => 'اسم العائلة',
		'Phone Number'                           => 'رقم الهاتف',
		'Email'                                  => 'البريد الإلكتروني',
		'Serial Number'                          => 'الرقم التسلسلي',
		'Confirm Password'                       => 'تأكيد كلمة المرور',
		'I agree to the terms and conditions'    => 'أوافق على الشروط والأحكام',
		'Register'                               => 'إنشاء الحساب',
		'Create My Account'                      => 'إنشاء الحساب',
		'Invalid email address'                  => 'البريد الإلكتروني غير صحيح',
		'Passwords do not match'                 => 'كلمتا المرور غير متطابقتين',
		'Invalid or used serial number'          => 'الرقم التسلسلي غير صالح أو تم استخدامه مسبقًا',
		'Registration successful'                => 'تم إنشاء الحساب بنجاح',
		'Required field'                         => 'هذا الحقل مطلوب',
		'Show Password'                          => 'إظهار كلمة المرور',
		'Hide Password'                          => 'إخفاء كلمة المرور',
		'This email address is already registered' => 'هذا البريد الإلكتروني مسجل مسبقًا',
		'This phone number is already registered'  => 'رقم الهاتف مسجل مسبقًا',

		// Shared account editing.
		'Account Details'                        => 'بيانات الحساب',
		'Current Password'                       => 'كلمة المرور الحالية',
		'Save Changes'                           => 'حفظ التغييرات',
		'Save'                                   => 'حفظ التغييرات',
		'Continue'                               => 'متابعة التعلم',
		'Progress'                               => 'التقدم',
		'Status'                                 => 'الحالة',
		'Completed'                              => 'مكتملة',
		'In Progress'                            => 'قيد التقدم',
		'Not Started'                            => 'لم تبدأ',
	);

	return $map;
}

/**
 * Whether the frontend Arabic gettext fallback should run.
 *
 * @return bool
 */
function alostora_should_apply_frontend_arabic_translations() {
	if ( is_admin() && ! ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) ) {
		return false;
	}

	$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();

	return 0 === strpos( (string) $locale, 'ar' );
}

/**
 * Scoped gettext fallback for Arabic frontend strings.
 *
 * @param string $translated Existing translation.
 * @param string $original   English source string.
 * @param string $domain     Text domain (ignored — see file header).
 * @return string
 */
function alostora_frontend_arabic_translations( $translated, $original, $domain = '' ) {
	unset( $domain );

	if ( ! alostora_should_apply_frontend_arabic_translations() ) {
		return $translated;
	}

	if ( ! is_string( $original ) || '' === $original ) {
		return $translated;
	}

	$map = alostora_get_frontend_translation_map();

	if ( isset( $map[ $original ] ) ) {
		return $map[ $original ];
	}

	return $translated;
}
add_filter( 'gettext', 'alostora_frontend_arabic_translations', 999, 3 );
add_filter( 'gettext_with_context', 'alostora_frontend_arabic_translations', 999, 3 );

/**
 * Apply the same map to ngettext sources used on account screens.
 *
 * @param string $translated Existing translation.
 * @param string $single     Singular source.
 * @param string $plural     Plural source.
 * @param int    $number     Number.
 * @param string $domain     Text domain.
 * @return string
 */
function alostora_frontend_arabic_ntranslations( $translated, $single, $plural, $number, $domain = '' ) {
	unset( $domain );

	if ( ! alostora_should_apply_frontend_arabic_translations() ) {
		return $translated;
	}

	$source = ( 1 === (int) $number ) ? $single : $plural;
	$map    = alostora_get_frontend_translation_map();

	return isset( $map[ $source ] ) ? $map[ $source ] : $translated;
}
add_filter( 'ngettext', 'alostora_frontend_arabic_ntranslations', 999, 5 );

/**
 * Translate a string via the frontend map (for non-gettext sources).
 *
 * @param string $text Source text.
 * @return string
 */
function alostora_map_frontend_string( $text ) {
	if ( ! is_string( $text ) || '' === $text ) {
		return $text;
	}

	$map = alostora_get_frontend_translation_map();

	if ( isset( $map[ $text ] ) ) {
		return $map[ $text ];
	}

	if ( preg_match( '/^Confirm (.+)$/', $text, $matches ) ) {
		$inner = isset( $map[ $matches[1] ] ) ? $map[ $matches[1] ] : $matches[1];
		return sprintf( 'تأكيد %s', $inner );
	}

	return $text;
}

/**
 * Translate LifterLMS form field attribute arrays (DB-persisted labels).
 *
 * @param array $attrs Field attributes.
 * @return array
 */
function alostora_translate_frontend_field_attrs( $attrs ) {
	if ( ! alostora_should_apply_frontend_arabic_translations() || ! is_array( $attrs ) ) {
		return $attrs;
	}

	foreach ( array( 'label', 'description', 'placeholder', 'value', 'button_text' ) as $key ) {
		if ( empty( $attrs[ $key ] ) || ! is_string( $attrs[ $key ] ) ) {
			continue;
		}

		if ( 'description' === $key && false !== strpos( $attrs[ $key ], '<' ) ) {
			$attrs[ $key ] = preg_replace_callback(
				'/>\s*([^<]+)\s*</',
				static function ( $matches ) {
					return '>' . alostora_map_frontend_string( trim( $matches[1] ) ) . '<';
				},
				$attrs[ $key ]
			);
			continue;
		}

		$attrs[ $key ] = alostora_map_frontend_string( $attrs[ $key ] );
	}

	return $attrs;
}
add_filter( 'llms_forms_block_to_field_settings', 'alostora_translate_frontend_field_attrs', 999 );

/**
 * Translate assembled LifterLMS / person form field lists.
 *
 * @param array $fields Fields.
 * @return array
 */
function alostora_translate_frontend_form_fields( $fields ) {
	if ( ! alostora_should_apply_frontend_arabic_translations() || ! is_array( $fields ) ) {
		return $fields;
	}

	foreach ( $fields as $index => $field ) {
		if ( is_array( $field ) ) {
			$fields[ $index ] = alostora_translate_frontend_field_attrs( $field );
		}
	}

	return $fields;
}
add_filter( 'llms_get_form_fields', 'alostora_translate_frontend_form_fields', 999 );
add_filter( 'lifterlms_get_person_fields', 'alostora_translate_frontend_form_fields', 999 );
add_filter( 'lifterlms_person_login_fields', 'alostora_translate_frontend_form_fields', 999 );

/**
 * Force stacked login fields and normalize remember / lost-password row.
 *
 * @param array $fields Login fields.
 * @return array
 */
function alostora_normalize_login_fields( $fields ) {
	if ( ! is_array( $fields ) ) {
		return $fields;
	}

	$normalized = array();
	$meta       = array();
	$submit     = null;

	foreach ( $fields as $field ) {
		if ( ! is_array( $field ) ) {
			continue;
		}

		$field['columns']     = 12;
		$field['last_column'] = true;

		$id = isset( $field['id'] ) ? (string) $field['id'] : '';

		if ( 'llms_remember' === $id || 'llms_lost_password' === $id ) {
			$field['wrapper_classes'] = trim(
				( isset( $field['wrapper_classes'] ) ? $field['wrapper_classes'] . ' ' : '' ) . 'alostora-login-meta-item'
			);
			$meta[] = $field;
			continue;
		}

		if ( isset( $field['type'] ) && 'submit' === $field['type'] ) {
			$submit = $field;
			continue;
		}

		if ( 'llms_password' === $id || ( isset( $field['type'] ) && 'password' === $field['type'] ) ) {
			$field['wrapper_classes'] = trim(
				( isset( $field['wrapper_classes'] ) ? $field['wrapper_classes'] . ' ' : '' ) . 'alostora-password-field'
			);
		}

		$normalized[] = $field;
	}

	if ( $meta ) {
		$normalized[] = array(
			'columns'         => 12,
			'id'              => 'alostora_login_meta',
			'last_column'     => true,
			'type'            => 'html',
			'wrapper_classes' => 'alostora-login-meta-row',
			'description'     => alostora_render_login_meta_html( $meta ),
		);
	}

	if ( null !== $submit ) {
		$normalized[] = $submit;
	}

	return $normalized;
}
add_filter( 'lifterlms_person_login_fields', 'alostora_normalize_login_fields', 1000 );

/**
 * Build remember-me + lost-password markup for one meta row.
 *
 * @param array $meta_fields Meta field definitions.
 * @return string
 */
function alostora_render_login_meta_html( $meta_fields ) {
	$remember = '';
	$lost     = '';

	foreach ( $meta_fields as $field ) {
		$id = isset( $field['id'] ) ? $field['id'] : '';

		if ( 'llms_remember' === $id ) {
			$label    = isset( $field['label'] ) ? $field['label'] : 'Remember me';
			$remember = sprintf(
				'<label class="alostora-login-remember" for="llms_remember"><input type="checkbox" name="llms_remember" id="llms_remember" value="yes" /> <span>%s</span></label>',
				esc_html( $label )
			);
		}

		if ( 'llms_lost_password' === $id && ! empty( $field['description'] ) ) {
			$lost = '<div class="alostora-login-lost">' . $field['description'] . '</div>';
		}
	}

	return '<div class="alostora-login-meta-row__inner">' . $remember . $lost . '</div>';
}

/**
 * Translate LifterLMS student dashboard tab titles (belt-and-suspenders).
 *
 * @param array $tabs Tabs.
 * @return array
 */
function alostora_translate_frontend_dashboard_tabs( $tabs ) {
	if ( ! alostora_should_apply_frontend_arabic_translations() || ! is_array( $tabs ) ) {
		return $tabs;
	}

	foreach ( $tabs as $key => $tab ) {
		if ( ! empty( $tab['title'] ) && is_string( $tab['title'] ) ) {
			$tabs[ $key ]['title'] = alostora_map_frontend_string( $tab['title'] );
		}
	}

	return $tabs;
}
add_filter( 'llms_get_student_dashboard_tabs', 'alostora_translate_frontend_dashboard_tabs', 999 );
add_filter( 'llms_get_student_dashboard_tabs_for_nav', 'alostora_translate_frontend_dashboard_tabs', 999 );

/**
 * Last-resort HTML swap for serial enrollment markup that bypasses gettext.
 *
 * @param string $content Post content.
 * @return string
 */
function alostora_translate_serial_enrollment_html( $content ) {
	if ( ! alostora_should_apply_frontend_arabic_translations() || ! is_string( $content ) || '' === $content ) {
		return $content;
	}

	if ( ! preg_match( '/serial-enrollment|academy-serial|ase-registration|ase-reg|Serial Number|الرقم التسلسلي/i', $content ) ) {
		return $content;
	}

	$map = alostora_get_frontend_translation_map();
	uksort(
		$map,
		static function ( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}
	);

	return strtr( $content, $map );
}
add_filter( 'the_content', 'alostora_translate_serial_enrollment_html', 999 );

/**
 * Mark the logged-out LifterLMS account page for login presentation CSS.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function alostora_login_body_class( $classes ) {
	if ( is_user_logged_in() ) {
		return $classes;
	}

	$page_id = function_exists( 'alostora_get_student_dashboard_page_id' )
		? (int) alostora_get_student_dashboard_page_id()
		: 0;

	if ( $page_id && is_page( $page_id ) ) {
		$classes[] = 'alostora-login-page';
	} elseif ( function_exists( 'is_llms_account_page' ) && is_llms_account_page() ) {
		$classes[] = 'alostora-login-page';
	}

	return $classes;
}
add_filter( 'body_class', 'alostora_login_body_class' );

/**
 * Render register CTA under the login form.
 *
 * @return void
 */
function alostora_render_login_register_cta() {
	if ( is_user_logged_in() ) {
		return;
	}

	$url = function_exists( 'alostora_get_registration_url' )
		? alostora_get_registration_url()
		: home_url( '/' );
	?>
	<div class="alostora-login-register">
		<p class="alostora-login-register__prompt"><?php echo esc_html__( 'ليس لديك حساب؟', 'alostora' ); ?></p>
		<a class="alostora-login-register__link" href="<?php echo esc_url( $url ); ?>">
			<?php echo esc_html__( 'إنشاء حساب جديد', 'alostora' ); ?>
		</a>
	</div>
	<?php
}
add_action( 'llms_after_person_login_form', 'alostora_render_login_register_cta', 20 );
