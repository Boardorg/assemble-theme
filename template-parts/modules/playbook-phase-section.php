<?php
/**
 * Module: playbook-phase-section (wireframe "5. Playbook"). A phase chapter
 * ("Days 0–30: Diagnose and Orient"): the chapter heading, then subsections
 * and callouts (template-parts/playbook-blocks.php).
 *
 * @var array $args { chapter: from the view model }
 */

defined( 'ABSPATH' ) || exit;

$assemble_chapter = (array) ( $args['chapter'] ?? array() );
?>
<section class="phase-section" id="<?php echo esc_attr( (string) ( $assemble_chapter['id'] ?? '' ) ); ?>" data-phase="<?php echo (int) ( $assemble_chapter['number'] ?? 0 ); ?>">
	<?php get_template_part( 'template-parts/playbook-blocks', null, array( 'chapter' => $assemble_chapter ) ); ?>
</section>
