<?php
/**
 * Shared by featured-intelligence-grid and personalized-feed-rows: the
 * content-type icon, type and date ("Field Report · Aug 13, 2026").
 *
 * @var array $args { card: from assemble_for_you_card() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_card = (array) ( $args['card'] ?? array() );
$assemble_type = (array) ( $assemble_card['type'] ?? array() );
?>
<p class="story-meta">
	<?php echo assemble_icon( (string) ( $assemble_type['icon'] ?? 'doc' ), 'format-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in assemble_icon(). ?>
	<span><?php echo esc_html( implode( ' · ', array_filter( array( (string) ( $assemble_type['label'] ?? '' ), (string) ( $assemble_card['date'] ?? '' ) ) ) ) ); ?></span>
</p>
