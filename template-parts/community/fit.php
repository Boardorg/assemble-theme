<?php
/**
 * Community page band: Is this you? The requirements as two cards, so the
 * right people self-select.
 * Shared by the Board and index layouts.
 *
 * @var array $args { page: a Community page view model }
 */

defined( 'ABSPATH' ) || exit;

$assemble_page = (array) $args['page'];
$assemble_fit  = (array) ( $assemble_page['fit'] ?? array() );
$assemble_t    = static fn( string $text ): string => assemble_community_text( $text, $assemble_page );

if ( empty( $assemble_fit['groups'] ) ) {
	return;
}
?>
<div class="community-band is-sunken">
	<div class="wrap">
		<section class="community-fit" aria-labelledby="community-fit">
			<div class="community-fit-head">
				<p class="eyebrow"><?php echo esc_html( (string) $assemble_fit['eyebrow'] ); ?></p>
				<h2 class="community-heading" id="community-fit"><?php echo esc_html( $assemble_t( (string) $assemble_fit['heading'] ) ); ?></h2>
				<?php if ( ! empty( $assemble_fit['lead'] ) ) : ?>
					<p class="body-lead"><?php echo esc_html( $assemble_t( (string) $assemble_fit['lead'] ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="community-fit-groups">
				<?php foreach ( $assemble_fit['groups'] as $assemble_group ) : ?>
					<div class="card">
						<p class="eyebrow"><?php echo esc_html( (string) $assemble_group['label'] ); ?></p>
						<dl class="fit-list">
							<?php foreach ( (array) $assemble_group['items'] as $assemble_req ) : ?>
								<div>
									<dt><?php echo esc_html( $assemble_t( (string) $assemble_req[0] ) ); ?></dt>
									<dd><?php echo esc_html( $assemble_t( (string) $assemble_req[1] ) ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	</div>
</div>
