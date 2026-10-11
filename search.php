<?php
/**
 * Search results: matching Insights as report cards (title and excerpt matches
 * only; see assemble_search_stories()), then matching pages and team members
 * as plain rows (the main query, paginated). No wireframe: on-system, flagged.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$assemble_terms   = get_search_query( false );
$assemble_stories = is_paged() ? array() : assemble_search_stories( $assemble_terms );
$assemble_pages   = (int) $GLOBALS['wp_query']->found_posts;
?>
<div class="page-shell search-page" data-area="neutral">
	<div class="wrap">
		<?php
		get_template_part(
			'template-parts/page-head',
			null,
			array(
				'title'  => assemble_label( 'search' ),
				'dek'    => '',
				'crumbs' => array(),
			)
		);
		?>
		<div class="search-page__form">
			<?php get_search_form(); ?>
			<?php if ( '' !== $assemble_terms ) : ?>
				<p class="meta search-page__summary" role="status">
					<?php
					if ( $assemble_stories || $assemble_pages ) {
						/* translators: %s: search terms. */
						printf( esc_html__( 'Results for “%s”', 'assemble' ), esc_html( $assemble_terms ) );
					} else {
						/* translators: %s: search terms. */
						printf( esc_html__( 'Nothing matched “%s”. Try fewer or different words.', 'assemble' ), esc_html( $assemble_terms ) );
					}
					?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $assemble_stories ) : ?>
			<section class="search-section" aria-labelledby="search-insights">
				<h2 class="stories-section-heading" id="search-insights"><?php echo esc_html( assemble_label( 'insights' ) ); ?></h2>
				<div class="related-grid">
					<?php
					foreach ( $assemble_stories as $assemble_story ) {
						get_template_part(
							'template-parts/modules/report-card',
							null,
							array(
								'story'   => $assemble_story,
								'heading' => 'h3',
							)
						);
					}
					?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<section class="search-section" aria-labelledby="search-pages">
				<h2 class="stories-section-heading" id="search-pages"><?php esc_html_e( 'Pages', 'assemble' ); ?></h2>
				<ul class="search-rows" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						$assemble_excerpt = 'team' === get_post_type() && function_exists( 'assemble_team_member' )
							? assemble_team_member( get_post() )['position']
							: wp_trim_words( get_the_excerpt(), 28 );
						?>
						<li class="search-row">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php if ( '' !== $assemble_excerpt ) : ?>
								<p><?php echo esc_html( $assemble_excerpt ); ?></p>
							<?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( 'Previous', 'assemble' ),
						'next_text' => __( 'Next', 'assemble' ),
					)
				);
				?>
			</section>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
