<?php
/**
 * The account pages: Sign in (with lost and reset password), Create account
 * (wireframe "6. Create Account") and Account. One shell, the mode from
 * assemble-core's assemble_account_state(). Sign in, reset and the Account
 * page's extras have no wireframe: on-system, flagged for design.
 */

defined( 'ABSPATH' ) || exit;

if ( ! assemble_accounts_available() ) : ?>
	<main class="account-main" data-area="neutral">
		<div class="account-wrap">
			<?php get_template_part( 'template-parts/modules/account-header', null, array( 'title' => get_the_title() ) ); ?>
			<div class="account-widget">
				<p><?php esc_html_e( 'Accounts aren’t available right now. Please try again later.', 'assemble' ); ?></p>
			</div>
		</div>
	</main>
	<?php
	return;
endif;

$assemble_state  = assemble_account_state();
$assemble_mode   = $assemble_state['mode'];
$assemble_errors = (array) $assemble_state['errors'];
$assemble_values = (array) $assemble_state['values'];
$assemble_notice = '' !== $assemble_state['notice'] ? assemble_account_message( $assemble_state['notice'] ) : '';
$assemble_error  = isset( $assemble_errors['form'] ) ? assemble_account_message( $assemble_errors['form'] ) : '';
if ( '' === $assemble_error && array_diff_key( $assemble_errors, array( 'form' => 1 ) ) ) {
	$assemble_error = __( 'Please check the highlighted fields.', 'assemble' );
}
$assemble_redirect = (string) $assemble_state['redirect_to'];

$assemble_titles = array(
	'signin'       => __( 'Sign in', 'assemble' ),
	'lostpassword' => __( 'Reset your password', 'assemble' ),
	'rp'           => __( 'Choose a new password', 'assemble' ),
	'register'     => __( 'Create a free account', 'assemble' ),
	'account'      => __( 'Your account', 'assemble' ),
);
?>
<main class="account-main" data-area="neutral">
	<div class="account-wrap">
		<?php
		get_template_part(
			'template-parts/modules/account-header',
			null,
			array(
				'title'  => $assemble_titles[ $assemble_mode ] ?? get_the_title(),
				'notice' => $assemble_notice,
				'error'  => $assemble_error,
			)
		);
		?>

		<?php if ( 'register' === $assemble_mode || 'account' === $assemble_mode ) : ?>
			<?php $assemble_is_new = 'register' === $assemble_mode; ?>
			<form class="account-form" method="post" action="" novalidate>
				<input type="hidden" name="assemble_account" value="<?php echo $assemble_is_new ? 'create' : 'save'; ?>">
				<?php wp_nonce_field( $assemble_is_new ? 'assemble_account_create' : 'assemble_account_save' ); ?>
				<?php if ( $assemble_redirect ) : ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $assemble_redirect ); ?>">
				<?php endif; ?>
				<?php if ( $assemble_is_new ) : ?>
					<div class="account-honeypot" aria-hidden="true">
						<label for="acct-website"><?php esc_html_e( 'Leave this empty', 'assemble' ); ?></label>
						<input id="acct-website" type="text" name="website" tabindex="-1" autocomplete="off">
					</div>
				<?php endif; ?>

				<?php
				get_template_part(
					'template-parts/modules/account-details-form',
					null,
					array(
						'mode'   => $assemble_mode,
						'values' => $assemble_values,
						'errors' => $assemble_errors,
					)
				);
				get_template_part(
					'template-parts/modules/interest-area-grid',
					null,
					array(
						'areas'       => (array) ( $assemble_values['areas'] ?? array() ),
						'communities' => (array) ( $assemble_values['communities'] ?? array() ),
					)
				);
				?>

				<div class="account-form-actions<?php echo $assemble_is_new ? ' account-create' : ''; ?>">
					<button class="btn-solid" type="submit"><?php echo $assemble_is_new ? esc_html__( 'Create account', 'assemble' ) : esc_html__( 'Save changes', 'assemble' ); ?></button>
					<?php if ( $assemble_is_new ) : ?>
						<a class="link-arrow" href="<?php echo esc_url( assemble_account_link( 'login', $assemble_redirect ) ); ?>"><?php esc_html_e( 'Already have an account? Sign in', 'assemble' ); ?></a>
					<?php endif; ?>
				</div>
				<?php if ( $assemble_is_new ) : ?>
					<p class="account-consent">
						<?php
						printf(
							/* translators: %s: link to the privacy policy. */
							esc_html__( 'By creating an account, you agree to our %s. We’ll email you a link to set your password.', 'assemble' ),
							'<a href="' . esc_url( assemble_url( (string) assemble_setting( 'privacy_url', '/policies/' ) ) ) . '">' . esc_html__( 'Privacy policy', 'assemble' ) . '</a>'
						);
						?>
					</p>
				<?php endif; ?>
			</form>

			<?php if ( ! $assemble_is_new ) : ?>
				<section class="account-widget" aria-labelledby="account-security-title">
					<h2 class="account-section-title" id="account-security-title"><?php esc_html_e( 'Password and sign-in', 'assemble' ); ?></h2>
					<div class="account-form-actions">
						<form method="post" action="<?php echo esc_url( add_query_arg( 'action', 'lostpassword', assemble_account_link( 'login' ) ) ); ?>">
							<input type="hidden" name="assemble_account" value="lostpassword">
							<input type="hidden" name="email" value="<?php echo esc_attr( (string) wp_get_current_user()->user_email ); ?>">
							<button class="btn-outline" type="submit"><?php esc_html_e( 'Email me a link to change my password', 'assemble' ); ?></button>
						</form>
						<form method="post" action="">
							<input type="hidden" name="assemble_account" value="signout">
							<?php wp_nonce_field( 'log-out' ); ?>
							<button class="btn-outline" type="submit"><?php esc_html_e( 'Sign out', 'assemble' ); ?></button>
						</form>
					</div>
				</section>
			<?php endif; ?>

		<?php elseif ( 'rp' === $assemble_mode ) : ?>
			<div class="account-widget">
				<form class="account-form account-form--narrow" method="post" action="<?php echo esc_url( add_query_arg( 'action', 'rp', assemble_account_link( 'login' ) ) ); ?>" novalidate>
					<input type="hidden" name="assemble_account" value="resetpass">
					<input type="hidden" name="key" value="<?php echo esc_attr( (string) ( $assemble_state['reset']['key'] ?? '' ) ); ?>">
					<input type="hidden" name="login" value="<?php echo esc_attr( (string) ( $assemble_state['reset']['login'] ?? '' ) ); ?>">
					<?php
					assemble_account_field(
						array(
							'id'           => 'acct-new-password',
							'name'         => 'password',
							'type'         => 'password',
							'label'        => __( 'New password', 'assemble' ),
							'help'         => __( 'At least 10 characters.', 'assemble' ),
							'autocomplete' => 'new-password',
							'error'        => $assemble_errors['password'] ?? '',
						)
					);
					?>
					<div class="account-form-actions">
						<button class="btn-solid" type="submit"><?php esc_html_e( 'Save password', 'assemble' ); ?></button>
					</div>
				</form>
			</div>

		<?php elseif ( 'lostpassword' === $assemble_mode ) : ?>
			<div class="account-widget">
				<form class="account-form account-form--narrow" method="post" action="<?php echo esc_url( add_query_arg( 'action', 'lostpassword', assemble_account_link( 'login' ) ) ); ?>" novalidate>
					<input type="hidden" name="assemble_account" value="lostpassword">
					<p class="account-help"><?php esc_html_e( 'Enter the email you use for Assemble and we’ll send you a link to choose a new password.', 'assemble' ); ?></p>
					<?php
					assemble_account_field(
						array(
							'id'           => 'acct-email',
							'name'         => 'email',
							'type'         => 'email',
							'label'        => __( 'Email', 'assemble' ),
							'autocomplete' => 'email',
							'placeholder'  => 'name@company.com',
						)
					);
					?>
					<div class="account-form-actions">
						<button class="btn-solid" type="submit"><?php esc_html_e( 'Email me a link', 'assemble' ); ?></button>
						<a class="link-arrow" href="<?php echo esc_url( assemble_account_link( 'login' ) ); ?>"><?php esc_html_e( 'Back to sign in', 'assemble' ); ?></a>
					</div>
				</form>
			</div>

		<?php else : ?>
			<div class="account-widget">
				<form class="account-form account-form--narrow" method="post" action="" novalidate>
					<input type="hidden" name="assemble_account" value="signin">
					<?php if ( $assemble_redirect ) : ?>
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $assemble_redirect ); ?>">
					<?php endif; ?>
					<?php
					assemble_account_field(
						array(
							'id'           => 'acct-email',
							'name'         => 'email',
							'type'         => 'email',
							'label'        => __( 'Email', 'assemble' ),
							'value'        => (string) ( $assemble_values['email'] ?? '' ),
							'autocomplete' => 'username',
							'placeholder'  => 'name@company.com',
						)
					);
					assemble_account_field(
						array(
							'id'           => 'acct-password',
							'name'         => 'password',
							'type'         => 'password',
							'label'        => __( 'Password', 'assemble' ),
							'autocomplete' => 'current-password',
						)
					);
					?>
					<label class="account-check"><input type="checkbox" name="remember" value="1"> <?php esc_html_e( 'Keep me signed in', 'assemble' ); ?></label>
					<div class="account-form-actions">
						<button class="btn-solid" type="submit"><?php echo esc_html( assemble_label( 'sign_in' ) ); ?></button>
						<a class="link-arrow" href="<?php echo esc_url( assemble_account_link( 'lostpassword' ) ); ?>"><?php esc_html_e( 'Forgot your password?', 'assemble' ); ?></a>
					</div>
				</form>
			</div>
			<p class="account-switch">
				<?php esc_html_e( 'New to Assemble?', 'assemble' ); ?>
				<a class="link-arrow" href="<?php echo esc_url( assemble_account_link( 'register', $assemble_redirect ) ); ?>"><?php echo esc_html( assemble_label( 'create' ) ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</main>
