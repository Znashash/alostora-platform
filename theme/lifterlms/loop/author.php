<?php
/**
 * LifterLMS override: course loop author.
 *
 * Overrides LifterLMS `loop/author.php`. Uses the LifterLMS course API to fetch
 * the instructor, wrapped in the brand instructor row.
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
<p class="alostora-course__instructor llms-author">
	<?php alostora_svg( 'user' ); ?>
	<span class="llms-author-name"><?php echo esc_html( $author ); ?></span>
</p>
