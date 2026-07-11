<?php
/**
 * Component: "Why learn with animation" premium dark banner.
 *
 * Centered title (with a small leading icon) + subtitle, an integrated character
 * bleeding out of the top-inline-end corner, and four features in a single row
 * where each feature is an orange icon beside its text.
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $title    Section title.
 *     @type string $subtitle Supporting line.
 *     @type array  $items    List of { icon, title, text } benefits.
 *     @type string $image    Character image URL (falls back to placeholder).
 * }
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array(
	'title'    => '',
	'subtitle' => '',
	'items'    => array(),
	'image'    => '',
) );

$items = is_array( $args['items'] ) && ! empty( $args['items'] ) ? $args['items'] : array();
$image = $args['image'] ? $args['image'] : alostora_placeholder_url( 'benefits-character.webp' );
?>
<div class="alostora-features has-animations">
	<div class="alostora-features__media" data-float>
		<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" decoding="async" width="360" height="360">
	</div>

	<div class="alostora-features__header" data-reveal>
		<?php if ( $args['title'] ) : ?>
			<h2 class="alostora-features__title">
				<span class="alostora-features__title-icon" aria-hidden="true"><?php alostora_svg( 'target' ); ?></span>
				<span><?php echo esc_html( $args['title'] ); ?></span>
			</h2>
		<?php endif; ?>
		<?php if ( $args['subtitle'] ) : ?>
			<p class="alostora-features__subtitle"><?php echo esc_html( $args['subtitle'] ); ?></p>
		<?php endif; ?>
	</div>

	<div class="alostora-features__grid">
		<?php foreach ( $items as $index => $item ) : ?>
			<?php $item = wp_parse_args( $item, array( 'icon' => 'chart', 'title' => '', 'text' => '' ) ); ?>
			<div class="alostora-features__item" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $index % 4 ) + 1 ) ); ?>">
				<span class="alostora-features__icon" aria-hidden="true"><?php alostora_svg( $item['icon'] ); ?></span>
				<span class="alostora-features__text">
					<span class="alostora-features__item-title"><?php echo esc_html( $item['title'] ); ?></span>
					<span class="alostora-features__item-text"><?php echo esc_html( $item['text'] ); ?></span>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
</div>
