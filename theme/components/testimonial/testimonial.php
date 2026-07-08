<?php
/**
 * Component: Testimonial.
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $quote  Testimonial text (HTML allowed).
 *     @type string $name   Person name.
 *     @type string $role   Person role / title.
 *     @type string $avatar Avatar image URL.
 *     @type float  $rating Rating out of 5.
 * }
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array(
	'quote'  => '',
	'name'   => '',
	'role'   => '',
	'avatar' => '',
	'rating' => 5,
) );
?>
<figure class="alostora-testimonial" data-reveal>
	<blockquote class="alostora-testimonial__quote"><?php echo wp_kses_post( $args['quote'] ); ?></blockquote>
	<?php if ( (float) $args['rating'] > 0 ) : ?>
		<?php echo alostora_get_rating( (float) $args['rating'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
	<?php endif; ?>
	<figcaption class="alostora-testimonial__person">
		<?php if ( $args['avatar'] ) : ?>
			<img class="alostora-testimonial__avatar" src="<?php echo esc_url( $args['avatar'] ); ?>" alt="<?php echo esc_attr( $args['name'] ); ?>" loading="lazy" decoding="async" width="52" height="52">
		<?php endif; ?>
		<span>
			<span class="alostora-testimonial__name"><?php echo esc_html( $args['name'] ); ?></span>
			<?php if ( $args['role'] ) : ?>
				<span class="alostora-testimonial__role"><?php echo esc_html( $args['role'] ); ?></span>
			<?php endif; ?>
		</span>
	</figcaption>
</figure>
