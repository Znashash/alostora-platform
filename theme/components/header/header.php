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

$auth = alostora_get_auth_controls();

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
			<?php if ( ! empty( $auth['logged_in'] ) && ! empty( $auth['account'] ) ) : ?>
				<?php
				alostora_component( 'buttons', array(
					'label' => $auth['account']['label'],
					'url'   => $auth['account']['url'],
					'style' => 'primary',
					'size'  => 'sm',
				) );
				?>
			<?php else : ?>
				<?php if ( ! empty( $auth['login'] ) ) : ?>
					<a class="alostora-header__login" href="<?php echo esc_url( $auth['login']['url'] ); ?>"><?php echo esc_html( $auth['login']['label'] ); ?></a>
				<?php endif; ?>
				<?php
				if ( ! empty( $auth['register'] ) ) {
					alostora_component( 'buttons', array(
						'label' => $auth['register']['label'],
						'url'   => $auth['register']['url'],
						'style' => 'primary',
						'size'  => 'sm',
					) );
				}
				?>
			<?php endif; ?>
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
		<?php if ( ! empty( $auth['logged_in'] ) && ! empty( $auth['account'] ) ) : ?>
			<?php
			alostora_component( 'buttons', array(
				'label' => $auth['account']['label'],
				'url'   => $auth['account']['url'],
				'style' => 'primary',
				'size'  => 'lg',
			) );
			?>
			<?php if ( ! empty( $auth['logout'] ) ) : ?>
				<a class="alostora-drawer__login" href="<?php echo esc_url( $auth['logout']['url'] ); ?>"><?php echo esc_html( $auth['logout']['label'] ); ?></a>
			<?php endif; ?>
		<?php else : ?>
			<?php if ( ! empty( $auth['login'] ) ) : ?>
				<a class="alostora-drawer__login" href="<?php echo esc_url( $auth['login']['url'] ); ?>"><?php echo esc_html( $auth['login']['label'] ); ?></a>
			<?php endif; ?>
			<?php
			if ( ! empty( $auth['register'] ) ) {
				alostora_component( 'buttons', array(
					'label' => $auth['register']['label'],
					'url'   => $auth['register']['url'],
					'style' => 'primary',
					'size'  => 'lg',
				) );
			}
			?>
		<?php endif; ?>
	</div>
</div>
<div class="alostora-drawer-backdrop" data-drawer-backdrop></div>
