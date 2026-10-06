<?php
/**
 * Front-end assets: the Adobe Fonts kit and the theme stylesheets.
 */

defined( 'ABSPATH' ) || exit;

/** Adobe Fonts kit "Assemble Rebranded Site" (IvyOra, Parabolica). Never self-host these fonts. */
const ASSEMBLE_ADOBE_KIT = 'https://use.typekit.net/zhl4zhx.css';

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		wp_enqueue_style( 'assemble-fonts', ASSEMBLE_ADOBE_KIT, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- the kit URL is versioned by Adobe.
		wp_enqueue_style(
			'assemble-base',
			get_template_directory_uri() . '/assets/css/base.css',
			array( 'assemble-fonts' ),
			ASSEMBLE_THEME_VERSION
		);
	}
);

add_filter(
	'wp_resource_hints',
	static function ( array $urls, string $relation ): array {
		if ( 'preconnect' === $relation ) {
			$urls[] = array(
				'href'        => 'https://use.typekit.net',
				'crossorigin' => 'anonymous',
			);
		}

		return $urls;
	},
	10,
	2
);
