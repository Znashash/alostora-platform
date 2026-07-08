<?php
/**
 * Component: Statistics strip.
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type array $items List of { value, label, icon } stats.
 * }
 */

defined( 'ABSPATH' ) || exit;

$args  = wp_parse_args( $args, array( 'items' => array() ) );
$items = is_array( $args['items'] ) ? $args['items'] : array();

if ( empty( $items ) ) {
	return;
}
?>
<div class="alostora-stats has-animations" data-reveal>
	<?php foreach ( $items as $index => $item ) : ?>
		<?php
		$item = wp_parse_args( $item, array( 'value' => '', 'label' => '', 'icon' => 'chart' ) );
		?>
		<div class="alostora-stats__item" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $index % 4 ) + 1 ) ); ?>">
			<span class="alostora-stats__icon" aria-hidden="true"><?php alostora_svg( $item['icon'] ); ?></span>
			<span class="alostora-stats__text">
				<span class="alostora-stats__value" data-countup><?php echo esc_html( $item['value'] ); ?></span>
				<span class="alostora-stats__label"><?php echo esc_html( $item['label'] ); ?></span>
			</span>
		</div>
	<?php endforeach; ?>
</div>
