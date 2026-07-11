<?php
/**
 * Component: Site header + accessible mobile drawer.
 *
 * Fallback when no Elementor header is assigned. The desktop inline navigation
 * is hidden below 1024px and replaced by a hamburger that opens an off-canvas
 * RTL drawer containing the logo, navigation, login link and register button.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

$primary_label = get_theme_mod( 'alostora_cta_primary_label', 'سجّل الآن' );
$primary_url   = get_theme_mod( 'alostora_cta_primary_url', home_url( '/register/' ) );
$login_label   = get_theme_mod( 'alostora_cta_login_label', 'تسجيل الدخول' );
$login_url     = get_theme_mod( 'alostora_cta_login_url', wp_login_url() );

$menu_args = array(
	'theme_location' => 'primary',
	'container'      => false,
	'depth'          => 2,
	'fallback_cb'    => 'alostora_primary_menu_fallback',
);
?>
<header class="alostora-header" data-header>
	<div class="alostora-header__inner">
		<?php alostora_brand_logo( array( 'variant' => 'light' ) ); ?>

		<nav class="alostora-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'alostora' ); ?>">
			<?php wp_nav_menu( array_merge( $menu_args, array( 'menu_class' => 'alostora-menu', 'menu_id' => 'alostora-primary-menu' ) ) ); ?>
		</nav>

		<div class="alostora-header__actions">
			<a class="alostora-header__login" href="<?php echo esc_url( $login_url ); ?>"><?php echo esc_html( $login_label ); ?></a>
			<?php
			alostora_component( 'buttons', array(
				'label' => $primary_label,
				'url'   => $primary_url,
				'style' => 'primary',
				'size'  => 'sm',
			) );
			?>
			<button class="alostora-header__toggle" type="button" aria-expanded="false" aria-controls="alostora-drawer" aria-label="فتح القائمة" data-nav-toggle>
				<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'alostora' ); ?></span>
				<?php alostora_svg( 'menu' ); ?>
			</button>
		</div>
	</div>
</header>

<div class="alostora-drawer" id="alostora-drawer" role="dialog" aria-modal="true" aria-label="قائمة التنقل" aria-hidden="true" data-drawer>
	<div class="alostora-drawer__top">
		<?php alostora_brand_logo( array( 'class' => 'alostora-brand alostora-drawer__brand', 'variant' => 'light' ) ); ?>
		<button class="alostora-drawer__close" type="button" aria-label="إغلاق القائمة" data-drawer-close>
			<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'alostora' ); ?></span>
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<nav class="alostora-drawer__nav" aria-label="<?php esc_attr_e( 'Mobile', 'alostora' ); ?>">
		<?php wp_nav_menu( array_merge( $menu_args, array( 'menu_class' => 'alostora-drawer__menu', 'menu_id' => 'alostora-drawer-menu' ) ) ); ?>
	</nav>

	<div class="alostora-drawer__actions">
		<a class="alostora-drawer__login" href="<?php echo esc_url( $login_url ); ?>"><?php echo esc_html( $login_label ); ?></a>
		<?php
		alostora_component( 'buttons', array(
			'label' => $primary_label,
			'url'   => $primary_url,
			'style' => 'primary',
			'size'  => 'lg',
		) );
		?>
	</div>
</div>
<div class="alostora-drawer-backdrop" data-drawer-backdrop></div>
