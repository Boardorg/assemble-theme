<?php
/**
 * Module: article-body (wireframe "4. Article"), for the member views.
 *
 * The body maps the Field Report's sections onto the article layout: the
 * Story in Brief as quotes with the speaker's headshot beside each one,
 * Picking Up the Story and Focus of the Discussion as h2 sections (label as
 * eyebrow, authored subhead as the heading), the pullquote as the blockquote,
 * charts as a "Poll results" set (stacked; three or more become a switcher,
 * assets/js/report-charts.js), Key Takeaways as a numbered list, and the
 * Community boilerplate last. The council view leads with the takeaways at a
 * glance and puts the full report behind an expander (no wireframe: on-system).
 *
 * No aside (Cale, 2026-11-10): the body is a centered 720px reading column
 * under the full-width hero, as in the signed-off image-style mockup
 * (IMAGE-STYLE-SPEC option B). The pull quote runs the full
 * article width, as wide as the hero image.
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
			<blockquote class="pull-quote"><p><?php echo esc_html( $assemble_quoted( $assemble_section ) ); ?></p></blockquote>
			<?php
			break;

		case 'charts':
			// The chart image carries its own title, so the caption is for screen
			// readers only. Three or more charts get a switcher; the server renders
			// them all stacked, which is also the no-script and print view.
			$assemble_charts = array_values( (array) $assemble_section );
			if ( ! $assemble_charts ) {
				break;
			}
			$assemble_switch = count( $assemble_charts ) >= 3;
			$assemble_chart_id = static fn( int $i ): string => 'report-chart-' . ( $i + 1 );
			?>
			<section class="report-charts"<?php echo $assemble_switch ? ' data-chart-switcher' : ''; ?>>
				<p class="eyebrow report-label"><?php esc_html_e( 'Poll results', 'assemble' ); ?></p>
				<?php if ( $assemble_switch ) : ?>
					<div class="chart-tabs" role="group" aria-label="<?php esc_attr_e( 'Choose a chart', 'assemble' ); ?>" hidden>
						<?php foreach ( $assemble_charts as $assemble_i => $assemble_chart ) : ?>
							<button class="chart-tab" type="button" aria-pressed="<?php echo 0 === $assemble_i ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $assemble_chart_id( $assemble_i ) ); ?>" data-chart-target="<?php echo esc_attr( $assemble_chart_id( $assemble_i ) ); ?>"><?php echo esc_html( '' !== $assemble_chart['caption'] ? $assemble_chart['caption'] : sprintf( /* translators: %d: chart number */ __( 'Chart %d', 'assemble' ), $assemble_i + 1 ) ); ?></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php foreach ( $assemble_charts as $assemble_i => $assemble_chart ) : ?>
					<figure class="report-chart" id="<?php echo esc_attr( $assemble_chart_id( $assemble_i ) ); ?>">
						<img class="media-natural" src="<?php echo esc_url( add_query_arg( array( 'w' => 1440, 'fm' => 'webp', 'q' => 85 ), $assemble_chart['url'] ) ); ?>" alt="<?php echo esc_attr( $assemble_chart['alt'] ); ?>" loading="lazy" decoding="async">
						<?php if ( '' !== $assemble_chart['caption'] ) : ?>
							<figcaption class="screen-reader-text"><?php echo esc_html( $assemble_chart['caption'] ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</section>
			<?php
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
					<?php $assemble_speaker = $assemble_take['speaker'] ?? null; ?>
					<figure class="report-take<?php echo $assemble_speaker ? ' report-take--speaker' : ''; ?>">
						<?php if ( $assemble_speaker ) : ?>
							<span class="report-take-face media-frame media-avatar" aria-hidden="true">
								<?php echo assemble_person_avatar( $assemble_speaker, '(max-width: 640px) 64px, 104px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_person_avatar(). ?>
							</span>
						<?php endif; ?>
						<blockquote class="lead"><?php echo esc_html( $assemble_quoted( $assemble_take['quote'] ) ); ?></blockquote>
						<?php if ( $assemble_speaker ) : ?>
							<figcaption class="report-take-who">
								<strong><?php echo esc_html( $assemble_speaker['name'] ); ?></strong>
								<?php
								// The role often already names the organization ("Membership Director, Learning & Development Board").
								$assemble_org   = (string) $assemble_speaker['organization'];
								$assemble_title = implode( ', ', array_filter( array( $assemble_speaker['role'], '' !== $assemble_org && false === stripos( (string) $assemble_speaker['role'], $assemble_org ) ? $assemble_org : '' ) ) );
								?>
								<?php if ( '' !== $assemble_title ) : ?>
									<span><?php echo esc_html( $assemble_title ); ?></span>
								<?php endif; ?>
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
</div>
