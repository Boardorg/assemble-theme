<?php
/**
 * A closing "get in touch" band linking to the Contact page. Prints nothing if
 * there's no Contact page.
 */

defined( 'ABSPATH' ) || exit;

$assemble_contact = assemble_page_url( 'contact-us' );

if ( '' === $assemble_contact ) {
	return;
}
?>
<aside class="contact-band">
	<div>
		<h2><?php esc_html_e( 'Interested in learning more about Assemble?', 'assemble' ); ?></h2>
		<p><?php esc_html_e( 'We’d love to connect.', 'assemble' ); ?></p>
	</div>
	<a class="btn-solid" href="<?php echo esc_url( $assemble_contact ); ?>"><?php esc_html_e( 'Contact us', 'assemble' ); ?></a>
</aside>
