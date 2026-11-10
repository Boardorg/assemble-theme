<?php
/**
 * Module: interest-area-grid (wireframe "6. Create Account", "Your interest
 * areas"). One row per practice area with a Follow toggle; a followed area
 * opens its Communities, each switchable (Cale, 2026-10-11). They're shown as
 * "Topics to follow", by topic name rather than Board name (Kristen's review,
 * Cale 2026-11-06); the values are still Community slugs. The mockup's
 * newsletter toggles and topic tags are held for a later phase.
 *
 * The toggles are real checkboxes, so the form works without JavaScript; CSS
 * (:has) shows an area's Communities only while it's followed. An area that
 * isn't followed has all its Communities ticked, so following it turns them
 * all on. The Communities drill-down has no wireframe: flagged for design.
 *
 * @var array $args { areas: string[], communities: string[] } The current choices.
 */

defined( 'ABSPATH' ) || exit;

$assemble_followed    = (array) ( $args['areas'] ?? array() );
$assemble_communities = (array) ( $args['communities'] ?? array() );
?>
<div class="account-widget interest-area-grid" id="interests">
	<h2 class="account-section-title"><?php esc_html_e( 'Your interest areas', 'assemble' ); ?></h2>
	<p class="interests-intro">
		<?php
		esc_html_e( 'Follow the practice areas you want reflected across your Assemble experience, then choose the topics within each.', 'assemble' );
		?>
	</p>

	<div class="interest-list">
		<?php foreach ( assemble_interest_areas() as $assemble_area ) : ?>
			<?php
			$assemble_key       = $assemble_area['key'];
			$assemble_is_on     = in_array( $assemble_key, $assemble_followed, true );
			$assemble_title_id  = 'interest-' . $assemble_key;
			?>
			<div class="interest-item<?php echo $assemble_is_on ? ' is-subscribed' : ''; ?>" data-area="<?php echo esc_attr( $assemble_key ); ?>">
				<div class="interest-copy">
					<h3 class="interest-title" id="<?php echo esc_attr( $assemble_title_id ); ?>"><?php echo esc_html( $assemble_area['label'] ); ?></h3>
					<p class="topic-pending"><?php echo esc_html( $assemble_area['blurb'] ); ?></p>

					<?php if ( $assemble_area['communities'] ) : ?>
						<fieldset class="interest-communities">
							<legend><?php esc_html_e( 'Topics to follow', 'assemble' ); ?></legend>
							<div class="community-options">
								<?php foreach ( $assemble_area['communities'] as $assemble_community ) : ?>
									<?php $assemble_checked = ! $assemble_is_on || in_array( $assemble_community['slug'], $assemble_communities, true ); ?>
									<label class="community-option">
										<input type="checkbox" name="communities[]" value="<?php echo esc_attr( $assemble_community['slug'] ); ?>"<?php checked( $assemble_checked ); ?>>
										<span><?php echo esc_html( assemble_interest_topic_label( $assemble_community ) ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</fieldset>
					<?php endif; ?>
				</div>
				<div class="preference-actions">
					<label class="subscribe-toggle">
						<input class="follow-input" type="checkbox" name="areas[]" value="<?php echo esc_attr( $assemble_key ); ?>"<?php checked( $assemble_is_on ); ?> aria-describedby="<?php echo esc_attr( $assemble_title_id ); ?>">
						<span class="plus" aria-hidden="true">+</span>
						<svg class="check" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path d="M3 8.5l3.2 3L13 4.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
						<span class="toggle-label toggle-label--off"><?php esc_html_e( 'Follow', 'assemble' ); ?></span>
						<span class="toggle-label toggle-label--on"><?php esc_html_e( 'Following', 'assemble' ); ?></span>
					</label>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
