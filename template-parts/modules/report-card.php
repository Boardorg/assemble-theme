<?php
/**
 * One Field Report card: thumbnail, topic tag, title, excerpt, and an optional
 * "See more" link. Used by related-peer-intelligence and the Insights archives.
 * Gated reports are listed on purpose (sign-ups) and carry the "Board Members
 * only" tag when the feed says this visitor would be refused.
 *
 * @var array $args { story: from assemble_story(), more: { url, label }|null, heading: 'h2'|'h3' }
 */

defined( 'ABSPATH' ) || exit;

$assemble_story   = (array) ( $args['story'] ?? array() );
$assemble_more    = $args['more'] ?? null;
$assemble_heading = 'h2' === ( $args['heading'] ?? 'h3' ) ? 'h2' : 'h3';

if ( ! $assemble_story ) {
	return;
}
?>
<article class="related-card" data-area="<?php echo esc_attr( $assemble_story['area'] ); ?>">
	<a class="thumb media-frame" href="<?php echo esc_url( $assemble_story['url'] ); ?>" tabindex="-1" aria-hidden="true">
		<?php echo assemble_cf_image( $assemble_story['image'], '16:9', '(max-width: 980px) 100vw, 360px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image(). ?>
	</a>
	<?php if ( $assemble_story['topic'] || ! empty( $assemble_story['locked'] ) ) : ?>
		<div class="card-tags">
			<?php if ( $assemble_story['topic'] ) : ?>
				<span class="tag tag-topic kicker" data-topic-slug="<?php echo esc_attr( $assemble_story['topic']['slug'] ); ?>"><?php echo esc_html( $assemble_story['topic']['name'] ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $assemble_story['locked'] ) ) : ?>
				<span class="tag tag-access"><?php echo assemble_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><?php echo esc_html( assemble_label( 'members_only' ) ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<<?php echo esc_attr( $assemble_heading ); ?> class="card-title"><a href="<?php echo esc_url( $assemble_story['url'] ); ?>"><?php echo esc_html( $assemble_story['title'] ); ?></a></<?php echo esc_attr( $assemble_heading ); ?>>
	<?php if ( $assemble_story['excerpt'] ) : ?>
		<p><?php echo esc_html( wp_trim_words( $assemble_story['excerpt'], 18 ) ); ?></p>
	<?php endif; ?>
	<?php if ( $assemble_more ) : ?>
		<a class="link-arrow related-more" href="<?php echo esc_url( $assemble_more['url'] ); ?>"><?php echo esc_html( $assemble_more['label'] ); ?></a>
	<?php else : ?>
		<p class="meta card-date"><?php echo esc_html( $assemble_story['date'] ); ?></p>
	<?php endif; ?>
</article>
