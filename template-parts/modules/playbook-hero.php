<?php
/**
 * Module: playbook-hero (web-style-guide.html → Web modules; wireframe "5. Playbook").
 *
 * Breadcrumbs (Insights › practice area › Community) above a left-rule content
 * head: the "Playbook" tag, title, dek, edition and reading time, the share
 * row, and "Written by" / "Edited by". No image (the gallery's hero has none);
 * the featured image is the share image (inc/seo.php).
 *
 * @var array $args { report: view model, area: data-area key, url: permalink }
 */

defined( 'ABSPATH' ) || exit;

$assemble_report = (array) ( $args['report'] ?? array() );
$assemble_s      = (array) ( $assemble_report['sections'] ?? array() );
$assemble_title  = (string) ( $assemble_s['headline'] ?? '' );
$assemble_url    = (string) ( $args['url'] ?? '' );
$assemble_people = array_filter( (array) ( $assemble_s['people'] ?? array() ) );
$assemble_mins   = assemble_read_minutes( (int) ( $assemble_report['words'] ?? 0 ) );

$assemble_meta = array();
if ( isset( $assemble_s['edition'] ) ) {
	$assemble_meta[] = $assemble_s['edition'];
}
if ( $assemble_mins ) {
	/* translators: %d: minutes. */
	$assemble_meta[] = sprintf( _n( '%d min read', '%d min read', $assemble_mins, 'assemble' ), $assemble_mins );
}

$assemble_credits = array(
	/* translators: %s: person's name. */
	'author' => __( 'Written by %s', 'assemble' ),
	/* translators: %s: person's name. */
	'editor' => __( 'Edited by %s', 'assemble' ),
);
?>
<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'assemble' ); ?>">
	<ol>
		<?php foreach ( assemble_report_crumbs( $assemble_s['kicker'] ?? null ) as $assemble_crumb ) : ?>
			<li><a href="<?php echo esc_url( $assemble_crumb['url'] ); ?>"><?php echo esc_html( $assemble_crumb['label'] ); ?></a></li>
		<?php endforeach; ?>
	</ol>
</nav>

<header class="playbook-hero content-head" data-area="<?php echo esc_attr( (string) ( $args['area'] ?? 'neutral' ) ); ?>">
	<span class="tag tag-format playbook-type"><?php echo esc_html( assemble_label( 'playbook' ) ); ?></span>

	<h1 class="playbook-title"><?php echo esc_html( $assemble_title ); ?></h1>

	<?php if ( isset( $assemble_s['dek'] ) ) : ?>
		<p class="playbook-dek"><?php echo esc_html( $assemble_s['dek'] ); ?></p>
	<?php endif; ?>

	<?php if ( $assemble_meta ) : ?>
		<p class="playbook-edition meta"><?php echo esc_html( implode( ' · ', $assemble_meta ) ); ?></p>
	<?php endif; ?>

	<?php if ( '' !== $assemble_url ) : ?>
	<div class="share-row" aria-label="<?php esc_attr_e( 'Share this playbook', 'assemble' ); ?>">
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

	<?php if ( $assemble_people ) : ?>
		<div class="meta-row">
			<?php foreach ( $assemble_people as $assemble_role => $assemble_person ) : ?>
				<div class="author-card">
					<span class="author-photo media-frame">
						<?php echo assemble_cf_image( $assemble_person['image'], '1:1', '40px' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in assemble_cf_image(). ?>
					</span>
					<div>
						<div class="author-name"><?php echo esc_html( sprintf( $assemble_credits[ $assemble_role ] ?? '%s', $assemble_person['name'] ) ); ?></div>
						<?php if ( '' !== $assemble_person['role'] ) : ?>
							<div class="author-role"><?php echo esc_html( $assemble_person['role'] ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</header>
