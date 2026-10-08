<?php
/**
 * Front-end assets: the Adobe Fonts kit, tokens, base and module styles.
 * No build step: plain CSS with custom properties and plain JS.
 */

defined( 'ABSPATH' ) || exit;

/** Adobe Fonts kit "Assemble Rebranded Site" (IvyOra, Parabolica). Never self-host these fonts. */
const ASSEMBLE_ADOBE_KIT = 'https://use.typekit.net/zhl4zhx.css';

/** Version a theme file by its modification time, so deploys bust caches. */
function assemble_asset_version( string $relative ): string {
	$file = get_template_directory() . '/' . $relative;

	return is_readable( $file ) ? (string) filemtime( $file ) : ASSEMBLE_THEME_VERSION;
}

/** Enqueue assets/css/modules/<id>.css, once, after base. */
function assemble_enqueue_module_style( string $module_id ): void {
	$relative = 'assets/css/modules/' . $module_id . '.css';
	wp_enqueue_style(
		'assemble-module-' . $module_id,
		get_template_directory_uri() . '/' . $relative,
		array( 'assemble-base' ),
		assemble_asset_version( $relative )
	);
}

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		wp_enqueue_style( 'assemble-fonts', ASSEMBLE_ADOBE_KIT, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- the kit URL is versioned by Adobe.

		foreach ( array( 'tokens', 'base' ) as $handle ) {
			wp_enqueue_style(
				'assemble-' . $handle,
				get_template_directory_uri() . '/assets/css/' . $handle . '.css',
				'base' === $handle ? array( 'assemble-fonts', 'assemble-tokens' ) : array(),
				assemble_asset_version( 'assets/css/' . $handle . '.css' )
			);
		}

		// Site chrome is on every page.
		assemble_enqueue_module_style( 'public-masthead' );
		assemble_enqueue_module_style( 'public-footer' );

		// Homepage modules.
		if ( is_front_page() ) {
			foreach ( array( 'home-top-stories', 'peer-intelligence-splash', 'content-explorer', 'learn-more-band' ) as $assemble_module ) {
				assemble_enqueue_module_style( $assemble_module );
			}

			wp_enqueue_script(
				'assemble-content-explorer',
				get_template_directory_uri() . '/assets/js/content-explorer.js',
				array(),
				assemble_asset_version( 'assets/js/content-explorer.js' ),
				array(
					'strategy'  => 'defer',
					'in_footer' => true,
				)
			);
		}

		// Field Report pages (single-field_report.php).
		if ( is_singular( 'field_report' ) ) {
			foreach ( array( 'article-hero', 'article-body-with-aside', 'article-author-bio', 'related-peer-intelligence', 'account-cta', 'report-gate' ) as $assemble_module ) {
				assemble_enqueue_module_style( $assemble_module );
			}

			wp_enqueue_script(
				'assemble-article-hero',
				get_template_directory_uri() . '/assets/js/article-hero.js',
				array(),
				assemble_asset_version( 'assets/js/article-hero.js' ),
				array(
					'strategy'  => 'defer',
					'in_footer' => true,
				)
			);
		}

		// Insights index and Community archives.
		if ( is_post_type_archive( 'field_report' ) || is_tax( 'field_report_community' ) ) {
			foreach ( array( 'article-hero', 'related-peer-intelligence', 'report-archive' ) as $assemble_module ) {
				assemble_enqueue_module_style( $assemble_module );
			}
		}

		wp_enqueue_script(
			'assemble-masthead',
			get_template_directory_uri() . '/assets/js/public-masthead.js',
			array(),
			assemble_asset_version( 'assets/js/public-masthead.js' ),
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
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

// The block editor gets the kit too, so headings preview in IvyOra.
add_action(
	'enqueue_block_editor_assets',
	static function (): void {
		wp_enqueue_style( 'assemble-fonts', ASSEMBLE_ADOBE_KIT, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
);
