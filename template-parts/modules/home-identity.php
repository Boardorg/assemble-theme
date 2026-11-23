<?php
/**
 * Module: home-identity. The homepage's short "who we are" band, above Top
 * stories (David's homepage review, Cale 2026-11-17): what Assemble is, who
 * it's for and why it matters, links to Insights and About, and the proof
 * figures (assemble_home_proof()), beside the speech-bubbles illustration on
 * the sunken band the splash used. Text lines up with the Top stories card's
 * content below. Content-first stays: a band, not a brochure hero. No wireframe: on-system, flagged for design.
 *
 * Copy is template code, as the peer-intelligence splash's was.
 */

defined( 'ABSPATH' ) || exit;

$assemble_proof = assemble_home_proof();
?>
<section class="home-identity" aria-labelledby="home-identity-title">
	<div class="wrap">
		<div class="home-identity-inner">
			<div class="home-identity-copy">
				<h1 class="home-identity-title type-brand-statement" id="home-identity-title"><?php esc_html_e( 'Peer intelligence for senior leaders', 'assemble' ); ?></h1>
				<p class="home-identity-text"><?php esc_html_e( 'We power the insights, summits, and communities that enable senior business leaders to move faster and make better decisions.', 'assemble' ); ?></p>
				<div class="home-identity-actions">
					<?php // Insights replaces the free-account button; the header carries sign-up (David's SEO review, Cale 2026-11-23). ?>
					<a class="link-arrow" href="<?php echo esc_url( assemble_url( '/insights/' ) ); ?>"><?php esc_html_e( 'Explore our Insights', 'assemble' ); ?></a>
					<a class="link-arrow" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'how_it_works_url', '/about-us/' ) ) ); ?>"><?php esc_html_e( 'More about Assemble', 'assemble' ); ?></a>
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
			</div>
			<div class="home-identity-art" aria-hidden="true">
				<?php // The shared library's speech-bubbles illustration, as the retired peer-intelligence splash used (Cale, 2026-11-10). ?>
				<figure class="home-identity-figure"><img src="<?php echo esc_url( content_url( 'assets/illustrations/speech-bubbles-1.png' ) ); ?>" alt="" width="1600" height="1600" decoding="async" fetchpriority="low"></figure>
			</div>
		</div>
	</div>
</section>
