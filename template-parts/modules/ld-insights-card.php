<?php
/**
 * Module: ld-insights-card (web-style-guide.html → Web modules → Peer intelligence).
 *
 * Up to three Insights from one Community: thumbnail, topic tag, headline and
 * date, then one "Explore all" link below the list. The mockup's attribution
 * line (a member's name, title and company) has no data source yet, so the
 * report date stands in for it.
 *
 * @var array $args { community: array from assemble_area_communities() plus 'stories' }
 */

defined( 'ABSPATH' ) || exit;

$assemble_community = (array) ( $args['community'] ?? array() );
$assemble_stories   = (array) ( $assemble_community['stories'] ?? array() );
$assemble_label     = (string) ( $assemble_community['label'] ?? '' );
?>
<div class="ld-card ld-insights-card">
	<div class="ld-card-header">
		<h4 class="ld-card-title">
			<?php
			/* translators: %s: Community label, e.g. "AEO". */
			printf( esc_html__( 'Insights from %s leaders', 'assemble' ), esc_html( $assemble_label ) );
			?>
		</h4>
	</div>

	<?php if ( $assemble_stories ) : ?>
		<ul class="ld-insight-list">
			<?php foreach ( $assemble_stories as $assemble_story ) : ?>
				<li class="ld-insight-item">
					<a class="hedcut-thumb media-frame" href="<?php echo esc_url( $assemble_story['url'] ); ?>" tabindex="-1" aria-hidden="true">
						<?php
						$assemble_img = assemble_cf_image( $assemble_story['image'], '16:9', '160px' );
						echo $assemble_img ? $assemble_img : '<span class="img-x"></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image().
						?>
					</a>
					<div>
						<?php if ( $assemble_story['topic'] ) : ?>
							<span class="tag tag-topic" data-topic-slug="<?php echo esc_attr( $assemble_story['topic']['slug'] ); ?>"><?php echo esc_html( $assemble_story['topic']['name'] ); ?></span>
						<?php endif; ?>
						<h5><a href="<?php echo esc_url( $assemble_story['url'] ); ?>"><?php echo esc_html( $assemble_story['title'] ); ?></a></h5>
						<p class="ld-person meta"><?php echo esc_html( $assemble_story['date'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="ld-card-footer">
			<a class="link-arrow" href="<?php echo esc_url( assemble_community_url( (string) $assemble_community['slug'] ) ); ?>">
				<?php
				/* translators: 1: Community label, 2: "Insights". */
				printf( esc_html__( 'Explore all %1$s %2$s', 'assemble' ), esc_html( $assemble_label ), esc_html( assemble_label( 'insights' ) ) );
				?>
			</a>
		</div>
	<?php else : ?>
		<p class="empty-state">
			<?php
			/* translators: 1: Community name, 2: "Insights". */
			printf( esc_html__( 'The first %2$s from the %1$s are on their way.', 'assemble' ), esc_html( (string) ( $assemble_community['name'] ?? '' ) ), esc_html( assemble_label( 'insights' ) ) );
			?>
		</p>
		<div class="ld-card-footer">
			<a class="link-arrow" href="<?php echo esc_url( assemble_insights_url() ); ?>">
				<?php
				/* translators: %s: "Insights". */
				printf( esc_html__( 'Explore all %s', 'assemble' ), esc_html( assemble_label( 'insights' ) ) );
				?>
			</a>
		</div>
	<?php endif; ?>
</div>
