<?php
/**
 * Module: home-top-stories (web-style-guide.html → Web modules → Peer intelligence).
 *
 * One lead story and three supporting stories, from the feed: featured reports
 * in their Contentful rank, then the newest. The section heading outranks the
 * story titles; "See more top stories" sits under the supporting list.
 */

defined( 'ABSPATH' ) || exit;

$assemble_stories = assemble_top_stories( 4 );
$assemble_lead    = array_shift( $assemble_stories );
?>
<section class="home-top-stories wrap" aria-labelledby="home-top-stories-title">
	<div class="widget">
		<h1 class="stories-section-heading" id="home-top-stories-title"><?php esc_html_e( 'Top stories across Assemble', 'assemble' ); ?></h1>

		<?php if ( ! $assemble_lead ) : ?>
			<p class="empty-state"><?php esc_html_e( 'New stories are on their way. Check back soon.', 'assemble' ); ?></p>
		<?php else : ?>
			<div class="featured-split-grid">
				<article class="featured-main" data-area="<?php echo esc_attr( $assemble_lead['area'] ); ?>">
					<a class="featured-image media-frame" href="<?php echo esc_url( $assemble_lead['url'] ); ?>" tabindex="-1" aria-hidden="true">
						<?php
						$assemble_img = assemble_cf_image( $assemble_lead['image'], '16:9', '(max-width: 980px) 100vw, 800px', '', 'eager' );
						echo $assemble_img ? $assemble_img : '<span class="img-x"></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image().
						?>
					</a>
					<?php if ( $assemble_lead['topic'] ) : ?>
						<span class="tag tag-topic" data-topic-slug="<?php echo esc_attr( $assemble_lead['topic']['slug'] ); ?>"><?php echo esc_html( $assemble_lead['topic']['name'] ); ?></span>
					<?php endif; ?>
					<h2 class="featured-headline type-feature-title"><a href="<?php echo esc_url( $assemble_lead['url'] ); ?>"><?php echo esc_html( $assemble_lead['title'] ); ?></a></h2>
					<?php if ( $assemble_lead['excerpt'] ) : ?>
						<p class="featured-excerpt"><?php echo esc_html( $assemble_lead['excerpt'] ); ?></p>
					<?php endif; ?>
				</article>

				<div class="featured-side-list">
					<?php foreach ( $assemble_stories as $assemble_story ) : ?>
						<article class="feed-card" data-area="<?php echo esc_attr( $assemble_story['area'] ); ?>">
							<a class="featured-image media-frame" href="<?php echo esc_url( $assemble_story['url'] ); ?>" tabindex="-1" aria-hidden="true">
								<?php
								$assemble_img = assemble_cf_image( $assemble_story['image'], '16:9', '160px' );
								echo $assemble_img ? $assemble_img : '<span class="img-x"></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image().
								?>
							</a>
							<?php if ( $assemble_story['topic'] ) : ?>
								<span class="tag tag-topic" data-topic-slug="<?php echo esc_attr( $assemble_story['topic']['slug'] ); ?>"><?php echo esc_html( $assemble_story['topic']['name'] ); ?></span>
							<?php endif; ?>
							<h2 class="type-h4"><a href="<?php echo esc_url( $assemble_story['url'] ); ?>"><?php echo esc_html( $assemble_story['title'] ); ?></a></h2>
							<?php if ( $assemble_story['excerpt'] ) : ?>
								<p><?php echo esc_html( wp_trim_words( $assemble_story['excerpt'], 16 ) ); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
					<a class="link-arrow featured-more" href="<?php echo esc_url( assemble_insights_url() ); ?>"><?php esc_html_e( 'See more top stories', 'assemble' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
