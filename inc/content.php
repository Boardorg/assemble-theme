<?php
/**
 * Content for templates: turns the Assemble Content feed's data into plain
 * arrays, so templates never call the plugin directly. Every function returns
 * an empty result when the plugin is inactive, and the templates render their
 * empty states.
 *
 * ⚠️ Listing a gated report as a card is deliberate (sign-ups). Nothing here
 * decides whether a report's body may be shown: that is the plugin's job.
 */

defined( 'ABSPATH' ) || exit;

/** Is the Assemble Content feed active, at 1.2.0 or later (topics and community queries)? */
function assemble_has_feed(): bool {
	return class_exists( 'AFR_Renderer' ) && method_exists( 'AFR_Query', 'topics' ) && method_exists( 'AFR_Query', 'in_community' );
}

/**
 * The five public practice areas, in selector order. Keys are the shared
 * `data-area` / Contentful `community.practiceArea` keys (BUILD-INSTRUCTIONS §6.5).
 *
 * @return array<string,string> area key => public label.
 */
function assemble_practice_areas(): array {
	return array(
		'human-resources' => __( 'Human Capital', 'assemble' ),
		'manufacturing'   => __( 'Supply Chain', 'assemble' ),
		'marketing'       => __( 'Marketing & Comms', 'assemble' ),
		'technology'      => __( 'Technology', 'assemble' ),
		'finance'         => __( 'Finance', 'assemble' ),
	);
}

/** A practice-area key, or 'neutral' when it isn't one of ours. */
function assemble_area( string $area ): string {
	return isset( assemble_practice_areas()[ $area ] ) ? $area : 'neutral';
}

/**
 * Communities in one practice area, from the feed's community directory.
 *
 * @return array<int,array{slug:string,name:string,label:string,area:string,description:string}>
 */
function assemble_area_communities( string $area ): array {
	if ( ! class_exists( 'AFR_Communities' ) ) {
		return array();
	}

	$communities = array();
	foreach ( AFR_Communities::in_area( $area ) as $community ) {
		$name          = (string) $community['name'];
		$communities[] = array(
			'slug'        => (string) $community['slug'],
			'name'        => $name,
			// "Learning & Development Board" reads as "Learning & Development" in a pill row.
			'label'       => (string) preg_replace( '/\s+Board$/', '', $name ),
			'area'        => $area,
			'description' => assemble_first_sentence( (string) $community['boilerplate'] ),
		);
	}

	/**
	 * Filter the communities listed for a practice area (order, exclusions).
	 *
	 * @param array  $communities
	 * @param string $area
	 */
	return (array) apply_filters( 'assemble/area_communities', $communities, $area );
}

/** The first sentence of a paragraph of text. */
function assemble_first_sentence( string $text ): string {
	$text = trim( (string) preg_replace( '/\s+/', ' ', $text ) );

	return preg_match( '/^.+?[.!?](?=\s|$)/u', $text, $m ) ? $m[0] : $text;
}

/**
 * One Field Report as a card.
 *
 * @return array{id:int,title:string,url:string,excerpt:string,date:string,area:string,topic:?array{name:string,slug:string},image:?array}
 */
function assemble_story( WP_Post $post ): array {
	$entry  = AFR_Renderer::data( $post->ID );
	$topics = AFR_Query::topics( $post->ID );
	$image  = AFR_Contentful::field( $entry, 'featuredImage' );

	return array(
		'id'      => (int) $post->ID,
		'title'   => get_the_title( $post ),
		'url'     => (string) get_permalink( $post ),
		'excerpt' => (string) get_the_excerpt( $post ),
		'date'    => (string) get_the_date( '', $post ),
		'area'    => assemble_area( class_exists( 'AFR_Communities' ) ? AFR_Communities::practice_area_for_post( $post->ID ) : '' ),
		'topic'   => $topics[0] ?? null,
		'image'   => is_array( $image ) ? $image : null,
	);
}

/**
 * Top stories: featured reports in their Contentful rank, then the newest.
 *
 * @return array<int,array> Cards from assemble_story().
 */
function assemble_top_stories( int $count = 4 ): array {
	return assemble_has_feed() ? array_map( 'assemble_story', AFR_Query::stream( $count ) ) : array();
}

/**
 * The newest reports in one community.
 *
 * @return array<int,array> Cards from assemble_story().
 */
function assemble_community_stories( string $community_slug, int $count = 3 ): array {
	return assemble_has_feed() ? array_map( 'assemble_story', AFR_Query::in_community( $community_slug, $count ) ) : array();
}

/** Where a community's full list of Insights lives. */
function assemble_community_url( string $community_slug ): string {
	if ( class_exists( 'AFR_CPT' ) ) {
		$link = get_term_link( $community_slug, AFR_CPT::TAXONOMY );
		if ( ! is_wp_error( $link ) ) {
			return $link;
		}
	}

	return assemble_insights_url();
}

/** The Insights index (the Field Reports archive). */
function assemble_insights_url(): string {
	$link = post_type_exists( 'field_report' ) ? get_post_type_archive_link( 'field_report' ) : false;

	return $link ? $link : home_url( '/field-reports/' );
}

/**
 * The active "Homepage hero" Site Feature from Contentful, or null. The homepage
 * shows it as the "Latest benchmark" card.
 *
 * @return array<string,mixed>|null
 */
function assemble_homepage_feature(): ?array {
	return class_exists( 'AFR_Features' ) ? AFR_Features::homepage_hero() : null;
}

/**
 * A Contentful image (Images API) with a srcset across the guide's IMG slots.
 * Contentful resizes and serves it from its CDN, so nothing is stored here
 * (BUILD-INSTRUCTIONS §6.2 change #4). '' when there is no image.
 *
 * @param array|null $asset Contentful asset payload from _afr_data.
 * @param string     $ratio "16:9" (L slots) or "1:1" (S slots).
 * @param string     $sizes The `sizes` attribute.
 */
function assemble_cf_image( ?array $asset, string $ratio = '16:9', string $sizes = '100vw', string $class = '', string $loading = 'lazy' ): string {
	$url = class_exists( 'AFR_Contentful' ) ? AFR_Contentful::asset_url( $asset ) : '';
	if ( '' === $url ) {
		return '';
	}

	$widths = '1:1' === $ratio ? array( 80, 192, 400, 600 ) : array( 320, 720, 1440, 2400 );
	$srcset = array();
	foreach ( $widths as $width ) {
		$height   = '1:1' === $ratio ? $width : (int) round( $width * 9 / 16 );
		$srcset[] = esc_url( add_query_arg( array( 'w' => $width, 'h' => $height, 'fit' => 'fill', 'fm' => 'webp', 'q' => 80 ), $url ) ) . ' ' . $width . 'w';
	}

	$alt = (string) ( $asset['fields']['description'] ?? $asset['fields']['title'] ?? '' );

	return sprintf(
		'<img src="%1$s" srcset="%2$s" sizes="%3$s" alt="%4$s" loading="%5$s" decoding="async"%6$s>',
		esc_url( add_query_arg( array( 'w' => $widths[1], 'fm' => 'webp', 'q' => 80 ), $url ) ),
		implode( ', ', $srcset ),
		esc_attr( $sizes ),
		esc_attr( $alt ),
		esc_attr( $loading ),
		$class ? ' class="' . esc_attr( $class ) . '"' : ''
	);
}
