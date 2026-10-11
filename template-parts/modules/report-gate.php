<?php
/**
 * The gate for the public and denied views. No wireframe: built on-system from
 * the guide's pieces (the "Board Members only" access tag, a card, pill
 * buttons) and flagged for design in the theme handoff.
 *
 * Public: the attribution line and the opening paragraphs the feed allows,
 * then the join prompt. Denied: the source line and the members-only gate.
 * The beta bypass button appears only when the feed offers it. A signed-in
 * free account gets "Ask about membership" (the membership_url Site Setting)
 * instead of Sign in.
 *
 * Metered: the free-read meter's prompt for a Public report (Phase 7, no
 * wireframe): the opening paragraphs, then Create free account and Sign in.
 *
 * Playbooks (kind 'playbook') use the same gate with playbook wording; their
 * public view prints the first chapter itself, so it has no `setup` here.
 *
 * @var array $args { report: view model, area: data-area key, metered?: bool, kind?: 'report'|'playbook' }
 */

defined( 'ABSPATH' ) || exit;

$assemble_report = (array) ( $args['report'] ?? array() );
$assemble_s      = (array) ( $assemble_report['sections'] ?? array() );
$assemble_public = 'public' === ( $assemble_report['view'] ?? '' );
$assemble_metered = ! empty( $args['metered'] );
$assemble_book   = 'playbook' === ( $args['kind'] ?? 'report' );
$assemble_join   = (array) ( $assemble_s['join'] ?? array() );
$assemble_gate   = (array) ( $assemble_s['gate'] ?? array() );
$assemble_bypass = $assemble_public ? ( $assemble_join['bypass'] ?? null ) : ( $assemble_gate['bypass'] ?? null );
$assemble_here   = (string) get_permalink();
$assemble_create = assemble_account_link( 'register', $assemble_here );
$assemble_signin = $assemble_gate['sign_in_url'] ?? ( is_user_logged_in() ? '' : assemble_account_link( 'login', $assemble_here ) );
// A signed-in free account can't sign in again: offer the membership link instead (Phase 7).
$assemble_member = is_user_logged_in() ? assemble_url( (string) assemble_setting( 'membership_url', '/contact-us/' ) ) : '';
?>
<div class="report-gate-layout" data-area="<?php echo esc_attr( (string) ( $args['area'] ?? 'neutral' ) ); ?>">
	<?php if ( $assemble_public && isset( $assemble_s['setup'] ) ) : ?>
		<div class="article-body">
			<?php if ( isset( $assemble_s['attribution'] ) ) : ?>
				<p class="eyebrow report-label"><?php echo esc_html( $assemble_s['attribution'] ); ?></p>
			<?php endif; ?>
			<?php echo wp_kses_post( $assemble_s['setup'] ); ?>
		</div>
	<?php endif; ?>

	<section class="report-gate" aria-labelledby="report-gate-title">
		<?php if ( ! $assemble_metered ) : ?>
			<span class="tag tag-access"><?php echo assemble_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><?php echo esc_html( assemble_label( 'members_only' ) ); ?></span>
		<?php endif; ?>

		<?php if ( $assemble_metered ) : ?>
			<h2 id="report-gate-title"><?php esc_html_e( 'Keep reading with a free account', 'assemble' ); ?></h2>
			<p><?php $assemble_book ? esc_html_e( 'You’ve read your free Insights for now. Create a free account to keep reading this playbook, or sign in.', 'assemble' ) : esc_html_e( 'You’ve read your free Field Reports for now. Create a free account to keep reading, or sign in.', 'assemble' ); ?></p>
		<?php elseif ( $assemble_public ) : ?>
			<h2 id="report-gate-title"><?php $assemble_book ? esc_html_e( 'Read the full playbook', 'assemble' ) : esc_html_e( 'Read the full Field Report', 'assemble' ); ?></h2>
			<p>
				<?php
				if ( $assemble_book ) {
					/* translators: %s: Community name, with "the" where it reads naturally. */
					printf( esc_html__( 'Join the Assemble network to read this playbook in full, and the rest of our peer intelligence from %s.', 'assemble' ), esc_html( (string) ( $assemble_join['community'] ?? '' ) ) );
				} else {
					/* translators: %s: Community name, with "the" where it reads naturally. */
					printf( esc_html__( 'Join the Assemble network to read this report in full, and the rest of our peer intelligence from %s.', 'assemble' ), esc_html( (string) ( $assemble_join['community'] ?? '' ) ) );
				}
				?>
			</p>
		<?php else : ?>
			<h2 id="report-gate-title"><?php $assemble_book ? esc_html_e( 'This playbook is for Assemble members', 'assemble' ) : esc_html_e( 'This Field Report is for Assemble members', 'assemble' ); ?></h2>
			<p><?php esc_html_e( 'It is available to specific member audiences.', 'assemble' ); ?></p>
			<?php if ( isset( $assemble_s['source_line'] ) ) : ?>
				<p class="meta"><?php echo esc_html( $assemble_s['source_line'] ); ?></p>
			<?php endif; ?>
		<?php endif; ?>

		<div class="actions">
			<?php if ( $assemble_metered ) : ?>
				<a class="btn-solid" href="<?php echo esc_url( $assemble_create ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
			<?php elseif ( $assemble_public ) : ?>
				<a class="btn-solid" href="<?php echo esc_url( ! empty( $assemble_join['url'] ) ? $assemble_join['url'] : $assemble_create ); ?>"><?php esc_html_e( 'Join the Assemble network', 'assemble' ); ?></a>
			<?php endif; ?>
			<?php if ( $assemble_member && ! $assemble_public ) : ?>
				<a class="btn-solid" href="<?php echo esc_url( $assemble_member ); ?>"><?php esc_html_e( 'Ask about membership', 'assemble' ); ?></a>
			<?php endif; ?>
			<?php if ( $assemble_signin ) : ?>
				<a class="<?php echo $assemble_public ? 'btn-outline' : 'btn-solid'; ?>" href="<?php echo esc_url( $assemble_signin ); ?>"><?php echo esc_html( assemble_label( 'sign_in' ) ); ?></a>
			<?php endif; ?>
			<?php if ( $assemble_bypass ) : ?>
				<a class="btn-outline" href="<?php echo esc_url( $assemble_bypass['url'] ); ?>" rel="nofollow"><?php echo esc_html( $assemble_bypass['label'] ); ?></a>
			<?php endif; ?>
		</div>

		<?php if ( $assemble_bypass ) : ?>
			<p class="meta"><?php $assemble_book ? esc_html_e( 'Reviewing this site before launch? The last button shows you the complete playbook.', 'assemble' ) : esc_html_e( 'Reviewing this site before launch? The last button shows you the complete report.', 'assemble' ); ?></p>
		<?php endif; ?>
	</section>
</div>
