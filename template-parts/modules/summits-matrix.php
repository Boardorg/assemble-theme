<?php
/**
 * Module: summits-matrix (wireframe 2, "All upcoming Summits"): one row per
 * upcoming summit, soonest first. Practice-area eyebrow and title, the full
 * date range and place (wireframe edits B.2: no period after the state, larger
 * text), the one-line description and "Reserve your seat". Rows take their
 * colour from the summit's practice area; Life Sciences stays neutral.
 *
 * @var array $args { summits: array from assemble_upcoming_summits() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_summits = (array) ( $args['summits'] ?? array() );
?>
<section class="summits-matrix" id="all-summits" aria-labelledby="all-summits-title">
	<div class="public-section-header public-section-header--borderless">
		<h2 id="all-summits-title"><?php esc_html_e( 'All upcoming Summits', 'assemble' ); ?></h2>
	</div>

	<?php if ( $assemble_summits ) : ?>
		<div class="matrix">
			<?php foreach ( $assemble_summits as $assemble_summit ) : ?>
				<article class="matrix-row" data-area="<?php echo esc_attr( $assemble_summit['area'] ); ?>">
					<div class="matrix-area">
						<?php if ( '' !== $assemble_summit['area_label'] ) : ?>
							<div class="eyebrow"><?php echo esc_html( $assemble_summit['area_label'] ); ?></div>
						<?php endif; ?>
						<h3><a href="<?php echo esc_url( assemble_summit_url( $assemble_summit ) ); ?>"><?php echo esc_html( $assemble_summit['title'] ); ?></a></h3>
					</div>
					<div class="matrix-copy">
						<p class="summit-date">
							<span><?php echo esc_html( assemble_summit_dates( $assemble_summit ) ); ?></span>
							<?php if ( '' !== assemble_summit_place( $assemble_summit ) ) : ?>
								<span aria-hidden="true">·</span>
								<span><?php echo esc_html( assemble_summit_place( $assemble_summit ) ); ?></span>
							<?php endif; ?>
						</p>
						<?php if ( '' !== $assemble_summit['blurb'] ) : ?>
							<p><?php echo esc_html( $assemble_summit['blurb'] ); ?></p>
						<?php endif; ?>
					</div>
					<div class="matrix-actions">
						<a class="btn-solid" href="<?php echo esc_url( assemble_summit_url( $assemble_summit, 'register' ) ); ?>"><?php echo esc_html( assemble_label( 'reserve_seat' ) ); ?><span class="screen-reader-text">: <?php echo esc_html( $assemble_summit['title'] ); ?></span></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p class="summits-empty"><?php esc_html_e( 'The next Summit dates are on their way.', 'assemble' ); ?></p>
	<?php endif; ?>
</section>
