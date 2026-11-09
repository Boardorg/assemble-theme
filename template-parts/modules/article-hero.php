<?php
/**
 * Module: article-hero (web-style-guide.html → Web modules; wireframe "4. Article").
 *
 * Breadcrumbs (Insights › practice area › Community), then the content head:
 * topic tag, title, dek, date and read time under the dek, the share row, and
 * the inline author (hedcut, assemble_person_avatar()). Then the hero image at
 * 16:9 with its "About this photo" caption and source line at the image's full
 * width (IMAGE-STYLE-SPEC §2–4). Every part prints only if the view
 * model has it: the public and denied views have no byline, for example.
 *
 * @var array $args { report: view model, area: data-area key, url: permalink ('' in a draft preview: no share row) }
 */

defined( 'ABSPATH' ) || exit;

$assemble_report = (array) ( $args['report'] ?? array() );
$assemble_s      = (array) ( $assemble_report['sections'] ?? array() );
$assemble_area   = (string) ( $args['area'] ?? 'neutral' );
$assemble_topic  = $assemble_s['topics'][0] ?? null;
$assemble_author = $assemble_s['byline'] ?? null;
$assemble_title  = (string) ( $assemble_s['headline'] ?? '' );
$assemble_url    = (string) ( $args['url'] ?? '' );
$assemble_crumbs = assemble_report_crumbs( $assemble_s['kicker'] ?? null );
$assemble_mins   = assemble_read_minutes( (int) ( $assemble_report['words'] ?? 0 ) );
// The featured image's "About this photo" caption and source credit (feed 2.1.0+), only with an image.
$assemble_note   = isset( $assemble_s['image'] ) ? ( $assemble_s['image_note'] ?? null ) : null;

$assemble_when = array();
if ( isset( $assemble_s['date'] ) ) {
	$assemble_when[] = sprintf( '<time datetime="%s">%s</time>', esc_attr( $assemble_s['date']['iso'] ), esc_html( $assemble_s['date']['display'] ) );
}
if ( $assemble_mins ) {
	/* translators: %d: minutes. */
	$assemble_when[] = esc_html( sprintf( _n( '%d min read', '%d min read', $assemble_mins, 'assemble' ), $assemble_mins ) );
}
?>
<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'assemble' ); ?>">
	<ol>
		<?php foreach ( $assemble_crumbs as $assemble_crumb ) : ?>
			<li><a href="<?php echo esc_url( $assemble_crumb['url'] ); ?>"><?php echo esc_html( $assemble_crumb['label'] ); ?></a></li>
		<?php endforeach; ?>
	</ol>
</nav>

<header class="article-hero content-head" data-area="<?php echo esc_attr( $assemble_area ); ?>">
	<?php if ( $assemble_topic ) : ?>
		<?php echo assemble_topic_tag( $assemble_topic, 'article-category' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in assemble_topic_tag(). ?>
	<?php endif; ?>

	<h1 class="article-title"><?php echo esc_html( $assemble_title ); ?></h1>

	<?php if ( isset( $assemble_s['dek'] ) ) : ?>
		<p class="article-dek"><?php echo esc_html( $assemble_s['dek'] ); ?></p>
	<?php endif; ?>

	<?php if ( $assemble_when ) : ?>
		<p class="article-date meta"><?php echo implode( ' · ', $assemble_when ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></p>
	<?php endif; ?>

	<?php if ( '' !== $assemble_url ) : ?>
	<div class="share-row" aria-label="<?php esc_attr_e( 'Share this Insight', 'assemble' ); ?>">
		<a class="share-link" href="<?php echo esc_url( add_query_arg( 'url', rawurlencode( $assemble_url ), 'https://www.linkedin.com/sharing/share-offsite/' ) ); ?>" target="_blank" rel="noopener">
			<?php echo assemble_icon( 'linkedin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><span><?php esc_html_e( 'LinkedIn', 'assemble' ); ?></span>
		</a>
		<a class="share-link" href="<?php echo esc_url( 'mailto:?subject=' . rawurlencode( $assemble_title ) . '&body=' . rawurlencode( $assemble_url ) ); ?>">
			<?php echo assemble_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><span><?php esc_html_e( 'Email', 'assemble' ); ?></span>
		</a>
		<button class="share-link" type="button" data-copy-link="<?php echo esc_url( $assemble_url ); ?>" data-copied-label="<?php esc_attr_e( 'Copied', 'assemble' ); ?>">
			<?php echo assemble_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><span><?php esc_html_e( 'Copy link', 'assemble' ); ?></span>
		</button>
	</div>
	<?php endif; ?>

	<?php if ( $assemble_author ) : ?>
		<div class="article-meta-row">
			<div class="author-inline">
				<span class="author-avatar media-frame media-avatar">
					<?php echo assemble_person_avatar( $assemble_author, '44px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_person_avatar(). ?>
				</span>
				<div class="article-meta-copy">
					<strong><?php echo esc_html( $assemble_author['name'] ); ?></strong>
					<?php if ( '' !== $assemble_author['role'] ) : ?>
						<span><?php echo esc_html( $assemble_author['role'] ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</header>

<figure class="hero-figure" data-area="<?php echo esc_attr( $assemble_area ); ?>">
	<div class="hero-image media-frame">
		<?php echo assemble_cf_image( $assemble_s['image'] ?? null, '16:9', '(max-width: 1200px) 100vw, 1120px', '', 'eager' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image(). ?>
	</div>
	<?php if ( $assemble_note ) : ?>
		<figcaption class="image-note">
			<?php if ( '' !== $assemble_note['caption'] ) : ?>
				<p class="image-note-story"><span class="image-note-label"><?php esc_html_e( 'About this photo', 'assemble' ); ?></span> <?php echo esc_html( $assemble_note['caption'] ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $assemble_note['credit'] ) : ?>
				<p class="image-note-source"><span><?php esc_html_e( 'Source:', 'assemble' ); ?></span> <?php echo esc_html( $assemble_note['credit'] ); ?></p>
			<?php endif; ?>
		</figcaption>
	<?php endif; ?>
</figure>
