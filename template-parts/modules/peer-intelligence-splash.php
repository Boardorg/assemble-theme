<?php
/**
 * Module: peer-intelligence-splash, with the free-account call to action below
 * it (web-style-guide.html → Web modules → Peer intelligence).
 *
 * Logged-out homepage only. Copy is template code (it rarely changes); the
 * links come from Site Settings.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="peer-intelligence-splash" aria-labelledby="peer-intelligence-title">
	<div class="wrap">
		<div class="peer-intelligence-banner">
			<div class="pi-copy">
				<h2 class="pi-headline type-brand-statement" id="peer-intelligence-title"><?php esc_html_e( 'Peer intelligence: What real business leaders are really doing.', 'assemble' ); ?></h2>
				<p class="pi-text"><?php esc_html_e( 'Fresh, practical intelligence sourced from conversations with senior practitioners confronting the same decisions you are.', 'assemble' ); ?></p>
				<a class="pi-link link-arrow" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'how_it_works_url', '/about-us/' ) ) ); ?>"><?php esc_html_e( 'How Assemble creates peer intelligence', 'assemble' ); ?></a>
			</div>
			<div class="pi-art" aria-hidden="true">
				<?php // Placeholder art: the mockup has no image for this slot yet. ?>
				<div class="img-x pi-placeholder"></div>
			</div>
		</div>

		<?php if ( ! is_user_logged_in() ) : ?>
			<div class="free-account-cta">
				<div class="free-account-cta-copy">
					<h3><?php esc_html_e( 'Create a free account to get our latest peer intelligence delivered to you.', 'assemble' ); ?></h3>
					<p>
						<?php
						/* translators: %s: "Communities" (or its replacement label). */
						printf( esc_html__( 'Follow the %s that matter to you and get new insights from Assemble delivered as they publish.', 'assemble' ), esc_html( assemble_label( 'communities' ) ) );
						?>
					</p>
				</div>
				<a class="btn-solid" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'create_account_url', '/register/' ) ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
