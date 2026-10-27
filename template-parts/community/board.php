<?php
/**
 * Community page layout: a Board or Council sales page, e.g. /communities/aeo/.
 *
 * Marketing's section order (their AEO mockup), in the design system: hero with
 * the Board logo, member count beside the logo wall, the problem and challenges,
 * recent Insights (ours), upcoming Working Sessions, impact at three levels,
 * what's included, requirements, good company, featured Summits (ours, when
 * any), and the closing apply band. Sections sit in full-width bands that
 * alternate the page background with the guide's sunken surface (as the
 * homepage's peer-intelligence splash). Any section without data is left out.
 * No wireframe: on-system, flag for design.
 *
 * @var array $args { page: the view model from assemble_current_community_page() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_page      = (array) $args['page'];
$assemble_area      = assemble_area( (string) $assemble_page['area'] );
$assemble_slug      = (string) ( $assemble_page['community_slug'] ?? '' );
$assemble_logo      = (array) ( $assemble_page['logo'] ?? array() );
$assemble_hero      = (array) ( $assemble_page['hero'] ?? array() );
$assemble_members   = (array) ( $assemble_page['members'] ?? array() );
$assemble_problem   = (array) ( $assemble_page['problem'] ?? array() );
$assemble_challenge = (array) ( $assemble_page['challenges'] ?? array() );
$assemble_impact    = (array) ( $assemble_page['impact'] ?? array() );
$assemble_included  = (array) ( $assemble_page['included'] ?? array() );
$assemble_company   = (array) ( $assemble_page['company'] ?? array() );
$assemble_sessions  = assemble_community_sessions( $assemble_page );
$assemble_stories   = '' !== $assemble_slug ? assemble_community_stories( $assemble_slug, 3 ) : array();
$assemble_summits   = '' !== $assemble_slug && assemble_has_summits() ? assemble_featured_summits( $assemble_slug, $assemble_area, 3 ) : array();
$assemble_crumbs    = assemble_community_crumbs( $assemble_page );
$assemble_t         = static fn( string $text ): string => assemble_community_text( $text, $assemble_page );
?>
<div class="community-page" data-area="<?php echo esc_attr( $assemble_area ); ?>">

	<div class="community-band community-band-hero">
		<div class="wrap">
			<section class="community-hero" aria-labelledby="community-title">
				<div class="community-hero-copy">
					<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'assemble' ); ?>">
						<ol>
							<?php foreach ( $assemble_crumbs as $assemble_i => $assemble_crumb ) : ?>
								<li>
									<?php if ( array_key_last( $assemble_crumbs ) === $assemble_i ) : ?>
										<span aria-current="page"><?php echo esc_html( $assemble_crumb['label'] ); ?></span>
									<?php elseif ( '' === $assemble_crumb['url'] ) : ?>
										<span><?php echo esc_html( $assemble_crumb['label'] ); ?></span>
									<?php else : ?>
										<a href="<?php echo esc_url( $assemble_crumb['url'] ); ?>"><?php echo esc_html( $assemble_crumb['label'] ); ?></a>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ol>
					</nav>
					<header class="content-head">
						<?php if ( ! empty( $assemble_logo['url'] ) ) : ?>
							<img class="community-logo" src="<?php echo esc_url( $assemble_logo['url'] ); ?>" alt="<?php echo esc_attr( (string) ( $assemble_logo['alt'] ?? $assemble_page['name'] ) ); ?>" loading="eager" decoding="async">
						<?php endif; ?>
						<p class="eyebrow">
							<?php
							/* translators: %s: Board name. */
							printf( esc_html__( '%s · An Assemble Community', 'assemble' ), esc_html( $assemble_page['name'] ) );
							?>
						</p>
						<h1 id="community-title"><?php echo esc_html( $assemble_t( (string) ( $assemble_hero['headline'] ?? $assemble_page['name'] ) ) ); ?></h1>
						<?php if ( ! empty( $assemble_hero['dek'] ) ) : ?>
							<p class="body-lead"><?php echo esc_html( $assemble_t( (string) $assemble_hero['dek'] ) ); ?></p>
						<?php endif; ?>
						<div class="community-cta-row">
							<a class="btn-solid" href="#apply"><?php esc_html_e( 'Apply to join', 'assemble' ); ?></a>
							<?php if ( $assemble_included ) : ?>
								<a class="link-arrow" href="#membership"><?php esc_html_e( 'See how membership works', 'assemble' ); ?></a>
							<?php endif; ?>
						</div>
					</header>
				</div>
				<div class="media-frame community-art">
					<?php if ( ! empty( $assemble_hero['image']['url'] ) ) : ?>
						<img src="<?php echo esc_url( $assemble_hero['image']['url'] ); ?>" alt="<?php echo esc_attr( (string) ( $assemble_hero['image']['alt'] ?? '' ) ); ?>" loading="eager" decoding="async">
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<?php get_template_part( 'template-parts/community/proof', null, array( 'page' => $assemble_page ) ); ?>

	<?php if ( ! empty( $assemble_problem['heading'] ) || ! empty( $assemble_challenge['items'] ) ) : ?>
		<div class="community-band">
			<div class="wrap">
				<?php if ( ! empty( $assemble_problem['heading'] ) ) : ?>
					<section class="community-split">
						<h2><?php echo esc_html( $assemble_t( (string) $assemble_problem['heading'] ) ); ?></h2>
						<div>
							<?php foreach ( (array) ( $assemble_problem['body'] ?? array() ) as $assemble_i => $assemble_para ) : ?>
								<p<?php echo 0 === $assemble_i ? ' class="body-lead"' : ''; ?>><?php echo esc_html( $assemble_t( (string) $assemble_para ) ); ?></p>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>

				<?php if ( ! empty( $assemble_challenge['items'] ) ) : ?>
					<section class="community-section" aria-labelledby="community-challenges">
						<h2 class="stories-section-heading" id="community-challenges"><?php echo esc_html( $assemble_t( (string) $assemble_challenge['heading'] ) ); ?></h2>
						<ol class="community-challenges">
							<?php foreach ( $assemble_challenge['items'] as $assemble_item ) : ?>
								<li><?php echo esc_html( $assemble_t( (string) $assemble_item ) ); ?></li>
							<?php endforeach; ?>
						</ol>
					</section>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $assemble_stories ) : ?>
		<div class="community-band is-sunken">
			<div class="wrap">
				<section aria-labelledby="community-insights">
					<h2 class="stories-section-heading" id="community-insights">
						<?php
						/* translators: %s: "the AEO Board". */
						printf( esc_html__( 'Recent peer intelligence from %s', 'assemble' ), esc_html( assemble_community_in_sentence( (string) $assemble_page['name'] ) ) );
						?>
					</h2>
					<div class="related-grid">
						<?php
						foreach ( $assemble_stories as $assemble_story ) {
							get_template_part( 'template-parts/modules/report-card', null, array( 'story' => $assemble_story ) );
						}
						?>
					</div>
					<p class="community-more">
						<a class="link-arrow" href="<?php echo esc_url( assemble_community_url( $assemble_slug ) ); ?>">
							<?php
							/* translators: %s: Board name. */
							printf( esc_html__( 'All %s Insights', 'assemble' ), esc_html( $assemble_page['name'] ) );
							?>
						</a>
					</p>
				</section>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $assemble_sessions ) : ?>
		<div class="community-band">
			<div class="wrap">
				<section aria-labelledby="community-sessions">
					<h2 class="stories-section-heading" id="community-sessions"><?php esc_html_e( 'Join our next conversation', 'assemble' ); ?></h2>
					<div class="community-sessions">
						<?php foreach ( $assemble_sessions as $assemble_session ) : ?>
							<article class="session-card">
								<time class="session-date" datetime="<?php echo esc_attr( wp_date( 'Y-m-d', $assemble_session['time'] ) ); ?>">
									<span class="session-month"><?php echo esc_html( wp_date( 'M', $assemble_session['time'] ) ); ?></span>
									<span class="session-day"><?php echo esc_html( wp_date( 'j', $assemble_session['time'] ) ); ?></span>
								</time>
								<div>
									<span class="tag tag-area"><?php esc_html_e( 'Working Session', 'assemble' ); ?></span>
									<h3><?php echo esc_html( $assemble_t( (string) $assemble_session['title'] ) ); ?></h3>
									<?php if ( ! empty( $assemble_session['body'] ) ) : ?>
										<p><?php echo esc_html( $assemble_t( (string) $assemble_session['body'] ) ); ?></p>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</section>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $assemble_impact['items'] ) ) : ?>
		<div class="community-band is-sunken">
			<div class="wrap">
				<section aria-labelledby="community-impact">
					<p class="eyebrow"><?php echo esc_html( (string) $assemble_impact['eyebrow'] ); ?></p>
					<h2 class="community-heading" id="community-impact"><?php echo esc_html( $assemble_t( (string) $assemble_impact['heading'] ) ); ?></h2>
					<div class="community-impact">
						<?php foreach ( $assemble_impact['items'] as $assemble_item ) : ?>
							<div class="card">
								<p class="eyebrow"><?php echo esc_html( (string) $assemble_item['level'] ); ?></p>
								<h3><?php echo esc_html( $assemble_t( (string) $assemble_item['title'] ) ); ?></h3>
								<p><?php echo esc_html( $assemble_t( (string) $assemble_item['body'] ) ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/community/included', null, array( 'page' => $assemble_page ) ); ?>

	<?php get_template_part( 'template-parts/community/fit', null, array( 'page' => $assemble_page ) ); ?>

	<?php if ( ! empty( $assemble_company['heading'] ) || $assemble_summits ) : ?>
		<div class="community-band">
			<div class="wrap">
				<?php if ( ! empty( $assemble_company['heading'] ) ) : ?>
					<section class="community-company" aria-labelledby="community-company">
						<p class="eyebrow"><?php echo esc_html( (string) $assemble_company['eyebrow'] ); ?></p>
						<h2 class="community-heading" id="community-company"><?php echo esc_html( $assemble_t( (string) $assemble_company['heading'] ) ); ?></h2>
						<?php if ( ! empty( $assemble_members['functions'] ) ) : ?>
							<p><?php echo esc_html( $assemble_t( (string) $assemble_members['functions'] ) ); ?></p>
						<?php endif; ?>
					</section>
				<?php endif; ?>

				<?php if ( $assemble_summits ) : ?>
					<section class="community-section" aria-labelledby="community-summits">
						<h2 class="stories-section-heading" id="community-summits"><?php esc_html_e( 'Meet in person at a Summit', 'assemble' ); ?></h2>
						<div class="event-card-grid">
							<?php foreach ( $assemble_summits as $assemble_summit ) : ?>
								<article class="event-card">
									<h3><?php echo esc_html( $assemble_summit['title'] ); ?></h3>
									<p class="event-when"><span><?php echo esc_html( assemble_summit_dates( $assemble_summit, 'short' ) ); ?></span></p>
									<?php if ( '' !== $assemble_summit['blurb'] ) : ?>
										<p><?php echo esc_html( $assemble_summit['blurb'] ); ?></p>
									<?php endif; ?>
									<a class="btn-outline" href="<?php echo esc_url( assemble_summit_url( $assemble_summit ) ); ?>"><?php echo esc_html( assemble_label( 'summit_details' ) ); ?><span class="screen-reader-text">: <?php echo esc_html( $assemble_summit['title'] ); ?></span></a>
								</article>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/community/closing', null, array( 'page' => $assemble_page ) ); ?>

</div>
