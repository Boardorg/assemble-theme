<?php
/**
 * Community page band: the proof band. The member count and descriptor beside
 * the member-company logo wall (name tiles until approved logos exist). The
 * index passes `stats` instead, figures from the Community directory ("13
 * Communities", "4 Practices"), shown with the member count as a row of big
 * numbers. Shared by the Board and index layouts; left out with nothing to show.
 *
 * @var array $args { page: a Community page view model, stats?: array<int,array{0:string,1:string}> value, label }
 */

defined( 'ABSPATH' ) || exit;

$assemble_page    = (array) $args['page'];
$assemble_members = (array) ( $assemble_page['members'] ?? array() );
$assemble_company = (array) ( $assemble_page['company'] ?? array() );
$assemble_stats   = array_values( array_filter( (array) ( $args['stats'] ?? array() ), static fn( $stat ) => '' !== (string) ( $stat[0] ?? '' ) ) );
$assemble_t       = static fn( string $text ): string => assemble_community_text( $text, $assemble_page );

if ( empty( $assemble_members['count'] ) && empty( $assemble_members['logos'] ) && ! $assemble_stats ) {
	return;
}
?>
<div class="community-band community-band-proof">
	<div class="wrap">
		<section class="community-proof<?php echo empty( $assemble_members['logos'] ) ? ' is-copy-only' : ''; ?>" aria-label="<?php esc_attr_e( 'Members', 'assemble' ); ?>">
			<div class="community-proof-copy">
				<?php if ( ! empty( $assemble_company['eyebrow'] ) ) : ?>
					<p class="eyebrow"><?php echo esc_html( (string) $assemble_company['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( $assemble_stats ) : ?>
					<?php
					if ( ! empty( $assemble_members['count'] ) ) {
						array_unshift( $assemble_stats, array( (string) $assemble_members['count'], $assemble_t( (string) ( $assemble_members['descriptor'] ?? '' ) ) ) );
					}
					?>
					<dl class="community-proof-stats">
						<?php foreach ( $assemble_stats as $assemble_stat ) : ?>
							<div>
								<dt class="community-stat"><?php echo esc_html( (string) $assemble_stat[0] ); ?></dt>
								<dd class="community-proof-line"><?php echo esc_html( (string) $assemble_stat[1] ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>
				<?php elseif ( ! empty( $assemble_members['count'] ) ) : ?>
					<p class="community-stat"><?php echo esc_html( $assemble_members['count'] ); ?></p>
					<p class="community-proof-line"><?php echo esc_html( $assemble_t( (string) ( $assemble_members['descriptor'] ?? '' ) ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $assemble_members['logos'] ) ) : ?>
				<ul class="community-logos" aria-label="<?php esc_attr_e( 'Member companies', 'assemble' ); ?>">
					<?php foreach ( $assemble_members['logos'] as $assemble_member_logo ) : ?>
						<li class="media-frame media-logo">
							<?php if ( is_array( $assemble_member_logo ) && ! empty( $assemble_member_logo['url'] ) ) : ?>
								<img src="<?php echo esc_url( $assemble_member_logo['url'] ); ?>" alt="<?php echo esc_attr( (string) $assemble_member_logo['name'] ); ?>" loading="lazy" decoding="async">
							<?php else : ?>
								<span class="logo-placeholder"><?php echo esc_html( is_array( $assemble_member_logo ) ? (string) $assemble_member_logo['name'] : (string) $assemble_member_logo ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
					<li class="community-logos-more meta"><?php esc_html_e( 'and more', 'assemble' ); ?></li>
				</ul>
			<?php endif; ?>
		</section>
	</div>
</div>
