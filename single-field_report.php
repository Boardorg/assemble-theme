<?php
/**
 * A Field Report (wireframe "4. Article"), rendered from the feed's view model.
 *
 * The plugin decides the view (standard, council, delegate, public, denied) and
 * hands over only the sections that view may show; the modules below print
 * whatever is present and never decide access themselves. If the feed is too
 * old to have a view model, the plugin's own document is used instead.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$assemble_report = assemble_report_view( get_the_ID() );

	if ( ! $assemble_report ) :
		?>
		<div class="wrap">
			<article <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
		</div>
		<?php
		continue;
	endif;

	$assemble_s         = $assemble_report['sections'];
	$assemble_community = $assemble_s['kicker'] ?? null;
	$assemble_area      = assemble_area( (string) ( $assemble_community['practice_area'] ?? '' ) );
	$assemble_args      = array(
		'report' => $assemble_report,
		'area'   => $assemble_area,
	);
	?>
	<div class="article-shell">
		<div class="wrap">
			<?php
			// Admin "Preview as" switcher and the beta bypass banner, from the plugin (empty for most visitors).
			$assemble_notices = $assemble_report['notices']['preview'] . $assemble_report['notices']['bypass'];
			if ( '' !== $assemble_notices ) {
				echo '<div class="report-notices">' . $assemble_notices . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped by the plugin.
			}
			?>
			<article <?php post_class( 'report report--' . $assemble_report['view'] ); ?>>
				<?php
				get_template_part( 'template-parts/modules/article-hero', null, $assemble_args );

				switch ( $assemble_report['view'] ) {
					case 'public':
					case 'denied':
						get_template_part( 'template-parts/modules/report-gate', null, $assemble_args );
						break;
					default:
						get_template_part( 'template-parts/modules/article-body-with-aside', null, $assemble_args );
						get_template_part( 'template-parts/modules/article-author-bio', null, $assemble_args );
				}

				get_template_part(
					'template-parts/modules/related-peer-intelligence',
					null,
					array(
						'area'      => $assemble_area,
						'community' => $assemble_community,
						'stories'   => assemble_related_stories( get_the_ID(), (string) ( $assemble_community['slug'] ?? '' ) ),
					)
				);

				if ( ! is_user_logged_in() ) {
					get_template_part( 'template-parts/modules/account-cta' );
				}
				?>
			</article>
		</div>
	</div>
	<?php
endwhile;

get_footer();
