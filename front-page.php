<?php
/**
 * The homepage: a static composition (Mark's plan). To change the layout,
 * commit here; the few strings people change live in Site Settings.
 *
 * Logged out (wireframe 1, revised after David's review, Cale 2026-11-17): a
 * short who-we-are band with the proof figures, top stories, the member logos
 * and free-account call to action, then Explore peer intelligence. The
 * peer-intelligence splash is retired from the homepage (its template stays). The logged-in
 * homepage (wireframe 7) comes after launch; until then signed-in visitors see
 * the same page without the account prompts.
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/modules/home-identity' );
get_template_part( 'template-parts/modules/home-top-stories' );
get_template_part( 'template-parts/modules/home-trust' );
get_template_part( 'template-parts/modules/content-explorer' );

get_footer();
