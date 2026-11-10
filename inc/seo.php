<?php
/**
 * Yoast SEO for Field Reports and playbooks: the share image from the entry's
 * Contentful featuredImage (Images API, 1200×630), the dek as the meta
 * description, and "Insights" / "Playbooks" titles for the indexes and Community
 * archives. Each hook does nothing without Yoast or the feed.
 */

defined( 'ABSPATH' ) || exit;

/** A Contentful image URL cropped to the 1200×630 share size, or ''. */
function assemble_share_image_url( ?array $asset ): string {
	$url = class_exists( 'AC_Contentful' ) ? AC_Contentful::asset_url( $asset ) : '';

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

// The report's or playbook's featured image as og:image (and so twitter:image).
add_action(
	'wpseo_add_opengraph_images',
	static function ( $images ): void {
		if ( ! is_singular( array( 'field_report', 'playbook' ) ) || ! assemble_has_feed() ) {
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
		if ( '' === (string) $description && is_singular( array( 'field_report', 'playbook' ) ) ) {
			return wp_trim_words( (string) get_the_excerpt( get_queried_object_id() ), 30 );
		}

		return $description;
	}
);

// "Insights" and "AEO Insights" instead of "Field Reports Archive" and "AEO Board Archives".
$assemble_archive_title = static function ( $title ) {
	if ( is_post_type_archive( 'field_report' ) ) {
		$filter = assemble_insights_filter();
		$prefix = $filter ? $filter['label'] . ' ' : '';

		return $prefix . assemble_label( 'insights' ) . ' - ' . get_bloginfo( 'name' );
	}

	if ( is_post_type_archive( 'playbook' ) ) {
		return assemble_label( 'playbooks' ) . ' - ' . get_bloginfo( 'name' );
	}

	if ( is_tax( 'field_report_community' ) ) {
		$term      = get_queried_object();
		$community = ( $term instanceof WP_Term && class_exists( 'AC_Communities' ) ) ? (array) AC_Communities::get( $term->slug ) : array();
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

// Draft previews are served from the home URL; title them with the draft's headline.
$assemble_preview_title = static function ( $title ) {
	if ( ! assemble_is_report_preview() ) {
		return $title;
	}

	$headline = (string) ( AC_Preview::current()['sections']['headline'] ?? '' );

	/* translators: %s: draft headline. */
	return sprintf( __( 'Draft preview: %s', 'assemble' ), $headline ) . ' - ' . get_bloginfo( 'name' );
};
add_filter( 'wpseo_title', $assemble_preview_title, 20 );
add_filter( 'pre_get_document_title', $assemble_preview_title, 20 );

/*
 * Article structured data for Field Reports and playbooks. Yoast's own Article
 * piece needs `author` support on the post type, and its author would be the
 * WordPress user that runs the Contentful sync. So Yoast's graph keeps its
 * WebPage, and this adds an Article (plus a Person for the Contentful author)
 * linked to that WebPage and to Yoast's Organization as publisher.
 */

/**
 * The Article (and author Person) nodes for one report or playbook.
 *
 * @param WP_Post $post      The report or playbook.
 * @param string  $page_id   The WebPage node's @id (Yoast: the canonical URL).
 * @param string  $org_id    The Organization node's @id, or '' when Yoast has none.
 * @return array<int,array<string,mixed>>
 */
function assemble_article_schema( WP_Post $post, string $page_id, string $org_id ): array {
	$entry = AC_Post_Type_Content::data( $post->ID );
	if ( ! $entry ) {
		return array();
	}

	$article_id = $page_id . '#article';
	$publisher  = '' !== $org_id
		? array( '@id' => $org_id )
		: array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
		);

	$article = array(
		'@type'            => 'Article',
		'@id'              => $article_id,
		'isPartOf'         => array( '@id' => $page_id ),
		'mainEntityOfPage' => array( '@id' => $page_id ),
		'headline'         => wp_strip_all_tags( html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ) ),
		'description'      => wp_strip_all_tags( html_entity_decode( (string) get_the_excerpt( $post ), ENT_QUOTES, 'UTF-8' ) ),
		'datePublished'    => (string) get_post_time( DATE_W3C, true, $post ),
		'dateModified'     => (string) get_post_modified_time( DATE_W3C, true, $post ),
		'publisher'        => $publisher,
		'inLanguage'       => get_bloginfo( 'language' ),
	);

	$image = assemble_share_image_url( assemble_story( $post )['image'] );
	if ( '' !== $image ) {
		$article['image'] = array(
			'@type'  => 'ImageObject',
			'url'    => $image,
			'width'  => 1200,
			'height' => 630,
		);
	}

	$nodes  = array();
	$person = AC_Field_Report_Renderer::person( AC_Contentful::field( $entry, 'author' ) );
	if ( $person ) {
		// No author pages yet, so the Person is identified by name alone.
		$node = array(
			'@type' => 'Person',
			'@id'   => home_url( '/' ) . '#/schema/person/ac-' . md5( $person['name'] ),
			'name'  => $person['name'],
		);
		if ( '' !== $person['role'] ) {
			$node['jobTitle'] = $person['role'];
		}
		if ( '' !== $person['image_url'] ) {
			$node['image'] = $person['image_url'];
		}
		$node['worksFor']  = $publisher;
		$nodes[]           = $node;
		$article['author'] = array( '@id' => $node['@id'] );
	} else {
		// A report with no author in Contentful: credit Assemble.
		$article['author'] = $publisher;
	}

	// Signed out, a Public report in full sits in the free-read meter, which can
	// swap it for the create-account prompt: mark it as paywalled content so the
	// text in the HTML isn't read as cloaking. A gated view never has the body in
	// the HTML, so it needs no markup.
	$model = 'playbook' === $post->post_type
		? ( method_exists( 'AC_Playbook_Renderer', 'view_model' ) ? AC_Playbook_Renderer::view_model( $post->ID ) : array() )
		: (array) assemble_report_view( $post->ID );
	if ( 'public' === ( $model['view'] ?? '' ) && ! empty( $model['full_text'] ) && ! is_user_logged_in() ) {
		$article['isAccessibleForFree'] = false;
		$article['hasPart']             = array(
			'@type'               => 'WebPageElement',
			'isAccessibleForFree' => false,
			'cssSelector'         => '.report-meter',
		);
	}

	array_unshift( $nodes, array_filter( $article, static fn( $value ) => '' !== $value ) );

	return $nodes;
}

add_filter(
	'wpseo_schema_graph',
	static function ( $graph ) {
		if ( ! is_array( $graph ) || ! is_singular( array( 'field_report', 'playbook' ) ) || ! assemble_has_feed() || ! class_exists( 'AC_Post_Type_Content' ) ) {
			return $graph;
		}

		$page_id = '';
		$org_id  = '';
		foreach ( $graph as $node ) {
			$types = (array) ( $node['@type'] ?? array() );
			if ( in_array( 'Article', $types, true ) ) {
				return $graph; // Yoast already outputs one (its own Article setting).
			}
			if ( '' === $page_id && array_intersect( $types, array( 'WebPage', 'ItemPage', 'CollectionPage' ) ) ) {
				$page_id = (string) ( $node['@id'] ?? '' );
			}
			if ( '' === $org_id && in_array( 'Organization', $types, true ) ) {
				$org_id = (string) ( $node['@id'] ?? '' );
			}
		}

		$post = get_post();
		if ( ! $post instanceof WP_Post ) {
			return $graph;
		}

		$nodes = assemble_article_schema( $post, '' !== $page_id ? $page_id : (string) get_permalink( $post ), $org_id );

		return $nodes ? array_merge( $graph, $nodes ) : $graph;
	}
);
