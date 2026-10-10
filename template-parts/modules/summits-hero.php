<?php
/**
 * Module: summits-hero (wireframe 2): kicker, headline, intro and a 16:9 image.
 *
 * The page's featured image fills the frame when one is set (Pages → Summits);
 * otherwise it stays an empty sunken frame, as on other pages without art.
 */

defined( 'ABSPATH' ) || exit;

$assemble_page_id = (int) get_queried_object_id();
?>
<section class="summits-hero" aria-labelledby="summits-hero-title">
	<div class="summits-hero-copy">
		<div class="eyebrow"><?php esc_html_e( 'Executive Summits', 'assemble' ); ?></div>
		<h1 class="type-hero" id="summits-hero-title"><?php esc_html_e( 'The peers you’d call — all here at once.', 'assemble' ); ?></h1>
		<p><?php esc_html_e( 'Every function has a set of decisions only its own leaders truly understand. We build rooms around those decisions, fill them with peers operating at your scale, and stay out of the way. Start with your practice area.', 'assemble' ); ?></p>
	</div>
	<div class="media-frame media-landscape summits-hero-image">
		<?php
		if ( $assemble_page_id && has_post_thumbnail( $assemble_page_id ) ) {
			echo get_the_post_thumbnail(
				$assemble_page_id,
				'assemble-l2',
				array(
					'sizes'   => '(max-width: 1100px) 100vw, 560px',
					'loading' => 'eager',
				)
			);
		}
		?>
	</div>
</section>
