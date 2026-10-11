<?php
/**
 * Not found. A short page with search and the main destinations. No wireframe:
 * on-system and flagged for design.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="page-shell" data-area="neutral">
	<div class="wrap">
		<?php
		get_template_part(
			'template-parts/page-head',
			null,
			array(
				'title'  => __( 'Page not found', 'assemble' ),
				'dek'    => __( 'The page you’re looking for has moved or no longer exists. Try a search, or start from one of these.', 'assemble' ),
				'crumbs' => array(),
			)
		);
		?>
		<div class="page-content">
			<?php get_search_form(); ?>
			<ul class="destination-links" role="list">
				<li><a class="link-arrow" href="<?php echo esc_url( assemble_insights_url() ); ?>"><?php echo esc_html( assemble_label( 'insights' ) ); ?></a></li>
				<li><a class="link-arrow" href="<?php echo esc_url( assemble_summits_url() ); ?>"><?php echo esc_html( assemble_label( 'summits' ) ); ?></a></li>
				<li><a class="link-arrow" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'assemble' ); ?></a></li>
			</ul>
		</div>
	</div>
</div>
<?php
get_footer();
