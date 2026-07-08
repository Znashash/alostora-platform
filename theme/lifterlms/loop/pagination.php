<?php
/**
 * LifterLMS override: catalog pagination.
 *
 * Overrides LifterLMS `loop/pagination.php` to use the theme's pagination markup.
 *
 * @package Alostora
 * @var int $max Total number of pages (provided by LifterLMS).
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;

$total = isset( $max ) ? (int) $max : (int) $wp_query->max_num_pages;

if ( $total <= 1 ) {
	return;
}

echo '<nav class="alostora-pagination llms-pagination" aria-label="' . esc_attr__( 'Course catalog pages', 'alostora' ) . '">';
echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links returns safe markup.
	array(
		'total'     => $total,
		'mid_size'  => 2,
		'prev_text' => esc_html__( 'Previous', 'alostora' ),
		'next_text' => esc_html__( 'Next', 'alostora' ),
		'type'      => 'list',
	)
);
echo '</nav>';
