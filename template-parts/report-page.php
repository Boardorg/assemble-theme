<?php
/**
 * The body of a Field Report page, from a view model: shared by the live page
 * (single-field_report.php) and the draft preview (afr_render_preview).
 *
 * @var array $args { report: view model, post_id: int (0 in a draft preview), url: permalink or '' }
 */

defined( 'ABSPATH' ) || exit;

$assemble_report    = (array) ( $args['report'] ?? array() );
$assemble_post_id   = (int) ( $args['post_id'] ?? 0 );
$assemble_s         = (array) ( $assemble_report['sections'] ?? array() );
$assemble_community = $assemble_s['kicker'] ?? null;
$assemble_area      = assemble_area( (string) ( $assemble_community['practice_area'] ?? '' ) );
$assemble_args      = array(
	'report' => $assemble_report,
	'area'   => $assemble_area,
	'url'    => (string) ( $args['url'] ?? '' ),
);
$assemble_classes   = array( 'report', 'report--' . $assemble_report['view'] );
?>
<div class="article-shell">
	<div class="wrap">
		<?php
		// Admin "Preview as" / draft-preview bar and the beta bypass banner, from the plugin (empty for most visitors).
		$assemble_notices = (string) ( $assemble_report['notices']['preview'] ?? '' ) . (string) ( $assemble_report['notices']['bypass'] ?? '' );
		if ( '' !== $assemble_notices ) {
			echo '<div class="report-notices">' . $assemble_notices . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped by the plugin.
		}
		?>
		<article class="<?php echo esc_attr( implode( ' ', $assemble_post_id ? get_post_class( $assemble_classes, $assemble_post_id ) : $assemble_classes ) ); ?>">
			<?php
			get_template_part( 'template-parts/modules/article-hero', null, $assemble_args );

			$assemble_view = (string) $assemble_report['view'];

			if ( 'public' === $assemble_view && ! empty( $assemble_report['full_text'] ) ) {
				// A Public report in full (the feed's afr_public_full_text, from assemble-core).
				// Signed out, the free-read meter (assets/js/report-meter.js) may swap it for
				// the create-account prompt after a few reads; without JavaScript it stays open.
				$assemble_metered = ! is_user_logged_in() && $assemble_post_id;
				if ( $assemble_metered ) {
					$assemble_free = function_exists( 'assemble_free_reads' ) ? assemble_free_reads() : (int) assemble_setting( 'free_reads', 2 );
					printf( '<div class="report-meter" data-report-meter data-report-id="%d" data-free-reads="%d">', (int) $assemble_post_id, (int) $assemble_free );
				}
				get_template_part( 'template-parts/modules/article-body-with-aside', null, $assemble_args );
				get_template_part( 'template-parts/modules/article-author-bio', null, $assemble_args );
				if ( $assemble_metered ) {
					echo '</div><div class="report-meter-prompt" data-report-meter-prompt hidden>';
					get_template_part( 'template-parts/modules/report-gate', null, $assemble_args + array( 'metered' => true ) );
					echo '</div>';
				}
			} elseif ( 'public' === $assemble_view || 'denied' === $assemble_view ) {
				get_template_part( 'template-parts/modules/report-gate', null, $assemble_args );
			} else {
				get_template_part( 'template-parts/modules/article-body-with-aside', null, $assemble_args );
				get_template_part( 'template-parts/modules/article-author-bio', null, $assemble_args );
			}

			get_template_part(
				'template-parts/modules/related-peer-intelligence',
				null,
				array(
					'area'      => $assemble_area,
					'community' => $assemble_community,
					'stories'   => assemble_related_stories( $assemble_post_id, (string) ( $assemble_community['slug'] ?? '' ) ),
				)
			);

			if ( ! is_user_logged_in() ) {
				get_template_part( 'template-parts/modules/account-cta' );
			}
			?>
		</article>
	</div>
</div>
