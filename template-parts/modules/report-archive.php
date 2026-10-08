<?php
/**
 * The Insights index (/field-reports/) and Community archives
 * (/field-reports/community/<slug>/): a page head and a grid of report cards
 * from the main query, with pagination. No wireframe: on-system, flagged.
 *
 * @var array $args { title, intro, area, crumbs: [ { label, url } ] }
 */

defined( 'ABSPATH' ) || exit;

$assemble_crumbs = (array) ( $args['crumbs'] ?? array() );
?>
<div class="article-shell report-archive" data-area="<?php echo esc_attr( (string) ( $args['area'] ?? 'neutral' ) ); ?>">
	<div class="wrap">
		<?php if ( $assemble_crumbs ) : ?>
			<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'assemble' ); ?>">
				<ol>
					<?php foreach ( $assemble_crumbs as $assemble_crumb ) : ?>
						<li><a href="<?php echo esc_url( $assemble_crumb['url'] ); ?>"><?php echo esc_html( $assemble_crumb['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ol>
			</nav>
		<?php endif; ?>

		<header class="content-head archive-head">
			<h1><?php echo esc_html( (string) ( $args['title'] ?? '' ) ); ?></h1>
			<?php if ( ! empty( $args['intro'] ) ) : ?>
				<p class="article-dek"><?php echo esc_html( (string) $args['intro'] ); ?></p>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="related-grid archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part(
						'template-parts/modules/report-card',
						null,
						array(
							'story'   => assemble_story( get_post() ),
							'heading' => 'h2',
						)
					);
				endwhile;
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => __( 'Previous', 'assemble' ),
					'next_text' => __( 'Next', 'assemble' ),
				)
			);
			?>
		<?php else : ?>
			<p class="empty-state">
				<?php
				/* translators: %s: "Insights". */
				printf( esc_html__( 'New %s are on their way. Check back soon.', 'assemble' ), esc_html( assemble_label( 'insights' ) ) );
				?>
			</p>
		<?php endif; ?>
	</div>
</div>
