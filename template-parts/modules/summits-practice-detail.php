<?php
/**
 * Module: summits-practice-detail (wireframe 2): one selector panel. The
 * Community's intro, its Featured Summits as event cards, and the free-account
 * call to action for visitors who aren't signed in. Coloured by its practice
 * area. Every summit link goes to EP through assemble_summit_url().
 *
 * @var array $args {
 *     id: string, area: string, area_label: string, open: bool,
 *     community: array|null (from assemble_area_communities(); null for a practice
 *     area with no Communities yet), summits: array (from assemble_featured_summits())
 * }
 */

defined( 'ABSPATH' ) || exit;

$assemble_community = $args['community'] ?? null;
$assemble_summits   = (array) ( $args['summits'] ?? array() );
$assemble_area      = (string) ( $args['area'] ?? 'neutral' );
$assemble_heading   = $assemble_community ? $assemble_community['label'] : (string) ( $args['area_label'] ?? '' );
?>
<section class="tab-panel summits-practice-detail" id="<?php echo esc_attr( (string) $args['id'] ); ?>" data-area="<?php echo esc_attr( $assemble_area ); ?>" aria-label="<?php echo esc_attr( $assemble_heading ); ?>"<?php echo empty( $args['open'] ) ? ' hidden' : ''; ?>>
	<div class="ld-topic-intro-row is-single">
		<div class="ld-topic-intro">
			<h2 class="type-practice-masthead"><?php echo esc_html( $assemble_heading ); ?></h2>
			<?php if ( $assemble_community && $assemble_community['description'] ) : ?>
				<p><?php echo esc_html( $assemble_community['description'] ); ?></p>
			<?php elseif ( ! $assemble_community ) : ?>
				<p>
					<?php
					/* translators: 1: practice area, 2: "Communities". */
					printf( esc_html__( '%1$s %2$s are on their way. Create a free account to hear when they launch.', 'assemble' ), esc_html( $assemble_heading ), esc_html( assemble_label( 'communities' ) ) );
					?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<div class="module-head"><h3><?php esc_html_e( 'Featured Summits', 'assemble' ); ?></h3></div>

	<?php if ( $assemble_summits ) : ?>
		<div class="event-card-grid">
			<?php foreach ( $assemble_summits as $assemble_summit ) : ?>
				<article class="event-card">
					<h4><?php echo esc_html( $assemble_summit['title'] ); ?></h4>
					<p class="event-when">
						<span><?php echo esc_html( assemble_summit_dates( $assemble_summit, 'short' ) ); ?></span>
						<?php if ( '' !== assemble_summit_place( $assemble_summit ) ) : ?>
							<span aria-hidden="true">·</span>
							<span><?php echo esc_html( assemble_summit_place( $assemble_summit ) ); ?></span>
						<?php endif; ?>
					</p>
					<p class="countdown"><?php echo esc_html( assemble_summit_countdown( $assemble_summit ) ); ?></p>
					<?php if ( '' !== $assemble_summit['blurb'] ) : ?>
						<p><?php echo esc_html( $assemble_summit['blurb'] ); ?></p>
					<?php endif; ?>
					<a class="btn-solid" href="<?php echo esc_url( assemble_summit_url( $assemble_summit, 'register' ) ); ?>"><?php echo esc_html( assemble_label( 'reserve_seat' ) ); ?><span class="screen-reader-text">: <?php echo esc_html( $assemble_summit['title'] ); ?></span></a>
				</article>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p class="summits-empty">
			<?php
			/* translators: %s: practice area or Community. */
			printf( esc_html__( 'No %s Summits are on the calendar yet.', 'assemble' ), esc_html( $assemble_community ? $assemble_community['label'] : $assemble_heading ) );
			?>
			<a class="link-arrow" href="#all-summits"><?php esc_html_e( 'See all upcoming Summits', 'assemble' ); ?></a>
		</p>
	<?php endif; ?>

	<?php if ( ! is_user_logged_in() ) : ?>
		<div class="free-account-cta">
			<div>
				<h3><?php esc_html_e( 'Create a free account for peer intelligence updates.', 'assemble' ); ?></h3>
				<p>
					<?php
					/* translators: %s: "Communities". */
					printf( esc_html__( 'Follow the %s that matter to you and get new insights from Assemble delivered as they publish.', 'assemble' ), esc_html( assemble_label( 'communities' ) ) );
					?>
				</p>
			</div>
			<a class="btn-solid" href="<?php echo esc_url( assemble_url( (string) assemble_setting( 'create_account_url', '/register/' ) ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
		</div>
	<?php endif; ?>
</section>
