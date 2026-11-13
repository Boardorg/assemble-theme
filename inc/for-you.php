<?php
/**
 * The For You page (wireframe 8, "Index (logged in)"): a view model for
 * page-for-you.php. The rules (who follows what, which pieces match, members-only
 * pieces left out) live in assemble-core's inc/for-you.php; this only turns its
 * answer into labels, cards and links.
 */

defined( 'ABSPATH' ) || exit;

/** True on the For You page. */
function assemble_is_for_you_page(): bool {
	return function_exists( 'assemble_account_page_kind' ) && 'for_you' === assemble_account_page_kind();
}

/** The signed-in person's first name, else the first word of their display name, else ''. */
function assemble_for_you_first_name(): string {
	$user = wp_get_current_user();
	$name = trim( (string) $user->first_name );

	return '' !== $name ? $name : trim( (string) strtok( (string) $user->display_name, ' ' ) );
}

/** A chip's or reason's visible label: the topic name for a Community, the area name for an area. */
function assemble_for_you_label( array $chip ): string {
	if ( 'community' === ( $chip['type'] ?? '' ) ) {
		$community = class_exists( 'AC_Communities' ) ? AC_Communities::get( (string) $chip['key'] ) : null;

		return assemble_interest_topic_label(
			array(
				'slug' => (string) $chip['key'],
				'name' => (string) ( $community['name'] ?? $chip['label'] ?? '' ),
			)
		);
	}

	return assemble_practice_areas()[ (string) ( $chip['key'] ?? '' ) ] ?? '';
}

/** "Field Report" or "Playbook", with its icon name. */
function assemble_for_you_format( WP_Post $post ): array {
	return 'playbook' === $post->post_type
		? array(
			'label' => assemble_label( 'playbook' ),
			'icon'  => 'book',
		)
		: array(
			'label' => assemble_label( 'field_report' ),
			'icon'  => 'doc',
		);
}

/** One card: assemble_story() plus why it's here and its content type. */
function assemble_for_you_card( WP_Post $post, ?array $reason = null ): array {
	$card           = assemble_story( $post );
	$card['reason'] = $reason ? sprintf( /* translators: %s: a topic or practice area. */ __( 'Because you follow %s', 'assemble' ), assemble_for_you_label( $reason ) ) : '';
	$card['type']   = assemble_for_you_format( $post );

	return $card;
}

/** A list of names as a sentence: "A", "A and B", "A, B, and C" (Oxford comma, 2026-11-10). */
function assemble_for_you_list( array $names ): string {
	$names = array_values( array_filter( $names ) );
	if ( count( $names ) < 2 ) {
		return (string) ( $names[0] ?? '' );
	}
	$last = array_pop( $names );

	if ( count( $names ) > 1 ) {
		/* translators: 1: a comma-separated list of two or more items, 2: the last item. */
		return sprintf( __( '%1$s, and %2$s', 'assemble' ), implode( ', ', $names ), $last );
	}

	/* translators: 1: the first item, 2: the second item. */
	return sprintf( __( '%1$s and %2$s', 'assemble' ), $names[0], $last );
}

/**
 * Everything page-for-you.php prints.
 *
 * @return array{available:bool,name:string,notice:string,chips:array,current:string,interests:string,cards:array,total:int,page:int,pages:int,follows_any:bool,fallback:array,discover:array,summits:array,manage_url:string,base_url:string}
 */
function assemble_for_you_view(): array {
	$user_id = get_current_user_id();
	$name    = assemble_for_you_first_name();

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only display state.
	$for    = isset( $_GET['for'] ) ? sanitize_text_field( wp_unslash( (string) $_GET['for'] ) ) : '';
	$page   = isset( $_GET['pg'] ) ? max( 1, absint( $_GET['pg'] ) ) : 1;
	$notice = isset( $_GET['done'] ) && 'created' === sanitize_key( wp_unslash( (string) $_GET['done'] ) ) && function_exists( 'assemble_account_message' )
		? assemble_account_message( 'created' )
		: '';
	// phpcs:enable

	$view = array(
		'available'   => function_exists( 'assemble_for_you' ) && assemble_has_feed(),
		'name'        => $name,
		'notice'      => $notice,
		'chips'       => array(),
		'current'     => '',
		'interests'   => '',
		'cards'       => array(),
		'total'       => 0,
		'page'        => $page,
		'pages'       => 0,
		'follows_any' => false,
		'fallback'    => array(),
		'discover'    => array(),
		'summits'     => array(),
		'manage_url'  => assemble_account_link( 'account' ) . '#interests',
		'base_url'    => assemble_account_link( 'for_you' ),
	);

	if ( ! $view['available'] ) {
		return $view;
	}

	$feed = assemble_for_you(
		$user_id,
		array(
			'for'  => $for,
			'page' => $page,
		)
	);

	foreach ( $feed['chips'] as $key => $chip ) {
		$view['chips'][ $key ] = array(
			'label' => assemble_for_you_label( $chip ),
			'url'   => add_query_arg( 'for', $key, $view['base_url'] ),
			'area'  => assemble_area( (string) $chip['area'] ),
		);
	}
	$view['current']     = $feed['current'];
	$view['interests']   = assemble_for_you_list( wp_list_pluck( $view['chips'], 'label' ) );
	$view['total']       = $feed['total'];
	$view['pages']       = $feed['pages'];
	$view['follows_any'] = $feed['follows_any'];
	foreach ( $feed['items'] as $item ) {
		$view['cards'][] = assemble_for_you_card( $item['post'], $item['reason'] );
	}

	// Nothing to show for "All for you": the newest across Assemble instead (Public only, like every list).
	if ( ! $view['cards'] && '' === $view['current'] ) {
		$view['fallback'] = array_map(
			static function ( array $story ): array {
				$post = get_post( $story['id'] );

				return $post ? assemble_for_you_card( $post ) : $story;
			},
			assemble_top_stories( 6 )
		);
	}

	$view['summits'] = assemble_for_you_summits( $feed['chips'] );

	// Up to three practice areas they don't follow yet.
	$followed = array_unique( wp_list_pluck( $feed['chips'], 'area' ) );
	foreach ( assemble_practice_areas() as $area => $label ) {
		if ( count( $view['discover'] ) < 3 && ! in_array( $area, $followed, true ) ) {
			$view['discover'][] = array(
				'area'  => $area,
				/* translators: %s: practice area. */
				'label' => sprintf( __( 'Explore %s', 'assemble' ), $label ),
				'url'   => assemble_area_url( $area ),
			);
		}
	}

	return $view;
}

/** The URL of another page of the feed, keeping the chip. */
function assemble_for_you_page_url( array $view, int $page ): string {
	$url = $view['current'] ? add_query_arg( 'for', $view['current'], $view['base_url'] ) : $view['base_url'];

	return $page > 1 ? add_query_arg( 'pg', $page, $url ) : $url;
}

/**
 * Upcoming Summits for what the person follows (Kristen's v2 edits, Cale
 * 2026-11-12), soonest first: a summit in a followed practice area that is
 * open to the whole area (no Communities, or "*"), or mapped to a followed
 * Community. A followed area with no Communities (Finance) takes every summit
 * in it. Facts and links come from assemble_upcoming_summits() and
 * assemble_summit_url(), as everywhere else.
 *
 * @param array $chips The feed's chips: key => { type, key, area }.
 * @return array<int,array>
 */
function assemble_for_you_summits( array $chips, int $count = 3 ): array {
	$areas       = array();
	$communities = array();
	foreach ( $chips as $chip ) {
		$area = (string) ( $chip['area'] ?? '' );
		if ( 'community' === ( $chip['type'] ?? '' ) ) {
			$areas[ $area ] = $areas[ $area ] ?? false;
			$communities[]  = (string) $chip['key'];
		} else {
			$areas[ $area ] = true; // The whole area.
		}
	}

	if ( ! $areas ) {
		return array();
	}

	$matches = array_filter(
		assemble_upcoming_summits(),
		static function ( array $summit ) use ( $areas, $communities ): bool {
			$area = (string) $summit['area'];
			if ( ! isset( $areas[ $area ] ) ) {
				return false;
			}
			$mapped = (array) ( $summit['communities'] ?? array() );

			return $areas[ $area ] || ! $mapped || in_array( '*', $mapped, true ) || array_intersect( $communities, $mapped );
		}
	);

	return array_slice( array_values( $matches ), 0, $count );
}
