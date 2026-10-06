<?php
/**
 * Module: public-masthead (web-style-guide.html → Web modules → Site chrome).
 *
 * Logo only in the masthead, no date (settled 2026-10-01). Search, account
 * actions and the main navigation row. Below 980px the account actions and nav
 * row move into a drawer; the drawer has no wireframe yet, so it is a plain,
 * on-system version flagged for design.
 */

defined( 'ABSPATH' ) || exit;

$assemble_nav = assemble_primary_nav_items();

$assemble_account = is_user_logged_in()
	? array(
		array(
			'label' => __( 'Account', 'assemble' ),
			'url'   => assemble_url( '/account/' ),
			'class' => 'btn-outline',
		),
	)
	: array(
		array(
			'label' => assemble_label( 'sign_in' ),
			'url'   => assemble_url( (string) assemble_setting( 'sign_in_url', '/login/' ) ),
			'class' => 'btn-outline',
		),
		array(
			'label' => assemble_label( 'create' ),
			'url'   => assemble_url( (string) assemble_setting( 'create_account_url', '/register/' ) ),
			'class' => 'btn-solid',
		),
	);

$assemble_banner_text = (string) assemble_setting( 'banner_text', '' );
?>
<?php if ( assemble_setting( 'banner_enabled', false ) && '' !== $assemble_banner_text ) : ?>
	<?php $assemble_banner_url = assemble_url( (string) assemble_setting( 'banner_url', '' ) ); ?>
	<div class="announcement-banner">
		<div class="wrap">
			<?php if ( $assemble_banner_url ) : ?>
				<a href="<?php echo esc_url( $assemble_banner_url ); ?>"><?php echo esc_html( $assemble_banner_text ); ?></a>
			<?php else : ?>
				<?php echo esc_html( $assemble_banner_text ); ?>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
<header class="site-header" data-area="neutral">
	<div class="wrap header-row-1">
		<div class="left">
			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-drawer">
				<span class="hamburger" aria-hidden="true"><span></span><span></span><span></span></span>
				<span class="screen-reader-text"><?php echo esc_html( assemble_label( 'menu' ) ); ?></span>
			</button>
		</div>
		<div class="masthead">
			<a class="masthead__home" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php echo assemble_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-owned SVG. ?>
			</a>
		</div>
		<div class="right">
			<a class="header-search-icon" href="<?php echo esc_url( get_search_link() ); ?>" aria-label="<?php echo esc_attr( assemble_label( 'search' ) ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
			</a>
			<?php foreach ( $assemble_account as $assemble_action ) : ?>
				<a class="<?php echo esc_attr( $assemble_action['class'] ); ?> header-account-action" href="<?php echo esc_url( $assemble_action['url'] ); ?>"><?php echo esc_html( $assemble_action['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
	<hr class="header-rule">
	<nav class="wrap header-row-2" aria-label="<?php esc_attr_e( 'Primary', 'assemble' ); ?>">
		<?php foreach ( $assemble_nav as $assemble_item ) : ?>
			<a href="<?php echo esc_url( $assemble_item['url'] ); ?>"<?php echo $assemble_item['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $assemble_item['label'] ); ?></a>
		<?php endforeach; ?>
	</nav>
	<hr class="header-rule header-rule--last">

	<div class="site-drawer" id="site-drawer" hidden>
		<div class="wrap">
			<nav aria-label="<?php esc_attr_e( 'Primary', 'assemble' ); ?>">
				<ul class="site-drawer__nav">
					<?php foreach ( $assemble_nav as $assemble_item ) : ?>
						<li><a href="<?php echo esc_url( $assemble_item['url'] ); ?>"<?php echo $assemble_item['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $assemble_item['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<div class="site-drawer__actions">
				<?php foreach ( $assemble_account as $assemble_action ) : ?>
					<a class="<?php echo esc_attr( $assemble_action['class'] ); ?>" href="<?php echo esc_url( $assemble_action['url'] ); ?>"><?php echo esc_html( $assemble_action['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</header>
