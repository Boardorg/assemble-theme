<?php
/**
 * Every visible label that is still being decided lives here, so a wording
 * change is one line (BUILD-INSTRUCTIONS §6.5, open decision #8).
 */

defined( 'ABSPATH' ) || exit;

function assemble_label( string $key ): string {
	$labels = [
		'insights'         => __( 'Insights', 'assemble' ),
		'communities'      => __( 'Communities', 'assemble' ), // Or "Functional Areas": decision #8.
		'community'        => __( 'Community', 'assemble' ),
		'summits'          => __( 'Summits', 'assemble' ),
		'working_sessions' => __( 'Working Sessions', 'assemble' ),
		'sign_in'          => __( 'Sign in', 'assemble' ),
		'create'           => __( 'Create free account', 'assemble' ),
		'account'          => __( 'Account', 'assemble' ),
		'sign_out'         => __( 'Sign out', 'assemble' ),
		'search'           => __( 'Search', 'assemble' ),
		'members_only'     => __( 'Board Members only', 'assemble' ), // The gated label, everywhere (wireframe edits A).
		'menu'             => __( 'Menu', 'assemble' ),
		'reserve_seat'     => __( 'Reserve your seat', 'assemble' ), // Summit CTA to EP's registration (wireframe 2).
		'summit_details'   => __( 'View Summit details', 'assemble' ), // Homepage Next Summit card (wireframe 1).
		'all_summits'      => __( 'See all Summits', 'assemble' ),
		'playbook'         => __( 'Playbook', 'assemble' ), // Content-type tag (wireframe 5).
		'playbooks'        => __( 'Playbooks', 'assemble' ),
		'guidance_layers'  => __( 'How the guidance layers', 'assemble' ), // Default heading of a Layers callout (wireframe 5).
		'phase'            => __( 'Phase %d', 'assemble' ), // TOC caption of a phase chapter; %d is its number.
		'help_card'        => __( 'We’re here to help.', 'assemble' ), // Playbook help card heading (wireframe 5).
		'manage_follows'   => __( 'Manage what you follow', 'assemble' ), // Signed-in TOC card (no wireframe).
	];

	/**
	 * Filter the visible labels.
	 *
	 * @param array<string,string> $labels
	 */
	$labels = (array) apply_filters( 'assemble/labels', $labels );

	return (string) ( $labels[ $key ] ?? $key );
}
