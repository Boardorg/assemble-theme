<?php
/**
 * Module: account-details-form (wireframe "6. Create Account", "Your details").
 * Email, full name, company and title. On the Account page the email is shown
 * but not editable.
 *
 * @var array $args { mode: register|account, values: array, errors: array<string,string> }
 */

defined( 'ABSPATH' ) || exit;

$assemble_values = (array) ( $args['values'] ?? array() );
$assemble_errors = (array) ( $args['errors'] ?? array() );
$assemble_is_new = 'register' === ( $args['mode'] ?? 'register' );
$assemble_value  = static fn ( string $key ): string => (string) ( $assemble_values[ $key ] ?? '' );
?>
<div class="account-widget account-details-form">
	<h2 class="account-section-title"><?php esc_html_e( 'Your details', 'assemble' ); ?></h2>
	<?php
	assemble_account_field(
		array(
			'id'           => 'acct-email',
			'name'         => $assemble_is_new ? 'email' : 'email_display',
			'type'         => 'email',
			'label'        => __( 'Email', 'assemble' ),
			'value'        => $assemble_value( 'email' ),
			'autocomplete' => 'email',
			'placeholder'  => 'name@company.com',
			'required'     => $assemble_is_new,
			'readonly'     => ! $assemble_is_new,
			'help'         => $assemble_is_new ? '' : __( 'To change your email, contact us.', 'assemble' ),
			'error'        => $assemble_errors['email'] ?? '',
		)
	);
	assemble_account_field(
		array(
			'id'           => 'acct-name',
			'name'         => 'full_name',
			'label'        => __( 'Full name', 'assemble' ),
			'value'        => $assemble_value( 'name' ),
			'autocomplete' => 'name',
			'placeholder'  => __( 'First and last name', 'assemble' ),
			'required'     => true,
			'error'        => $assemble_errors['name'] ?? '',
		)
	);
	?>
	<div class="form-row-pair">
		<?php
		assemble_account_field(
			array(
				'id'           => 'acct-company',
				'name'         => 'company',
				'label'        => __( 'Company', 'assemble' ),
				'value'        => $assemble_value( 'company' ),
				'autocomplete' => 'organization',
				'placeholder'  => __( 'Company name', 'assemble' ),
				'required'     => true,
				'error'        => $assemble_errors['company'] ?? '',
			)
		);
		assemble_account_field(
			array(
				'id'           => 'acct-title',
				'name'         => 'job_title',
				'label'        => __( 'Title', 'assemble' ),
				'value'        => $assemble_value( 'job_title' ),
				'autocomplete' => 'organization-title',
				'placeholder'  => __( 'Your job title', 'assemble' ),
			)
		);
		?>
	</div>
</div>
