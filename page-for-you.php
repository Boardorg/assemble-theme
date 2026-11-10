<?php
/**
 * For You (/for-you/): the signed-in feed from wireframe "8. Index (logged in)".
 * assemble-core sends signed-out visitors to Sign in and keeps the page out of
 * caches and search; inc/for-you.php builds the view model.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$assemble_view = assemble_for_you_view();
?>
<main class="for-you-main" data-area="neutral">
	<div class="wrap">
		<?php get_template_part( 'template-parts/modules/logged-in-feed-hero', null, array( 'view' => $assemble_view ) ); ?>

		<?php if ( ! $assemble_view['available'] ) : ?>
			<p class="for-you-empty"><?php esc_html_e( 'Your feed is unavailable right now. Please try again shortly.', 'assemble' ); ?></p>
		<?php elseif ( $assemble_view['cards'] ) : ?>
			<?php
			$assemble_cards    = $assemble_view['cards'];
			$assemble_featured = 1 === $assemble_view['page'] ? array_splice( $assemble_cards, 0, 3 ) : array();
			if ( $assemble_featured ) {
				get_template_part( 'template-parts/modules/featured-intelligence-grid', null, array( 'cards' => $assemble_featured ) );
			}
			if ( $assemble_cards ) {
				get_template_part(
					'template-parts/modules/personalized-feed-rows',
					null,
					array(
						'cards'   => $assemble_cards,
						'heading' => __( 'More from the areas you follow', 'assemble' ),
					)
				);
			}
			?>
			<?php if ( $assemble_view['pages'] > 1 ) : ?>
				<nav class="feed-pager" aria-label="<?php esc_attr_e( 'More of your feed', 'assemble' ); ?>">
					<?php if ( $assemble_view['page'] > 1 ) : ?>
						<a class="link-arrow feed-pager-newer" href="<?php echo esc_url( assemble_for_you_page_url( $assemble_view, $assemble_view['page'] - 1 ) ); ?>"><?php esc_html_e( 'Newer', 'assemble' ); ?></a>
					<?php endif; ?>
					<?php if ( $assemble_view['page'] < $assemble_view['pages'] ) : ?>
						<a class="link-arrow feed-pager-older" href="<?php echo esc_url( assemble_for_you_page_url( $assemble_view, $assemble_view['page'] + 1 ) ); ?>"><?php esc_html_e( 'Older', 'assemble' ); ?></a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
		<?php elseif ( '' !== $assemble_view['current'] ) : ?>
			<p class="for-you-empty">
				<?php esc_html_e( 'Nothing here yet.', 'assemble' ); ?>
				<a class="link-arrow" href="<?php echo esc_url( $assemble_view['base_url'] ); ?>"><?php esc_html_e( 'Back to All for you', 'assemble' ); ?></a>
			</p>
		<?php else : ?>
			<?php if ( $assemble_view['chips'] ) : ?>
				<p class="for-you-empty"><?php esc_html_e( 'Nothing new yet from the areas you follow. Here’s the latest across Assemble.', 'assemble' ); ?></p>
			<?php endif; ?>
			<?php
			get_template_part(
				'template-parts/modules/personalized-feed-rows',
				null,
				array(
					'cards'   => $assemble_view['fallback'],
					'heading' => __( 'Latest across Assemble', 'assemble' ),
				)
			);
			?>
		<?php endif; ?>

		<?php
		if ( $assemble_view['available'] && $assemble_view['chips'] && $assemble_view['discover'] ) {
			get_template_part( 'template-parts/modules/feed-discovery-prompt', null, array( 'links' => $assemble_view['discover'] ) );
		}
		?>
	</div>
</main>
<?php
get_footer();
