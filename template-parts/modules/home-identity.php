<?php
/**
 * Module: home-identity. The homepage's short "who we are" band, above Top
 * stories (David's homepage review, Cale 2026-11-17): what Assemble is, who
 * it's for and why it matters, the free-account button, and the proof
 * figures (assemble_home_proof()). Content-first stays: it's a band, not a
 * brochure hero. No wireframe: on-system, flagged for design.
 *
 * Copy is template code, as the peer-intelligence splash's was.
 */

defined( 'ABSPATH' ) || exit;

$assemble_proof = assemble_home_proof();
?>
<section class="home-identity wrap" aria-labelledby="home-identity-title">
	<div class="home-identity-copy">
		<h1 class="home-identity-title" id="home-identity-title"><?php esc_html_e( 'Peer intelligence for senior leaders', 'assemble' ); ?></h1>
		<p class="home-identity-text"><?php esc_html_e( 'Discover what leaders at the world’s largest companies are really doing. Assemble brings them together in private Communities and executive Summits, and turns what they share into practical insight, free to read.', 'assemble' ); ?></p>
		<div class="home-identity-actions">
			<?php if ( ! is_user_logged_in() ) : ?>
				<a class="btn-solid" href="<?php echo esc_url( assemble_account_link( 'register' ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
			<?php endif; ?>
			<a class="link-arrow" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'how_it_works_url', '/about-us/' ) ) ); ?>"><?php esc_html_e( 'How Assemble works', 'assemble' ); ?></a>
		</div>
	</div>
	<?php if ( $assemble_proof['stats'] ) : ?>
		<dl class="home-identity-stats">
			<?php foreach ( $assemble_proof['stats'] as $assemble_stat ) : ?>
				<div>
					<dt><?php echo esc_html( $assemble_stat[0] ); ?></dt>
					<dd><?php echo esc_html( $assemble_stat[1] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	<?php endif; ?>
</section>
