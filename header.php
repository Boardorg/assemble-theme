<?php
/**
 * Public masthead. Theme-owned (Mark's plan): logo only, no date.
 * Phase 2 replaces this with the style guide's public-masthead module.
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
<header class="site-header">
	<div class="wrap">
		<a class="site-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
	</div>
</header>
<main class="site-main" id="main">
