<?php
/**
 * The body of a playbook page, from the feed's view model (single-playbook.php).
 *
 * Standard view: the hero, then the sticky table of contents beside the
 * chapters, the author's bio, the continuing cards and (signed out) the account
 * CTA. Public view: the first chapter, then the join card; a playbook ticked
 * Public comes in full and the free-read meter may swap it for the
 * create-account prompt, as on Field Reports. Denied view: the gate.
 *
 * @var array $args { playbook: view model, post_id: int, url: permalink }
 */

defined( 'ABSPATH' ) || exit;

$assemble_playbook  = (array) ( $args['playbook'] ?? array() );
$assemble_post_id   = (int) ( $args['post_id'] ?? 0 );
$assemble_s         = (array) ( $assemble_playbook['sections'] ?? array() );
$assemble_view      = (string) ( $assemble_playbook['view'] ?? '' );
$assemble_community = $assemble_s['kicker'] ?? null;
$assemble_area      = assemble_area( (string) ( $assemble_community['practice_area'] ?? '' ) );
$assemble_args      = array(
	'report' => $assemble_playbook,
	'area'   => $assemble_area,
	'url'    => (string) ( $args['url'] ?? '' ),
	'kind'   => 'playbook',
);
$assemble_classes   = array( 'playbook', 'playbook--' . $assemble_view );
$assemble_full      = isset( $assemble_s['chapters'] );
$assemble_metered   = $assemble_full && 'public' === $assemble_view && ! is_user_logged_in() && $assemble_post_id;
?>
<div class="playbook-shell">
	<div class="wrap">
		<?php
		// Admin "Preview as" bar and the beta bypass banner, from the plugin (empty for most visitors).
		$assemble_notices = (string) ( $assemble_playbook['notices']['preview'] ?? '' ) . (string) ( $assemble_playbook['notices']['bypass'] ?? '' );
		if ( '' !== $assemble_notices ) {
			echo '<div class="report-notices">' . $assemble_notices . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped by the plugin.
		}
		?>
		<article class="<?php echo esc_attr( implode( ' ', get_post_class( $assemble_classes, $assemble_post_id ) ) ); ?>">
			<?php get_template_part( 'template-parts/modules/playbook-hero', null, $assemble_args ); ?>

			<?php if ( 'denied' === $assemble_view ) : ?>
				<?php get_template_part( 'template-parts/modules/report-gate', null, $assemble_args ); ?>
			<?php else : ?>
				<div class="playbook-layout" data-area="<?php echo esc_attr( $assemble_area ); ?>">
					<?php get_template_part( 'template-parts/modules/playbook-toc', null, $assemble_args ); ?>

					<div class="playbook-body">
						<?php
						if ( $assemble_full ) {
							if ( $assemble_metered ) {
								$assemble_free = function_exists( 'assemble_free_reads' ) ? assemble_free_reads() : (int) assemble_setting( 'free_reads', 2 );
								printf( '<div class="report-meter" data-report-meter data-report-id="%d" data-free-reads="%d">', (int) $assemble_post_id, (int) $assemble_free );
							}

							foreach ( $assemble_s['chapters'] as $assemble_chapter ) {
								get_template_part( 'template-parts/modules/' . ( 'phase' === $assemble_chapter['kind'] ? 'playbook-phase-section' : 'playbook-intro-block' ), null, array( 'chapter' => $assemble_chapter ) );
							}

							if ( $assemble_metered ) {
								echo '</div><div class="report-meter-prompt" data-report-meter-prompt hidden>';
								get_template_part( 'template-parts/modules/report-gate', null, $assemble_args + array( 'metered' => true ) );
								echo '</div>';
							}
						} else {
							// Public view: the first chapter, then the join card.
							if ( isset( $assemble_s['intro'] ) ) {
								get_template_part( 'template-parts/modules/playbook-intro-block', null, array( 'chapter' => $assemble_s['intro'] ) );
							}
							get_template_part( 'template-parts/modules/report-gate', null, $assemble_args );
						}
						?>
					</div>
				</div>

				<?php get_template_part( 'template-parts/modules/article-author-bio', null, $assemble_args ); ?>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/modules/playbook-continuing-cards',
				null,
				array(
					'community' => $assemble_community,
					'area'      => $assemble_area,
				)
			);

			if ( ! is_user_logged_in() ) {
				get_template_part( 'template-parts/modules/account-cta' );
			}
			?>
		</article>
	</div>
</div>
