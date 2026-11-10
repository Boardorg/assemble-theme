<?php
/**
 * Module: logged-in-feed-hero (web-style-guide.html → Web modules; wireframe
 * "8. Index (logged in)"). The greeting, the strip naming the interests that
 * shape the feed, the narrowing chips and the "All for you" hint. Someone who
 * follows nothing gets a "Choose what you follow" panel instead (no wireframe:
 * on-system, flagged for design).
 *
 * Chips are links (?for=…), so the page works without JavaScript and each
 * view has its own URL. The mockup's "Newest" chip is left out: the feed is
 * always newest first.
 *
 * @var array $args { view: from assemble_for_you_view() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_view = (array) ( $args['view'] ?? array() );
$assemble_name = (string) ( $assemble_view['name'] ?? '' );
?>
<div class="feed-hero">
	<div class="feed-head">
		<h1>
			<?php
			echo esc_html(
				'' !== $assemble_name
					/* translators: %s: the person's first name. */
					? sprintf( __( '%s, here’s your latest peer intelligence', 'assemble' ), $assemble_name )
					: __( 'Your latest peer intelligence', 'assemble' )
			);
			?>
		</h1>
		<p><?php esc_html_e( 'New peer intelligence from the Communities and practice areas you follow.', 'assemble' ); ?></p>
	</div>

	<?php if ( ! empty( $assemble_view['notice'] ) ) : ?>
		<div class="account-message feed-notice" role="status"><?php echo esc_html( $assemble_view['notice'] ); ?></div>
	<?php endif; ?>

	<?php if ( empty( $assemble_view['chips'] ) ) : ?>
		<?php if ( ! empty( $assemble_view['available'] ) ) : ?>
			<div class="personalization-strip personalization-strip--empty">
				<p><strong><?php esc_html_e( 'Choose what you follow.', 'assemble' ); ?></strong> <?php esc_html_e( 'Pick the practice areas and topics you care about, and this page fills with the newest peer intelligence from them.', 'assemble' ); ?></p>
				<a class="btn-solid" href="<?php echo esc_url( $assemble_view['manage_url'] ); ?>"><?php esc_html_e( 'Choose what you follow', 'assemble' ); ?></a>
			</div>
		<?php endif; ?>
		<?php return; ?>
	<?php endif; ?>

	<div class="personalization-strip">
		<p>
			<strong><?php esc_html_e( 'Personalized from your interests:', 'assemble' ); ?></strong>
			<?php
			/* translators: %s: the topics and practice areas the person follows. */
			echo esc_html( sprintf( __( '%s. Your feed updates as new peer intelligence is published.', 'assemble' ), $assemble_view['interests'] ) );
			?>
		</p>
		<a class="link-arrow manage" href="<?php echo esc_url( $assemble_view['manage_url'] ); ?>"><?php esc_html_e( 'Manage what you follow', 'assemble' ); ?></a>
	</div>

	<?php if ( count( $assemble_view['chips'] ) > 1 ) : ?>
		<nav class="feed-filters" aria-label="<?php esc_attr_e( 'Narrow your feed', 'assemble' ); ?>">
			<a class="feed-filter" href="<?php echo esc_url( $assemble_view['base_url'] ); ?>"<?php echo '' === $assemble_view['current'] ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All for you', 'assemble' ); ?></a>
			<?php foreach ( $assemble_view['chips'] as $assemble_key => $assemble_chip ) : ?>
				<a class="feed-filter" href="<?php echo esc_url( $assemble_chip['url'] ); ?>"<?php echo $assemble_key === $assemble_view['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $assemble_chip['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<p class="filter-hint"><?php esc_html_e( '“All for you” shows everything from the topics and areas you follow. Choose one to narrow the feed.', 'assemble' ); ?></p>
	<?php endif; ?>
</div>
