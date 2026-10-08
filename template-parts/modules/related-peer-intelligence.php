<?php
/**
 * Module: related-peer-intelligence (wireframe "4. Article"). Three cards from
 * the same Community, topped up from the stream. Each card has its own "See
 * more" link for its type (content-review edits); every card is an Insight
 * today, so each links to its Community's Insights. Benchmark and Working
 * Session cards wait on data sources.
 *
 * @var array $args { area: data-area key, community: view model `kicker`, stories: cards from assemble_story() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_stories = (array) ( $args['stories'] ?? array() );

if ( ! $assemble_stories ) {
	return;
}
?>
<section class="related" aria-labelledby="related-title">
	<h2 class="section-title" id="related-title"><?php esc_html_e( 'Related peer intelligence', 'assemble' ); ?></h2>
	<div class="related-grid">
		<?php foreach ( $assemble_stories as $assemble_story ) : ?>
			<?php
			$assemble_terms = get_the_terms( $assemble_story['id'], 'field_report_community' );
			$assemble_term  = is_array( $assemble_terms ) ? $assemble_terms[0] : null;
			$assemble_label = $assemble_term ? (string) preg_replace( '/\s+Board$/', '', $assemble_term->name ) : '';

			get_template_part(
				'template-parts/modules/report-card',
				null,
				array(
					'story' => $assemble_story,
					'more'  => array(
						'url'   => $assemble_term ? assemble_community_url( $assemble_term->slug ) : assemble_insights_url(),
						/* translators: 1: Community label, e.g. "AEO", 2: "Insights". */
						'label' => '' !== $assemble_label ? sprintf( __( 'See more %1$s %2$s', 'assemble' ), $assemble_label, assemble_label( 'insights' ) ) : sprintf( /* translators: %s: "Insights". */ __( 'See more %s', 'assemble' ), assemble_label( 'insights' ) ),
					),
				)
			);
			?>
		<?php endforeach; ?>
	</div>
</section>
