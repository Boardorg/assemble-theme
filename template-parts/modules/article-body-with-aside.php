<?php
/**
 * Module: article-body-with-aside (wireframe "4. Article"), for the member views.
 *
 * The body maps the Field Report's sections onto the article layout: the
 * Story in Brief as attributed lead quotes, Picking Up the Story and Focus of
 * the Discussion as h2 sections (label as eyebrow, authored subhead as the
 * heading), the pullquote as the blockquote, charts as captioned figures, Key
 * Takeaways as a numbered list, and the Community boilerplate last. The council
 * view leads with the takeaways at a glance and puts the full report behind an
 * expander (no wireframe: on-system). The aside carries the source line, a link
 * to the Community's Insights and the view's engagement actions.
 *
 * Only sections present in the view model are printed.
 *
 * @var array $args { report: view model, area: data-area key }
 */

defined( 'ABSPATH' ) || exit;

$assemble_report    = (array) ( $args['report'] ?? array() );
$assemble_s         = (array) ( $assemble_report['sections'] ?? array() );
$assemble_area      = (string) ( $args['area'] ?? 'neutral' );
$assemble_community = $assemble_s['kicker'] ?? null;
$assemble_council   = 'council' === ( $assemble_report['view'] ?? '' );

/** Curly quotes round a quote unless it already has them. */
$assemble_quoted = static fn( string $text ): string => preg_match( '/^["\x{201C}]/u', $text ) ? $text : '“' . $text . '”';

// The full report: in the council view it sits behind the expander.
ob_start();
foreach ( array( 'picking_up', 'pullquote', 'charts', 'focus', 'takeaways', 'community' ) as $assemble_key ) {
	if ( ! isset( $assemble_s[ $assemble_key ] ) ) {
		continue;
	}
	$assemble_section = $assemble_s[ $assemble_key ];

	switch ( $assemble_key ) {
		case 'picking_up':
		case 'focus':
			?>
			<section class="report-section">
				<?php if ( '' !== $assemble_section['subhead'] ) : ?>
					<p class="eyebrow report-label"><?php echo esc_html( $assemble_section['label'] ); ?></p>
					<h2><?php echo esc_html( $assemble_section['subhead'] ); ?></h2>
				<?php else : ?>
					<h2><?php echo esc_html( $assemble_section['label'] ); ?></h2>
				<?php endif; ?>
				<?php echo wp_kses_post( $assemble_section['html'] ); ?>
			</section>
			<?php
			break;

		case 'pullquote':
			?>
			<blockquote><?php echo esc_html( $assemble_quoted( $assemble_section ) ); ?></blockquote>
			<?php
			break;

		case 'charts':
			foreach ( $assemble_section as $assemble_chart ) :
				?>
				<figure class="report-chart">
					<img class="article-inline-image media-natural" src="<?php echo esc_url( add_query_arg( array( 'w' => 1440, 'fm' => 'webp', 'q' => 85 ), $assemble_chart['url'] ) ); ?>" alt="<?php echo esc_attr( $assemble_chart['alt'] ); ?>" loading="lazy" decoding="async">
					<?php if ( '' !== $assemble_chart['caption'] ) : ?>
						<figcaption class="image-caption"><?php echo esc_html( $assemble_chart['caption'] ); ?></figcaption>
					<?php endif; ?>
				</figure>
				<?php
			endforeach;
			break;

		case 'takeaways':
			?>
			<section class="report-section">
				<h2><?php echo esc_html( $assemble_section['label'] ); ?></h2>
				<?php echo wp_kses_post( str_replace( '<ol>', '<ol class="report-takeaways">', $assemble_section['html'] ) ); ?>
			</section>
			<?php
			break;

		case 'community':
			?>
			<section class="report-section report-about">
				<h3>
					<?php
					/* translators: %s: Community name. */
					printf( esc_html__( 'About %s and Assemble', 'assemble' ), esc_html( assemble_community_in_sentence( $assemble_section['name'] ) ) );
					?>
				</h3>
				<?php echo wp_kses_post( wpautop( esc_html( $assemble_section['boilerplate'] ) ) ); ?>
			</section>
			<?php
			break;
	}
}
$assemble_full = (string) ob_get_clean();
?>
<div class="article-layout" data-area="<?php echo esc_attr( $assemble_area ); ?>">
	<div class="article-body">
		<?php if ( isset( $assemble_s['note'] ) ) : ?>
			<p class="report-note"><?php echo esc_html( $assemble_s['note'] ); ?></p>
		<?php endif; ?>

		<?php if ( isset( $assemble_s['story_in_brief'] ) ) : ?>
			<section class="report-brief" aria-label="<?php esc_attr_e( 'The Story in Brief', 'assemble' ); ?>">
				<p class="eyebrow report-label"><?php esc_html_e( 'The Story in Brief', 'assemble' ); ?></p>
				<?php foreach ( $assemble_s['story_in_brief'] as $assemble_take ) : ?>
					<figure class="report-take">
						<blockquote class="lead"><?php echo esc_html( $assemble_quoted( $assemble_take['quote'] ) ); ?></blockquote>
						<?php if ( $assemble_take['speaker'] ) : ?>
							<?php $assemble_speaker = $assemble_take['speaker']; ?>
							<figcaption class="author-inline">
								<span class="author-avatar media-frame media-avatar">
									<?php echo assemble_cf_image( $assemble_speaker['image'], '1:1', '40px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image(). ?>
								</span>
								<span class="article-meta-copy">
									<strong><?php echo esc_html( $assemble_speaker['name'] ); ?></strong>
									<?php $assemble_title = implode( ', ', array_filter( array( $assemble_speaker['role'], $assemble_speaker['organization'] ) ) ); ?>
									<?php if ( '' !== $assemble_title ) : ?>
										<span><?php echo esc_html( $assemble_title ); ?></span>
									<?php endif; ?>
								</span>
							</figcaption>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</section>
		<?php endif; ?>

		<?php if ( isset( $assemble_s['glance'] ) ) : ?>
			<section class="report-section report-glance">
				<h2><?php esc_html_e( 'Key Takeaways at a Glance', 'assemble' ); ?></h2>
				<ol class="report-takeaways report-takeaways--short">
					<?php foreach ( $assemble_s['glance'] as $assemble_line ) : ?>
						<li><?php echo esc_html( $assemble_line ); ?></li>
					<?php endforeach; ?>
				</ol>
			</section>
		<?php endif; ?>

		<?php if ( $assemble_council && '' !== trim( $assemble_full ) ) : ?>
			<details class="report-expander">
				<summary><?php esc_html_e( 'Expand the full Field Report', 'assemble' ); ?></summary>
				<div class="report-expander-body">
					<?php echo $assemble_full; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
				</div>
			</details>
		<?php else : ?>
			<?php echo $assemble_full; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
		<?php endif; ?>
	</div>

	<aside class="article-aside">
		<?php get_template_part( 'template-parts/modules/report-aside', null, $args ); ?>
	</aside>
</div>
