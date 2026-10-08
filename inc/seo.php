<?php
/**
 * Yoast SEO for Field Reports: the share image from the report's Contentful
 * featuredImage (Images API, 1200×630), the dek as the meta description, and
 * "Insights" titles for the index and Community archives. Each hook does
 * nothing without Yoast or the feed.
 */

defined( 'ABSPATH' ) || exit;

/** A Contentful image URL cropped to the 1200×630 share size, or ''. */
function assemble_share_image_url( ?array $asset ): string {
	$url = class_exists( 'AFR_Contentful' ) ? AFR_Contentful::asset_url( $asset ) : '';

	return '' === $url ? '' : add_query_arg(
		array(
			'w'   => 1200,
			'h'   => 630,
			'fit' => 'fill',
			'fm'  => 'jpg',
			'q'   => 85,
		),
		$url
	);
}

// The report's featured image as og:image (and so twitter:image).
add_action(
	'wpseo_add_opengraph_images',
	static function ( $images ): void {
		if ( ! is_singular( 'field_report' ) || ! assemble_has_feed() ) {
			return;
		}

		$url = assemble_share_image_url( assemble_story( get_post() )['image'] );
		if ( '' !== $url ) {
			$images->add_image(
				array(
					'url'    => $url,
					'width'  => 1200,
					'height' => 630,
				)
			);
		}
	}
);

// The dek (the feed's excerpt) as the meta description when Yoast has none.
add_filter(
	'wpseo_metadesc',
	static function ( $description ) {
		if ( '' === (string) $description && is_singular( 'field_report' ) ) {
			return wp_trim_words( (string) get_the_excerpt( get_queried_object_id() ), 30 );
		}

		return $description;
	}
);

// "Insights" and "AEO Insights" instead of "Field Reports Archive" and "AEO Board Archives".
$assemble_archive_title = static function ( $title ) {
	if ( is_post_type_archive( 'field_report' ) ) {
		return assemble_label( 'insights' ) . ' - ' . get_bloginfo( 'name' );
	}

	if ( is_tax( 'field_report_community' ) ) {
		$term      = get_queried_object();
		$community = ( $term instanceof WP_Term && class_exists( 'AFR_Communities' ) ) ? (array) AFR_Communities::get( $term->slug ) : array();
		$label     = assemble_community_label(
			array(
				'name'       => $term instanceof WP_Term ? $term->name : '',
				'short_name' => (string) ( $community['short_name'] ?? '' ),
			)
		);

		return $label . ' ' . assemble_label( 'insights' ) . ' - ' . get_bloginfo( 'name' );
	}

	return $title;
};
add_filter( 'wpseo_title', $assemble_archive_title );
add_filter( 'wpseo_opengraph_title', $assemble_archive_title );
