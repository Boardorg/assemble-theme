<?php
/**
 * A playbook (wireframe "5. Playbook"), rendered from the feed's view model.
 *
 * The plugin decides the view (standard, public or denied) and hands over only
 * the sections that view may show (AFR_Playbook::view_model()); the modules
 * print whatever is present and never decide access themselves. Without the
 * feed, the page shows the title and the teaser.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$assemble_playbook = assemble_playbook_view( get_the_ID() );

	if ( ! $assemble_playbook ) :
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
		'template-parts/playbook-page',
		null,
		array(
			'playbook' => $assemble_playbook,
			'post_id'  => get_the_ID(),
			'url'      => (string) get_permalink(),
		)
	);
endwhile;

get_footer();
