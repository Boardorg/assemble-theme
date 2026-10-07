<?php
/**
 * The homepage: a static composition (Mark's plan). To change the layout,
 * commit here; the few strings people change live in Site Settings.
 *
 * Logged out (wireframe 1): top stories, the peer-intelligence splash with the
 * free-account call to action, then Explore peer intelligence. The logged-in
 * homepage (wireframe 7) comes after launch; until then signed-in visitors see
 * the same page without the account prompts.
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/modules/home-top-stories' );
get_template_part( 'template-parts/modules/peer-intelligence-splash' );
get_template_part( 'template-parts/modules/content-explorer' );

get_footer();
