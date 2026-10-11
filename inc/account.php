<?php
/**
 * Account helpers for templates (Phase 7). The behaviour lives in assemble-core
 * (inc/accounts.php, inc/account-forms.php); without it, the links fall back to
 * Site Settings defaults and the account pages say accounts are unavailable.
 */

defined( 'ABSPATH' ) || exit;

/** True when assemble-core provides the account forms. */
function assemble_accounts_available(): bool {
	return function_exists( 'assemble_account_state' );
}

/** True on the Sign in, Create account and Account pages. */
function assemble_is_account_page(): bool {
	if ( function_exists( 'assemble_account_page_kind' ) ) {
		return '' !== assemble_account_page_kind();
	}

	return is_page( array( 'login', 'register', 'account' ) );
}

/**
 * An account URL: login, register, account, lostpassword or logout.
 */
function assemble_account_link( string $which, string $redirect = '' ): string {
	if ( function_exists( 'assemble_account_url' ) ) {
		return assemble_account_url( $which, $redirect );
	}

	if ( 'logout' === $which ) {
		return wp_logout_url( home_url( '/' ) );
	}

	$defaults = array(
		'login'        => array( 'sign_in_url', '/login/' ),
		'lostpassword' => array( 'sign_in_url', '/login/' ),
		'register'     => array( 'create_account_url', '/register/' ),
		'account'      => array( 'account_url', '/account/' ),
	);
	list( $key, $fallback ) = $defaults[ $which ] ?? $defaults['account'];

	return assemble_url( (string) assemble_setting( $key, $fallback ) );
}

/**
 * The interest areas on the Create account and Account pages: every practice
 * area, with its Communities from the feed (none for Finance yet).
 *
 * @return array<int,array{key:string,label:string,blurb:string,communities:array}>
 */
function assemble_interest_areas(): array {
	$blurbs = array(
		'human-resources' => __( 'Peer conversations on talent, learning and culture.', 'assemble' ),
		'manufacturing'   => __( 'Peer conversations on planning, sourcing and resilience.', 'assemble' ),
		'marketing'       => __( 'Peer conversations on brand, content and AI-driven discovery.', 'assemble' ),
		'technology'      => __( 'Peer conversations on AI, data and security.', 'assemble' ),
		'finance'         => __( 'Peer conversations on planning, risk and strategy.', 'assemble' ),
	);

	$areas = array();
	foreach ( assemble_practice_areas() as $key => $label ) {
		$areas[] = array(
			'key'         => $key,
			'label'       => $label,
			'blurb'       => $blurbs[ $key ] ?? '',
			'communities' => assemble_area_communities( $key ),
		);
	}

	return $areas;
}

/**
 * One labelled form field (the guide's .form-row), with its help and error
 * text tied to the input for screen readers.
 *
 * @param array $field { id, name, label, type?, value?, autocomplete?, placeholder?, help?, error? (code), required?, readonly? }
 */
function assemble_account_field( array $field ): void {
	$id       = (string) $field['id'];
	$error    = (string) ( $field['error'] ?? '' );
	$message  = '' !== $error && function_exists( 'assemble_account_message' ) ? assemble_account_message( $error ) : '';
	$help     = (string) ( $field['help'] ?? '' );
	$describe = trim( ( '' !== $help ? $id . '-help ' : '' ) . ( '' !== $message ? $id . '-error' : '' ) );
	?>
	<div class="form-row<?php echo '' !== $message ? ' has-error' : ''; ?>">
		<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( (string) $field['label'] ); ?></label>
		<input
			id="<?php echo esc_attr( $id ); ?>"
			name="<?php echo esc_attr( (string) $field['name'] ); ?>"
			type="<?php echo esc_attr( (string) ( $field['type'] ?? 'text' ) ); ?>"
			value="<?php echo esc_attr( (string) ( $field['value'] ?? '' ) ); ?>"
			<?php if ( ! empty( $field['autocomplete'] ) ) : ?>
				autocomplete="<?php echo esc_attr( (string) $field['autocomplete'] ); ?>"
			<?php endif; ?>
			<?php if ( ! empty( $field['placeholder'] ) ) : ?>
				placeholder="<?php echo esc_attr( (string) $field['placeholder'] ); ?>"
			<?php endif; ?>
			<?php echo ! empty( $field['required'] ) ? 'required aria-required="true"' : ''; ?>
			<?php echo ! empty( $field['readonly'] ) ? 'readonly' : ''; ?>
			<?php if ( '' !== $describe ) : ?>
				aria-describedby="<?php echo esc_attr( $describe ); ?>"
			<?php endif; ?>
			<?php echo '' !== $message ? 'aria-invalid="true"' : ''; ?>
		>
		<?php if ( '' !== $help ) : ?>
			<p class="field-help" id="<?php echo esc_attr( $id ); ?>-help"><?php echo esc_html( $help ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $message ) : ?>
			<p class="field-error" id="<?php echo esc_attr( $id ); ?>-error"><?php echo esc_html( $message ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}
