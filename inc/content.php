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
 * `locked` is the plugin's answer to "would this visitor be refused?", for
 * the "Board Members only" label on cards. It never hides anything.
 *
 * @return array{id:int,title:string,url:string,excerpt:string,date:string,area:string,topic:?array{name:string,slug:string},image:?array,locked:bool}
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
		'locked'  => class_exists( 'AFR_Audience' ) && 'denied' === AFR_Audience::view_for( $post->ID ),
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

/*
 * ---------------------------------------------------------------------------
 * Field Report pages (Phase 4). The plugin decides what a visitor may see and
 * hands the theme a view model; the theme only formats it. Nothing below
 * decides access.
 */

/** Does the feed expose the view model (assemble-content 1.3.0 or later)? */
function assemble_has_report_view(): bool {
	return class_exists( 'AFR_Renderer' ) && method_exists( 'AFR_Renderer', 'view_model' );
}

/**
 * The view model for one report, for the current visitor: `view` plus only the
 * sections that view may show (see AFR_Renderer::view_model()). Null when the
 * feed is too old or the report has no data, and the template falls back to
 * the_content().
 *
 * @return array{view:string,post_id:int,sections:array,words:int,notices:array}|null
 */
function assemble_report_view( int $post_id ): ?array {
	if ( ! assemble_has_report_view() ) {
		return null;
	}

	$view = AFR_Renderer::view_model( $post_id );

	return '' === $view['view'] ? null : $view;
}

/*
 * The theme renders single reports itself, so the feed's the_content document
 * and its styles are switched off there. Listings and feeds still get the
 * feed's teaser.
 */
add_filter(
	'afr_filter_the_content',
	static function ( bool $filter ): bool {
		return is_singular( 'field_report' ) && assemble_has_report_view() ? false : $filter;
	}
);

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		if ( ( is_singular( 'field_report' ) && assemble_has_report_view() ) || is_post_type_archive( 'field_report' ) || is_tax( 'field_report_community' ) ) {
			wp_dequeue_style( 'afr-field-report' );
			wp_dequeue_style( 'afr-fonts' );
		}
	},
	20
);

/** Reading time in minutes at about 230 words a minute; 0 when there is nothing to read. */
function assemble_read_minutes( int $words ): int {
	return $words > 0 ? (int) max( 1, ceil( $words / 230 ) ) : 0;
}

/**
 * Where a practice area's Insights live. There is no per-area index yet, so
 * this is the Insights index (placeholder; see the theme handoff).
 */
function assemble_area_url( string $area ): string {
	/**
	 * Filter a practice area's Insights URL.
	 *
	 * @param string $url
	 * @param string $area
	 */
	return (string) apply_filters( 'assemble/area_url', assemble_insights_url(), $area );
}

/** A Community's short label, e.g. "AEO" for the AEO Board. */
function assemble_community_label( array $community ): string {
	$short = trim( (string) ( $community['short_name'] ?? '' ) );

	return '' !== $short ? $short : (string) preg_replace( '/\s+Board$/', '', (string) ( $community['name'] ?? '' ) );
}

/** "AEO Board" reads as "the AEO Board" in a sentence; brand names like "SocialMedia.org" take no article. */
function assemble_community_in_sentence( string $name ): string {
	return preg_match( '/\b(Board|Council|Network|Community)$/', $name ) ? sprintf( /* translators: %s: Community name. */ __( 'the %s', 'assemble' ), $name ) : $name;
}

/**
 * Breadcrumbs for a report: Insights › practice area › Community.
 *
 * @param array|null $community The view model's `kicker` section.
 * @return array<int,array{label:string,url:string}>
 */
function assemble_report_crumbs( ?array $community ): array {
	$crumbs = array(
		array(
			'label' => assemble_label( 'insights' ),
			'url'   => assemble_insights_url(),
		),
	);

	if ( $community ) {
		$area  = assemble_area( (string) $community['practice_area'] );
		$areas = assemble_practice_areas();

		if ( isset( $areas[ $area ] ) ) {
			$crumbs[] = array(
				'label' => $areas[ $area ],
				'url'   => assemble_area_url( $area ),
			);
		}

		if ( '' !== $community['slug'] ) {
			$crumbs[] = array(
				'label' => assemble_community_label( $community ),
				'url'   => assemble_community_url( (string) $community['slug'] ),
			);
		}
	}

	return $crumbs;
}

/**
 * Up to $count other reports from the same Community, newest first, topped up
 * from the stream. Cards only: listing a gated report is deliberate.
 *
 * @return array<int,array> Cards from assemble_story().
 */
function assemble_related_stories( int $post_id, string $community_slug, int $count = 3 ): array {
	if ( ! assemble_has_feed() ) {
		return array();
	}

	$posts = '' !== $community_slug ? AFR_Query::in_community( $community_slug, $count + 1 ) : array();
	$posts = array_filter( $posts, static fn( $post ) => (int) $post->ID !== $post_id );

	if ( count( $posts ) < $count ) {
		$exclude = array_merge( array( $post_id ), wp_list_pluck( $posts, 'ID' ) );
		$posts   = array_merge( $posts, AFR_Query::rest_of_stream( $exclude, $count - count( $posts ) ) );
	}

	return array_map( 'assemble_story', array_slice( array_values( $posts ), 0, $count ) );
}

/** Twelve cards a page on the Insights index and Community archives (a 3-up grid). */
add_action(
	'pre_get_posts',
	static function ( WP_Query $query ): void {
		if ( ! is_admin() && $query->is_main_query() && ( $query->is_post_type_archive( 'field_report' ) || $query->is_tax( 'field_report_community' ) ) ) {
			$query->set( 'posts_per_page', 12 );
		}
	}
);
