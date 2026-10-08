<?php
/**
 * A Field Report (wireframe "4. Article"), rendered from the feed's view model.
 *
 * The plugin decides the view (standard, council, delegate, public, denied) and
 * hands over only the sections that view may show; the modules print whatever
 * is present and never decide access themselves. If the feed is too old to
 * have a view model, the plugin's own document is used instead. Draft previews
 * render the same template part (inc/content.php, afr_render_preview).
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$assemble_report = assemble_report_view( get_the_ID() );

	if ( ! $assemble_report ) :
		?>
		<div class="wrap">
			<article <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
		</div>
		<?php
		continue;
	endif;

	get_template_part(
		'template-parts/report-page',
		null,
		array(
			'report'  => $assemble_report,
			'post_id' => get_the_ID(),
			'url'     => (string) get_permalink(),
		)
	);
endwhile;

get_footer();
