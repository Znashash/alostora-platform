<?php
/**
 * Component: Button / call-to-action link.
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $label  Button text.
 *     @type string $url    Destination URL.
 *     @type string $style  Variant: primary|secondary|ghost.
 *     @type string $icon   Optional icon slug (assets/images/icons).
 *     @type string $target Link target.
 *     @type string $size   Optional size modifier: sm|lg.
 * }
 */

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'label'  => '',
	'url'    => '#',
	'style'  => 'primary',
	'icon'   => '',
	'target' => '_self',
	'size'   => '',
);

$args  = wp_parse_args( $args, $defaults );
$style = in_array( $args['style'], array( 'primary', 'secondary', 'ghost' ), true ) ? $args['style'] : 'primary';

$classes = array( 'alostora-btn', 'alostora-btn--' . $style );
if ( in_array( $args['size'], array( 'sm', 'lg' ), true ) ) {
	$classes[] = 'alostora-btn--' . $args['size'];
}

$rel = ( '_blank' === $args['target'] ) ? ' rel="noopener noreferrer"' : '';
?>
<a class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" href="<?php echo esc_url( $args['url'] ); ?>" target="<?php echo esc_attr( $args['target'] ); ?>"<?php echo $rel; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static string. ?>>
	<?php if ( ! empty( $args['icon'] ) ) : ?>
		<span class="alostora-btn__icon"><?php alostora_svg( $args['icon'] ); ?></span>
	<?php endif; ?>
	<span class="alostora-btn__label"><?php echo esc_html( $args['label'] ); ?></span>
</a>
