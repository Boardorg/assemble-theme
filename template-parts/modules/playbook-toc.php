<?php
/**
 * Module: playbook-toc (wireframe "5. Playbook"). The chapters the view model
 * lists, each linking to its anchor (phases captioned "Phase N"), and the
 * follow card. Sticky beside the body on desktop; above it below 980.
 *
 * Signed out, the card asks the visitor to create an account to follow the
 * Community. Signed in, it links to the Account page's interest areas (no
 * wireframe: on-system). The public and denied views have no `toc`, so only
 * the card shows.
 *
 * @var array $args { report: view model, area: data-area key }
 */

defined( 'ABSPATH' ) || exit;

$assemble_s         = (array) ( $args['report']['sections'] ?? array() );
$assemble_toc       = (array) ( $assemble_s['toc'] ?? array() );
$assemble_community = $assemble_s['kicker'] ?? null;
$assemble_short     = $assemble_community ? assemble_community_label( $assemble_community ) : '';
?>
<aside class="toc" aria-label="<?php esc_attr_e( 'In this playbook', 'assemble' ); ?>">
	<div class="toc-card">
		<?php if ( $assemble_toc ) : ?>
			<div class="toc-title"><?php esc_html_e( 'In this playbook', 'assemble' ); ?></div>
			<?php foreach ( $assemble_toc as $assemble_item ) : ?>
				<a<?php echo 'phase' === $assemble_item['kind'] ? ' class="toc-phase"' : ''; ?> href="#<?php echo esc_attr( $assemble_item['id'] ); ?>">
					<?php echo esc_html( $assemble_item['title'] ); ?>
					<?php if ( 'phase' === $assemble_item['kind'] ) : ?>
						<span><?php echo esc_html( sprintf( assemble_label( 'phase' ), (int) $assemble_item['number'] ) ); ?></span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="toc-cta">
			<?php if ( is_user_logged_in() ) : ?>
				<p><?php esc_html_e( 'Choose the practice areas and Communities you follow on your Account page.', 'assemble' ); ?></p>
				<a class="link-arrow" href="<?php echo esc_url( assemble_account_link( 'account' ) . '#interest-' . assemble_area( (string) ( $assemble_community['practice_area'] ?? '' ) ) ); ?>"><?php echo esc_html( assemble_label( 'manage_follows' ) ); ?></a>
			<?php else : ?>
				<p>
					<?php
					if ( '' !== $assemble_short ) {
						/* translators: %s: Community short name, e.g. AEO. */
						printf( esc_html__( 'Follow %s to keep getting peer intelligence as this playbook evolves.', 'assemble' ), esc_html( $assemble_short ) );
					} else {
						esc_html_e( 'Create a free account to keep getting peer intelligence as this playbook evolves.', 'assemble' );
					}
					?>
				</p>
				<a class="link-arrow" href="<?php echo esc_url( assemble_account_link( 'register', (string) get_permalink() ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</aside>
