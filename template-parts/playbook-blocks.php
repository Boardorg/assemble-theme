<?php
/**
 * One playbook chapter's heading and blocks, shared by playbook-intro-block and
 * playbook-phase-section. Blocks come from the feed's view model: the lead
 * paragraph, Rich Text HTML (rendered and escaped by the plugin), subsections
 * with their subtitles, and callouts (template-parts/playbook-callout.php).
 *
 * @var array $args { chapter: { id, title, kind, blocks } }
 */

defined( 'ABSPATH' ) || exit;

$assemble_chapter = (array) ( $args['chapter'] ?? array() );

if ( '' !== (string) ( $assemble_chapter['title'] ?? '' ) ) : ?>
	<div class="phase-kicker"><h2 class="days type-chapter"><?php echo esc_html( $assemble_chapter['title'] ); ?></h2><span class="line" aria-hidden="true"></span></div>
	<?php
endif;

foreach ( (array) ( $assemble_chapter['blocks'] ?? array() ) as $assemble_block ) {
	switch ( $assemble_block['type'] ?? '' ) {
		case 'lead':
			echo '<p class="lead">' . wp_kses_post( $assemble_block['html'] ) . '</p>';
			break;
		case 'html':
			echo wp_kses_post( $assemble_block['html'] );
			break;
		case 'subhead':
			printf( '<h3 id="%s">%s</h3>', esc_attr( $assemble_block['id'] ), esc_html( $assemble_block['title'] ) );
			if ( '' !== $assemble_block['subtitle'] ) {
				echo '<p class="phase-subtitle">' . esc_html( $assemble_block['subtitle'] ) . '</p>';
			}
			break;
		case 'callout':
			get_template_part( 'template-parts/playbook-callout', null, array( 'callout' => $assemble_block ) );
			break;
	}
}
