<?php
/**
 * Module: playbook-continuing-cards (wireframe "5. Playbook", "Continuing the
 * conversation"). "See all {Community} Insights", then two cards: About the
 * Community (its boilerplate, from the feed's community directory: public
 * text, the same the homepage shows) and the Community's featured Summit,
 * linked to EP (Cale, 2026-10-11; hidden when none is on the calendar).
 *
 * The mockup's About card has a heading ("A trusted peer community for …")
 * that no field supplies yet: left out and flagged in the theme handoff.
 *
 * @var array $args { community: view model `kicker`|null, area: data-area key }
 */

defined( 'ABSPATH' ) || exit;

$assemble_community = $args['community'] ?? null;

if ( ! $assemble_community || '' === (string) $assemble_community['slug'] ) {
	return;
}

$assemble_short     = assemble_community_label( $assemble_community );
$assemble_directory = class_exists( 'AC_Communities' ) ? (array) AC_Communities::get( (string) $assemble_community['slug'] ) : array();
$assemble_about     = trim( (string) ( $assemble_directory['boilerplate'] ?? '' ) );
$assemble_summit    = assemble_playbook_summit( $assemble_community );
?>
<section class="continuing" data-area="<?php echo esc_attr( (string) ( $args['area'] ?? 'neutral' ) ); ?>" aria-labelledby="continuing-title">
	<div class="eyebrow"><?php esc_html_e( 'Continuing the conversation', 'assemble' ); ?></div>
	<h2 id="continuing-title"><?php esc_html_e( 'Keep going with the peer intelligence behind the playbook.', 'assemble' ); ?></h2>
	<p class="section-link">
		<a class="link-arrow" href="<?php echo esc_url( assemble_community_url( (string) $assemble_community['slug'] ) ); ?>">
			<?php
			/* translators: 1: Community short name, 2: "Insights". */
			echo esc_html( sprintf( __( 'See all %1$s %2$s', 'assemble' ), $assemble_short, assemble_label( 'insights' ) ) );
			?>
		</a>
	</p>

	<?php if ( '' !== $assemble_about || $assemble_summit ) : ?>
		<div class="continuing-grid<?php echo ( '' !== $assemble_about && $assemble_summit ) ? '' : ' is-single'; ?>">
			<?php if ( '' !== $assemble_about ) : ?>
				<div class="info-card">
					<div class="eyebrow">
						<?php
						/* translators: %s: Community name, e.g. "the AEO Board". */
						echo esc_html( sprintf( __( 'About %s', 'assemble' ), assemble_community_in_sentence( (string) $assemble_community['name'] ) ) );
						?>
					</div>
					<p><?php echo esc_html( $assemble_about ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $assemble_summit ) : ?>
				<div class="info-card">
					<div class="eyebrow"><?php echo esc_html( (string) $assemble_summit['title'] ); ?></div>
					<h3><?php echo esc_html( assemble_label( 'help_card' ) ); ?></h3>
					<?php if ( '' !== (string) ( $assemble_summit['blurb'] ?? '' ) ) : ?>
						<p><?php echo esc_html( (string) $assemble_summit['blurb'] ); ?></p>
					<?php endif; ?>
					<a class="link-arrow" href="<?php echo esc_url( assemble_summit_url( $assemble_summit ) ); ?>">
						<?php
						/* translators: %s: Summit short code, e.g. NAMLS. */
						echo esc_html( sprintf( __( 'Explore %s', 'assemble' ), strtoupper( (string) ( $assemble_summit['code'] ?? '' ) ) ) );
						?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
