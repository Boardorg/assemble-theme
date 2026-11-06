<?php
/**
 * Our Team (/our-team/): a copy-only hero (the page's title as the eyebrow, its
 * content's text as the headline, as production's H1), everyone in the `team` post type
 * (assemble-core) in the editors' order, then the banded pages' closing card.
 * People data stays in WordPress. No wireframe: on-system and flagged for design.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$assemble_people   = assemble_team();
	$assemble_headline = trim( wp_strip_all_tags( (string) get_the_content() ) );
	?>
	<div class="page-bands" data-area="neutral">
		<section class="page-band page-band-hero">
			<div class="wrap">
				<div class="wp-block-columns band-hero is-copy-only">
					<div class="wp-block-column">
						<p class="eyebrow"><?php the_title(); ?></p>
						<h1><?php echo esc_html( '' !== $assemble_headline ? $assemble_headline : get_the_title() ); ?></h1>
						<?php if ( $assemble_people ) : ?>
							<p class="is-style-lead">
								<?php
								/* translators: %d: number of people on the team. */
								echo esc_html( sprintf( _n( 'The %d person behind Assemble’s Communities and Summits.', 'The %d people behind Assemble’s Communities and Summits.', count( $assemble_people ), 'assemble' ), count( $assemble_people ) ) );
								?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<section class="page-band is-surface page-band-team" aria-label="<?php esc_attr_e( 'Team members', 'assemble' ); ?>">
			<div class="wrap">
				<?php get_template_part( 'template-parts/modules/team-grid', null, array( 'people' => $assemble_people ) ); ?>
			</div>
		</section>

		<?php get_template_part( 'template-parts/closing-cta' ); ?>
	</div>
	<?php
endwhile;

get_footer();
