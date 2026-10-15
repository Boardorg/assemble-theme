<?php
/**
 * The Insights index (/field-reports/): the page head, then the homepage's
 * Explore peer intelligence module (content-explorer), so readers pick a
 * practice area and then a Community. Each Community's full list is its
 * archive (/field-reports/community/<slug>/), linked from the Insights card.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="article-shell report-archive insights-index" data-area="neutral">
	<div class="wrap">
		<header class="content-head archive-head">
			<h1><?php echo esc_html( assemble_label( 'insights' ) ); ?></h1>
			<p class="article-dek"><?php esc_html_e( 'Peer intelligence from Assemble’s Communities: what senior leaders are deciding, measuring and changing.', 'assemble' ); ?></p>
		</header>
	</div>
</div>
<?php
get_template_part( 'template-parts/modules/content-explorer' );

get_footer();
