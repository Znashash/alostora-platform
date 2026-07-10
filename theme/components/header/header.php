<?php
/**
 * Component: Site header (fallback when no Elementor header is assigned).
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

$primary_label = get_theme_mod( 'alostora_cta_primary_label', 'سجّل الآن' );
$primary_url   = get_theme_mod( 'alostora_cta_primary_url', home_url( '/register/' ) );
$login_label   = get_theme_mod( 'alostora_cta_login_label', 'تسجيل الدخول' );
$login_url     = get_theme_mod( 'alostora_cta_login_url', wp_login_url() );
?>
<header class="alostora-header" data-header>
	<div class="alostora-header__inner">
		<?php alostora_brand_logo( array( 'variant' => 'light' ) ); ?>

		<nav class="alostora-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'alostora' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'alostora-menu',
				'menu_id'        => 'alostora-primary-menu',
				'depth'          => 2,
				'fallback_cb'    => 'alostora_primary_menu_fallback',
			) );
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
			<button class="alostora-header__toggle" type="button" aria-expanded="false" aria-controls="alostora-primary-menu" data-nav-toggle>
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'alostora' ); ?></span>
				<?php alostora_svg( 'menu' ); ?>
			</button>
		</div>
	</div>
	<div class="alostora-nav-backdrop" data-nav-backdrop></div>
</header>
