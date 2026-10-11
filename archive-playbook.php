<?php
/**
 * The playbooks index (/playbooks/): every playbook as a card, newest first.
 * No wireframe: the Insights index's shell and cards (on-system, flagged).
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/modules/report-archive',
	null,
	array(
		'title'  => assemble_label( 'playbooks' ),
		'intro'  => __( 'Field-tested guides built from what senior leaders in Assemble’s Communities have tried, measured and learned.', 'assemble' ),
		'area'   => 'neutral',
		'crumbs' => array(
			array(
				'label' => assemble_label( 'insights' ),
				'url'   => assemble_insights_url(),
			),
		),
	)
);

get_footer();
