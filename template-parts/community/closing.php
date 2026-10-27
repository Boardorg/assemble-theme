<?php
/**
 * Community page band: the closing band, #apply. Heading, body and the button to
 * Site Settings → membership_url, with the closing illustration.
 * Shared by the Board and index layouts.
 *
 * @var array $args { page: a Community page view model }
 */

defined( 'ABSPATH' ) || exit;

$assemble_page    = (array) $args['page'];
$assemble_closing = (array) ( $assemble_page['closing'] ?? array() );
$assemble_apply   = (string) assemble_setting( 'membership_url', '/contact-us/' );
$assemble_cta     = (string) ( $assemble_closing['cta'] ?? '' );
$assemble_t       = static fn( string $text ): string => assemble_community_text( $text, $assemble_page );
?>
<div class="community-band community-band-apply">
	<div class="wrap">
		<section class="community-apply" id="apply" aria-labelledby="community-apply">
			<div>
				<h2 id="community-apply"><?php echo esc_html( $assemble_t( (string) ( $assemble_closing['heading'] ?? '' ) ) ); ?></h2>
				<?php if ( ! empty( $assemble_closing['body'] ) ) : ?>
					<p><?php echo esc_html( $assemble_t( (string) $assemble_closing['body'] ) ); ?></p>
				<?php endif; ?>
				<a class="btn-solid" href="<?php echo esc_url( assemble_url( $assemble_apply ) ); ?>"><?php echo esc_html( '' !== $assemble_cta ? $assemble_t( $assemble_cta ) : __( 'Apply to join', 'assemble' ) ); ?></a>
			</div>
			<?php if ( ! empty( $assemble_closing['image']['url'] ) ) : ?>
				<img class="community-apply-art" src="<?php echo esc_url( $assemble_closing['image']['url'] ); ?>" alt="" loading="lazy" decoding="async">
			<?php endif; ?>
		</section>
	</div>
</div>
