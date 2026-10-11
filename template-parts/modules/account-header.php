<?php
/**
 * Module: account-header (web-style-guide.html → Web modules; wireframe
 * "6. Create Account"). The page title, then any notice or form-level error.
 *
 * @var array $args { title: string, notice?: string, error?: string }
 */

defined( 'ABSPATH' ) || exit;

$assemble_notice = (string) ( $args['notice'] ?? '' );
$assemble_error  = (string) ( $args['error'] ?? '' );
?>
<div class="account-header">
	<h1><?php echo esc_html( (string) ( $args['title'] ?? '' ) ); ?></h1>
</div>
<?php if ( '' !== $assemble_error ) : ?>
	<div class="account-message account-message--error" role="alert"><?php echo esc_html( $assemble_error ); ?></div>
<?php elseif ( '' !== $assemble_notice ) : ?>
	<div class="account-message" role="status"><?php echo esc_html( $assemble_notice ); ?></div>
<?php endif; ?>
