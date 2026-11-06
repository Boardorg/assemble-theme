<?php
/**
 * The Insights index (/insights/): the shared page hero (as the Summits
 * and Communities indexes: section name as the kicker, a promise as the
 * headline), then the homepage's Explore peer intelligence module
 * (content-explorer), so readers pick a practice area and then a Community. Each Community's full list is its
 * archive (/insights/community/<slug>/), linked from the Insights card.
 *
 * The same archive, filtered by assemble-core to a practice area
 * (/insights/area/<area>/) or a topic (/insights/topic/<slug>/), is a grid of
 * report cards instead (report-archive), like a Community's archive.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$assemble_filter = assemble_insights_filter();

if ( $assemble_filter ) {
	$assemble_crumbs = array(
		array(
			'label' => assemble_label( 'insights' ),
			'url'   => assemble_insights_url(),
		),
	);

	if ( 'area' === $assemble_filter['type'] ) {
		/* translators: 1: practice area, 2: "Communities". */
		$assemble_intro = sprintf( __( 'Peer intelligence from Assemble’s %1$s %2$s, newest first.', 'assemble' ), $assemble_filter['label'], assemble_label( 'communities' ) );
		$assemble_area  = $assemble_filter['key'];
	} else {
		/* translators: %s: topic name, e.g. "Manager Capability". */
		$assemble_intro = sprintf( __( 'Every Insight on %s, newest first.', 'assemble' ), $assemble_filter['label'] );
		$assemble_area  = 'neutral';
	}

	get_template_part(
		'template-parts/modules/report-archive',
		null,
		array(
			'title'  => $assemble_filter['label'],
			'intro'  => $assemble_intro,
			'area'   => $assemble_area,
			'crumbs' => $assemble_crumbs,
		)
	);

	get_footer();
	return;
}
?>
<div class="report-archive insights-index" data-area="neutral">
	<div class="wrap">
		<section class="page-hero is-copy-only" aria-labelledby="insights-title">
			<div class="page-hero-copy">
				<div class="eyebrow"><?php echo esc_html( assemble_label( 'insights' ) ); ?></div>
				<h1 class="type-hero" id="insights-title"><?php esc_html_e( 'Peer intelligence from the people doing the work.', 'assemble' ); ?></h1>
				<p><?php esc_html_e( 'Peer intelligence from Assemble’s Communities: what senior leaders are deciding, measuring and changing.', 'assemble' ); ?></p>
			</div>
		</section>
	</div>
</div>
<?php
get_template_part( 'template-parts/modules/content-explorer' );

get_footer();
