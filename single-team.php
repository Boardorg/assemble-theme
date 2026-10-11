<?php
/**
 * One person (/our-team/<slug>/): headshot, name, title and bio, from the `team`
 * post type (assemble-core). No wireframe: on-system and flagged for design.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$assemble_person = assemble_team_person( get_post() );
	$assemble_team   = assemble_team_url();
	?>
	<div class="page-shell" data-area="neutral">
		<div class="wrap">
			<?php
			get_template_part(
				'template-parts/page-head',
				null,
				array(
					'title'  => $assemble_person['name'],
					'dek'    => $assemble_person['position'],
					'crumbs' => array(
						array(
							'label' => __( 'Our team', 'assemble' ),
							'url'   => $assemble_team,
						),
					),
				)
			);
			?>
			<div class="team-profile">
				<div class="team-profile__photo media-frame media-square">
					<?php
					if ( $assemble_person['image_id'] ) {
						echo wp_get_attachment_image(
							$assemble_person['image_id'],
							'assemble-s4',
							false,
							array(
								'sizes' => '(max-width: 640px) 100vw, 320px',
								'alt'   => '',
							)
						);
					}
					?>
				</div>
				<div class="team-profile__bio page-content">
					<?php echo wp_kses_post( wpautop( $assemble_person['bio'] ) ); ?>
					<p><a class="link-arrow" href="<?php echo esc_url( $assemble_team ); ?>"><?php esc_html_e( 'Meet the rest of the team', 'assemble' ); ?></a></p>
				</div>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
