<?php
/**
 * Module: ld-summit-card, the homepage's "Next Summit" card
 * (web-style-guide.html → Web modules → Peer intelligence; wireframe 1).
 *
 * The soonest summit featured for the panel's Community: its title, full date
 * range and place, its colour logo from the shared image library, a link to its
 * executiveplatforms.com page and "See all Summits". The logo is decorative
 * (the title sits beside it), so its alt is empty. No "Summit" bar
 * (wireframe edits B.1).
 *
 * @var array $args { summit: array from assemble_next_summit() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_summit = (array) ( $args['summit'] ?? array() );
$assemble_place  = assemble_summit_place( $assemble_summit );
?>
<div class="ld-card ld-summit-card">
	<div class="ld-card-header">
		<h4 class="ld-card-title"><?php esc_html_e( 'Next Summit', 'assemble' ); ?></h4>
	</div>
	<div class="ld-summit-body">
		<h5><?php echo esc_html( (string) $assemble_summit['title'] ); ?></h5>
		<p>
			<?php echo esc_html( assemble_summit_dates( $assemble_summit ) ); ?>
			<?php if ( '' !== $assemble_place ) : ?>
				<br><?php echo esc_html( $assemble_place ); ?>
			<?php endif; ?>
		</p>
		<?php if ( '' !== (string) ( $assemble_summit['logo'] ?? '' ) ) : ?>
			<div class="ld-summit-visual">
				<img src="<?php echo esc_url( (string) $assemble_summit['logo'] ); ?>" alt="" loading="lazy" decoding="async">
			</div>
		<?php endif; ?>
		<div class="ld-cta-row">
			<a class="btn-solid" href="<?php echo esc_url( assemble_summit_url( $assemble_summit ) ); ?>"><?php echo esc_html( assemble_label( 'summit_details' ) ); ?><span class="screen-reader-text">: <?php echo esc_html( (string) $assemble_summit['title'] ); ?></span></a>
		</div>
	</div>
	<div class="ld-card-footer">
		<a class="link-arrow" href="<?php echo esc_url( assemble_summits_url() ); ?>"><?php echo esc_html( assemble_label( 'all_summits' ) ); ?></a>
	</div>
</div>
