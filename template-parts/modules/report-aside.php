<?php
/**
 * The article aside (part of article-body-with-aside): "From Assemble peer
 * intelligence" with the report's source line and a link to the Community's
 * Insights, then the view's engagement actions from the feed. Related reports
 * are left out here because related-peer-intelligence shows them below.
 *
 * The delegate view's locked block has no wireframe: on-system, flagged.
 *
 * @var array $args { report: view model }
 */

defined( 'ABSPATH' ) || exit;

$assemble_s         = (array) ( $args['report']['sections'] ?? array() );
$assemble_community = $assemble_s['kicker'] ?? null;
?>
<div class="aside-card">
	<div class="aside-title"><?php esc_html_e( 'From Assemble peer intelligence', 'assemble' ); ?></div>
	<?php if ( isset( $assemble_s['source_line'] ) ) : ?>
		<p><?php echo esc_html( $assemble_s['source_line'] ); ?></p>
	<?php endif; ?>
	<?php if ( $assemble_community && '' !== $assemble_community['slug'] ) : ?>
		<a class="btn-solid" href="<?php echo esc_url( assemble_community_url( $assemble_community['slug'] ) ); ?>">
			<?php
			/* translators: 1: Community label, e.g. "AEO", 2: "Insights". */
			printf( esc_html__( 'Explore %1$s %2$s', 'assemble' ), esc_html( assemble_community_label( $assemble_community ) ), esc_html( assemble_label( 'insights' ) ) );
			?>
		</a>
	<?php endif; ?>
</div>

<?php
foreach ( (array) ( $assemble_s['engagement'] ?? array() ) as $assemble_block ) :
	$assemble_items = array_filter( (array) $assemble_block['items'], static fn( $item ) => 'related' !== $item['key'] );
	if ( ! $assemble_items ) {
		continue;
	}
	$assemble_lock = $assemble_block['lock'] ?? null;
	?>
	<div class="aside-card report-engage<?php echo $assemble_lock ? ' report-engage--locked' : ''; ?>">
		<?php if ( $assemble_lock ) : ?>
			<span class="tag tag-access"><?php echo assemble_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?><?php echo esc_html( assemble_label( 'members_only' ) ); ?></span>
			<h3 class="report-engage-title"><?php echo esc_html( $assemble_lock['title'] ); ?></h3>
			<p><?php echo esc_html( $assemble_lock['copy'] ); ?></p>
		<?php else : ?>
			<div class="aside-title"><?php echo esc_html( $assemble_block['title'] ); ?></div>
		<?php endif; ?>

		<ul class="report-engage-list">
			<?php foreach ( $assemble_items as $assemble_item ) : ?>
				<li>
					<strong><?php echo esc_html( $assemble_item['heading'] ); ?></strong>
					<?php if ( ! $assemble_lock && '' !== $assemble_item['body'] ) : ?>
						<p><?php echo esc_html( $assemble_item['body'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! $assemble_lock && $assemble_item['actions'] ) : ?>
						<div class="report-engage-actions">
							<?php foreach ( $assemble_item['actions'] as $assemble_action ) : ?>
								<a class="<?php echo 'solid' === $assemble_action['variant'] ? 'btn-solid' : 'btn-outline'; ?>" href="<?php echo esc_url( $assemble_action['url'] ); ?>"><?php echo esc_html( $assemble_action['label'] ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! $assemble_lock && $assemble_item['links'] ) : ?>
						<ul class="report-engage-links">
							<?php foreach ( $assemble_item['links'] as $assemble_link ) : ?>
								<li>
									<a href="<?php echo esc_url( $assemble_link['url'] ); ?>"><?php echo esc_html( $assemble_link['title'] ); ?></a>
									<?php if ( '' !== $assemble_link['when'] ) : ?>
										<span class="meta"><?php echo esc_html( $assemble_link['when'] ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( $assemble_lock && ! empty( $assemble_lock['url'] ) ) : ?>
			<a class="btn-solid" href="<?php echo esc_url( $assemble_lock['url'] ); ?>"><?php echo esc_html( $assemble_lock['label'] ); ?></a>
		<?php endif; ?>
	</div>
<?php endforeach; ?>
