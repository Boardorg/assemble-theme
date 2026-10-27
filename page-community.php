<?php
/**
 * Template Name: Community page
 *
 * A Community (Board) sales page, e.g. /communities/aeo/. The page's SCF fields
 * (assemble-core) supply the view model through `assemble/community_page`.
 * Practice-area and index pages use this template too; until their layouts
 * exist they render as standard pages.
 *
 * Marketing's section order (their AEO mockup), in the design system: hero with
 * the Board logo, member count beside the logo wall, the problem and challenges,
 * recent Insights (ours), upcoming Working Sessions, impact at three levels,
 * what's included, requirements, good company, featured Summits (ours, when
 * any), and the closing apply band. Sections sit in full-width bands that
 * alternate the page background with the guide's sunken surface (as the
 * homepage's peer-intelligence splash). Any section without data is left out.
 * No wireframe: on-system, flag for design.
 */

defined( 'ABSPATH' ) || exit;

$assemble_page = assemble_current_community_page();

if ( ! assemble_is_community_sales_page() ) {
	require get_template_directory() . '/page.php';
	return;
}

get_header();

$assemble_area      = assemble_area( (string) $assemble_page['area'] );
$assemble_slug      = (string) ( $assemble_page['community_slug'] ?? '' );
$assemble_logo      = (array) ( $assemble_page['logo'] ?? array() );
$assemble_hero      = (array) ( $assemble_page['hero'] ?? array() );
$assemble_members   = (array) ( $assemble_page['members'] ?? array() );
$assemble_problem   = (array) ( $assemble_page['problem'] ?? array() );
$assemble_challenge = (array) ( $assemble_page['challenges'] ?? array() );
$assemble_impact    = (array) ( $assemble_page['impact'] ?? array() );
$assemble_included  = (array) ( $assemble_page['included'] ?? array() );
$assemble_fit       = (array) ( $assemble_page['fit'] ?? array() );
$assemble_company   = (array) ( $assemble_page['company'] ?? array() );
$assemble_closing   = (array) ( $assemble_page['closing'] ?? array() );
$assemble_sessions  = assemble_community_sessions( $assemble_page );
$assemble_stories   = '' !== $assemble_slug ? assemble_community_stories( $assemble_slug, 3 ) : array();
$assemble_summits   = '' !== $assemble_slug && assemble_has_summits() ? assemble_featured_summits( $assemble_slug, $assemble_area, 3 ) : array();
$assemble_apply     = (string) assemble_setting( 'membership_url', '/contact-us/' );
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

	<?php if ( ! empty( $assemble_members['count'] ) || ! empty( $assemble_members['logos'] ) ) : ?>
		<div class="community-band community-band-proof">
			<div class="wrap">
				<section class="community-proof" aria-label="<?php esc_attr_e( 'Members', 'assemble' ); ?>">
					<div class="community-proof-copy">
						<?php if ( ! empty( $assemble_company['eyebrow'] ) ) : ?>
							<p class="eyebrow"><?php echo esc_html( (string) $assemble_company['eyebrow'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $assemble_members['count'] ) ) : ?>
							<p class="community-stat"><?php echo esc_html( $assemble_members['count'] ); ?></p>
							<p class="community-proof-line"><?php echo esc_html( $assemble_t( (string) ( $assemble_members['descriptor'] ?? '' ) ) ); ?></p>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $assemble_members['logos'] ) ) : ?>
						<ul class="community-logos" aria-label="<?php esc_attr_e( 'Member companies', 'assemble' ); ?>">
							<?php foreach ( $assemble_members['logos'] as $assemble_member_logo ) : ?>
								<li class="media-frame media-logo">
									<?php if ( is_array( $assemble_member_logo ) && ! empty( $assemble_member_logo['url'] ) ) : ?>
										<img src="<?php echo esc_url( $assemble_member_logo['url'] ); ?>" alt="<?php echo esc_attr( (string) $assemble_member_logo['name'] ); ?>" loading="lazy" decoding="async">
									<?php else : ?>
										<span class="logo-placeholder"><?php echo esc_html( is_array( $assemble_member_logo ) ? (string) $assemble_member_logo['name'] : (string) $assemble_member_logo ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
							<li class="community-logos-more meta"><?php esc_html_e( 'and more', 'assemble' ); ?></li>
						</ul>
					<?php endif; ?>
				</section>
			</div>
		</div>
	<?php endif; ?>

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

	<?php if ( ! empty( $assemble_included['items'] ) ) : ?>
		<div class="community-band" id="membership">
			<div class="wrap">
				<section aria-labelledby="community-included">
					<p class="eyebrow"><?php echo esc_html( (string) $assemble_included['eyebrow'] ); ?></p>
					<h2 class="community-heading" id="community-included"><?php echo esc_html( $assemble_t( (string) $assemble_included['heading'] ) ); ?></h2>
					<ol class="community-included">
						<?php foreach ( $assemble_included['items'] as $assemble_item ) : ?>
							<li>
								<div class="included-intro">
									<h3><?php echo esc_html( $assemble_t( (string) $assemble_item['title'] ) ); ?></h3>
									<p><?php echo esc_html( $assemble_t( (string) $assemble_item['body'] ) ); ?></p>
								</div>
								<?php if ( ! empty( $assemble_item['tags'] ) ) : ?>
									<div class="included-tags">
										<?php if ( ! empty( $assemble_item['tags_in'] ) ) : ?>
											<p class="meta"><?php echo esc_html( (string) $assemble_item['tags_in'] ); ?></p>
										<?php endif; ?>
										<?php foreach ( $assemble_item['tags'] as $assemble_tag ) : ?>
											<span class="tag tag-area"><?php echo esc_html( (string) $assemble_tag ); ?></span>
										<?php endforeach; ?>
									</div>
								<?php elseif ( ! empty( $assemble_item['details'] ) ) : ?>
									<dl class="included-details">
										<?php foreach ( $assemble_item['details'] as $assemble_detail ) : ?>
											<div>
												<dt><?php echo esc_html( $assemble_t( (string) $assemble_detail[0] ) ); ?></dt>
												<dd><?php echo esc_html( $assemble_t( (string) $assemble_detail[1] ) ); ?></dd>
											</div>
										<?php endforeach; ?>
									</dl>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
				</section>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $assemble_fit['groups'] ) ) : ?>
		<div class="community-band is-sunken">
			<div class="wrap">
				<section class="community-fit" aria-labelledby="community-fit">
					<div class="community-fit-head">
						<p class="eyebrow"><?php echo esc_html( (string) $assemble_fit['eyebrow'] ); ?></p>
						<h2 class="community-heading" id="community-fit"><?php echo esc_html( $assemble_t( (string) $assemble_fit['heading'] ) ); ?></h2>
						<?php if ( ! empty( $assemble_fit['lead'] ) ) : ?>
							<p class="body-lead"><?php echo esc_html( $assemble_t( (string) $assemble_fit['lead'] ) ); ?></p>
						<?php endif; ?>
					</div>
					<div class="community-fit-groups">
						<?php foreach ( $assemble_fit['groups'] as $assemble_group ) : ?>
							<div class="card">
								<p class="eyebrow"><?php echo esc_html( (string) $assemble_group['label'] ); ?></p>
								<dl class="fit-list">
									<?php foreach ( (array) $assemble_group['items'] as $assemble_req ) : ?>
										<div>
											<dt><?php echo esc_html( $assemble_t( (string) $assemble_req[0] ) ); ?></dt>
											<dd><?php echo esc_html( $assemble_t( (string) $assemble_req[1] ) ); ?></dd>
										</div>
									<?php endforeach; ?>
								</dl>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			</div>
		</div>
	<?php endif; ?>

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

	<div class="community-band community-band-apply">
		<div class="wrap">
			<section class="community-apply" id="apply" aria-labelledby="community-apply">
				<div>
					<h2 id="community-apply"><?php echo esc_html( $assemble_t( (string) ( $assemble_closing['heading'] ?? '' ) ) ); ?></h2>
					<?php if ( ! empty( $assemble_closing['body'] ) ) : ?>
						<p><?php echo esc_html( $assemble_t( (string) $assemble_closing['body'] ) ); ?></p>
					<?php endif; ?>
					<a class="btn-solid" href="<?php echo esc_url( assemble_url( $assemble_apply ) ); ?>"><?php echo esc_html( $assemble_t( (string) ( $assemble_closing['cta'] ?? __( 'Apply to join', 'assemble' ) ) ) ); ?></a>
				</div>
				<?php if ( ! empty( $assemble_closing['image']['url'] ) ) : ?>
					<img class="community-apply-art" src="<?php echo esc_url( $assemble_closing['image']['url'] ); ?>" alt="" loading="lazy" decoding="async">
				<?php endif; ?>
			</section>
		</div>
	</div>

</div>
<?php
get_footer();
