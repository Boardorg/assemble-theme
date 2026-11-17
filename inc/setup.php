<?php
/**
 * Theme supports, menus and image sizes.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function (): void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_editor_style( array( 'assets/css/tokens.css', 'assets/css/base.css', 'assets/css/modules/page-content.css', 'assets/css/modules/page-bands.css' ) );

		// The footer reads Site Settings → Footer columns, so the header holds the only menus.
		register_nav_menus(
			array(
				'primary' => __( 'Primary (masthead row)', 'assemble' ),
				'utility' => __( 'Utility (bar above the masthead)', 'assemble' ),
			)
		);

		// The guide's IMG slots (BUILD-INSTRUCTIONS §5.3). Hard-cropped so cards line up.
		add_image_size( 'assemble-l1', 2400, 1350, true ); // Hero source; displayed at most 560px tall / 65vh.
		add_image_size( 'assemble-l2', 1440, 810, true );
		add_image_size( 'assemble-l3', 720, 405, true );
		add_image_size( 'assemble-l4', 320, 180, true );
		add_image_size( 'assemble-s1', 80, 80, true );
		add_image_size( 'assemble-s2', 192, 192, true );
		add_image_size( 'assemble-s3', 400, 400, true );
		add_image_size( 'assemble-s4', 600, 600, true );
	}
);

/**
 * Masthead navigation when no menu is assigned to "primary" (`wp assemble setup`
 * creates and assigns the "Masthead" menu). Labels come from assemble_label().
 *
 * @return array<int,array{label:string,url:string}>
 */
function assemble_primary_nav_fallback(): array {
	return array(
		array(
			'label' => assemble_label( 'insights' ),
			'url'   => assemble_insights_url(),
		),
		array(
			'label' => assemble_label( 'communities' ),
			'url'   => assemble_url( (string) assemble_setting( 'communities_url', '/communities/' ) ),
		),
		array(
			'label' => assemble_label( 'summits' ),
			'url'   => assemble_summits_url(),
		),
	);
}

/**
 * Utility bar navigation when no menu is assigned to "utility" (`wp assemble
 * setup` creates and assigns the "Utility" menu): the company pages.
 *
 * @return array<int,array{label:string,url:string}>
 */
function assemble_utility_nav_fallback(): array {
	return array(
		array(
			'label' => __( 'About', 'assemble' ),
			'url'   => home_url( '/about-us/' ),
		),
		array(
			'label' => __( 'Our product', 'assemble' ),
			'url'   => home_url( '/our-product/' ),
		),
		array(
			'label' => __( 'Our team', 'assemble' ),
			'url'   => home_url( '/our-team/' ),
		),
		array(
			'label' => __( 'Careers', 'assemble' ),
			'url'   => home_url( '/careers/' ),
		),
		array(
			'label' => __( 'Contact', 'assemble' ),
			'url'   => home_url( '/contact-us/' ),
		),
	);
}

/**
 * Top-level items of the menu assigned to a location, else the fallback, with
 * the first item matching this URL marked current.
 *
 * @param string                                   $location Theme location.
 * @param array<int,array{label:string,url:string}> $fallback Used when the location has no menu or it's empty.
 * @return array<int,array{label:string,url:string,current:bool,section:bool}>
 */
function assemble_nav_items( string $location, array $fallback ): array {
	$items     = array();
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations[ $location ] ) ) {
		foreach ( (array) wp_get_nav_menu_items( $locations[ $location ] ) as $item ) {
			if ( (int) $item->menu_item_parent === 0 ) {
				$items[] = array(
					'label' => (string) $item->title,
					'url'   => (string) $item->url,
				);
			}
		}
	}

	if ( ! $items ) {
		$items = $fallback;
	}

	// Mark the first item that matches this URL; placeholders may share a URL.
	$here  = untrailingslashit( (string) strtok( home_url( add_query_arg( array() ) ), '?' ) );
	$found = false;
	foreach ( $items as $i => $item ) {
		$items[ $i ]['current'] = ! $found && untrailingslashit( $item['url'] ) === $here;
		$items[ $i ]['section'] = false;
		$found                  = $found || $items[ $i ]['current'];
	}

	// Anywhere inside a section (Kristen's v2 edits, Cale 2026-11-12): the item whose URL is the longest prefix of this one.
	if ( ! $found ) {
		$best = -1;
		$len  = 0;
		foreach ( $items as $i => $item ) {
			$path = untrailingslashit( (string) wp_parse_url( $item['url'], PHP_URL_PATH ) );
			if ( '' !== $path && assemble_nav_in_section( $item['url'], $here ) && strlen( $path ) > $len ) {
				$best = $i;
				$len  = strlen( $path );
			}
		}
		if ( $best >= 0 ) {
			$items[ $best ]['section'] = true;
		}
	}

	return $items;
}

/**
 * Is this URL inside the section a nav item leads to? A path under the item's
 * own (/communities/aeo/ under /communities/), and for Insights also the
 * playbooks (/playbooks/), which are peer intelligence too.
 *
 * @param string $item_url The nav item's URL.
 * @param string $here     This page's URL, untrailingslashed, no query.
 */
function assemble_nav_in_section( string $item_url, string $here ): bool {
	$item = untrailingslashit( strtok( $item_url, '?' ) );
	if ( '' === $item || untrailingslashit( home_url( '/' ) ) === $item ) {
		return false;
	}

	$in = 0 === strpos( $here . '/', $item . '/' );
	if ( ! $in && untrailingslashit( assemble_insights_url() ) === $item ) {
		$in = is_singular( 'playbook' ) || is_post_type_archive( 'playbook' );
	}

	/**
	 * Filter whether this page counts as inside a nav item's section.
	 *
	 * @param bool   $in
	 * @param string $item_url
	 */
	return (bool) apply_filters( 'assemble/nav_in_section', $in, $item_url );
}

/**
 * The aria-current attribute for a nav item: "page" on its own URL, "true"
 * anywhere else in its section, else nothing. Already escaped.
 *
 * @param array $item From assemble_nav_items().
 */
function assemble_nav_current_attr( array $item ): string {
	if ( ! empty( $item['current'] ) ) {
		return ' aria-current="page"';
	}

	return ! empty( $item['section'] ) ? ' aria-current="true"' : '';
}

/**
 * Masthead navigation items: the menu assigned to "primary", else the fallback.
 *
 * @return array<int,array{label:string,url:string,current:bool}>
 */
function assemble_primary_nav_items(): array {
	return assemble_nav_items( 'primary', assemble_primary_nav_fallback() );
}

/**
 * Utility bar items: the menu assigned to "utility", else the fallback.
 *
 * @return array<int,array{label:string,url:string,current:bool}>
 */
function assemble_utility_nav_items(): array {
	return assemble_nav_items( 'utility', assemble_utility_nav_fallback() );
}

/**
 * The masthead's one-row nav (David's homepage review, Cale 2026-11-17): the
 * primary items, each of Insights, Communities and Summits with a panel
 * grouped by practice area, and the utility (company) pages folded into an
 * "About" item at the end.
 *
 * A panel is { groups, all } (practice groups, and an "All …" link) or
 * { links } (a plain list).
 *
 * @return array<int,array{label:string,url:string,current:bool,section:bool,panel:array}>
 */
function assemble_masthead_nav(): array {
	$items  = assemble_primary_nav_items();
	$panels = array(
		untrailingslashit( assemble_insights_url() )      => 'assemble_insights_nav_panel',
		untrailingslashit( home_url( '/communities/' ) )  => 'assemble_communities_nav_panel',
		untrailingslashit( assemble_summits_url() )       => 'assemble_summits_nav_panel',
	);

	foreach ( $items as $i => $item ) {
		$key                  = untrailingslashit( (string) $item['url'] );
		$items[ $i ]['panel'] = isset( $panels[ $key ] ) ? call_user_func( $panels[ $key ] ) : array();
	}

	$utility = assemble_utility_nav_items();
	if ( $utility ) {
		$current = (bool) array_filter( $utility, static fn( $u ) => ! empty( $u['current'] ) || ! empty( $u['section'] ) );
		$items[] = array(
			'label'   => __( 'About', 'assemble' ),
			'url'     => (string) $utility[0]['url'],
			'current' => false,
			'section' => $current,
			'panel'   => array( 'links' => $utility ),
		);
	}

	return $items;
}

/**
 * Practice areas with Communities, in selector order, each with its Communities.
 *
 * @return array<string,array{label:string,blurb:string,communities:array}>
 */
function assemble_nav_practices(): array {
	$areas = array();
	foreach ( assemble_interest_areas() as $area ) {
		if ( ! empty( $area['communities'] ) ) {
			$areas[ $area['key'] ] = $area;
		}
	}

	return $areas;
}

/** Insights: each practice area's /insights/area/… page, with its blurb (Cale: categories only, no types for now). */
function assemble_insights_nav_panel(): array {
	$groups = array();
	foreach ( assemble_nav_practices() as $key => $area ) {
		$groups[] = array(
			'area'  => $key,
			'label' => $area['label'],
			'url'   => assemble_area_url( $key ),
			'blurb' => $area['blurb'],
			'links' => array(),
		);
	}

	return array(
		'groups' => $groups,
		'all'    => array( __( 'All insights', 'assemble' ), assemble_insights_url() ),
	);
}

/** Communities: each practice area's Communities, to their sales pages (else the index's finder). */
function assemble_communities_nav_panel(): array {
	$pages = array();
	if ( function_exists( 'assemble_community_directory' ) ) {
		foreach ( assemble_community_directory() as $communities ) {
			foreach ( $communities as $community ) {
				$pages[ $community['slug'] ] = (string) ( $community['page']['url'] ?? '' );
			}
		}
	}

	$groups = array();
	foreach ( assemble_nav_practices() as $key => $area ) {
		$links = array();
		foreach ( $area['communities'] as $community ) {
			$url     = '' !== ( $pages[ $community['slug'] ] ?? '' ) ? $pages[ $community['slug'] ] : home_url( '/communities/#' . $community['slug'] );
			$links[] = array( assemble_community_pill_label( $community ), $url );
		}
		$groups[] = array(
			'area'  => $key,
			'label' => $area['label'],
			'url'   => '',
			'blurb' => '',
			'links' => $links,
		);
	}

	return array(
		'groups' => $groups,
		'all'    => array( __( 'All Communities', 'assemble' ), home_url( '/communities/' ) ),
	);
}

/** Summits: each practice area, to its first Community's panel on /summits/ (#slug), with its upcoming count. */
function assemble_summits_nav_panel(): array {
	$upcoming = assemble_upcoming_summits();
	$groups   = array();
	foreach ( assemble_nav_practices() as $key => $area ) {
		$count    = count( array_filter( $upcoming, static fn( $summit ) => $summit['area'] === $key ) );
		$groups[] = array(
			'area'  => $key,
			'label' => $area['label'],
			'url'   => assemble_summits_url() . '#' . $area['communities'][0]['slug'],
			/* translators: %s: number of upcoming Summits. */
			'blurb' => $count ? sprintf( _n( '%s upcoming Summit', '%s upcoming Summits', $count, 'assemble' ), number_format_i18n( $count ) ) : '',
			'links' => array(),
		);
	}

	return array(
		'groups' => $groups,
		'all'    => array( __( 'All Summits', 'assemble' ), assemble_summits_url() ),
	);
}
