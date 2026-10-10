<?php
/**
 * Module: explore-peer-intelligence / content-explorer
 * (web-style-guide.html → Web modules → Peer intelligence).
 *
 * Heading, a practice-area row and a Community row of pills, then one panel per
 * Community: intro, the Insights card (ld-insights-card) and the Latest
 * benchmark card (ld-benchmark-card). Communities come from the feed's
 * community directory, so Communities without reports still get a tab and an
 * empty state.
 *
 * Next Summit (ld-summit-card) is the soonest summit featured for the
 * Community, from executiveplatforms.com through assemble-core (Phase 5a).
 *
 * Not built yet, because nothing feeds them: the source-branding card
 * (topic-intro-source-card), Upcoming Working Sessions and Leaders to follow.
 * See docs/handoff.md → Placeholders.
 *
 * Without JavaScript the opening panel shows and the pills do nothing.
 */

defined( 'ABSPATH' ) || exit;

$assemble_areas   = array();
$assemble_opening = '';
foreach ( assemble_practice_areas() as $assemble_key => $assemble_label ) {
	$assemble_communities = assemble_area_communities( $assemble_key );
	foreach ( $assemble_communities as $assemble_i => $assemble_community ) {
		$assemble_communities[ $assemble_i ]['stories'] = assemble_community_stories( $assemble_community['slug'], 3 );

		// Open on the first Community that has something to show.
		if ( '' === $assemble_opening && $assemble_communities[ $assemble_i ]['stories'] ) {
			$assemble_opening = $assemble_community['slug'];
		}
	}

	$assemble_areas[ $assemble_key ] = array(
		'label'       => $assemble_label,
		'communities' => $assemble_communities,
	);
}

// Nothing published anywhere: open on the first Community, else the first area.
if ( '' === $assemble_opening ) {
	foreach ( $assemble_areas as $assemble_key => $assemble_area ) {
		$assemble_opening = $assemble_area['communities'][0]['slug'] ?? 'area-' . $assemble_key;
		break;
	}
}

$assemble_feature = assemble_homepage_feature();

/** The panel id for a Community slug (or "area-<key>" for an area with none). */
$assemble_panel_id = static fn ( string $slug ): string => 'explore-' . sanitize_html_class( $slug );
?>
<section class="content-explorer" aria-labelledby="content-explorer-title" data-content-explorer>
	<div class="wrap">
		<h2 class="stories-section-heading explore-peer-heading" id="content-explorer-title"><?php esc_html_e( 'Explore peer intelligence', 'assemble' ); ?></h2>

		<div class="tab-buttons two-row-topic-nav">
			<div class="topic-row topic-row-primary" role="group" aria-label="<?php esc_attr_e( 'Practice areas', 'assemble' ); ?>">
				<?php foreach ( $assemble_areas as $assemble_key => $assemble_area ) : ?>
					<?php
					$assemble_first   = $assemble_area['communities'][0]['slug'] ?? 'area-' . $assemble_key;
					$assemble_pressed = $assemble_first === $assemble_opening || in_array( $assemble_opening, array_column( $assemble_area['communities'], 'slug' ), true );
					?>
					<button class="tab-btn tab-btn-primary" type="button" aria-pressed="<?php echo $assemble_pressed ? 'true' : 'false'; ?>" data-explorer-area="<?php echo esc_attr( $assemble_key ); ?>" data-explorer-target="<?php echo esc_attr( $assemble_panel_id( $assemble_first ) ); ?>"><?php echo esc_html( $assemble_area['label'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="topic-row-secondary-wrap">
				<?php foreach ( $assemble_areas as $assemble_key => $assemble_area ) : ?>
					<?php
					if ( ! $assemble_area['communities'] ) {
						continue;
					}
					$assemble_open_row = in_array( $assemble_opening, array_column( $assemble_area['communities'], 'slug' ), true );
					?>
					<div class="topic-row topic-row-secondary" role="group" data-explorer-group="<?php echo esc_attr( $assemble_key ); ?>" aria-label="<?php echo esc_attr( $assemble_area['label'] . ' ' . assemble_label( 'communities' ) ); ?>"<?php echo $assemble_open_row ? '' : ' hidden'; ?>>
						<?php foreach ( $assemble_area['communities'] as $assemble_community ) : ?>
							<button class="tab-btn" type="button" aria-pressed="<?php echo $assemble_community['slug'] === $assemble_opening ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $assemble_panel_id( $assemble_community['slug'] ) ); ?>" data-explorer-target="<?php echo esc_attr( $assemble_panel_id( $assemble_community['slug'] ) ); ?>"><?php echo esc_html( $assemble_community['label'] ); ?></button>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php foreach ( $assemble_areas as $assemble_key => $assemble_area ) : ?>
			<?php if ( ! $assemble_area['communities'] ) : ?>
				<div class="tab-panel" id="<?php echo esc_attr( $assemble_panel_id( 'area-' . $assemble_key ) ); ?>" data-area="<?php echo esc_attr( $assemble_key ); ?>"<?php echo 'area-' . $assemble_key === $assemble_opening ? '' : ' hidden'; ?>>
					<div class="ld-topic-shell">
						<div class="ld-topic-intro">
							<h3 class="type-practice-masthead"><?php echo esc_html( $assemble_area['label'] ); ?></h3>
							<p>
								<?php
								/* translators: 1: practice area, 2: "Communities". */
								printf( esc_html__( '%1$s %2$s are on their way. Create a free account to hear when they launch.', 'assemble' ), esc_html( $assemble_area['label'] ), esc_html( assemble_label( 'communities' ) ) );
								?>
							</p>
							<a class="link-arrow" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'create_account_url', '/register/' ) ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
						</div>
					</div>
				</div>
				<?php continue; ?>
			<?php endif; ?>

			<?php foreach ( $assemble_area['communities'] as $assemble_community ) : ?>
				<div class="tab-panel" id="<?php echo esc_attr( $assemble_panel_id( $assemble_community['slug'] ) ); ?>" data-area="<?php echo esc_attr( $assemble_key ); ?>"<?php echo $assemble_community['slug'] === $assemble_opening ? '' : ' hidden'; ?>>
					<div class="ld-topic-shell">
						<div class="ld-topic-intro-row is-single">
							<div class="ld-topic-intro">
								<h3 class="type-practice-masthead"><?php echo esc_html( $assemble_community['label'] ); ?></h3>
								<?php if ( $assemble_community['description'] ) : ?>
									<p><?php echo esc_html( $assemble_community['description'] ); ?></p>
								<?php endif; ?>
							</div>
						</div>

						<?php $assemble_summit = assemble_next_summit( $assemble_key, $assemble_community['slug'] ); ?>
						<div class="ld-card-grid<?php echo $assemble_feature || $assemble_summit ? ' ld-card-grid--two' : ' ld-card-grid--one'; ?>">
							<?php
							get_template_part(
								'template-parts/modules/ld-insights-card',
								null,
								array( 'community' => $assemble_community )
							);

							if ( $assemble_feature || $assemble_summit ) {
								echo '<div class="ld-side-stack">';
								if ( $assemble_feature ) {
									get_template_part( 'template-parts/modules/ld-benchmark-card', null, array( 'feature' => $assemble_feature ) );
								}
								if ( $assemble_summit ) {
									get_template_part( 'template-parts/modules/ld-summit-card', null, array( 'summit' => $assemble_summit ) );
								}
								echo '</div>';
							}
							?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>

		<?php
		if ( ! is_user_logged_in() ) {
			get_template_part( 'template-parts/modules/learn-more-band' );
		}
		?>
	</div>
</section>
