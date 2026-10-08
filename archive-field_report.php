<?php
/**
 * The Insights index (/field-reports/): every Field Report as a card, newest first.
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/modules/report-archive',
	null,
	array(
		'title' => assemble_label( 'insights' ),
		'intro' => __( 'Peer intelligence from Assemble’s Communities: what senior leaders are deciding, measuring and changing.', 'assemble' ),
		'area'  => 'neutral',
	)
);

get_footer();
