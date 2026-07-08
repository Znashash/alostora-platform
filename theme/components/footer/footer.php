<?php
/**
 * Component: Site footer (fallback when no Elementor footer is assigned).
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

$socials = alostora_get_social_links();
$icons   = array(
	'facebook'  => 'facebook',
	'instagram' => 'instagram',
	'youtube'   => 'youtube',
	'telegram'  => 'telegram',
	'x'         => 'x',
);
?>
<footer class="alostora-footer" role="contentinfo">
	<div class="alostora-footer__main">
		<div class="alostora-footer__col alostora-footer__col--brand">
			<?php alostora_brand_logo( array( 'class' => 'alostora-brand alostora-footer__brand' ) ); ?>
			<p class="alostora-footer__brand-desc"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>

			<?php if ( ! empty( $socials ) ) : ?>
				<div class="alostora-footer__socials">
					<?php foreach ( $socials as $network => $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
							<?php alostora_svg( isset( $icons[ $network ] ) ? $icons[ $network ] : 'link' ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
				<div class="alostora-footer__col">
					<?php dynamic_sidebar( 'footer-' . $i ); ?>
				</div>
			<?php endif; ?>
		<?php endfor; ?>

		<?php if ( has_nav_menu( 'footer' ) && ! is_active_sidebar( 'footer-1' ) ) : ?>
			<div class="alostora-footer__col">
				<h3 class="alostora-footer__col-title"><?php esc_html_e( 'Quick Links', 'alostora' ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'alostora-footer__menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
				?>
			</div>
		<?php endif; ?>
	</div>

	<div class="alostora-footer__bottom">
		<div class="alostora-footer__bottom-inner">
			<span>
				<?php
				printf(
					/* translators: 1: year, 2: site name. */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'alostora' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</span>
		</div>
	</div>
</footer>
