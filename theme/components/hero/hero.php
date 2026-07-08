<?php
/**
 * Component: Hero.
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $eyebrow       Small label above the title.
 *     @type string $title         Main headline (first line).
 *     @type string $title_accent  Accent headline (second, orange line).
 *     @type string $description   Supporting paragraph (HTML allowed).
 *     @type string $primary_label Primary CTA label.
 *     @type string $primary_url   Primary CTA URL.
 *     @type string $video_url     Optional video URL for the play trigger.
 *     @type string $video_label   Label next to the play button.
 *     @type string $image         Media image URL.
 * }
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array(
	'eyebrow'       => '',
	'title'         => '',
	'title_accent'  => '',
	'description'   => '',
	'primary_label' => '',
	'primary_url'   => '#',
	'video_url'     => '',
	'video_label'   => '',
	'image'         => '',
) );

// Never render an empty media slot: fall back to the bundled placeholder art.
$hero_image = $args['image'] ? $args['image'] : alostora_placeholder_url( 'hero-character.webp' );
?>
<section class="alostora-hero has-animations">
	<div class="alostora-hero__inner">
		<div class="alostora-hero__content" data-reveal>
			<?php if ( $args['eyebrow'] ) : ?>
				<span class="alostora-hero__eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></span>
			<?php endif; ?>

			<h1 class="alostora-hero__title">
				<?php echo esc_html( $args['title'] ); ?>
				<?php if ( $args['title_accent'] ) : ?>
					<span class="alostora-hero__accent"><?php echo esc_html( $args['title_accent'] ); ?></span>
				<?php endif; ?>
			</h1>

			<?php if ( $args['description'] ) : ?>
				<div class="alostora-hero__desc"><?php echo wp_kses_post( wpautop( $args['description'] ) ); ?></div>
			<?php endif; ?>

			<div class="alostora-hero__actions">
				<?php
				if ( $args['primary_label'] ) {
					alostora_component( 'buttons', array(
						'label' => $args['primary_label'],
						'url'   => $args['primary_url'],
						'style' => 'primary',
						'size'  => 'lg',
					) );
				}
				?>
			</div>

			<?php if ( $args['video_url'] ) : ?>
				<a class="alostora-hero__video" href="<?php echo esc_url( $args['video_url'] ); ?>" data-video-trigger>
					<span class="alostora-hero__play" aria-hidden="true"><?php alostora_svg( 'play' ); ?></span>
					<span class="alostora-hero__video-label"><?php echo esc_html( $args['video_label'] ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<div class="alostora-hero__media" data-reveal data-reveal-delay="2" data-float>
			<img src="<?php echo esc_url( $hero_image ); ?>" alt="" loading="eager" decoding="async" fetchpriority="high" width="760" height="760">
		</div>
	</div>
</section>
