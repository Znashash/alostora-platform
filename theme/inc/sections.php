<?php
/**
 * Homepage section shortcodes.
 *
 * Each shortcode renders one full homepage section by composing the reusable
 * components. They are used by the Elementor homepage template so content stays
 * editable (via attributes) while the theme owns the markup and styling. All
 * imagery falls back to bundled placeholders — no section is ever empty.
 *
 * @package Alostora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the homepage section shortcodes.
 *
 * Registration is co-located with the implementations in this file so a
 * shortcode can never be registered without its callback being available (and
 * vice-versa). Runs on `init`, the standard, safe hook for shortcodes so they
 * work on the front end without Elementor editing mode. Guarded with
 * function_exists as a final safety net.
 *
 * @return void
 */
function alostora_register_section_shortcodes() {
	$shortcodes = array(
		'alostora_courses_carousel' => 'alostora_shortcode_courses_carousel',
		'alostora_steps'            => 'alostora_shortcode_steps',
		'alostora_features'         => 'alostora_shortcode_features',
		'alostora_video_showcase'   => 'alostora_shortcode_video_showcase',
		'alostora_cta_banner'       => 'alostora_shortcode_cta_banner',
	);

	foreach ( $shortcodes as $tag => $callback ) {
		if ( function_exists( $callback ) ) {
			add_shortcode( $tag, $callback );
		}
	}
}
add_action( 'init', 'alostora_register_section_shortcodes' );

/**
 * Render a section heading block.
 *
 * @param string $title    Title.
 * @param string $subtitle Subtitle.
 * @param bool   $invert   Whether the section is on a dark background.
 * @return string
 */
function alostora_section_head( $title, $subtitle = '', $invert = false ) {
	if ( ! $title && ! $subtitle ) {
		return '';
	}

	$out  = '<div class="alostora-section__head"' . ( $invert ? ' data-reveal' : ' data-reveal' ) . '>';
	$out .= '<hr class="u-divider" aria-hidden="true">';
	if ( $title ) {
		$out .= '<h2 class="alostora-section__title">' . esc_html( $title ) . '</h2>';
	}
	if ( $subtitle ) {
		$out .= '<p class="alostora-section__subtitle">' . esc_html( $subtitle ) . '</p>';
	}
	$out .= '</div>';

	return $out;
}

/**
 * [alostora_features] — "Why learn with animation?" benefits band.
 *
 * @param array $atts Attributes.
 * @return string
 */
function alostora_shortcode_features( $atts ) {
	$atts = shortcode_atts(
		array(
			'title' => 'لماذا التعلّم بالرسوم المتحركة؟',
			'image' => '',
		),
		$atts,
		'alostora_features'
	);

	$items = array(
		array( 'icon' => 'chart', 'title' => 'رفع مستوى التحصيل', 'text' => 'نتائج وتذكّر أفضل في الاختبارات.' ),
		array( 'icon' => 'brain', 'title' => 'ثبات المعلومة في الذاكرة', 'text' => 'تساعد على تذكّر المعلومة لفترة أطول.' ),
		array( 'icon' => 'video', 'title' => 'اجعل التعلّم ممتعاً', 'text' => 'تحوّل الدروس إلى قصص مشوّقة.' ),
		array( 'icon' => 'trophy', 'title' => 'زيادة التركيز والفهم', 'text' => 'تحافظ على الانتباه ووضوح الفكرة.' ),
	);

	$inner = alostora_get_component( 'features', array(
		'title' => $atts['title'],
		'items' => $items,
		'image' => $atts['image'],
	) );

	return '<div class="alostora-section"><div class="alostora-container">' . $inner . '</div></div>';
}

/**
 * [alostora_courses_carousel] — featured courses slider from LifterLMS.
 *
 * @param array $atts Attributes.
 * @return string
 */
function alostora_shortcode_courses_carousel( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'    => 'دوراتنا المميّزة',
			'subtitle' => 'تعلّم من خلال أفضل الدورات المصمّمة بطريقة عصرية وممتعة.',
			'count'    => 8,
		),
		$atts,
		'alostora_courses_carousel'
	);

	$cards = alostora_get_course_cards( (int) $atts['count'] );

	ob_start();
	?>
	<div class="alostora-section" id="courses">
		<div class="alostora-container">
			<?php echo alostora_section_head( $atts['title'], $atts['subtitle'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>

			<?php if ( ! empty( $cards ) ) : ?>
				<div class="alostora-carousel" data-carousel>
					<div class="alostora-carousel__viewport" data-carousel-viewport>
						<div class="alostora-carousel__track">
							<?php foreach ( $cards as $card ) : ?>
								<div class="alostora-carousel__item"><?php echo $card; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Component escapes its own output. ?></div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="alostora-carousel__controls">
						<button class="alostora-carousel__btn alostora-carousel__btn--prev" type="button" data-carousel-prev aria-label="السابق"><?php alostora_svg( 'chevron' ); ?></button>
						<button class="alostora-carousel__btn alostora-carousel__btn--next" type="button" data-carousel-next aria-label="التالي"><?php alostora_svg( 'chevron' ); ?></button>
					</div>
				</div>
			<?php else : ?>
				<p class="u-text-center">ستظهر الدورات هنا بعد نشر دورات LifterLMS.</p>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Build an array of rendered course-card component strings from LifterLMS.
 *
 * @param int $count Maximum number of courses.
 * @return array
 */
function alostora_get_course_cards( $count = 8 ) {
	$cards = array();

	if ( ! post_type_exists( 'course' ) ) {
		return $cards;
	}

	$query = new WP_Query( array(
		'post_type'              => 'course',
		'post_status'            => 'publish',
		'posts_per_page'         => max( 1, $count ),
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'ignore_sticky_posts'    => true,
	) );

	foreach ( $query->posts as $post ) {
		$course_id = $post->ID;

		$category = '';
		$terms    = get_the_terms( $course_id, 'course_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$category = $terms[0]->name;
		}

		$lessons = 0;
		if ( function_exists( 'llms_get_post' ) ) {
			$course = llms_get_post( $course_id );
			if ( $course && is_callable( array( $course, 'get_lessons' ) ) ) {
				$lessons = count( $course->get_lessons( 'ids' ) );
			}
		}

		$cards[] = alostora_get_component( 'course-card', array(
			'title'      => get_the_title( $course_id ),
			'url'        => get_permalink( $course_id ),
			'image'      => get_the_post_thumbnail_url( $course_id, 'alostora-course-card' ) ?: alostora_placeholder_url( 'video-cover.webp' ),
			'category'   => $category,
			'instructor' => get_the_author_meta( 'display_name', $post->post_author ),
			'lessons'    => $lessons,
			'cta_label'  => 'ابدأ الآن',
		) );
	}

	wp_reset_postdata();

	return $cards;
}

/**
 * [alostora_steps] — "How we teach" four-step flow.
 *
 * @param array $atts Attributes.
 * @return string
 */
function alostora_shortcode_steps( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'    => 'كيف ندرّس؟',
			'subtitle' => 'حوّلنا التعلّم إلى تجربة لا تُنسى.',
		),
		$atts,
		'alostora_steps'
	);

	$steps = array(
		array( 'number' => '1', 'icon' => 'book', 'title' => 'من الكتاب', 'description' => 'نأخذ المعلومة الأساسية.' ),
		array( 'number' => '2', 'icon' => 'video', 'title' => 'إلى الرسوم المتحركة', 'description' => 'نحوّلها إلى قصة مرئية.' ),
		array( 'number' => '3', 'icon' => 'brain', 'title' => 'إلى الفهم والذكر', 'description' => 'تصل المعلومة بطريقة أسهل.' ),
		array( 'number' => '4', 'icon' => 'trophy', 'title' => 'إلى التفوق والنجاح', 'description' => 'لتحقيق أفضل النتائج.' ),
	);

	ob_start();
	?>
	<div class="alostora-section alostora-section--surface" id="how">
		<div class="alostora-container">
			<?php echo alostora_section_head( $atts['title'], $atts['subtitle'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
			<div class="alostora-steps" style="--steps: <?php echo count( $steps ); ?>;">
				<?php
				$last = count( $steps ) - 1;
				foreach ( $steps as $i => $step ) {
					alostora_component( 'step-card', $step );
					if ( $i < $last ) {
						echo '<span class="alostora-steps__arrow" aria-hidden="true">' . alostora_get_svg( 'chevron' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted SVG.
					}
				}
				?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * [alostora_video_showcase] — "Watch how we teach" video gallery.
 *
 * @param array $atts Attributes.
 * @return string
 */
function alostora_shortcode_video_showcase( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'    => 'شاهد طريقة تدريسنا',
			'subtitle' => 'تجربة تعليمية محاكاة بالكامل.',
			'main_id'  => '',
			'tiles'    => '',
		),
		$atts,
		'alostora_video_showcase'
	);

	$poster = alostora_placeholder_url( 'video-cover.webp' );

	$tiles = array_filter( array_map( 'trim', explode( ',', (string) $atts['tiles'] ) ) );
	if ( empty( $tiles ) ) {
		$tiles = array( '', '', '' );
	}

	ob_start();
	?>
	<div class="alostora-section">
		<div class="alostora-container">
			<?php echo alostora_section_head( $atts['title'], $atts['subtitle'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
			<div class="alostora-showcase">
				<div class="alostora-showcase__main" data-reveal>
					<?php
					alostora_component( 'video-card', array(
						'id'    => $atts['main_id'],
						'title' => 'مشاهدة درس تجريبي',
						'image' => $poster,
					) );
					?>
				</div>
				<div class="alostora-showcase__tiles">
					<?php foreach ( array_slice( $tiles, 0, 3 ) as $i => $tile_id ) : ?>
						<?php
						alostora_component( 'video-card', array(
							'id'    => $tile_id,
							'title' => '',
							'image' => $poster,
						) );
						?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * [alostora_cta_banner] — closing call-to-action banner.
 *
 * @param array $atts Attributes.
 * @return string
 */
function alostora_shortcode_cta_banner( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'    => 'ابدأ رحلتك التعليمية اليوم',
			'text'     => 'انضمّ إلى آلاف الطلاب الذين اختاروا طريقة الرسوم المتحركة للتعلّم.',
			'label'    => 'سجّل الآن مجاناً',
			'url'      => '#',
		),
		$atts,
		'alostora_cta_banner'
	);

	ob_start();
	?>
	<div class="alostora-section alostora-section--tight">
		<div class="alostora-container">
			<div class="alostora-cta" data-reveal>
				<div class="alostora-cta__text">
					<h2 class="alostora-cta__title"><?php echo esc_html( $atts['title'] ); ?></h2>
					<p class="alostora-cta__subtitle"><?php echo esc_html( $atts['text'] ); ?></p>
				</div>
				<div class="alostora-cta__actions">
					<?php
					alostora_component( 'buttons', array(
						'label' => $atts['label'],
						'url'   => $atts['url'],
						'style' => 'primary',
						'size'  => 'lg',
					) );
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
