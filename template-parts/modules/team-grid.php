<?php
/**
 * The team grid on /our-team/: headshot, name and title per person. When they have
 * a bio, the photo and name link to the person's page (one tab stop: the name). No wireframe: on-system, flagged.
 *
 * @var array $args { people: from assemble_team() }
 */

defined( 'ABSPATH' ) || exit;

$assemble_people = (array) ( $args['people'] ?? array() );

if ( ! $assemble_people ) {
	return;
}
?>
<ul class="team-grid" role="list">
	<?php foreach ( $assemble_people as $assemble_person ) : ?>
		<li class="team-card">
			<?php $assemble_linked = '' !== $assemble_person['bio']; ?>
			<?php if ( $assemble_linked ) : ?>
				<a class="team-card__photo-link" href="<?php echo esc_url( $assemble_person['url'] ); ?>" tabindex="-1" aria-hidden="true">
			<?php endif; ?>
			<div class="team-card__photo media-frame media-square">
				<?php
				if ( $assemble_person['image_id'] ) {
					echo wp_get_attachment_image(
						$assemble_person['image_id'],
						'assemble-s3',
						false,
						array(
							'sizes'   => '(max-width: 640px) 50vw, (max-width: 980px) 33vw, 260px',
							'alt'     => '',
							'loading' => 'lazy',
						)
					);
				}
				?>
			</div>
			<?php if ( $assemble_linked ) : ?>
				</a>
			<?php endif; ?>
			<h2 class="team-card__name">
				<?php if ( $assemble_linked ) : ?>
					<a href="<?php echo esc_url( $assemble_person['url'] ); ?>"><?php echo esc_html( $assemble_person['name'] ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $assemble_person['name'] ); ?>
				<?php endif; ?>
			</h2>
			<?php if ( '' !== $assemble_person['position'] ) : ?>
				<p class="team-card__role"><?php echo esc_html( $assemble_person['position'] ); ?></p>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
