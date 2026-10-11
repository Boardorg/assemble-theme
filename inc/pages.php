<?php
/**
 * Standard pages (Phase 6): helpers for page.php, Our Team, search and 404, and
 * the block editor's limits, so editors stay on-system.
 */

defined( 'ABSPATH' ) || exit;

/** A page's URL by its path ('contact-us'), or '' when there's no such published page. */
function assemble_page_url( string $path ): string {
	$page = get_page_by_path( $path );

	return $page instanceof WP_Post && 'publish' === $page->post_status ? (string) get_permalink( $page ) : '';
}

/** The Our Team page. */
function assemble_team_url(): string {
	$url = assemble_page_url( 'our-team' );

	return '' !== $url ? $url : home_url( '/' );
}

/**
 * Everyone on the team page, via assemble-core; empty without it.
 *
 * @return array<int,array{id:int,name:string,position:string,bio:string,image_id:int,url:string}>
 */
function assemble_team(): array {
	return function_exists( 'assemble_team_members' ) ? assemble_team_members() : array();
}

/**
 * One person, via assemble-core; the post title alone without it.
 *
 * @return array{id:int,name:string,position:string,bio:string,image_id:int,url:string}
 */
function assemble_team_person( WP_Post $post ): array {
	if ( function_exists( 'assemble_team_member' ) ) {
		return assemble_team_member( $post );
	}

	return array(
		'id'       => (int) $post->ID,
		'name'     => get_the_title( $post ),
		'position' => '',
		'bio'      => '',
		'image_id' => (int) get_post_thumbnail_id( $post ),
		'url'      => (string) get_permalink( $post ),
	);
}

/*
 * Search: the main query lists pages and team members only. Field Reports are
 * searched separately by assemble_search_stories(), on their title and excerpt
 * only, because their stored post_content is the full rendered report: matching
 * on it would tell an anonymous visitor what a gated report says.
 */
add_action(
	'pre_get_posts',
	static function ( WP_Query $query ): void {
		if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
			$query->set( 'post_type', array( 'page', 'team' ) );
			$query->set( 'posts_per_page', 10 );
		}
	}
);

/*
 * The block editor. Pages are built from a small set of core blocks, styled in
 * assets/css/modules/page-content.css (front end and editor). Colour, font and
 * size controls are already off in theme.json; practice colours never come
 * from editors.
 */
add_filter(
	'allowed_block_types_all',
	static function ( $allowed, WP_Block_Editor_Context $context ) {
		if ( ! $context->post || 'page' !== $context->post->post_type ) {
			return $allowed;
		}

		return array(
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/list-item',
			'core/quote',
			'core/image',
			'core/buttons',
			'core/button',
			'core/columns',
			'core/column',
			'core/group',
			'core/separator',
			'core/table',
			'core/embed',
			'core/html',
			'core/shortcode',
			'core/block',
			'core/pattern',
		);
	},
	10,
	2
);

add_action(
	'init',
	static function (): void {
		// Core's own patterns are off-brand; offer only Assemble's.
		remove_theme_support( 'core-block-patterns' );

		register_block_style(
			'core/paragraph',
			array(
				'name'  => 'lead',
				'label' => __( 'Lead', 'assemble' ),
			)
		);

		register_block_pattern_category( 'assemble', array( 'label' => __( 'Assemble', 'assemble' ) ) );

		register_block_pattern(
			'assemble/contact-band',
			array(
				'title'      => __( 'Contact band', 'assemble' ),
				'categories' => array( 'assemble' ),
				'postTypes'  => array( 'page' ),
				'content'    => '<!-- wp:group {"className":"contact-band"} --><div class="wp-block-group contact-band"><!-- wp:heading --><h2 class="wp-block-heading">' . esc_html__( 'Contact us', 'assemble' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'A sentence on why to get in touch.', 'assemble' ) . '</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/contact-us/">' . esc_html__( 'Contact us', 'assemble' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			)
		);

		register_block_pattern(
			'assemble/link-columns',
			array(
				'title'       => __( 'Lists in columns', 'assemble' ),
				'description' => __( 'Short headed lists side by side, such as practice areas.', 'assemble' ),
				'categories'  => array( 'assemble' ),
				'postTypes'   => array( 'page' ),
				'content'     => '<!-- wp:columns {"className":"list-columns"} --><div class="wp-block-columns list-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html__( 'Heading', 'assemble' ) . '</h3><!-- /wp:heading --><!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>' . esc_html__( 'Item', 'assemble' ) . '</li><!-- /wp:list-item --></ul><!-- /wp:list --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html__( 'Heading', 'assemble' ) . '</h3><!-- /wp:heading --><!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>' . esc_html__( 'Item', 'assemble' ) . '</li><!-- /wp:list-item --></ul><!-- /wp:list --></div><!-- /wp:column --></div><!-- /wp:columns -->',
			)
		);
	}
);
