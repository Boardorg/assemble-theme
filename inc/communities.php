<?php
/**
 * Community sales pages: one page per Board at /communities/<board>/, from
 * marketing's AEO mockup in the site's design system.
 *
 * Pages on the "Community page" template (page-community.php) get their view
 * model from `assemble/community_page`, which assemble-core answers from the
 * page's SCF fields, its Community defaults and the feed's Community directory
 * (hub COMMUNITY-PAGES-PROPOSAL.md §5). Without a view model the template
 * falls back to page.php.
 */

defined( 'ABSPATH' ) || exit;

/**
 * The Community page view model for a page, or null.
 *
 * Shape: kind (board, council, area, index), name, field, a_field,
 * community_slug, area, logo, hero, members, problem, challenges, sessions,
 * impact, included, fit, company, closing; the index adds board, points and
 * finder. Copy may hold {board}, {field} and
 * {a_field}, filled in by assemble_community_text().
 */
function assemble_community_page( WP_Post $post ): ?array {
	/**
	 * Filter the Community page data for a page.
	 *
	 * @param array|null $page
	 * @param WP_Post    $post
	 */
	$page = apply_filters( 'assemble/community_page', null, $post );

	return is_array( $page ) && ! empty( $page['name'] ) ? $page : null;
}

/** The current request's Community page data, or null when this isn't a Community page. */
function assemble_current_community_page(): ?array {
	static $page = false;

	if ( false !== $page ) {
		return $page;
	}

	$page = null;
	$post = get_queried_object();

	if ( is_page() && $post instanceof WP_Post && is_page_template( 'page-community.php' ) ) {
		$page = assemble_community_page( $post );
	}

	return $page;
}

/**
 * The current Community page's layout (a template part in template-parts/community/):
 * "board" for Board and Council pages, "index" for /communities/, or "" when
 * there's no layout for it yet (practice-area pages) and page.php renders it.
 */
function assemble_community_layout(): string {
	$kind = (string) ( assemble_current_community_page()['kind'] ?? '' );

	return array( 'board' => 'board', 'council' => 'board', 'index' => 'index' )[ $kind ] ?? '';
}

/** True on a Board or Council sales page. */
function assemble_is_community_sales_page(): bool {
	return 'board' === assemble_community_layout();
}

/** True on the Communities index (/communities/). */
function assemble_is_community_index(): bool {
	return 'index' === assemble_community_layout();
}

/**
 * Fill a shared line with this Board's names: {board}, {field}, {a_field}. The
 * index names no Board, so its view model supplies its own `board`.
 */
function assemble_community_text( string $text, array $page ): string {
	return strtr(
		$text,
		array(
			'{board}'    => (string) ( $page['board'] ?? $page['name'] ),
			'{field}'    => (string) ( $page['field'] ?? $page['name'] ),
			'{a_field}'  => (string) ( $page['a_field'] ?? $page['name'] ),
		)
	);
}

/**
 * Upcoming Working Sessions, soonest first; past ones drop off on their own.
 *
 * @return array<int,array{date:string,title:string,body:string,time:int}>
 */
function assemble_community_sessions( array $page ): array {
	$today    = strtotime( wp_date( 'Y-m-d' ) );
	$sessions = array();

	foreach ( (array) ( $page['sessions'] ?? array() ) as $session ) {
		$time = strtotime( (string) ( $session['date'] ?? '' ) );
		if ( $time && $time >= $today ) {
			$sessions[] = $session + array( 'time' => $time );
		}
	}

	usort( $sessions, static fn( $a, $b ) => $a['time'] <=> $b['time'] );

	return $sessions;
}

/**
 * Breadcrumbs: the practice by its official name, then the Board. The practice
 * links once its page (/communities/<area>/) exists; until then it prints as text.
 *
 * @return array<int,array{label:string,url:string}>
 */
function assemble_community_crumbs( array $page ): array {
	$area   = assemble_area( (string) $page['area'] );
	$name   = assemble_practice_areas()[ $area ] ?? '';
	$crumbs = array();

	if ( '' !== $name ) {
		$crumbs[] = array(
			'label' => $name,
			'url'   => assemble_page_url( 'communities/' . $area ),
		);
	}

	$crumbs[] = array(
		'label' => (string) $page['name'],
		'url'   => '',
	);

	return $crumbs;
}
