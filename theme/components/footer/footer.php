<?php
/**
 * Component: Site footer (fallback when no Elementor footer is assigned).
 *
 * Matches the approved dark design: four columns — academy about (inline-start),
 * support links, quick links, and a "follow us" column with social icons on the
 * inline-end. Configured menus/social links from the Customizer override the
 * Arabic defaults. The brand logo lives in the CTA banner above the footer.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

$socials = alostora_get_social_links();

if ( empty( $socials ) ) {
	$socials = array(
		'youtube'   => '#',
		'instagram' => '#',
		'facebook'  => '#',
		'telegram'  => '#',
	);
}

$icons = array(
	'facebook'  => 'facebook',
	'instagram' => 'instagram',
	'youtube'   => 'youtube',
	'telegram'  => 'telegram',
	'x'         => 'x',
);

$support_links = array(
	array( 'label' => 'الأسئلة الشائعة', 'url' => home_url( '/#faq' ) ),
	array( 'label' => 'سياسة الخصوصية', 'url' => home_url( '/privacy-policy/' ) ),
	array( 'label' => 'الشروط والأحكام', 'url' => home_url( '/terms/' ) ),
);

$quick_links = array(
	array( 'label' => 'الرئيسية', 'url' => home_url( '/' ) ),
	array( 'label' => 'الدورات', 'url' => home_url( '/courses/' ) ),
	array( 'label' => 'عن الأسطورة', 'url' => home_url( '/#about' ) ),
	array( 'label' => 'تواصل معنا', 'url' => home_url( '/#contact' ) ),
);
?>
<footer class="alostora-footer" role="contentinfo">
	<div class="alostora-footer__main">
		<div class="alostora-footer__col alostora-footer__col--about">
			<h3 class="alostora-footer__col-title">الأكاديمية الأولى في الأردن</h3>
			<p class="alostora-footer__note">لتعليم التاريخ والدروس المدرسية</p>
			<p class="alostora-footer__note">بطريقة الرسوم المتحركة</p>
		</div>

		<div class="alostora-footer__col">
			<h3 class="alostora-footer__col-title">الدعم والمساعدة</h3>
			<ul class="alostora-footer__menu">
				<?php foreach ( $support_links as $link ) : ?>
					<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="alostora-footer__col">
			<h3 class="alostora-footer__col-title">روابط سريعة</h3>
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'alostora-footer__menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
				?>
			<?php else : ?>
				<ul class="alostora-footer__menu">
					<?php foreach ( $quick_links as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="alostora-footer__col alostora-footer__col--follow">
			<h3 class="alostora-footer__col-title">تابعنا</h3>
			<div class="alostora-footer__socials">
				<?php foreach ( $socials as $network => $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>"<?php echo ( '#' !== $url ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?> aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
						<?php alostora_svg( isset( $icons[ $network ] ) ? $icons[ $network ] : 'link' ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<div class="alostora-footer__bottom">
		<div class="alostora-footer__bottom-inner">
			<span>
				<?php
				printf(
					/* translators: 1: year, 2: site name. */
					esc_html__( '© %1$s %2$s. جميع الحقوق محفوظة.', 'alostora' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</span>
		</div>
	</div>
</footer>
