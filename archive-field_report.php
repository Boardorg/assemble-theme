<?php
/**
 * The Insights index (/field-reports/): the shared page hero (as the Summits
 * and Communities indexes: section name as the kicker, a promise as the
 * headline), then the homepage's Explore peer intelligence module
 * (content-explorer), so readers pick a practice area and then a Community. Each Community's full list is its
 * archive (/field-reports/community/<slug>/), linked from the Insights card.
 */

defined( 'ABSPATH' ) || exit;

get_header();
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
