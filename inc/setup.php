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
		add_editor_style( array( 'assets/css/tokens.css', 'assets/css/base.css' ) );

		register_nav_menus(
			array(
				'primary' => __( 'Primary (masthead row)', 'assemble' ),
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
 * Masthead navigation when no menu is assigned yet (`wp assemble setup` creates
 * the real one in Phase 6). Labels come from assemble_label().
 *
 * @return array<int,array{label:string,url:string}>
 */
function assemble_primary_nav_fallback(): array {
	return array(
		array(
			'label' => assemble_label( 'insights' ),
			'url'   => home_url( '/field-reports/' ),
		),
		array(
			'label' => assemble_label( 'communities' ),
			'url'   => home_url( '/field-reports/' ), // Placeholder until a communities index exists.
		),
		array(
			'label' => assemble_label( 'summits' ),
			'url'   => home_url( '/summits/' ),
		),
	);
}

/**
 * Masthead navigation items: the menu assigned to "primary", else the fallback.
 *
 * @return array<int,array{label:string,url:string,current:bool}>
 */
function assemble_primary_nav_items(): array {
	$items     = array();
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['primary'] ) ) {
		foreach ( (array) wp_get_nav_menu_items( $locations['primary'] ) as $item ) {
			if ( (int) $item->menu_item_parent === 0 ) {
				$items[] = array(
					'label' => (string) $item->title,
					'url'   => (string) $item->url,
				);
			}
		}
	}

	if ( ! $items ) {
		$items = assemble_primary_nav_fallback();
	}

	// Mark the first item that matches this URL; placeholders may share a URL.
	$here  = untrailingslashit( (string) strtok( home_url( add_query_arg( array() ) ), '?' ) );
	$found = false;
	foreach ( $items as $i => $item ) {
		$items[ $i ]['current'] = ! $found && untrailingslashit( $item['url'] ) === $here;
		$found                  = $found || $items[ $i ]['current'];
	}

	return $items;
}
