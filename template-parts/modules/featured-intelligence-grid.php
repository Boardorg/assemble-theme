<?php
/**
 * Module: featured-intelligence-grid (web-style-guide.html → Web modules;
 * wireframe "8. Index (logged in)"). One lead story beside two stacked side
 * stories, each saying why it's in the feed.
 *
 * @var array $args { cards: array<int,array> from assemble_for_you_card(), 1–3 }
 */

defined( 'ABSPATH' ) || exit;

$assemble_cards = array_values( (array) ( $args['cards'] ?? array() ) );
if ( ! $assemble_cards ) {
	return;
}
$assemble_lead = array_shift( $assemble_cards );
?>
<section class="featured-grid<?php echo $assemble_cards ? '' : ' featured-grid--single'; ?>" aria-label="<?php esc_attr_e( 'Latest for you', 'assemble' ); ?>">
	<article class="fi-card fi-lead" data-area="<?php echo esc_attr( $assemble_lead['area'] ); ?>">
		<a class="fi-image media-frame" href="<?php echo esc_url( $assemble_lead['url'] ); ?>" tabindex="-1" aria-hidden="true">
			<?php echo assemble_card_image( $assemble_lead['image'], '(max-width: 980px) 100vw, 740px', 'eager' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image(). ?>
		</a>
		<div class="tag-row">
			<?php if ( $assemble_lead['topic'] ) : ?>
				<?php echo assemble_topic_tag( $assemble_lead['topic'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in assemble_topic_tag(). ?>
			<?php endif; ?>
			<?php if ( $assemble_lead['reason'] ) : ?>
				<span class="reason"><?php echo esc_html( $assemble_lead['reason'] ); ?></span>
			<?php endif; ?>
		</div>
		<h2><a href="<?php echo esc_url( $assemble_lead['url'] ); ?>"><?php echo esc_html( $assemble_lead['title'] ); ?></a></h2>
		<?php if ( $assemble_lead['excerpt'] ) : ?>
			<p class="fi-excerpt"><?php echo esc_html( wp_trim_words( $assemble_lead['excerpt'], 40 ) ); ?></p>
		<?php endif; ?>
		<?php get_template_part( 'template-parts/modules/feed-card-meta', null, array( 'card' => $assemble_lead ) ); ?>
	</article>

	<?php if ( $assemble_cards ) : ?>
		<div class="side-stack">
			<?php foreach ( $assemble_cards as $assemble_card ) : ?>
				<article class="fi-card side-story" data-area="<?php echo esc_attr( $assemble_card['area'] ); ?>">
					<a class="fi-thumb media-frame" href="<?php echo esc_url( $assemble_card['url'] ); ?>" tabindex="-1" aria-hidden="true">
						<?php echo assemble_card_image( $assemble_card['image'], '160px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image(). ?>
					</a>
					<div class="fi-copy">
						<div class="tag-row">
							<?php if ( $assemble_card['topic'] ) : ?>
								<?php echo assemble_topic_tag( $assemble_card['topic'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in assemble_topic_tag(). ?>
							<?php endif; ?>
							<?php if ( $assemble_card['reason'] ) : ?>
								<span class="reason"><?php echo esc_html( $assemble_card['reason'] ); ?></span>
							<?php endif; ?>
						</div>
						<h3><a href="<?php echo esc_url( $assemble_card['url'] ); ?>"><?php echo esc_html( $assemble_card['title'] ); ?></a></h3>
						<?php if ( $assemble_card['excerpt'] ) : ?>
							<p class="fi-excerpt"><?php echo esc_html( wp_trim_words( $assemble_card['excerpt'], 16 ) ); ?></p>
						<?php endif; ?>
						<?php get_template_part( 'template-parts/modules/feed-card-meta', null, array( 'card' => $assemble_card ) ); ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
