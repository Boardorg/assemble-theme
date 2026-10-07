<?php
/**
 * Module: ld-benchmark-card (web-style-guide.html → Web modules → Peer intelligence).
 *
 * Filled from Contentful's active "Homepage hero" Site Feature (today: the
 * Decision Intelligence Benchmark). Badge, headline, blurb, one pill action,
 * and "See all benchmarks" below.
 *
 * @var array $args { feature: array from AFR_Features::homepage_hero() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_feature = (array) ( $args['feature'] ?? array() );
$assemble_cta_url = class_exists( 'AFR_Features' ) ? AFR_Features::url( (string) ( $assemble_feature['cta_url'] ?? '' ) ) : '';
?>
<div class="ld-card ld-benchmark-card">
	<div class="ld-card-header">
		<h4 class="ld-card-title"><?php esc_html_e( 'Latest benchmark', 'assemble' ); ?></h4>
	</div>
	<div class="ld-benchmark-body">
		<div>
			<?php if ( ! empty( $assemble_feature['badge'] ) ) : ?>
				<span class="status-pill"><?php echo esc_html( (string) $assemble_feature['badge'] ); ?></span>
			<?php endif; ?>
			<h5><?php echo esc_html( (string) ( $assemble_feature['headline'] ?? '' ) ); ?></h5>
		</div>
		<?php if ( ! empty( $assemble_feature['blurb'] ) ) : ?>
			<p><?php echo esc_html( (string) $assemble_feature['blurb'] ); ?></p>
		<?php endif; ?>
		<div class="benchmark-bars" aria-hidden="true"><span></span><span></span><span></span></div>
		<?php if ( $assemble_cta_url && ! empty( $assemble_feature['cta_label'] ) ) : ?>
			<div class="ld-cta-row">
				<a class="btn-solid" href="<?php echo esc_url( $assemble_cta_url ); ?>"><?php echo esc_html( (string) $assemble_feature['cta_label'] ); ?></a>
			</div>
		<?php endif; ?>
	</div>
	<div class="ld-card-footer">
		<a class="link-arrow" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'benchmark_url', '' ) ) ); ?>"><?php esc_html_e( 'See all benchmarks', 'assemble' ); ?></a>
	</div>
</div>
