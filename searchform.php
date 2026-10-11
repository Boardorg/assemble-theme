<?php
/**
 * The search form (search page, 404). No wireframe: on-system, flagged.
 */

defined( 'ABSPATH' ) || exit;

$assemble_field = wp_unique_id( 'search-field-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $assemble_field ); ?>"><?php esc_html_e( 'Search for', 'assemble' ); ?></label>
	<input class="field-input" type="search" id="<?php echo esc_attr( $assemble_field ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search Insights and pages', 'assemble' ); ?>">
	<button class="btn-solid" type="submit"><?php echo esc_html( assemble_label( 'search' ) ); ?></button>
</form>
