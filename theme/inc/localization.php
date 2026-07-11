<?php
/**
 * Frontend Arabic localization for LifterLMS account UI and related forms.
 *
 * Upgrade-safe: does not edit LifterLMS core. Uses gettext filters and LifterLMS
 * form field filters so plugin updates remain intact. Only runs on the frontend
 * when the site locale is Arabic, so English remains available when the site
 * language changes.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'alostora_is_frontend_arabic' ) ) {
	return;
}

/**
 * Whether frontend Arabic localization should run.
 *
 * Skips the WordPress admin UI (except frontend-bound AJAX) so admin strings
 * stay in their original language. Uses locale, blog language, and RTL as
 * signals — production sites sometimes keep en_US locale while serving Arabic.
 *
 * @return bool
 */
function alostora_is_frontend_arabic() {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return false;
	}

	$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
	if ( preg_match( '/^ar([_\-]|$)/i', (string) $locale ) ) {
		return true;
	}

	$lang = (string) get_bloginfo( 'language' );
	if ( preg_match( '/^ar([_\-]|$)/i', $lang ) ) {
		return true;
	}

	$wplang = (string) get_option( 'WPLANG', '' );
	if ( preg_match( '/^ar([_\-]|$)/i', $wplang ) ) {
		return true;
	}

	// Arabic production installs are RTL; use as a final frontend signal.
	if ( function_exists( 'is_rtl' ) && is_rtl() ) {
		return true;
	}

	return false;
}

/**
 * Map of English LifterLMS (and shared form) strings → Arabic.
 *
 * Keys must match LifterLMS gettext sources exactly where possible.
 *
 * @return array<string,string>
 */
function alostora_get_lifterlms_frontend_translations() {
	static $map = null;

	if ( null !== $map ) {
		return $map;
	}

	$map = array(
		// Dashboard navigation & sections.
		'Dashboard'                             => 'لوحة التحكم',
		'My Courses'                            => 'دوراتي',
		'My Grades'                             => 'علاماتي',
		'Edit Account'                          => 'تعديل الحساب',
		'Sign Out'                              => 'تسجيل الخروج',
		'View All My Courses'                   => 'عرض جميع دوراتي',
		'You are not enrolled in any courses.'  => 'أنت غير مسجل في أي دورة حاليًا.',
		'My Achievements'                       => 'إنجازاتي',
		'View All My Achievements'              => 'عرض جميع إنجازاتي',
		'My Certificates'                       => 'شهاداتي',
		'View All My Certificates'              => 'عرض جميع شهاداتي',
		'My Memberships'                        => 'عضوياتي',
		'View All My Memberships'               => 'عرض جميع عضوياتي',
		'You are not enrolled in any memberships.' => 'أنت غير مسجل في أي عضوية حاليًا.',
		'My Orders'                             => 'طلباتي',
		'Order History'                         => 'طلباتي',
		'Notifications'                         => 'الإشعارات',
		'View Notifications'                    => 'عرض الإشعارات',
		'Manage Preferences'                    => 'إدارة التفضيلات',
		'My Favorites'                          => 'مفضلاتي',
		'Redeem a Voucher'                      => 'استبدال قسيمة',
		'Welcome'                               => 'مرحبًا',
		'Course'                                => 'الدورة',
		'Courses'                               => 'الدورات',
		'Progress'                              => 'التقدم',
		'Status'                                => 'الحالة',
		'Continue'                              => 'متابعة التعلم',
		'Completed'                             => 'مكتملة',
		'In Progress'                           => 'قيد التقدم',
		'Not Started'                           => 'لم تبدأ',

		// Login.
		'Username or Email Address'             => 'اسم المستخدم أو البريد الإلكتروني',
		'Username'                              => 'اسم المستخدم',
		'Email Address'                         => 'البريد الإلكتروني',
		'Password'                              => 'كلمة المرور',
		'Remember Me'                           => 'تذكرني',
		'Remember me'                           => 'تذكرني',
		'Log In'                                => 'تسجيل الدخول',
		'Login'                                 => 'تسجيل الدخول',
		'Forgot Password?'                      => 'هل نسيت كلمة المرور؟',
		'Lost your password?'                   => 'نسيت كلمة المرور؟',
		'Register'                              => 'إنشاء الحساب',
		'Create Account'                        => 'إنشاء حساب جديد',
		'Not a member yet?'                     => 'ليس لديك حساب؟',
		'Already have an account?'              => 'لديك حساب بالفعل؟',

		// Registration.
		'First Name'                            => 'الاسم الأول',
		'Last Name'                             => 'اسم العائلة',
		'Phone Number'                          => 'رقم الهاتف',
		'Email'                                 => 'البريد الإلكتروني',
		'Confirm Email'                         => 'تأكيد البريد الإلكتروني',
		'Confirm Password'                      => 'تأكيد كلمة المرور',
		'Create My Account'                     => 'إنشاء حسابي',
		'Terms and Conditions'                  => 'الشروط والأحكام',
		'I agree to the terms and conditions'   => 'أوافق على الشروط والأحكام',

		// Password reset.
		'Reset Password'                        => 'إعادة تعيين كلمة المرور',
		'Enter your email address'              => 'أدخل بريدك الإلكتروني',
		'Get New Password'                      => 'إرسال رابط إعادة التعيين',
		'New Password'                          => 'كلمة المرور الجديدة',
		'Confirm New Password'                  => 'تأكيد كلمة المرور الجديدة',
		'Save Password'                         => 'حفظ كلمة المرور',
		'Check your email'                      => 'تحقق من بريدك الإلكتروني',
		'Check your e-mail for the confirmation link.' => 'تحقق من بريدك الإلكتروني لرابط التأكيد.',
		'Lost your password? Enter your email address and we will send you a link to reset it.' => 'نسيت كلمة المرور؟ أدخل بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيينها.',
		'Lost your password? Enter your username or email address and we will send you a link to reset it.' => 'نسيت كلمة المرور؟ أدخل اسم المستخدم أو بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيينها.',

		// Account editing.
		'Account Details'                       => 'بيانات الحساب',
		'Current Password'                      => 'كلمة المرور الحالية',
		'Save Changes'                          => 'حفظ التغييرات',
		'Save'                                  => 'حفظ التغييرات',
		'Profile'                               => 'الملف الشخصي',
		'Billing Address'                       => 'عنوان الفوترة',
		'Update'                                => 'تحديث',
		'Display Name'                          => 'اسم العرض',
		'Address'                               => 'العنوان',
		'City'                                  => 'المدينة',
		'Country'                               => 'الدولة',
		'State / Region'                        => 'المنطقة / المحافظة',
		'Postal / Zip Code'                     => 'الرمز البريدي',
		'Your account information has been saved.' => 'تم حفظ معلومات حسابك.',
		'Your password has been updated.'       => 'تم تحديث كلمة المرور.',

		// Serial enrollment (unique strings + shared labels when domain matches).
		'Serial Number'                         => 'الرقم التسلسلي',
		'Show Password'                         => 'إظهار كلمة المرور',
		'Hide Password'                         => 'إخفاء كلمة المرور',
		'Required field'                        => 'هذا الحقل مطلوب',
		'Invalid email address'                 => 'البريد الإلكتروني غير صحيح',
		'Passwords do not match'                => 'كلمتا المرور غير متطابقتين',
		'Invalid or used serial number'         => 'الرقم التسلسلي غير صالح أو تم استخدامه مسبقًا',
		'Registration successful'               => 'تم إنشاء الحساب بنجاح',
		'This email address is already registered' => 'هذا البريد الإلكتروني مسجل مسبقًا',
		'This phone number is already registered' => 'رقم الهاتف مسجل مسبقًا',
	);

	return $map;
}

/**
 * Domains allowed for frontend Arabic string replacement.
 *
 * @param string $domain Text domain.
 * @return bool
 */
function alostora_is_localizable_frontend_domain( $domain ) {
	$domain = strtolower( (string) $domain );

	if ( 'lifterlms' === $domain ) {
		return true;
	}

	// Academy Serial Enrollment and close variants.
	if ( false !== strpos( $domain, 'serial' ) || false !== strpos( $domain, 'academy' ) || 0 === strpos( $domain, 'ase' ) ) {
		return true;
	}

	return false;
}

/**
 * Unique serial-enrollment strings safe to translate on any frontend domain.
 *
 * @param string $text Source string.
 * @return bool
 */
function alostora_is_unique_serial_string( $text ) {
	$unique = array(
		'Serial Number'                              => true,
		'Show Password'                              => true,
		'Hide Password'                              => true,
		'Invalid or used serial number'              => true,
		'Registration successful'                    => true,
		'This email address is already registered'   => true,
		'This phone number is already registered'    => true,
		'I agree to the terms and conditions'        => true,
		'Passwords do not match'                     => true,
		'Invalid email address'                      => true,
		'Required field'                             => true,
		'First Name'                                 => true,
		'Last Name'                                  => true,
		'Phone Number'                               => true,
		'Confirm Password'                           => true,
		'Terms and Conditions'                       => true,
	);

	return isset( $unique[ $text ] );
}

/**
 * Translate a single English source string when a mapping exists.
 *
 * @param string $text Source string.
 * @return string
 */
function alostora_translate_mapped_string( $text ) {
	$map = alostora_get_lifterlms_frontend_translations();

	if ( isset( $map[ $text ] ) ) {
		return $map[ $text ];
	}

	// Dynamic "Confirm {Label}" used by LifterLMS duplicate fields.
	if ( preg_match( '/^Confirm (.+)$/', $text, $matches ) ) {
		$inner = isset( $map[ $matches[1] ] ) ? $map[ $matches[1] ] : $matches[1];
		return sprintf( 'تأكيد %s', $inner );
	}

	return $text;
}

/**
 * gettext filter for LifterLMS / serial enrollment frontend strings.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @return string
 */
function alostora_filter_frontend_gettext( $translation, $text, $domain ) {
	if ( ! alostora_is_frontend_arabic() ) {
		return $translation;
	}

	if ( ! alostora_is_localizable_frontend_domain( $domain ) && ! alostora_is_unique_serial_string( $text ) ) {
		return $translation;
	}

	$mapped = alostora_translate_mapped_string( $text );

	return ( $mapped !== $text ) ? $mapped : $translation;
}
add_filter( 'gettext', 'alostora_filter_frontend_gettext', 20, 3 );

/**
 * gettext_with_context filter for the same domains.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $context     Context.
 * @param string $domain      Text domain.
 * @return string
 */
function alostora_filter_frontend_gettext_with_context( $translation, $text, $context, $domain ) {
	unset( $context );
	return alostora_filter_frontend_gettext( $translation, $text, $domain );
}
add_filter( 'gettext_with_context', 'alostora_filter_frontend_gettext_with_context', 20, 4 );

/**
 * ngettext filter for LifterLMS plural forms used on account pages.
 *
 * @param string $translation Translated text.
 * @param string $single      Singular.
 * @param string $plural      Plural.
 * @param int    $number      Number.
 * @param string $domain      Text domain.
 * @return string
 */
function alostora_filter_frontend_ngettext( $translation, $single, $plural, $number, $domain ) {
	if ( ! alostora_is_frontend_arabic() ) {
		return $translation;
	}

	$source = ( 1 === (int) $number ) ? $single : $plural;

	if ( ! alostora_is_localizable_frontend_domain( $domain ) && ! alostora_is_unique_serial_string( $source ) ) {
		return $translation;
	}

	$mapped = alostora_translate_mapped_string( $source );

	return ( $mapped !== $source ) ? $mapped : $translation;
}
add_filter( 'ngettext', 'alostora_filter_frontend_ngettext', 20, 5 );

/**
 * Translate LifterLMS form field labels stored in form posts / blocks.
 *
 * Block forms persist English labels in the database; gettext alone cannot
 * reach them. This filter rewrites known labels at render time.
 *
 * @param array $attrs Field attributes.
 * @return array
 */
function alostora_translate_llms_field_attrs( $attrs ) {
	if ( ! alostora_is_frontend_arabic() || ! is_array( $attrs ) ) {
		return $attrs;
	}

	foreach ( array( 'label', 'description', 'placeholder', 'value', 'button_text' ) as $key ) {
		if ( empty( $attrs[ $key ] ) || ! is_string( $attrs[ $key ] ) ) {
			continue;
		}

		// Do not translate HTML-heavy descriptions except known plain strings.
		if ( 'description' === $key && false !== strpos( $attrs[ $key ], '<' ) ) {
			$attrs[ $key ] = preg_replace_callback(
				'/>\s*([^<]+)\s*</',
				static function ( $m ) {
					$mapped = alostora_translate_mapped_string( trim( $m[1] ) );
					return '>' . $mapped . '<';
				},
				$attrs[ $key ]
			);
			continue;
		}

		$attrs[ $key ] = alostora_translate_mapped_string( $attrs[ $key ] );
	}

	return $attrs;
}
add_filter( 'llms_forms_block_to_field_settings', 'alostora_translate_llms_field_attrs', 20 );

/**
 * Translate assembled LifterLMS form field arrays.
 *
 * @param array  $fields   Fields.
 * @param string $location Form location.
 * @param array  $args     Args.
 * @return array
 */
function alostora_translate_llms_form_fields( $fields, $location = '', $args = array() ) {
	unset( $location, $args );

	if ( ! alostora_is_frontend_arabic() || ! is_array( $fields ) ) {
		return $fields;
	}

	foreach ( $fields as $index => $field ) {
		if ( is_array( $field ) ) {
			$fields[ $index ] = alostora_translate_llms_field_attrs( $field );
		}
	}

	return $fields;
}
add_filter( 'llms_get_form_fields', 'alostora_translate_llms_form_fields', 20, 3 );
add_filter( 'lifterlms_get_person_fields', 'alostora_translate_llms_form_fields', 20, 3 );

/**
 * Translate LifterLMS student dashboard menu titles.
 *
 * @param array $tabs Dashboard tabs.
 * @return array
 */
function alostora_translate_llms_dashboard_tabs( $tabs ) {
	if ( ! alostora_is_frontend_arabic() || ! is_array( $tabs ) ) {
		return $tabs;
	}

	foreach ( $tabs as $key => $tab ) {
		if ( ! empty( $tab['title'] ) && is_string( $tab['title'] ) ) {
			$tabs[ $key ]['title'] = alostora_translate_mapped_string( $tab['title'] );
		}
	}

	return $tabs;
}
add_filter( 'llms_get_student_dashboard_tabs', 'alostora_translate_llms_dashboard_tabs', 20 );
add_filter( 'llms_get_student_dashboard_tabs_for_nav', 'alostora_translate_llms_dashboard_tabs', 20 );

/**
 * Last-resort HTML string swap for persisted LifterLMS / serial form labels.
 *
 * Sorted longest-first so short tokens like "Email" do not corrupt longer phrases.
 *
 * @param string $html Markup.
 * @return string
 */
function alostora_replace_mapped_strings_in_html( $html ) {
	if ( ! alostora_is_frontend_arabic() || ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	$map = alostora_get_lifterlms_frontend_translations();
	uksort(
		$map,
		static function ( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}
	);

	return strtr( $html, $map );
}
add_filter( 'llms_get_form_html', 'alostora_replace_mapped_strings_in_html', 20 );

/**
 * Translate serial enrollment form markup after shortcodes render.
 *
 * @param string $content Post content.
 * @return string
 */
function alostora_translate_serial_enrollment_content( $content ) {
	if ( ! alostora_is_frontend_arabic() || ! is_string( $content ) || '' === $content ) {
		return $content;
	}

	if ( ! preg_match( '/serial-enrollment|academy-serial|ase-registration|ase-reg|Serial Number|الرقم التسلسلي/i', $content ) ) {
		return $content;
	}

	return alostora_replace_mapped_strings_in_html( $content );
}
add_filter( 'the_content', 'alostora_translate_serial_enrollment_content', 20 );

