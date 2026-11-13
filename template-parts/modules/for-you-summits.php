<?php
/**
 * Module: for-you-summits. Upcoming Summits matched to what the person follows,
 * at the foot of the For You page (Kristen's v2 edits, Cale 2026-11-12; no
 * wireframe). Reuses the Summits page's event cards (summits-practice-detail),
 * each in its practice area's colour. Every link goes through
 * assemble_summit_url().
 *
 * @var array $args { summits: array from assemble_for_you_summits() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_summits = (array) ( $args['summits'] ?? array() );
if ( ! $assemble_summits ) {
	return;
}
?>
<section class="for-you-summits" aria-labelledby="for-you-summits-title">
	<div class="module-head">
		<h2 id="for-you-summits-title"><?php esc_html_e( 'Upcoming Summits for you', 'assemble' ); ?></h2>
		<a class="link-arrow" href="<?php echo esc_url( assemble_summits_url() ); ?>"><?php echo esc_html( assemble_label( 'all_summits' ) ); ?></a>
	</div>
	<div class="event-card-grid">
		<?php foreach ( $assemble_summits as $assemble_summit ) : ?>
			<?php $assemble_place = assemble_summit_place( $assemble_summit ); ?>
			<article class="event-card" data-area="<?php echo esc_attr( (string) $assemble_summit['area'] ); ?>">
				<h3><a href="<?php echo esc_url( assemble_summit_url( $assemble_summit ) ); ?>"><?php echo esc_html( (string) $assemble_summit['title'] ); ?></a></h3>
				<p class="event-when">
					<span><?php echo esc_html( assemble_summit_dates( $assemble_summit, 'short' ) ); ?></span>
					<?php if ( '' !== $assemble_place ) : ?>
						<span aria-hidden="true">·</span>
						<span><?php echo esc_html( $assemble_place ); ?></span>
					<?php endif; ?>
				</p>
				<p class="countdown"><?php echo esc_html( assemble_summit_countdown( $assemble_summit ) ); ?></p>
				<?php if ( '' !== (string) ( $assemble_summit['blurb'] ?? '' ) ) : ?>
					<p><?php echo esc_html( (string) $assemble_summit['blurb'] ); ?></p>
				<?php endif; ?>
				<a class="btn-solid" href="<?php echo esc_url( assemble_summit_url( $assemble_summit ) ); ?>"><?php echo esc_html( assemble_label( 'summit_details' ) ); ?><span class="screen-reader-text">: <?php echo esc_html( (string) $assemble_summit['title'] ); ?></span></a>
			</article>
		<?php endforeach; ?>
	</div>
</section>
