<?php
/**
 * The banded pages' closing card (page-bands.css .band-cta) for templates that
 * aren't built in the block editor (Our Team): copy and actions beside the
 * handshake illustration. Prints nothing without a Contact page.
 *
 * @var array $args { heading?: string, text?: string }
 */

defined( 'ABSPATH' ) || exit;

$assemble_contact = assemble_page_url( 'contact-us' );

if ( '' === $assemble_contact ) {
	return;
}
?>
<section class="page-band page-band-close">
	<div class="wrap">
		<div class="wp-block-columns band-cta">
			<div class="wp-block-column">
				<h2><?php echo esc_html( (string) ( $args['heading'] ?? __( 'Looking to learn more about Assemble?', 'assemble' ) ) ); ?></h2>
				<p><?php echo esc_html( (string) ( $args['text'] ?? __( 'Interested in joining a Community, attending a Summit or becoming a sponsor? We’d love to connect.', 'assemble' ) ) ); ?></p>
				<div class="wp-block-buttons">
					<div class="wp-block-button"><a class="wp-block-button__link" href="<?php echo esc_url( $assemble_contact ); ?>"><?php esc_html_e( 'Contact us', 'assemble' ); ?></a></div>
					<?php if ( '' !== assemble_page_url( 'careers' ) ) : ?>
						<div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="<?php echo esc_url( assemble_page_url( 'careers' ) ); ?>"><?php esc_html_e( 'Join our team', 'assemble' ); ?></a></div>
					<?php endif; ?>
				</div>
			</div>
			<div class="wp-block-column">
				<figure class="band-figure"><img src="<?php echo esc_url( content_url( 'assets/illustrations/handshake-with-wave-color.png' ) ); ?>" alt="" loading="lazy" decoding="async"></figure>
			</div>
		</div>
	</div>
</section>
