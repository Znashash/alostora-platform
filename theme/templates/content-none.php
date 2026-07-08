<?php
/**
 * Content partial: shown when no posts are found.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="alostora-empty u-text-center">
	<p><?php esc_html_e( 'Nothing found. Try a different search.', 'alostora' ); ?></p>
	<?php get_search_form(); ?>
</div>
