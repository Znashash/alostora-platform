<?php
/**
 * LifterLMS override: course loop author.
 *
 * Overrides LifterLMS `loop/author.php`. Renders a compact instructor row with a
 * small branded avatar placeholder (never the large WordPress mystery-person)
 * plus the instructor name, using the LifterLMS course API.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'llms_get_post' ) ) {
	return;
}

$course = llms_get_post( get_the_ID() );

if ( ! $course || ! is_callable( array( $course, 'get_author_name' ) ) ) {
	return;
}

$author = $course->get_author_name();

if ( ! $author ) {
	return;
}
?>
<span class="alostora-course__instructor">
	<span class="alostora-course__avatar"><?php alostora_svg( 'user' ); ?></span>
	<span class="alostora-course__instructor-name"><?php echo esc_html( $author ); ?></span>
</span>
