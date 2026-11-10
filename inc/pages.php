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

/*
 * Banded pages (About, Careers, Contact): full-width sections built from core
 * blocks with Assemble class names, styled in assets/css/modules/page-bands.css.
 * A page whose content has a top-level "page-band" group drops page.php's plain
 * content head and 720px column; its first band carries the H1.
 */

/** Does this page's content use full-width bands? */
function assemble_page_has_bands( ?WP_Post $post = null ): bool {
	$post = $post ?? get_post();
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type || ! has_blocks( $post ) ) {
		return false;
	}

	foreach ( parse_blocks( $post->post_content ) as $block ) {
		if ( 'core/group' === $block['blockName'] && preg_match( '/(^|\s)page-band(\s|$)/', (string) ( $block['attrs']['className'] ?? '' ) ) ) {
			return true;
		}
	}

	return false;
}

/*
 * Practice colours on page blocks. Editors pick a practice by adding the class
 * "area-<key>" (e.g. area-marketing) to a group or column; this turns it into the
 * data-area attribute, so the ramps stay in tokens.css and editors never set a colour.
 */
add_filter(
	'render_block',
	static function ( string $html, array $block ): string {
		if ( ! in_array( $block['blockName'], array( 'core/group', 'core/column' ), true ) ) {
			return $html;
		}
		if ( ! preg_match( '/(?:^|\s)area-([a-z-]+)/', (string) ( $block['attrs']['className'] ?? '' ), $m ) || 'neutral' === assemble_area( $m[1] ) ) {
			return $html;
		}

		$tags = new WP_HTML_Tag_Processor( $html );
		if ( $tags->next_tag() ) {
			$tags->set_attribute( 'data-area', $m[1] );
		}

		return $tags->get_updated_html();
	},
	10,
	2
);

/*
 * [assemble_team_strip count="6"]: the first people on Our Team (by their Order
 * field), headshot, name and title, for a page band. Links to Our Team.
 */
add_shortcode(
	'assemble_team_strip',
	static function ( $atts ): string {
		$atts   = shortcode_atts( array( 'count' => 6 ), (array) $atts );
		$people = array_slice( array_filter( assemble_team(), static fn ( array $p ): bool => (bool) $p['image_id'] ), 0, max( 1, (int) $atts['count'] ) );

		if ( ! $people ) {
			return '';
		}

		ob_start();
		?>
		<ul class="team-strip" role="list">
			<?php foreach ( $people as $person ) : ?>
				<li class="team-strip__person">
					<a href="<?php echo esc_url( '' !== $person['bio'] ? $person['url'] : assemble_team_url() ); ?>">
						<span class="team-strip__photo media-frame media-square">
							<?php
							echo wp_get_attachment_image(
								$person['image_id'],
								'assemble-s3',
								false,
								array(
									'sizes'   => '(max-width: 640px) 33vw, 180px',
									'alt'     => '',
									'loading' => 'lazy',
								)
							);
							?>
						</span>
						<span class="team-strip__name"><?php echo esc_html( $person['name'] ); ?></span>
					</a>
					<?php if ( '' !== $person['position'] ) : ?>
						<span class="team-strip__role"><?php echo esc_html( $person['position'] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		return (string) ob_get_clean();
	}
);

/*
 * [assemble_logo_strip type="summits|communities"]: a row of linked logos for a
 * page band (Our Product, Kristen's review 2026-11-09). Summits: each upcoming
 * Summit with a library logo, once per logo (Spring and Fall share one), linked
 * to its event page. Communities: each Board or Council in the Community
 * directory with a logo, linked to its page, else its panel on /communities/.
 */
add_shortcode(
	'assemble_logo_strip',
	static function ( $atts ): string {
		$atts  = shortcode_atts( array( 'type' => 'summits' ), (array) $atts );
		$items = array();

		if ( 'communities' === $atts['type'] ) {
			$directory = function_exists( 'assemble_community_directory' ) ? assemble_community_directory() : array();
			foreach ( $directory as $communities ) {
				foreach ( (array) $communities as $community ) {
					if ( empty( $community['logo']['url'] ) ) {
						continue;
					}
					$items[ $community['logo']['url'] ] = array(
						'logo'  => $community['logo']['url'],
						'label' => (string) $community['name'],
						'url'   => ! empty( $community['page']['url'] ) ? $community['page']['url'] : assemble_url( '/communities/#' . $community['slug'] ),
					);
				}
			}
		} else {
			foreach ( assemble_upcoming_summits() as $summit ) {
				$logo = (string) ( $summit['logo'] ?? '' );
				if ( '' === $logo || isset( $items[ $logo ] ) ) {
					continue;
				}
				$items[ $logo ] = array(
					'logo'  => $logo,
					'label' => (string) $summit['title'],
					'url'   => assemble_summit_url( $summit ),
				);
			}
		}

		if ( ! $items ) {
			return '';
		}

		ob_start();
		?>
		<ul class="logo-strip logo-strip--<?php echo esc_attr( 'communities' === $atts['type'] ? 'communities' : 'summits' ); ?>" role="list">
			<?php foreach ( $items as $item ) : ?>
				<li><a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo str_starts_with( $item['url'], home_url() ) ? '' : ' rel="noopener"'; ?>><img src="<?php echo esc_url( $item['logo'] ); ?>" alt="<?php echo esc_attr( $item['label'] ); ?>" loading="lazy" decoding="async"></a></li>
			<?php endforeach; ?>
		</ul>
		<?php
		return (string) ob_get_clean();
	}
);
