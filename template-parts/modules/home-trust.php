<?php
/**
 * Module: home-trust. Under Top stories, in place of the peer-intelligence
 * splash (its message moved up into home-identity; Cale 2026-11-17): the
 * member-company logos, when the Communities index has four or more approved
 * ones (Public Mention = OK only), then the free-account call to action
 * (logged out). Left out when neither applies.
 */

defined( 'ABSPATH' ) || exit;

$assemble_logos = assemble_home_proof()['logos'];
$assemble_cta   = ! is_user_logged_in();

if ( ! $assemble_logos && ! $assemble_cta ) {
	return;
}
?>
<section class="home-trust<?php echo $assemble_logos ? ' has-logos' : ''; ?>" aria-labelledby="home-trust-title">
	<div class="wrap">
		<?php if ( $assemble_logos ) : ?>
			<div class="home-trust-head">
				<p class="eyebrow" id="home-trust-title"><?php esc_html_e( 'You’re in good company', 'assemble' ); ?></p>
				<p class="home-trust-line"><?php esc_html_e( 'Leaders from these companies, and many more, share what’s working inside Assemble Communities.', 'assemble' ); ?></p>
			</div>
			<ul class="home-trust-logos" role="list">
				<?php foreach ( $assemble_logos as $assemble_logo ) : ?>
					<li class="media-frame media-logo">
						<?php if ( ! empty( $assemble_logo['url'] ) ) : ?>
							<img src="<?php echo esc_url( $assemble_logo['url'] ); ?>" alt="<?php echo esc_attr( $assemble_logo['name'] ); ?>" loading="lazy" decoding="async">
						<?php else : ?>
							<span class="logo-placeholder"><?php echo esc_html( $assemble_logo['name'] ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<h2 class="screen-reader-text" id="home-trust-title"><?php esc_html_e( 'Create a free account', 'assemble' ); ?></h2>
		<?php endif; ?>

		<?php if ( $assemble_cta ) : ?>
			<div class="free-account-cta">
				<div class="free-account-cta-copy">
					<h3><?php esc_html_e( 'Create a free account to get our latest peer intelligence delivered to you.', 'assemble' ); ?></h3>
					<p>
						<?php
						/* translators: %s: "Communities" (or its replacement label). */
						printf( esc_html__( 'Follow the %s that matter to you and get new insights from Assemble delivered as they’re published.', 'assemble' ), esc_html( assemble_label( 'communities' ) ) );
						?>
					</p>
				</div>
				<a class="btn-solid" href="<?php echo esc_url( assemble_account_link( 'register' ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>
