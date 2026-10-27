<?php
/**
 * Template Name: Community page
 *
 * Pages under /communities/. The page's SCF fields (assemble-core) supply the
 * view model through `assemble/community_page`, and its Page type picks the
 * layout: Board and Council pages use template-parts/community/board.php, the
 * Communities index (/communities/) template-parts/community/index.php.
 * Practice-area pages render as standard pages until their layout exists.
 */

defined( 'ABSPATH' ) || exit;

$assemble_page = assemble_current_community_page();
$assemble_kind = assemble_community_layout();

if ( '' === $assemble_kind ) {
	require get_template_directory() . '/page.php';
	return;
}

get_header();
get_template_part( 'template-parts/community/' . $assemble_kind, null, array( 'page' => $assemble_page ) );
get_footer();
