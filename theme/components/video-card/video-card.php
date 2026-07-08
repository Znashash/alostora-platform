<?php
/**
 * Component: Video card (clickable poster that opens a VdoCipher lesson video).
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $id    VdoCipher video ID (opened in a lightbox by JS).
 *     @type string $title Overlay title.
 *     @type string $image Poster image URL.
 * }
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array(
	'id'    => '',
	'title' => '',
	'image' => '',
) );
?>
<a class="alostora-video-card" href="#" data-video-id="<?php echo esc_attr( $args['id'] ); ?>" data-video-trigger>
	<?php if ( $args['image'] ) : ?>
		<img src="<?php echo esc_url( $args['image'] ); ?>" alt="<?php echo esc_attr( $args['title'] ); ?>" loading="lazy" decoding="async" width="480" height="300">
	<?php endif; ?>
	<span class="alostora-video-card__play" aria-hidden="true"><?php alostora_svg( 'play' ); ?></span>
	<?php if ( $args['title'] ) : ?>
		<h3 class="alostora-video-card__title"><?php echo esc_html( $args['title'] ); ?></h3>
	<?php endif; ?>
</a>
