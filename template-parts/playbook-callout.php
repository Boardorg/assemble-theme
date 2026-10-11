<?php
/**
 * One callout inside a playbook body (a Playbook Callout entry embedded in the
 * Rich Text, feed change #6), in the wireframe's markup: pull-stat, guidance
 * list, checklist card, "good looks like" checkpoint, manager note, and the
 * two-column table.
 *
 * @var array $args { callout: { kind, label, tag, stat, text, items, rows, columns } }
 */

defined( 'ABSPATH' ) || exit;

$assemble_c = (array) ( $args['callout'] ?? array() );

switch ( $assemble_c['kind'] ?? '' ) {
	case 'stat':
		?>
		<div class="pull-stat">
			<?php if ( '' !== $assemble_c['stat'] ) : ?>
				<div class="stat"><?php echo esc_html( $assemble_c['stat'] ); ?></div>
			<?php endif; ?>
			<p><?php echo esc_html( $assemble_c['text'] ); ?></p>
		</div>
		<?php
		break;

	case 'layers':
		?>
		<div class="guidance-stack">
			<span class="meta-label"><?php echo esc_html( '' !== $assemble_c['label'] ? $assemble_c['label'] : assemble_label( 'guidance_layers' ) ); ?></span>
			<ol>
				<?php foreach ( $assemble_c['rows'] as $assemble_row ) : ?>
					<li><span><?php echo esc_html( $assemble_row['label'] ); ?></span><strong><?php echo esc_html( $assemble_row['value'] ); ?></strong></li>
				<?php endforeach; ?>
			</ol>
		</div>
		<?php
		break;

	case 'checklist':
		?>
		<div class="action-card">
			<div class="action-head">
				<strong><?php echo esc_html( '' !== $assemble_c['label'] ? $assemble_c['label'] : __( 'Checklist', 'assemble' ) ); ?></strong>
				<?php if ( '' !== $assemble_c['tag'] ) : ?>
					<span><?php echo esc_html( $assemble_c['tag'] ); ?></span>
				<?php endif; ?>
			</div>
			<ul class="checklist">
				<?php foreach ( $assemble_c['items'] as $assemble_item ) : ?>
					<li><span class="box" aria-hidden="true"></span><span><?php echo esc_html( $assemble_item ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		break;

	case 'checkpoint':
		?>
		<div class="checkpoint">
			<?php if ( '' !== $assemble_c['label'] ) : ?>
				<div class="label"><?php echo esc_html( $assemble_c['label'] ); ?></div>
			<?php endif; ?>
			<p><?php echo esc_html( $assemble_c['text'] ); ?></p>
		</div>
		<?php
		break;

	case 'note':
		?>
		<div class="manager-note">
			<?php if ( '' !== $assemble_c['label'] ) : ?>
				<strong><?php echo esc_html( $assemble_c['label'] ); ?></strong>
			<?php endif; ?>
			<?php echo esc_html( $assemble_c['text'] ); ?>
		</div>
		<?php
		break;

	case 'table':
		?>
		<table class="readiness-table">
			<?php if ( 2 === count( $assemble_c['columns'] ) ) : ?>
				<thead><tr><th scope="col"><?php echo esc_html( $assemble_c['columns'][0] ); ?></th><th scope="col"><?php echo esc_html( $assemble_c['columns'][1] ); ?></th></tr></thead>
			<?php endif; ?>
			<tbody>
				<?php foreach ( $assemble_c['rows'] as $assemble_row ) : ?>
					<tr><th scope="row"><?php echo esc_html( $assemble_row['label'] ); ?></th><td><?php echo esc_html( $assemble_row['value'] ); ?></td></tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		break;
}
