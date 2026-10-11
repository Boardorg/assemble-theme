<?php
/**
 * Standard pages (About, Careers, Contact, Policies…): the content head, then
 * the block editor's content in a 720px column. No wireframe: on-system and
 * flagged for design (BUILD-INSTRUCTIONS §7.3). Breadcrumbs only for a child page.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="page-shell" data-area="neutral">
		<div class="wrap">
			<?php get_template_part( 'template-parts/page-head' ); ?>
			<div class="page-content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
