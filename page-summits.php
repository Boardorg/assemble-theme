<?php
/**
 * The Summits page (wireframe "2. Summits"), for the page with the slug
 * "summits". A static composition: summit facts come from executiveplatforms.com
 * through assemble-core, and every summit link goes to EP (Phase 5a).
 *
 * Hero, then the practice-area selector with one panel per Community (intro,
 * Featured Summits, free-account call to action), then every upcoming summit.
 * Not built yet, because nothing feeds them: the "Convening N leaders" source
 * panel, Summit talks and Leaders to follow (see docs/handoff.md → Placeholders).
 */

defined( 'ABSPATH' ) || exit;

get_header();

$assemble_summits = assemble_upcoming_summits();
?>
<div class="wrap summits-page">
	<?php
	get_template_part( 'template-parts/modules/summits-hero' );
	get_template_part( 'template-parts/modules/summits-selector' );
	get_template_part( 'template-parts/modules/summits-matrix', null, array( 'summits' => $assemble_summits ) );
	?>
</div>
<?php
get_footer();
