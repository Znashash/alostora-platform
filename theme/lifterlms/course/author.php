<?php
/**
 * LifterLMS override: single course instructor block.
 *
 * Overrides LifterLMS `course/author.php`. Renders the instructor with an avatar
 * and bio using the LifterLMS instructor API, styled to the brand.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'llms_get_post' ) ) {
	return;
}

$course = llms_get_post( get_the_ID() );

if ( ! $course || ! is_callable( array( $course, 'get_instructors' ) ) ) {
	return;
}

$instructors = $course->get_instructors();

if ( empty( $instructors ) ) {
	return;
}
?>
<section class="alostora-instructors llms-instructor-info">
	<h2 class="alostora-instructors__title"><?php esc_html_e( 'Instructors', 'alostora' ); ?></h2>
	<div class="alostora-instructors__grid">
		<?php
		foreach ( $instructors as $data ) :
			$user_id = isset( $data['id'] ) ? (int) $data['id'] : 0;
			if ( ! $user_id ) {
				continue;
			}
			$user = get_userdata( $user_id );
			if ( ! $user ) {
				continue;
			}
			?>
			<div class="alostora-instructor">
				<?php echo get_avatar( $user_id, 64, '', $user->display_name, array( 'class' => 'alostora-instructor__avatar' ) ); ?>
				<div class="alostora-instructor__body">
					<p class="alostora-instructor__name"><?php echo esc_html( $user->display_name ); ?></p>
					<?php if ( $user->description ) : ?>
						<p class="alostora-instructor__bio"><?php echo esc_html( wp_trim_words( $user->description, 28 ) ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
