<?php
/**
 * The head of a standard page: breadcrumbs when the page has parents, then the
 * content head (title in sentence case, as editors write it; the excerpt, if
 * set, as the dek).
 *
 * @var array $args { title?: string, dek?: string, crumbs?: [ { label, url } ] } Defaults from the current page.
 */

defined( 'ABSPATH' ) || exit;

$assemble_post   = get_post();
$assemble_title  = (string) ( $args['title'] ?? get_the_title() );
$assemble_dek    = (string) ( $args['dek'] ?? ( $assemble_post && has_excerpt( $assemble_post ) ? get_the_excerpt( $assemble_post ) : '' ) );
$assemble_crumbs = $args['crumbs'] ?? null;

if ( null === $assemble_crumbs ) {
	$assemble_crumbs = array();
	if ( $assemble_post && $assemble_post->post_parent ) {
		foreach ( array_reverse( get_post_ancestors( $assemble_post ) ) as $assemble_ancestor ) {
			$assemble_crumbs[] = array(
				'label' => get_the_title( $assemble_ancestor ),
				'url'   => get_permalink( $assemble_ancestor ),
			);
		}
	}
}
?>
<?php if ( $assemble_crumbs ) : ?>
	<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'assemble' ); ?>">
		<ol>
			<?php foreach ( $assemble_crumbs as $assemble_crumb ) : ?>
				<li><a href="<?php echo esc_url( $assemble_crumb['url'] ); ?>"><?php echo esc_html( $assemble_crumb['label'] ); ?></a></li>
			<?php endforeach; ?>
			<li><span aria-current="page"><?php echo esc_html( $assemble_title ); ?></span></li>
		</ol>
	</nav>
<?php endif; ?>
<header class="content-head page-head">
	<h1><?php echo esc_html( $assemble_title ); ?></h1>
	<?php if ( '' !== $assemble_dek ) : ?>
		<p class="page-dek"><?php echo esc_html( $assemble_dek ); ?></p>
	<?php endif; ?>
</header>
