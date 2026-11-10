<?php
/**
 * The Benchmarks page (/benchmark/), three ways in (Kristen's review, Cale
 * 2026-11-06): read the published reports (by practice area), take the open
 * survey, or get your personal results. No wireframe: on-system and flagged
 * for design, built from the banded pages' vocabulary (page-bands.css).
 *
 * - Reports: assemble-core's assemble_benchmark_reports(), links to
 *   report.theassemble.com, grouped into practice cards.
 * - Survey: the page's own block content (intro and the Formstack embed), so
 *   the next round's form is swapped in wp-admin.
 * - Personal results: the `benchmark_url` Site Setting (report.theassemble.com).
 */

defined( 'ABSPATH' ) || exit;

get_header();

$assemble_editions = function_exists( 'assemble_benchmark_reports' ) ? assemble_benchmark_reports() : array();
$assemble_results  = assemble_url( (string) assemble_setting( 'benchmark_url', '' ) );
$assemble_areas    = assemble_practice_areas();

while ( have_posts() ) :
	the_post();
	?>
	<div class="page-bands benchmark-page" data-area="neutral">
		<section class="page-band page-band-hero" aria-labelledby="benchmark-title">
			<div class="wrap">
				<div class="page-hero is-copy-only">
					<div class="page-hero-copy">
						<div class="eyebrow"><?php esc_html_e( 'Benchmark', 'assemble' ); ?></div>
						<h1 class="type-hero" id="benchmark-title"><?php the_title(); ?></h1>
						<p><?php esc_html_e( 'See how senior leaders in your function are prioritizing, staffing, and spending. Read the latest reports, add your own answers to the current survey, or get your personal results.', 'assemble' ); ?></p>
						<div class="page-hero-actions">
							<a class="btn-solid" href="#take-the-survey"><?php esc_html_e( 'Take the survey', 'assemble' ); ?></a>
							<?php if ( $assemble_editions ) : ?>
								<a class="link-arrow" href="#benchmark-reports"><?php esc_html_e( 'Read the reports', 'assemble' ); ?></a>
							<?php endif; ?>
							<?php if ( $assemble_results ) : ?>
								<a class="link-arrow" href="<?php echo esc_url( $assemble_results ); ?>" rel="noopener"><?php esc_html_e( 'See your results', 'assemble' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php foreach ( $assemble_editions as $assemble_i => $assemble_edition ) : ?>
			<?php
			$assemble_by_area = array();
			foreach ( (array) $assemble_edition['reports'] as $assemble_report ) {
				$assemble_by_area[ assemble_area( (string) $assemble_report['area'] ) ][] = $assemble_report;
			}
			// Practice-area order, then anything unmapped.
			$assemble_by_area = array_merge( array_intersect_key( array_flip( array_keys( $assemble_areas ) ), $assemble_by_area ), $assemble_by_area );
			?>
			<section class="page-band is-surface benchmark-reports"<?php echo 0 === $assemble_i ? ' id="benchmark-reports"' : ''; ?> aria-labelledby="benchmark-edition-<?php echo esc_attr( (string) $assemble_i ); ?>">
				<div class="wrap">
					<div class="wp-block-columns band-split">
						<div class="wp-block-column">
							<p class="eyebrow"><?php echo 0 === $assemble_i ? esc_html__( 'Latest reports', 'assemble' ) : esc_html__( 'Earlier reports', 'assemble' ); ?></p>
							<h2 id="benchmark-edition-<?php echo esc_attr( (string) $assemble_i ); ?>"><?php echo esc_html( (string) $assemble_edition['title'] ); ?></h2>
						</div>
						<div class="wp-block-column">
							<p class="is-style-lead">
								<?php
								/* translators: %s: edition date, e.g. "Q3 2026". */
								printf( esc_html__( 'Released %s. Each report filters by role, revenue, team size, growth, and industry.', 'assemble' ), esc_html( (string) $assemble_edition['date'] ) );
								?>
							</p>
						</div>
					</div>
					<div class="wp-block-columns practice-cards benchmark-cards">
						<?php foreach ( $assemble_by_area as $assemble_area => $assemble_reports ) : ?>
							<div class="practice-card" data-area="<?php echo esc_attr( $assemble_area ); ?>">
								<h3><?php echo esc_html( $assemble_areas[ $assemble_area ] ?? __( 'More reports', 'assemble' ) ); ?></h3>
								<ul class="benchmark-links">
									<?php foreach ( $assemble_reports as $assemble_report ) : ?>
										<li><a class="link-arrow" href="<?php echo esc_url( (string) $assemble_report['url'] ); ?>" rel="noopener"><?php echo esc_html( (string) $assemble_report['label'] ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endforeach; ?>

		<section class="page-band benchmark-survey" id="take-the-survey" aria-labelledby="benchmark-survey-title">
			<div class="wrap">
				<div class="wp-block-columns band-split">
					<div class="wp-block-column">
						<p class="eyebrow"><?php esc_html_e( 'Open survey', 'assemble' ); ?></p>
						<h2 id="benchmark-survey-title"><?php esc_html_e( 'Take the current survey', 'assemble' ); ?></h2>
					</div>
					<div class="wp-block-column benchmark-survey-body">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</section>

		<?php if ( $assemble_results ) : ?>
			<section class="page-band page-band-close" aria-labelledby="benchmark-results-title">
				<div class="wrap">
					<div class="wp-block-columns band-cta benchmark-results">
						<div class="wp-block-column">
							<h2 id="benchmark-results-title"><?php esc_html_e( 'Already took the survey?', 'assemble' ); ?></h2>
							<p><?php esc_html_e( 'Enter the email you used to take the survey and we’ll send a private link to your results, with your answers highlighted against the full Community.', 'assemble' ); ?></p>
							<div class="wp-block-buttons">
								<a class="btn-solid" href="<?php echo esc_url( $assemble_results ); ?>" rel="noopener"><?php esc_html_e( 'Get my results link', 'assemble' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</div>
	<?php
endwhile;

get_footer();
