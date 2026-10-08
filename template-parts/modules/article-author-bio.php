<?php
/**
 * Module: article-author-bio (wireframe "4. Article"; full width per the
 * content-review edits). The report's author for now (decision #18: staff
 * author until a featured-voice field exists).
 *
 * @var array $args { report: view model }
 */

defined( 'ABSPATH' ) || exit;

$assemble_author = $args['report']['sections']['author'] ?? null;

if ( ! $assemble_author ) {
	return;
}
?>
<section class="author-bio" aria-labelledby="author-bio-name">
	<div class="portrait media-frame">
		<?php echo assemble_cf_image( $assemble_author['image'], '1:1', '110px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image(). ?>
	</div>
	<div>
		<div class="eyebrow"><?php esc_html_e( 'About the author', 'assemble' ); ?></div>
		<h3 id="author-bio-name"><?php echo esc_html( $assemble_author['name'] ); ?></h3>
		<?php if ( '' !== $assemble_author['bio'] ) : ?>
			<p><?php echo esc_html( $assemble_author['bio'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
