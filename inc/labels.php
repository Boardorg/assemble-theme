<?php
/**
 * Every visible label that is still being decided lives here, so a wording
 * change is one line (BUILD-INSTRUCTIONS §6.5, open decision #8).
 */

defined( 'ABSPATH' ) || exit;

function assemble_label( string $key ): string {
	$labels = [
		'insights'    => __( 'Insights', 'assemble' ),
		'communities' => __( 'Communities', 'assemble' ), // Or "Functional Areas": decision #8.
		'community'   => __( 'Community', 'assemble' ),
		'summits'     => __( 'Summits', 'assemble' ),
		'sign_in'     => __( 'Sign in', 'assemble' ),
		'create'      => __( 'Create free account', 'assemble' ),
		'search'      => __( 'Search', 'assemble' ),
		'menu'        => __( 'Menu', 'assemble' ),
	];

	/**
	 * Filter the visible labels.
	 *
	 * @param array<string,string> $labels
	 */
	$labels = (array) apply_filters( 'assemble/labels', $labels );

	return (string) ( $labels[ $key ] ?? $key );
}
