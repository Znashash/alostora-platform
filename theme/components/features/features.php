<?php
/**
 * Component: Feature band ("Why learn with animation?").
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $title Section title.
 *     @type array  $items List of { icon, title, text } benefits.
 *     @type string $image Character image URL (falls back to placeholder).
 * }
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array(
	'title' => '',
	'items' => array(),
	'image' => '',
) );

$items = is_array( $args['items'] ) && ! empty( $args['items'] ) ? $args['items'] : array();
$image = $args['image'] ? $args['image'] : alostora_placeholder_url( 'benefits-character.webp' );
?>
<div class="alostora-features has-animations">
	<div class="alostora-features__inner">
		<div class="alostora-features__content" data-reveal>
			<?php if ( $args['title'] ) : ?>
				<h2 class="alostora-features__title"><?php echo esc_html( $args['title'] ); ?></h2>
			<?php endif; ?>

			<div class="alostora-features__grid">
				<?php foreach ( $items as $index => $item ) : ?>
					<?php $item = wp_parse_args( $item, array( 'icon' => 'chart', 'title' => '', 'text' => '' ) ); ?>
					<div class="alostora-features__item" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $index % 4 ) + 1 ) ); ?>">
						<span class="alostora-features__icon" aria-hidden="true"><?php alostora_svg( $item['icon'] ); ?></span>
						<div>
							<h3 class="alostora-features__item-title"><?php echo esc_html( $item['title'] ); ?></h3>
							<p class="alostora-features__item-text"><?php echo esc_html( $item['text'] ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="alostora-features__media" data-reveal data-reveal-delay="2" data-float>
			<img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" decoding="async" width="640" height="640">
		</div>
	</div>
</div>
