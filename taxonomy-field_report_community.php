<?php
/**
 * A Community's Insights (/field-reports/community/<slug>/), in its practice
 * area's colour, with the Community's description from the feed's directory.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$assemble_term      = get_queried_object();
$assemble_community = ( $assemble_term instanceof WP_Term && class_exists( 'AFR_Communities' ) ) ? AFR_Communities::get( $assemble_term->slug ) : null;
$assemble_kicker    = array(
	'name'          => $assemble_term instanceof WP_Term ? $assemble_term->name : '',
	'short_name'    => (string) ( $assemble_community['short_name'] ?? '' ),
	'slug'          => $assemble_term instanceof WP_Term ? $assemble_term->slug : '',
	'practice_area' => (string) ( $assemble_community['practice_area'] ?? '' ),
);
$assemble_crumbs    = assemble_report_crumbs( $assemble_kicker );
array_pop( $assemble_crumbs ); // This page is the Community crumb.

get_template_part(
	'template-parts/modules/report-archive',
	null,
	array(
		/* translators: 1: Community label, e.g. "AEO", 2: "Insights". */
		'title'  => sprintf( __( '%1$s %2$s', 'assemble' ), assemble_community_label( $assemble_kicker ), assemble_label( 'insights' ) ),
		'intro'  => assemble_first_sentence( (string) ( $assemble_community['boilerplate'] ?? '' ) ),
		'area'   => assemble_area( $assemble_kicker['practice_area'] ),
		'crumbs' => $assemble_crumbs,
	)
);

get_footer();
