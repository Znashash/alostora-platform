<?php
/**
 * LifterLMS override: course loop author.
 *
 * Overrides LifterLMS `loop/author.php`. Wraps the standard LifterLMS author
 * output in the brand instructor row so the catalog card matches the design.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'llms_get_author' ) ) {
	return;
}

echo '<span class="alostora-course__instructor">';
echo llms_get_author( array( 'avatar_size' => 28 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped inside function.
echo '</span>';
