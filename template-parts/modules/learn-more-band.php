<?php
/**
 * Module: learn-more-band (web-style-guide.html → Web modules → Peer intelligence).
 *
 * A white band introducing Communities, Summits and Insights, each a whole-card
 * link. Logged-out homepage.
 */

defined( 'ABSPATH' ) || exit;

$assemble_cards = array(
	array(
		'eyebrow' => assemble_label( 'communities' ),
		'title'   => __( 'Connect with peers facing the same challenges', 'assemble' ),
		/* translators: %s: "Communities" (or its replacement label). */
		'text'    => sprintf( __( 'Join trusted %s built for candid discussion, practical problem-solving, and ongoing exchange with senior leaders in your field.', 'assemble' ), assemble_label( 'communities' ) ),
		/* translators: %s: "Communities" (or its replacement label). */
		'link'    => sprintf( __( 'Explore %s', 'assemble' ), assemble_label( 'communities' ) ),
		'url'     => assemble_url( (string) assemble_setting( 'communities_url', '/field-reports/' ) ),
	),
	array(
		'eyebrow' => assemble_label( 'summits' ),
		'title'   => __( 'Meet the people behind the ideas', 'assemble' ),
		'text'    => __( 'Take the conversation offline at curated gatherings designed for substantive exchange, new connections, and practical takeaways.', 'assemble' ),
		/* translators: %s: "Summits". */
		'link'    => sprintf( __( 'Explore %s', 'assemble' ), assemble_label( 'summits' ) ),
		'url'     => home_url( '/summits/' ),
	),
	array(
		'eyebrow' => assemble_label( 'insights' ),
		'title'   => __( 'See what leaders are really doing', 'assemble' ),
		/* translators: %s: "Working Sessions" (or its replacement label). */
		'text'    => sprintf( __( 'Get fresh, practical intelligence drawn from real peer conversations, benchmarks, %s, and executive exchanges.', 'assemble' ), assemble_label( 'working_sessions' ) ),
		/* translators: %s: "Insights". */
		'link'    => sprintf( __( 'Explore %s', 'assemble' ), assemble_label( 'insights' ) ),
		'url'     => assemble_insights_url(),
	),
);
?>
<section class="learn-more-band" aria-labelledby="learn-more-title">
	<div class="learn-more-head">
		<h2 class="type-h3" id="learn-more-title"><?php esc_html_e( 'Learn more about Assemble', 'assemble' ); ?></h2>
		<p><?php esc_html_e( 'See how Assemble brings senior leaders together to exchange ideas, compare approaches, and turn peer conversations into practical intelligence.', 'assemble' ); ?></p>
	</div>
	<div class="learn-more-grid">
		<?php foreach ( $assemble_cards as $assemble_card ) : ?>
			<a class="learn-more-card" href="<?php echo esc_url( $assemble_card['url'] ); ?>">
				<span class="eyebrow"><?php echo esc_html( $assemble_card['eyebrow'] ); ?></span>
				<h3 class="type-h4"><?php echo esc_html( $assemble_card['title'] ); ?></h3>
				<p><?php echo esc_html( $assemble_card['text'] ); ?></p>
				<span class="link-arrow"><?php echo esc_html( $assemble_card['link'] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
