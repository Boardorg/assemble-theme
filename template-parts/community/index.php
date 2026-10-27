<?php
/**
 * Community page layout: the Communities index, /communities/ (hub
 * COMMUNITY-INDEX-HANDOFF.md §2). Seven bands in the Board page's band system:
 * the hero, the proof band (member count, the Community and practice counts,
 * member companies), the value points, "Find your Community" (the filter),
 * how membership works and "Is this you?" (the shared pillars and
 * requirements, as on every Board page), and the closing band.
 *
 * All copy is the page's own fields (Pages → Communities, "Index page" tab and
 * the hero, members and closing tabs) or Site Settings → Community defaults.
 * No breadcrumbs: it's the top level. Any band without data is left out.
 * No wireframe: on-system, flag for design.
 *
 * @var array $args { page: the view model from assemble_current_community_page() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_page      = (array) $args['page'];
$assemble_hero      = (array) ( $assemble_page['hero'] ?? array() );
$assemble_points    = (array) ( $assemble_page['points'] ?? array() );
$assemble_finder    = (array) ( $assemble_page['finder'] ?? array() );
$assemble_included  = (array) ( $assemble_page['included'] ?? array() );
$assemble_directory = function_exists( 'assemble_community_directory' ) ? assemble_community_directory() : array();
$assemble_count     = array_sum( array_map( 'count', $assemble_directory ) );
$assemble_practices = count( array_filter( $assemble_directory ) );
$assemble_find      = '' !== (string) ( $assemble_finder['heading'] ?? '' ) ? (string) $assemble_finder['heading'] : __( 'Find your Community', 'assemble' );
$assemble_t         = static fn( string $text ): string => assemble_community_text( $text, $assemble_page );

$assemble_stats = array(
	array( $assemble_count ? number_format_i18n( $assemble_count ) : '', _n( 'Community', 'Communities', $assemble_count, 'assemble' ) ),
	array( $assemble_practices ? number_format_i18n( $assemble_practices ) : '', _n( 'Practice', 'Practices', $assemble_practices, 'assemble' ) ),
);?>
<div class="community-page community-index" data-area="neutral">

	<div class="community-band community-band-hero">
		<div class="wrap">
			<section class="community-hero" aria-labelledby="community-title">
				<div class="community-hero-copy">
					<header class="content-head">
						<p class="eyebrow"><?php esc_html_e( 'Assemble Communities', 'assemble' ); ?></p>
						<h1 id="community-title"><?php echo esc_html( $assemble_t( (string) ( $assemble_hero['headline'] ?? '' ) ?: (string) $assemble_page['name'] ) ); ?></h1>
						<?php if ( ! empty( $assemble_hero['dek'] ) ) : ?>
							<p class="body-lead"><?php echo esc_html( $assemble_t( (string) $assemble_hero['dek'] ) ); ?></p>
						<?php endif; ?>
						<div class="community-cta-row">
							<a class="btn-solid" href="#find"><?php echo esc_html( $assemble_find ); ?></a>
							<?php if ( ! empty( $assemble_included['items'] ) ) : ?>
								<a class="link-arrow" href="#membership"><?php echo esc_html( $assemble_t( (string) $assemble_included['heading'] ) ); ?></a>
							<?php endif; ?>
						</div>
					</header>
				</div>
				<?php if ( ! empty( $assemble_hero['image']['url'] ) ) : ?>
					<div class="media-frame community-art">
						<img src="<?php echo esc_url( $assemble_hero['image']['url'] ); ?>" alt="<?php echo esc_attr( (string) ( $assemble_hero['image']['alt'] ?? '' ) ); ?>" loading="eager" decoding="async">
					</div>
				<?php endif; ?>
			</section>
		</div>
	</div>

	<?php get_template_part( 'template-parts/community/proof', null, array( 'page' => $assemble_page, 'stats' => $assemble_stats ) ); ?>

	<?php if ( ! empty( $assemble_points['items'] ) ) : ?>
		<div class="community-band">
			<div class="wrap">
				<section aria-labelledby="community-points">
					<?php if ( ! empty( $assemble_points['heading'] ) ) : ?>
						<h2 class="community-heading" id="community-points"><?php echo esc_html( $assemble_t( (string) $assemble_points['heading'] ) ); ?></h2>
					<?php endif; ?>
					<ol class="community-points<?php echo 4 === count( $assemble_points['items'] ) ? ' has-four' : ''; ?>"<?php echo empty( $assemble_points['heading'] ) ? ' aria-label="' . esc_attr__( 'Why join', 'assemble' ) . '"' : ''; ?>>
						<?php foreach ( $assemble_points['items'] as $assemble_point ) : ?>
							<li>
								<h3><?php echo esc_html( $assemble_t( (string) $assemble_point['title'] ) ); ?></h3>
								<?php if ( '' !== $assemble_point['body'] ) : ?>
									<p><?php echo esc_html( $assemble_t( (string) $assemble_point['body'] ) ); ?></p>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
				</section>
			</div>
		</div>
	<?php endif; ?>

	<div class="community-band is-sunken" id="find">
		<div class="wrap">
			<?php
			get_template_part(
				'template-parts/modules/community-explorer',
				null,
				array(
					'heading' => $assemble_find,
					'lead'    => (string) ( $assemble_finder['lead'] ?? '' ),
					'empty'   => (string) ( $assemble_finder['empty'] ?? '' ),
				)
			);
			?>
		</div>
	</div>

	<?php get_template_part( 'template-parts/community/included', null, array( 'page' => $assemble_page ) ); ?>

	<?php get_template_part( 'template-parts/community/fit', null, array( 'page' => $assemble_page ) ); ?>

	<?php get_template_part( 'template-parts/community/closing', null, array( 'page' => $assemble_page ) ); ?>

</div>
