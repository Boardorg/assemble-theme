<?php
/**
 * Our Team (/our-team/): the page's own content as the intro, then everyone in
 * the `team` post type (assemble-core), in the editors' order. People data stays
 * in WordPress. No wireframe: on-system and flagged for design.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="page-shell" data-area="neutral">
		<div class="wrap">
			<?php get_template_part( 'template-parts/page-head' ); ?>
			<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/modules/team-grid', null, array( 'people' => assemble_team() ) ); ?>
			<?php get_template_part( 'template-parts/contact-band' ); ?>
		</div>
	</div>
	<?php
endwhile;

get_footer();
