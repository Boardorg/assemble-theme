<?php
/**
 * Module: account-cta (wireframe "4. Article"). Logged-out visitors only; the
 * template decides that.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="account-cta" aria-labelledby="account-cta-title">
	<div>
		<div class="eyebrow"><?php esc_html_e( 'Keep the peer intelligence coming', 'assemble' ); ?></div>
		<h2 id="account-cta-title"><?php esc_html_e( 'Create a free account to get our latest peer intelligence delivered to you.', 'assemble' ); ?></h2>
		<p><?php esc_html_e( 'Follow the Communities that matter to you and get fresh insights, benchmarks, community conversations, and upcoming executive gatherings from across Assemble.', 'assemble' ); ?></p>
	</div>
	<div class="actions">
		<a class="btn-solid" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'create_account_url', '/register/' ) ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
		<a class="btn-outline" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'sign_in_url', '/login/' ) ) ); ?>"><?php echo esc_html( assemble_label( 'sign_in' ) ); ?></a>
	</div>
</section>
