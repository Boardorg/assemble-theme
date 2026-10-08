<?php
/**
 * Assemble theme bootstrap. Requires inc/*.php only; no logic here.
 */

defined( 'ABSPATH' ) || exit;

define( 'ASSEMBLE_THEME_VERSION', '0.4.0' );

require_once get_template_directory() . '/inc/labels.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/content.php';
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/seo.php';
