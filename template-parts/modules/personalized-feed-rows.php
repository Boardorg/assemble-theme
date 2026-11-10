<?php
/**
 * Module: personalized-feed-rows (web-style-guide.html → Web modules;
 * wireframe "8. Index (logged in)"). Rows with a thumbnail, topic tag, title,
 * summary, and a content-type icon with the type and date.
 *
 * @var array $args { cards: array<int,array> from assemble_for_you_card(), heading: string }
 */

defined( 'ABSPATH' ) || exit;

$assemble_cards = array_values( (array) ( $args['cards'] ?? array() ) );
if ( ! $assemble_cards ) {
	return;
}
?>
<div class="feed-section-head"><h2><?php echo esc_html( (string) ( $args['heading'] ?? '' ) ); ?></h2></div>
<section class="feed-list">
	<?php foreach ( $assemble_cards as $assemble_card ) : ?>
		<article class="feed-row" data-area="<?php echo esc_attr( $assemble_card['area'] ); ?>">
			<a class="feed-thumb media-frame" href="<?php echo esc_url( $assemble_card['url'] ); ?>" tabindex="-1" aria-hidden="true">
				<?php echo assemble_card_image( $assemble_card['image'], '160px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image(). ?>
			</a>
			<div class="feed-copy">
				<?php if ( $assemble_card['topic'] || ! empty( $assemble_card['reason'] ) ) : ?>
					<div class="tag-row">
						<?php if ( $assemble_card['topic'] ) : ?>
							<?php echo assemble_topic_tag( $assemble_card['topic'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in assemble_topic_tag(). ?>
						<?php endif; ?>
						<?php if ( ! empty( $assemble_card['reason'] ) ) : ?>
							<span class="reason"><?php echo esc_html( $assemble_card['reason'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<h3><a href="<?php echo esc_url( $assemble_card['url'] ); ?>"><?php echo esc_html( $assemble_card['title'] ); ?></a></h3>
				<?php if ( $assemble_card['excerpt'] ) : ?>
					<p class="feed-excerpt"><?php echo esc_html( wp_trim_words( $assemble_card['excerpt'], 32 ) ); ?></p>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/modules/feed-card-meta', null, array( 'card' => $assemble_card ) ); ?>
			</div>
		</article>
	<?php endforeach; ?>
</section>
