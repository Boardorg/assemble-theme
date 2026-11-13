<?php
/**
 * Module: public-masthead (web-style-guide.html → Web modules → Site chrome).
 *
 * Logo only in the masthead, no date (settled 2026-10-01). Search, account
 * actions and the main navigation row. Below 980px the account actions and nav
 * row move into a drawer; the drawer has no wireframe yet, so it is a plain,
 * on-system version flagged for design.
 *
 * The utility bar above the masthead (company pages, menu location "utility")
 * has no wireframe either: on-system, flagged for design. It moves into the
 * drawer too, under the main navigation.
 */

defined( 'ABSPATH' ) || exit;

$assemble_nav     = assemble_primary_nav_items();
$assemble_utility = assemble_utility_nav_items();

// Signed in: My Page, and an Account menu holding Account Settings and Sign out
// (Kristen's v2 edits: Sign out was only in the drawer); the drawer lists all three.
// The signed-in state has no wireframe: on-system, flagged for design.
$assemble_account = is_user_logged_in()
	? array(
		array(
			'label'   => assemble_label( 'for_you' ),
			'url'     => assemble_account_link( 'for_you' ),
			'class'   => 'btn-solid',
			'current' => assemble_is_for_you_page(),
		),
		array(
			'label'   => assemble_label( 'account' ),
			'url'     => assemble_account_link( 'account' ),
			'class'   => 'btn-outline',
			'current' => function_exists( 'assemble_account_page_kind' ) && 'account' === assemble_account_page_kind(),
		),
	)
	: array(
		array(
			'label' => assemble_label( 'sign_in' ),
			'url'   => assemble_account_link( 'login' ),
			'class' => 'btn-outline',
		),
		array(
			'label' => assemble_label( 'create' ),
			'url'   => assemble_account_link( 'register' ),
			'class' => 'btn-solid',
		),
	);
$assemble_drawer_actions = $assemble_account;
if ( is_user_logged_in() ) {
	$assemble_drawer_actions[] = array(
		'label' => assemble_label( 'sign_out' ),
		'url'   => assemble_account_link( 'logout' ),
		'class' => 'btn-outline',
	);
}

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
	<?php if ( $assemble_utility ) : ?>
		<nav class="utility-bar" aria-label="<?php esc_attr_e( 'Company', 'assemble' ); ?>">
			<div class="wrap utility-bar__row">
				<?php foreach ( $assemble_utility as $assemble_item ) : ?>
					<a href="<?php echo esc_url( $assemble_item['url'] ); ?>"<?php echo assemble_nav_current_attr( $assemble_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute. ?>><?php echo esc_html( $assemble_item['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
		</nav>
	<?php endif; ?>
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
			<a class="header-search-icon" href="<?php echo esc_url( add_query_arg( 's', '', home_url( '/' ) ) ); ?>" aria-label="<?php echo esc_attr( assemble_label( 'search' ) ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
			</a>
			<?php if ( is_user_logged_in() ) : ?>
				<?php list( $assemble_for_you, $assemble_settings ) = $assemble_account; ?>
				<a class="btn-solid header-account-action" href="<?php echo esc_url( $assemble_for_you['url'] ); ?>"<?php echo $assemble_for_you['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $assemble_for_you['label'] ); ?></a>
				<details class="account-menu header-account-action">
					<summary class="btn-outline<?php echo $assemble_settings['current'] ? ' is-current' : ''; ?>">
						<?php echo esc_html( assemble_label( 'account_menu' ) ); ?>
						<svg class="account-menu__chevron" viewBox="0 0 12 12" aria-hidden="true" focusable="false"><path d="M2.5 4.5 6 8l3.5-3.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</summary>
					<ul class="account-menu__panel">
						<li><a href="<?php echo esc_url( $assemble_settings['url'] ); ?>"<?php echo $assemble_settings['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $assemble_settings['label'] ); ?></a></li>
						<li><a href="<?php echo esc_url( assemble_account_link( 'logout' ) ); ?>"><?php echo esc_html( assemble_label( 'sign_out' ) ); ?></a></li>
					</ul>
				</details>
			<?php else : ?>
				<?php foreach ( $assemble_account as $assemble_action ) : ?>
					<a class="<?php echo esc_attr( $assemble_action['class'] ); ?> header-account-action" href="<?php echo esc_url( $assemble_action['url'] ); ?>"><?php echo esc_html( $assemble_action['label'] ); ?></a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<hr class="header-rule">
	<nav class="wrap header-row-2" aria-label="<?php esc_attr_e( 'Primary', 'assemble' ); ?>">
		<?php foreach ( $assemble_nav as $assemble_item ) : ?>
			<a href="<?php echo esc_url( $assemble_item['url'] ); ?>"<?php echo assemble_nav_current_attr( $assemble_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute. ?>><?php echo esc_html( $assemble_item['label'] ); ?></a>
		<?php endforeach; ?>
	</nav>
	<hr class="header-rule header-rule--last">

	<div class="site-drawer" id="site-drawer" hidden>
		<div class="wrap">
			<nav aria-label="<?php esc_attr_e( 'Primary', 'assemble' ); ?>">
				<ul class="site-drawer__nav">
					<?php foreach ( $assemble_nav as $assemble_item ) : ?>
						<li><a href="<?php echo esc_url( $assemble_item['url'] ); ?>"<?php echo assemble_nav_current_attr( $assemble_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute. ?>><?php echo esc_html( $assemble_item['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<?php if ( $assemble_utility ) : ?>
				<nav aria-label="<?php esc_attr_e( 'Company', 'assemble' ); ?>">
					<ul class="site-drawer__utility">
						<?php foreach ( $assemble_utility as $assemble_item ) : ?>
							<li><a href="<?php echo esc_url( $assemble_item['url'] ); ?>"<?php echo assemble_nav_current_attr( $assemble_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute. ?>><?php echo esc_html( $assemble_item['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
			<div class="site-drawer__actions">
				<?php foreach ( $assemble_drawer_actions as $assemble_action ) : ?>
					<a class="<?php echo esc_attr( $assemble_action['class'] ); ?>" href="<?php echo esc_url( $assemble_action['url'] ); ?>"<?php echo ! empty( $assemble_action['current'] ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $assemble_action['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</header>
