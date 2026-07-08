<?php
/**
 * Content partial: a single page's content (used as a fallback include).
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'alostora-entry' ); ?>>
	<div class="alostora-entry__content entry-content">
		<?php the_content(); ?>
	</div>
</article>
