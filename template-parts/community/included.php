<?php
/**
 * Community page band: What’s included. The shared pillars from Community
 * defaults (plus a Board’s own), numbered, as #membership.
 * Shared by the Board and index layouts.
 *
 * Every pillar's right column starts with the same label style (the tags intro,
 * "Topics include" / "Access", reads like the Working Sessions names), so the
 * rows match (Kristen, 2026-11-12). The index lists tags as check marks; Board
 * pages keep them as the practice-coloured tags.
 *
 * @var array $args { page: a Community page view model }
 */

defined( 'ABSPATH' ) || exit;

$assemble_page     = (array) $args['page'];
$assemble_included = (array) ( $assemble_page['included'] ?? array() );
$assemble_checks   = 'index' === ( $assemble_page['kind'] ?? '' );
$assemble_t        = static fn( string $text ): string => assemble_community_text( $text, $assemble_page );

if ( empty( $assemble_included['items'] ) ) {
	return;
}
?>
<div class="community-band" id="membership">
	<div class="wrap">
		<section aria-labelledby="community-included">
			<p class="eyebrow"><?php echo esc_html( (string) $assemble_included['eyebrow'] ); ?></p>
			<h2 class="community-heading" id="community-included"><?php echo esc_html( $assemble_t( (string) $assemble_included['heading'] ) ); ?></h2>
			<ol class="community-included">
				<?php foreach ( $assemble_included['items'] as $assemble_item ) : ?>
					<li>
						<div class="included-intro">
							<h3><?php echo esc_html( $assemble_t( (string) $assemble_item['title'] ) ); ?></h3>
							<p><?php echo esc_html( $assemble_t( (string) $assemble_item['body'] ) ); ?></p>
						</div>
						<?php if ( ! empty( $assemble_item['tags'] ) ) : ?>
							<div class="included-tags<?php echo $assemble_checks ? ' is-checks' : ''; ?>">
								<?php if ( ! empty( $assemble_item['tags_in'] ) ) : ?>
									<p class="included-label"><?php echo esc_html( $assemble_t( (string) $assemble_item['tags_in'] ) ); ?></p>
								<?php endif; ?>
								<?php if ( $assemble_checks ) : ?>
									<ul class="included-checks">
										<?php foreach ( $assemble_item['tags'] as $assemble_tag ) : ?>
											<li><?php echo esc_html( $assemble_t( (string) $assemble_tag ) ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php else : ?>
									<?php foreach ( $assemble_item['tags'] as $assemble_tag ) : ?>
										<span class="tag tag-area"><?php echo esc_html( $assemble_t( (string) $assemble_tag ) ); ?></span>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						<?php elseif ( ! empty( $assemble_item['details'] ) ) : ?>
							<dl class="included-details">
								<?php foreach ( $assemble_item['details'] as $assemble_detail ) : ?>
									<div>
										<dt><?php echo esc_html( $assemble_t( (string) $assemble_detail[0] ) ); ?></dt>
										<dd><?php echo esc_html( $assemble_t( (string) $assemble_detail[1] ) ); ?></dd>
									</div>
								<?php endforeach; ?>
							</dl>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</section>
	</div>
</div>
