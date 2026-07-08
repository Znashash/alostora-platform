<?php
/**
 * Component: Course card.
 *
 * Rendered by the LifterLMS loop override and the homepage course carousel. All
 * data is passed in so the component is decoupled from LifterLMS internals.
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $title      Course title.
 *     @type string $url        Course permalink.
 *     @type string $image      Thumbnail URL.
 *     @type string $category   Category / badge label.
 *     @type string $instructor Instructor name.
 *     @type int    $lessons    Lesson count.
 *     @type string $duration   Human-readable duration.
 *     @type float  $rating     Rating out of 5.
 *     @type int    $reviews    Review count.
 *     @type string $cta_label  Enrol button label.
 * }
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array(
	'title'      => '',
	'url'        => '#',
	'image'      => '',
	'category'   => '',
	'instructor' => '',
	'lessons'    => 0,
	'duration'   => '',
	'rating'     => 0,
	'reviews'    => 0,
	'cta_label'  => esc_html__( 'Enrol now', 'alostora' ),
) );
?>
<article class="alostora-course">
	<a class="alostora-course__media" href="<?php echo esc_url( $args['url'] ); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( $args['image'] ) : ?>
			<img src="<?php echo esc_url( $args['image'] ); ?>" alt="" loading="lazy" decoding="async" width="640" height="400">
		<?php endif; ?>
		<?php if ( $args['category'] ) : ?>
			<span class="alostora-course__badge"><?php echo esc_html( $args['category'] ); ?></span>
		<?php endif; ?>
	</a>

	<div class="alostora-course__body">
		<h3 class="alostora-course__title">
			<a href="<?php echo esc_url( $args['url'] ); ?>"><?php echo esc_html( $args['title'] ); ?></a>
		</h3>

		<?php if ( $args['instructor'] ) : ?>
			<p class="alostora-course__instructor">
				<?php alostora_svg( 'user' ); ?>
				<span><?php echo esc_html( $args['instructor'] ); ?></span>
			</p>
		<?php endif; ?>

		<div class="alostora-course__meta">
			<?php if ( $args['lessons'] ) : ?>
				<span>
					<?php alostora_svg( 'play' ); ?>
					<?php
					/* translators: %s: number of lessons. */
					echo esc_html( sprintf( _n( '%s lesson', '%s lessons', (int) $args['lessons'], 'alostora' ), number_format_i18n( (int) $args['lessons'] ) ) );
					?>
				</span>
			<?php endif; ?>
			<?php if ( $args['duration'] ) : ?>
				<span><?php alostora_svg( 'clock' ); ?><?php echo esc_html( $args['duration'] ); ?></span>
			<?php endif; ?>
		</div>

		<div class="alostora-course__footer">
			<?php echo alostora_get_rating( (float) $args['rating'], (int) $args['reviews'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
			<?php
			alostora_component( 'buttons', array(
				'label' => $args['cta_label'],
				'url'   => $args['url'],
				'style' => 'primary',
				'size'  => 'sm',
			) );
			?>
		</div>
	</div>
</article>
