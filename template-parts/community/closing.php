<?php
/**
 * Community page band: the closing band, #apply.
 *
 * Board and Council pages: a "Join the community" eyebrow (the proof band's
 * "You're in good company" style), heading and body beside the apply-to-join
 * form. The form is board.org's own (/join/), a HubSpot embed, with this
 * Board preselected in its "group you're most interested in" radio
 * (assemble-core's assemble_join_form(); Cale, 2026-11-12: match what board.org
 * does). No illustration there: the form fills the right column (Kristen: the
 * image made the section too large). Without a form (no HubSpot config, or the
 * index) it's the button to Site Settings → membership_url, with the closing
 * illustration. Shared by the Board and index layouts.
 *
 * @var array $args { page: a Community page view model }
 */

defined( 'ABSPATH' ) || exit;

$assemble_page    = (array) $args['page'];
$assemble_closing = (array) ( $assemble_page['closing'] ?? array() );
$assemble_join    = (array) ( $assemble_page['join'] ?? array() );
$assemble_apply   = (string) assemble_setting( 'membership_url', '/contact-us/' );
$assemble_cta     = (string) ( $assemble_closing['cta'] ?? '' );
$assemble_has_form = ! empty( $assemble_join['form'] ) && ! empty( $assemble_join['portal'] );
$assemble_t       = static fn( string $text ): string => assemble_community_text( $text, $assemble_page );
?>
<div class="community-band community-band-apply">
	<div class="wrap">
		<section class="community-apply<?php echo $assemble_has_form ? ' has-form' : ''; ?>" id="apply" aria-labelledby="community-apply">
			<div class="community-apply-copy">
				<?php if ( $assemble_has_form ) : ?>
					<p class="eyebrow"><?php esc_html_e( 'Join the community', 'assemble' ); ?></p>
				<?php endif; ?>
				<h2 id="community-apply"><?php echo esc_html( $assemble_t( (string) ( $assemble_closing['heading'] ?? '' ) ) ); ?></h2>
				<?php if ( ! empty( $assemble_closing['body'] ) ) : ?>
					<p><?php echo esc_html( $assemble_t( (string) $assemble_closing['body'] ) ); ?></p>
				<?php endif; ?>
				<?php if ( ! $assemble_has_form ) : ?>
					<a class="btn-solid" href="<?php echo esc_url( assemble_url( $assemble_apply ) ); ?>"><?php echo esc_html( '' !== $assemble_cta ? $assemble_t( $assemble_cta ) : __( 'Apply to join', 'assemble' ) ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( $assemble_has_form ) : ?>
				<div class="community-apply-form">
					<div
						class="hs-form-frame"
						data-region="<?php echo esc_attr( (string) ( $assemble_join['region'] ?? 'na1' ) ); ?>"
						data-form-id="<?php echo esc_attr( (string) $assemble_join['form'] ); ?>"
						data-portal-id="<?php echo esc_attr( (string) $assemble_join['portal'] ); ?>"
						data-assemble-join-field="<?php echo esc_attr( (string) ( $assemble_join['field'] ?? '' ) ); ?>"
						data-assemble-join-value="<?php echo esc_attr( (string) ( $assemble_join['value'] ?? '' ) ); ?>"
					></div>
					<noscript>
						<a class="btn-solid" href="<?php echo esc_url( assemble_url( $assemble_apply ) ); ?>"><?php echo esc_html( '' !== $assemble_cta ? $assemble_t( $assemble_cta ) : __( 'Apply to join', 'assemble' ) ); ?></a>
					</noscript>
				</div>
			<?php elseif ( ! empty( $assemble_closing['image']['url'] ) ) : ?>
				<img class="community-apply-art" src="<?php echo esc_url( $assemble_closing['image']['url'] ); ?>" alt="" loading="lazy" decoding="async">
			<?php endif; ?>
		</section>
	</div>
</div>
