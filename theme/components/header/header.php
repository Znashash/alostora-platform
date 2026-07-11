<?php
/**
 * Component: Site header (fallback when no Elementor header is assigned).
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

$primary_label = get_theme_mod( 'alostora_cta_primary_label', esc_html__( 'Register Now', 'alostora' ) );
$primary_url   = get_theme_mod( 'alostora_cta_primary_url', '#' );
$login_label   = get_theme_mod( 'alostora_cta_login_label', esc_html__( 'Login', 'alostora' ) );
$login_url     = get_theme_mod( 'alostora_cta_login_url', '#' );

$nav_args = array(
	'theme_location' => 'primary',
	'container'      => false,
	'depth'          => 2,
	'fallback_cb'    => false,
);
?>
<header class="alostora-header" data-header>
	<div class="alostora-header__inner">
		<?php alostora_brand_logo( array( 'class' => 'alostora-brand alostora-header__brand' ) ); ?>

		<nav class="alostora-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'alostora' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array_merge( $nav_args, array(
					'menu_class' => 'alostora-menu alostora-menu--desktop',
					'menu_id'    => 'alostora-primary-menu',
				) ) );
			}
			?>
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
			<button class="alostora-header__toggle" type="button" aria-expanded="false" aria-controls="alostora-mobile-drawer" data-nav-toggle>
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'alostora' ); ?></span>
				<?php alostora_svg( 'menu' ); ?>
			</button>
		</div>
	</div>

	<div
		id="alostora-mobile-drawer"
		class="mobile-drawer"
		data-mobile-drawer
		aria-hidden="true"
	>
		<div class="mobile-drawer__top">
			<?php alostora_brand_logo( array( 'class' => 'alostora-brand mobile-drawer__brand' ) ); ?>
			<button class="mobile-drawer__close" type="button" data-nav-close>
				<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'alostora' ); ?></span>
				<?php alostora_svg( 'close' ); ?>
			</button>
		</div>

		<nav class="mobile-drawer__nav" aria-label="<?php esc_attr_e( 'Mobile primary', 'alostora' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array_merge( $nav_args, array(
					'menu_class' => 'menu menu-primary',
					'menu_id'    => 'alostora-mobile-menu',
				) ) );
			}
			?>
		</nav>

		<div class="mobile-drawer__actions">
			<a class="mobile-drawer__login" href="<?php echo esc_url( $login_url ); ?>"><?php echo esc_html( $login_label ); ?></a>
			<?php
			alostora_component( 'buttons', array(
				'label' => $primary_label,
				'url'   => $primary_url,
				'style' => 'primary',
				'size'  => 'sm',
			) );
			?>
		</div>
	</div>

	<div class="alostora-nav-backdrop" data-nav-backdrop></div>
</header>
