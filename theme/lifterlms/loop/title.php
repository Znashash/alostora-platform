<?php
/**
 * LifterLMS override: course/membership loop title.
 *
 * Overrides LifterLMS `loop/title.php`.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;
?>
<h3 class="alostora-course__title llms-loop-title">
	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h3>
