<?php
/**
 * Module: feed-discovery-prompt (web-style-guide.html → Web modules; wireframe
 * "8. Index (logged in)"). Up to three practice areas the person doesn't
 * follow, as area-coloured "Explore X →" links (wireframe edits: simplified
 * from the mockup's boxes).
 *
 * @var array $args { links: array<int,array{area:string,label:string,url:string}> }
 */

defined( 'ABSPATH' ) || exit;

$assemble_links = (array) ( $args['links'] ?? array() );
if ( ! $assemble_links ) {
	return;
}
?>
<section class="discovery">
	<div>
		<h2 class="discovery-title"><?php esc_html_e( 'Want to broaden your feed?', 'assemble' ); ?></h2>
		<p><?php esc_html_e( 'Follow more practice areas to bring their peer intelligence into this page, without losing the focus of what you follow now.', 'assemble' ); ?></p>
	</div>
	<div class="discovery-links">
		<?php foreach ( $assemble_links as $assemble_link ) : ?>
			<a class="link-arrow discover-link" data-area="<?php echo esc_attr( $assemble_link['area'] ); ?>" href="<?php echo esc_url( $assemble_link['url'] ); ?>"><?php echo esc_html( $assemble_link['label'] ); ?></a>
		<?php endforeach; ?>
	</div>
</section>
