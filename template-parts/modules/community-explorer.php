<?php
/**
 * Module: community-explorer, the Communities index's "Find your Community"
 * filter (hub COMMUNITY-INDEX-HANDOFF.md §3). The homepage explorer's two-row
 * menu, practice then Community, with a Community panel in place of the
 * Insights panel. Same classes and data attributes as content-explorer.php and
 * summits-selector.php, so content-explorer.js runs unchanged.
 *
 * Each panel: the Community's logo and name, its one-liner (its sales page's,
 * else the first sentence of its Contentful boilerplate), who it's for, its
 * member count (sales page only) and two buttons; beside them its three newest
 * Insights (ld-insights-card) and its next Working Session (sales page) or
 * next Summit (ld-summit-card). With a sales page the buttons are "Explore the
 * AEO Board" and "Apply to join"; without one, "Read AEO Insights" and "Talk to
 * us about joining", so every Community is useful before it has a page.
 * A practice with no Communities yet gets one "on their way" panel.
 *
 * Opens on the first Community with a sales page, else the first with
 * Insights. Without JavaScript the opening panel shows and the pills do
 * nothing. /communities/#aeo opens that Community's panel.
 *
 * @var array $args { heading?: string, lead?: string, empty?: string }
 */

defined( 'ABSPATH' ) || exit;

$assemble_directory = function_exists( 'assemble_community_directory' ) ? assemble_community_directory() : array();
$assemble_join_url  = assemble_url( (string) assemble_setting( 'membership_url', '/contact-us/' ) );
$assemble_heading   = (string) ( $args['heading'] ?? '' );
$assemble_empty     = (string) ( $args['empty'] ?? '' );
$assemble_areas     = array();
$assemble_opening   = '';
$assemble_fallback  = '';

foreach ( assemble_practice_areas() as $assemble_key => $assemble_label ) {
	$assemble_communities = (array) ( $assemble_directory[ $assemble_key ] ?? array() );
	foreach ( $assemble_communities as $assemble_i => $assemble_community ) {
		$assemble_communities[ $assemble_i ]['stories'] = assemble_community_stories( $assemble_community['slug'], 3 );

		if ( '' === $assemble_opening && $assemble_community['page'] ) {
			$assemble_opening = $assemble_community['slug'];
		}
		if ( '' === $assemble_fallback && $assemble_communities[ $assemble_i ]['stories'] ) {
			$assemble_fallback = $assemble_community['slug'];
		}
	}

	$assemble_areas[ $assemble_key ] = array(
		'label'       => $assemble_label,
		'communities' => $assemble_communities,
	);
}

// No sales pages yet: open on the first Community with Insights, else the first panel.
if ( '' === $assemble_opening ) {
	$assemble_opening = $assemble_fallback;
}
if ( '' === $assemble_opening ) {
	$assemble_first   = reset( $assemble_areas );
	$assemble_opening = $assemble_first['communities'][0]['slug'] ?? 'area-' . array_key_first( $assemble_areas );
}

/** The panel id for a Community slug (or "area-<key>" for a practice with none). */
$assemble_panel_id = static fn ( string $slug ): string => 'find-' . sanitize_html_class( $slug );
?>
<section class="community-explorer" aria-labelledby="find-title" data-content-explorer data-explorer-hash="find-">
	<div class="community-explorer-head">
		<h2 class="stories-section-heading" id="find-title"><?php echo esc_html( '' !== $assemble_heading ? $assemble_heading : __( 'Find your Community', 'assemble' ) ); ?></h2>
		<?php if ( ! empty( $args['lead'] ) ) : ?>
			<p class="body-lead"><?php echo esc_html( (string) $args['lead'] ); ?></p>
		<?php endif; ?>
	</div>

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
					<div class="ld-topic-intro community-panel-intro is-empty">
						<h3 class="type-practice-masthead"><?php echo esc_html( $assemble_area['label'] ); ?></h3>
						<p>
							<?php
							echo esc_html(
								'' !== $assemble_empty
									? strtr( $assemble_empty, array( '{practice}' => $assemble_area['label'] ) )
									/* translators: %s: practice name. */
									: sprintf( __( '%s Communities are on their way. Tell us what you’d want from one.', 'assemble' ), $assemble_area['label'] )
							);
							?>
						</p>
						<div class="community-cta-row">
							<a class="btn-solid" href="<?php echo esc_url( $assemble_join_url ); ?>"><?php esc_html_e( 'Talk to us', 'assemble' ); ?></a>
							<?php if ( ! is_user_logged_in() ) : ?>
								<a class="link-arrow" href="<?php echo esc_url( assemble_account_link( 'register' ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php continue; ?>
		<?php endif; ?>

		<?php foreach ( $assemble_area['communities'] as $assemble_community ) : ?>
			<?php
			$assemble_page    = (array) ( $assemble_community['page'] ?? array() );
			$assemble_session = $assemble_page['session'] ?? null;
			$assemble_summit  = $assemble_session ? null : assemble_next_summit( $assemble_key, $assemble_community['slug'] );
			$assemble_in_text = assemble_community_in_sentence( $assemble_community['name'] );
			$assemble_one     = '' !== (string) ( $assemble_page['one_liner'] ?? '' ) ? (string) $assemble_page['one_liner'] : assemble_first_sentence( $assemble_community['boilerplate'] );
			?>
			<div class="tab-panel" id="<?php echo esc_attr( $assemble_panel_id( $assemble_community['slug'] ) ); ?>" data-area="<?php echo esc_attr( $assemble_key ); ?>"<?php echo $assemble_community['slug'] === $assemble_opening ? '' : ' hidden'; ?>>
				<div class="ld-topic-shell">
					<div class="ld-card-grid community-panel-grid">
						<div class="ld-topic-intro community-panel-intro">
							<?php if ( ! empty( $assemble_community['logo']['url'] ) ) : ?>
								<img class="community-panel-logo" src="<?php echo esc_url( $assemble_community['logo']['url'] ); ?>" alt="" loading="lazy" decoding="async">
							<?php endif; ?>
							<?php if ( 'council' === $assemble_community['kind'] ) : ?>
								<p class="eyebrow"><?php esc_html_e( 'Council', 'assemble' ); ?></p>
							<?php endif; ?>
							<h3 class="type-practice-masthead"><?php echo esc_html( $assemble_community['name'] ); ?></h3>
							<?php if ( '' !== $assemble_one ) : ?>
								<p><?php echo esc_html( $assemble_one ); ?></p>
							<?php endif; ?>
							<?php if ( $assemble_page && '' !== $assemble_community['audience'] ) : ?>
								<p class="community-panel-for">
									<?php
									/* translators: %s: e.g. "senior L&D leaders at the world's largest companies". */
									printf( esc_html__( 'For %s.', 'assemble' ), esc_html( $assemble_community['audience'] ) );
									?>
								</p>
							<?php endif; ?>
							<?php if ( '' !== (string) ( $assemble_page['member_count'] ?? '' ) ) : ?>
								<p class="community-panel-count">
									<span class="community-panel-stat"><?php echo esc_html( (string) $assemble_page['member_count'] ); ?></span>
									<?php esc_html_e( 'members', 'assemble' ); ?>
								</p>
							<?php endif; ?>
							<div class="community-cta-row">
								<?php if ( $assemble_page ) : ?>
									<a class="btn-solid" href="<?php echo esc_url( $assemble_page['url'] ); ?>">
										<?php
										/* translators: %s: "the AEO Board". */
										printf( esc_html__( 'Explore %s', 'assemble' ), esc_html( $assemble_in_text ) );
										?>
									</a>
									<a class="link-arrow" href="<?php echo esc_url( $assemble_page['url'] . '#apply' ); ?>"><?php esc_html_e( 'Apply to join', 'assemble' ); ?></a>
								<?php else : ?>
									<a class="btn-solid" href="<?php echo esc_url( assemble_community_url( $assemble_community['slug'] ) ); ?>">
										<?php
										/* translators: 1: Community label, e.g. "AEO", 2: "Insights". */
										printf( esc_html__( 'Read %1$s %2$s', 'assemble' ), esc_html( $assemble_community['label'] ), esc_html( assemble_label( 'insights' ) ) );
										?>
									</a>
									<a class="link-arrow" href="<?php echo esc_url( $assemble_join_url ); ?>"><?php esc_html_e( 'Talk to us about joining', 'assemble' ); ?></a>
								<?php endif; ?>
							</div>
						</div>

						<div class="ld-side-stack">
							<?php
							get_template_part( 'template-parts/modules/ld-insights-card', null, array( 'community' => $assemble_community ) );

							if ( $assemble_session ) {
								$assemble_time = strtotime( (string) $assemble_session['date'] );
								?>
								<div class="ld-card community-session-card">
									<div class="ld-card-header">
										<h4 class="ld-card-title"><?php esc_html_e( 'Next Working Session', 'assemble' ); ?></h4>
									</div>
									<div class="session-card">
										<time class="session-date" datetime="<?php echo esc_attr( wp_date( 'Y-m-d', $assemble_time ) ); ?>">
											<span class="session-month"><?php echo esc_html( wp_date( 'M', $assemble_time ) ); ?></span>
											<span class="session-day"><?php echo esc_html( wp_date( 'j', $assemble_time ) ); ?></span>
										</time>
										<div>
											<h5><?php echo esc_html( (string) $assemble_session['title'] ); ?></h5>
											<a class="link-arrow" href="<?php echo esc_url( $assemble_page['url'] . '#apply' ); ?>"><?php esc_html_e( 'Join the conversation', 'assemble' ); ?></a>
										</div>
									</div>
								</div>
								<?php
							} elseif ( $assemble_summit ) {
								get_template_part( 'template-parts/modules/ld-summit-card', null, array( 'summit' => $assemble_summit ) );
							}
							?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endforeach; ?>
</section>
