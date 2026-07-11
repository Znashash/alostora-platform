<?php
/**
 * Component: "Why learn with animation" premium dark banner.
 *
 * Centered title + subtitle, an integrated (edge-blended) character on the
 * inline-start side, and four features in a single row with orange icons on top.
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
	<div class="alostora-features__header" data-reveal>
		<?php if ( $args['title'] ) : ?>
			<h2 class="alostora-features__title"><?php echo esc_html( $args['title'] ); ?></h2>
		<?php endif; ?>
		<?php if ( $args['subtitle'] ) : ?>
			<p class="alostora-features__subtitle"><?php echo esc_html( $args['subtitle'] ); ?></p>
		<?php endif; ?>
	</div>

	<div class="alostora-features__body">
		<div class="alostora-features__media" data-reveal data-float>
			<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" decoding="async" width="360" height="360">
		</div>

		<div class="alostora-features__grid">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php $item = wp_parse_args( $item, array( 'icon' => 'chart', 'title' => '', 'text' => '' ) ); ?>
				<div class="alostora-features__item" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $index % 4 ) + 1 ) ); ?>">
					<span class="alostora-features__icon" aria-hidden="true"><?php alostora_svg( $item['icon'] ); ?></span>
					<h3 class="alostora-features__item-title"><?php echo esc_html( $item['title'] ); ?></h3>
					<p class="alostora-features__item-text"><?php echo esc_html( $item['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
