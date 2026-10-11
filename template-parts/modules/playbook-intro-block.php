<?php
/**
 * Module: playbook-intro-block (wireframe "5. Playbook"). A chapter that isn't
 * a phase: Executive Summary, How to Use This Guide, and the closing chapters.
 * The H2 chapter style, then its blocks (template-parts/playbook-blocks.php).
 *
 * @var array $args { chapter: from the view model }
 */

defined( 'ABSPATH' ) || exit;

$assemble_chapter = (array) ( $args['chapter'] ?? array() );
?>
<section class="intro-block"<?php echo '' !== (string) ( $assemble_chapter['id'] ?? '' ) ? ' id="' . esc_attr( $assemble_chapter['id'] ) . '"' : ''; ?>>
	<?php get_template_part( 'template-parts/playbook-blocks', null, array( 'chapter' => $assemble_chapter ) ); ?>
</section>
