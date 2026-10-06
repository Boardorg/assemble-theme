<?php
/**
 * Document head and the public masthead. Theme-owned (Mark's plan).
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-area="neutral">
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'assemble' ); ?></a>
<?php get_template_part( 'template-parts/modules/public-masthead' ); ?>
<main class="site-main" id="main">
