<?php
/**
 * Component: Course card.
 *
 * Rendered by the LifterLMS loop override and the homepage course carousel. All
 * data is passed in so the component is decoupled from LifterLMS internals. The
 * instructor avatar is strictly capped to a small circle and falls back to a
 * branded placeholder — never the large WordPress mystery-person.
 *
 * @package Alostora
 *
 * @var array $args {
 *     @type string $title      Course title.
 *     @type string $url        Course permalink.
 *     @type string $image      Thumbnail URL.
 *     @type string $category   Category / badge label.
 *     @type string $instructor Instructor name.
 *     @type string $avatar     Instructor avatar URL (optional).
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
	'avatar'     => '',
	'lessons'    => 0,
	'duration'   => '',
	'rating'     => 0,
	'reviews'    => 0,
	'cta_label'  => 'ابدأ الآن',
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
				<span class="alostora-course__avatar">
					<?php if ( $args['avatar'] ) : ?>
						<img src="<?php echo esc_url( $args['avatar'] ); ?>" alt="<?php echo esc_attr( $args['instructor'] ); ?>" width="40" height="40" loading="lazy" decoding="async">
					<?php else : ?>
						<?php alostora_svg( 'user' ); ?>
					<?php endif; ?>
				</span>
				<span class="alostora-course__instructor-name"><?php echo esc_html( $args['instructor'] ); ?></span>
			</p>
		<?php endif; ?>

		<div class="alostora-course__meta">
			<?php if ( $args['lessons'] ) : ?>
				<span>
					<?php alostora_svg( 'play' ); ?>
					<?php
					/* translators: %s: number of lessons. */
					echo esc_html( sprintf( _n( '%s درس', '%s دروس', (int) $args['lessons'], 'alostora' ), number_format_i18n( (int) $args['lessons'] ) ) );
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
