<?php
/**
 * Standard pages (About, Careers, Contact, Policies…): the content head, then
 * the block editor's content in a 720px column. No wireframe: on-system and
 * flagged for design (BUILD-INSTRUCTIONS §7.3). Breadcrumbs only for a child page.
 *
 * A page built from full-width bands (assemble_page_has_bands(): About, Careers,
 * Contact) skips the content head and column: its bands set their own widths
 * and its first band carries the H1.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	if ( assemble_page_has_bands() ) :
		?>
		<div class="page-bands" data-area="neutral">
			<?php the_content(); ?>
		</div>
		<?php
		continue;
	endif;
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
