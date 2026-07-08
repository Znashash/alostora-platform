<?php
/**
 * Component: Step card ("How we teach" flow item).
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $number      Step number.
 *     @type string $title       Step title.
 *     @type string $description Step description (HTML allowed).
 *     @type string $icon        Icon slug.
 * }
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array(
	'number'      => '',
	'title'       => '',
	'description' => '',
	'icon'        => 'book',
) );
?>
<div class="alostora-step" data-reveal>
	<span class="alostora-step__icon" aria-hidden="true">
		<?php alostora_svg( $args['icon'] ); ?>
		<?php if ( '' !== $args['number'] ) : ?>
			<span class="alostora-step__number"><?php echo esc_html( $args['number'] ); ?></span>
		<?php endif; ?>
	</span>
	<h3 class="alostora-step__title"><?php echo esc_html( $args['title'] ); ?></h3>
	<?php if ( $args['description'] ) : ?>
		<p class="alostora-step__desc"><?php echo wp_kses_post( $args['description'] ); ?></p>
	<?php endif; ?>
</div>
