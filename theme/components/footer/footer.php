<?php
/**
 * Component: Site footer (fallback when no Elementor footer is assigned).
 *
 * Renders a complete, always-populated Arabic footer matching the approved dark
 * design: brand + description + socials, quick links, support links, an academy
 * info column, and a copyright bar. Configured menus/widgets/social links from
 * the Customizer override the sensible Arabic defaults.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

$socials = alostora_get_social_links();

// Fall back to the design's default social set (configurable in the Customizer).
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

$description = get_bloginfo( 'description' );
if ( ! $description ) {
	$description = 'منصة الأسطورة التعليمية: نحوّل المناهج الدراسية إلى تجربة تعليمية سينمائية بالرسوم المتحركة تساعد الطالب على الفهم والتذكّر والتفوق.';
}

$quick_links = array(
	array( 'label' => 'الرئيسية', 'url' => home_url( '/' ) ),
	array( 'label' => 'الدورات', 'url' => home_url( '/courses/' ) ),
	array( 'label' => 'كيف ندرّس؟', 'url' => home_url( '/#how' ) ),
	array( 'label' => 'عن الأسطورة', 'url' => home_url( '/#about' ) ),
);

$support_links = array(
	array( 'label' => 'الأسئلة الشائعة', 'url' => home_url( '/#faq' ) ),
	array( 'label' => 'سياسة الخصوصية', 'url' => home_url( '/privacy-policy/' ) ),
	array( 'label' => 'الشروط والأحكام', 'url' => home_url( '/terms/' ) ),
	array( 'label' => 'تواصل معنا', 'url' => home_url( '/#contact' ) ),
);

$account_link = alostora_get_account_link();
$support_links[] = array(
	'label' => $account_link['label'],
	'url'   => $account_link['url'],
);
?>
<footer class="alostora-footer" role="contentinfo">
	<div class="alostora-footer__main">
		<div class="alostora-footer__col alostora-footer__col--brand">
			<?php alostora_brand_logo( array( 'class' => 'alostora-brand alostora-footer__brand', 'variant' => 'light' ) ); ?>
			<p class="alostora-footer__brand-desc"><?php echo esc_html( $description ); ?></p>

			<div class="alostora-footer__socials">
				<?php foreach ( $socials as $network => $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>"<?php echo ( '#' !== $url ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?> aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
						<?php alostora_svg( isset( $icons[ $network ] ) ? $icons[ $network ] : 'link' ); ?>
					</a>
				<?php endforeach; ?>
			</div>
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

		<div class="alostora-footer__col">
			<h3 class="alostora-footer__col-title">الدعم والمساعدة</h3>
			<ul class="alostora-footer__menu">
				<?php foreach ( $support_links as $link ) : ?>
					<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="alostora-footer__col">
			<h3 class="alostora-footer__col-title">الأكاديمية</h3>
			<p class="alostora-footer__note">الأكاديمية الأولى في الأردن للتعليم بطريقة الرسوم المتحركة.</p>
			<p class="alostora-footer__note">انضمّ إلى آلاف الطلاب المتفوقين وابدأ رحلتك التعليمية اليوم.</p>
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
			<span class="alostora-footer__made">صُنع بشغف لتعليم أفضل</span>
		</div>
	</div>
</footer>
