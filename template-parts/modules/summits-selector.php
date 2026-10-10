<?php
/**
 * Module: summits-selector (wireframe 2, "Find your humans."): a practice-area
 * row and a Community row of pills, then one summits-practice-detail panel per
 * Community. A practice area with no Communities yet (Finance) gets one panel
 * of its own. Shares content-explorer.js and the explorer's pill styles with
 * the homepage, so the pills behave the same.
 *
 * Opens on the first Community with a summit to show. Without JavaScript the
 * opening panel shows and the pills do nothing.
 */

defined( 'ABSPATH' ) || exit;

$assemble_areas   = array();
$assemble_opening = '';
foreach ( assemble_practice_areas() as $assemble_key => $assemble_label ) {
	$assemble_communities = assemble_area_communities( $assemble_key );
	$assemble_panels      = array();

	foreach ( $assemble_communities ? $assemble_communities : array( null ) as $assemble_community ) {
		$assemble_id       = $assemble_community ? $assemble_community['slug'] : 'area-' . $assemble_key;
		$assemble_panels[] = array(
			'id'        => $assemble_id,
			'community' => $assemble_community,
			'summits'   => assemble_featured_summits( $assemble_community ? $assemble_community['slug'] : '', $assemble_key ),
		);
		if ( '' === $assemble_opening && end( $assemble_panels )['summits'] ) {
			$assemble_opening = $assemble_id;
		}
	}

	$assemble_areas[ $assemble_key ] = array(
		'label'       => $assemble_label,
		'communities' => $assemble_communities,
		'panels'      => $assemble_panels,
	);
}

// No summits anywhere: open on the first panel.
if ( '' === $assemble_opening ) {
	$assemble_opening = reset( $assemble_areas )['panels'][0]['id'] ?? '';
}

/** The panel id for a Community slug (or "area-<key>"). */
$assemble_panel_id = static fn ( string $id ): string => 'summits-' . sanitize_html_class( $id );
?>
<div class="summits-explorer" data-content-explorer>
	<section class="summits-selector" aria-labelledby="summits-selector-title">
		<div class="summits-selector-head">
			<h2 id="summits-selector-title"><?php esc_html_e( 'Find your humans.', 'assemble' ); ?></h2>
		</div>
		<div class="tab-buttons two-row-topic-nav">
			<div class="topic-row topic-row-primary" role="group" aria-label="<?php esc_attr_e( 'Practice areas', 'assemble' ); ?>">
				<?php foreach ( $assemble_areas as $assemble_key => $assemble_area ) : ?>
					<?php $assemble_pressed = in_array( $assemble_opening, array_column( $assemble_area['panels'], 'id' ), true ); ?>
					<button class="tab-btn tab-btn-primary" type="button" aria-pressed="<?php echo $assemble_pressed ? 'true' : 'false'; ?>" data-explorer-area="<?php echo esc_attr( $assemble_key ); ?>" data-explorer-target="<?php echo esc_attr( $assemble_panel_id( $assemble_area['panels'][0]['id'] ) ); ?>"><?php echo esc_html( $assemble_area['label'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="topic-row-secondary-wrap">
				<?php foreach ( $assemble_areas as $assemble_key => $assemble_area ) : ?>
					<?php
					if ( ! $assemble_area['communities'] ) {
						continue;
					}
					$assemble_open_row = in_array( $assemble_opening, array_column( $assemble_area['communities'], 'slug' ), true );
					?>
					<div class="topic-row topic-row-secondary" role="group" data-explorer-group="<?php echo esc_attr( $assemble_key ); ?>" aria-label="<?php echo esc_attr( $assemble_area['label'] . ' ' . assemble_label( 'communities' ) ); ?>"<?php echo $assemble_open_row ? '' : ' hidden'; ?>>
						<?php foreach ( $assemble_area['communities'] as $assemble_community ) : ?>
							<button class="tab-btn" type="button" aria-pressed="<?php echo $assemble_community['slug'] === $assemble_opening ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $assemble_panel_id( $assemble_community['slug'] ) ); ?>" data-explorer-target="<?php echo esc_attr( $assemble_panel_id( $assemble_community['slug'] ) ); ?>"><?php echo esc_html( $assemble_community['label'] ); ?></button>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php
	foreach ( $assemble_areas as $assemble_key => $assemble_area ) {
		foreach ( $assemble_area['panels'] as $assemble_panel ) {
			get_template_part(
				'template-parts/modules/summits-practice-detail',
				null,
				array(
					'id'         => $assemble_panel_id( $assemble_panel['id'] ),
					'area'       => $assemble_key,
					'area_label' => $assemble_area['label'],
					'community'  => $assemble_panel['community'],
					'summits'    => $assemble_panel['summits'],
					'open'       => $assemble_panel['id'] === $assemble_opening,
				)
			);
		}
	}
	?>
</div>
