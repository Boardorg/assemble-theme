<?php
/**
 * Community apply band: the apply-to-join form, in the site's own markup.
 *
 * Replaces HubSpot's iframe embed (which our CSS can't reach) with the same
 * three steps as board.org's "Apply to join form (new)": the community, the
 * membership criteria, then contact details. assets/js/community-join.js runs
 * the steps and posts the answers to that same HubSpot form through its public
 * submissions endpoint, so entries, notifications and workflows are unchanged.
 * Field names are HubSpot's ("<objectTypeId>/<property>"); this Board's option
 * comes checked. Without JavaScript the form stays hidden and the <noscript>
 * button in closing.php shows instead.
 *
 * @var array $args { join: assemble_join_form()'s array, fallback: URL }
 */

defined( 'ABSPATH' ) || exit;

$assemble_join  = (array) ( $args['join'] ?? array() );
$assemble_value = (string) ( $assemble_join['value'] ?? '' );
$assemble_uid   = 'join-' . substr( md5( (string) ( $assemble_join['form'] ?? '' ) . $assemble_value ), 0, 6 );

// HubSpot's option values => the labels the form shows (same order as HubSpot).
$assemble_groups = array(
	'AEO'                                     => 'AEO Board',
	'DAIS'                                    => 'Data & AI Strategy Board',
	'Data Privacy Board'                      => 'Data Privacy Board',
	'DEI Board'                               => 'DEI Board',
	'Employee Experience Board'               => 'Culture & Experience Board',
	'ESG & CSR Board'                         => 'ESG & CSR Board',
	'LDB'                                     => 'Learning & Development Board',
	'MFB'                                     => 'Manufacturing Board',
	'SocialMedia.org'                         => 'SocialMedia.org',
	'SocialMedia.org Health'                  => 'SocialMedia.org Health',
	'Supply Chain Planning & Logistics Board' => 'Supply Chain Board',
	'Talent Marketing Board'                  => 'Talent Marketing Board',
);

$assemble_criteria = array(
	'Yes'                                                                 => 'Yes, the above describes me and my organization',
	'No'                                                                  => 'No, not exactly, but I’d still like to be considered for membership',
	"I'm not sure, I'd still like to connect with someone from your team" => 'I’m not sure. I’d still like to connect with someone from your team',
);

$assemble_contact = array(
	array( '0-1/firstname', __( 'First name', 'assemble' ), 'text', 'given-name', true ),
	array( '0-1/lastname', __( 'Last name', 'assemble' ), 'text', 'family-name', true ),
	array( '0-1/email', __( 'Work email', 'assemble' ), 'email', 'email', true ),
	array( '0-2/name', __( 'Company name', 'assemble' ), 'text', 'organization', true ),
	array( '0-1/jobtitle', __( 'Job title', 'assemble' ), 'text', 'organization-title', true ),
	array( '0-1/phone', __( 'Phone number', 'assemble' ), 'tel', 'tel', true ),
	array( '0-1/other_source__c', __( 'How did you hear about us?', 'assemble' ), 'text', 'off', false ),
	array( '0-1/description', __( 'What else should we know about you? Are you interested in any of our other communities?', 'assemble' ), 'textarea', 'off', false ),
);

$assemble_endpoint = sprintf(
	'https://api.hsforms.com/submissions/v3/integration/submit/%s/%s',
	rawurlencode( (string) ( $assemble_join['portal'] ?? '' ) ),
	rawurlencode( (string) ( $assemble_join['form'] ?? '' ) )
);
?>
<form class="join-form" id="<?php echo esc_attr( $assemble_uid ); ?>" data-endpoint="<?php echo esc_url( $assemble_endpoint ); ?>" data-fallback="<?php echo esc_url( (string) ( $args['fallback'] ?? '' ) ); ?>" novalidate hidden>
	<p class="join-form-progress" aria-live="polite"><?php printf( esc_html__( 'Step %1$s of %2$s', 'assemble' ), '<span data-join-step-number>1</span>', '3' ); ?></p>

	<fieldset class="join-step" data-join-step="1">
		<legend class="join-step-heading" tabindex="-1"><?php esc_html_e( 'Which community are you most interested in?', 'assemble' ); ?></legend>
		<div class="join-options" data-join-required="0-1/mql_community_radio">
			<?php foreach ( $assemble_groups as $assemble_option => $assemble_label ) : ?>
				<label class="join-option">
					<input type="radio" name="0-1/mql_community_radio" value="<?php echo esc_attr( $assemble_option ); ?>" <?php checked( $assemble_option, $assemble_value ); ?>>
					<span><?php echo esc_html( $assemble_label ); ?></span>
				</label>
			<?php endforeach; ?>
		</div>
		<p class="field-error" data-join-error="0-1/mql_community_radio" hidden><?php esc_html_e( 'Choose a community to continue.', 'assemble' ); ?></p>
		<div class="join-actions">
			<button type="button" class="btn-solid" data-join-next><?php esc_html_e( 'Next', 'assemble' ); ?></button>
		</div>
	</fieldset>

	<fieldset class="join-step" data-join-step="2" hidden>
		<legend class="join-step-heading" tabindex="-1"><?php esc_html_e( 'Great, we’d love to meet you. Help us understand you and your organization.', 'assemble' ); ?></legend>
		<div class="join-criteria" id="<?php echo esc_attr( $assemble_uid ); ?>-criteria">
			<p><?php esc_html_e( 'Membership is exclusively for senior leaders at large enterprises. Does the following apply to you and your organization?', 'assemble' ); ?></p>
			<ul class="included-checks join-checks">
				<li><?php esc_html_e( 'You are a senior decision-maker with responsibility for your program.', 'assemble' ); ?></li>
				<li><?php esc_html_e( 'You represent a large enterprise with at least $500 million in annual revenue.', 'assemble' ); ?></li>
				<li><?php esc_html_e( 'You and your organization do not sell related products or services.', 'assemble' ); ?></li>
			</ul>
		</div>
		<div class="join-options" role="radiogroup" aria-describedby="<?php echo esc_attr( $assemble_uid ); ?>-criteria" aria-label="<?php esc_attr_e( 'Meets community criteria', 'assemble' ); ?>" data-join-required="0-1/meets_community_criteria__dei_">
			<?php foreach ( $assemble_criteria as $assemble_option => $assemble_label ) : ?>
				<label class="join-option">
					<input type="radio" name="0-1/meets_community_criteria__dei_" value="<?php echo esc_attr( $assemble_option ); ?>">
					<span><?php echo esc_html( $assemble_label ); ?></span>
				</label>
			<?php endforeach; ?>
		</div>
		<p class="field-error" data-join-error="0-1/meets_community_criteria__dei_" hidden><?php esc_html_e( 'Choose the answer that fits best.', 'assemble' ); ?></p>
		<div class="join-actions">
			<button type="button" class="btn-outline" data-join-back><?php esc_html_e( 'Back', 'assemble' ); ?></button>
			<button type="button" class="btn-solid" data-join-next><?php esc_html_e( 'Next', 'assemble' ); ?></button>
		</div>
	</fieldset>

	<fieldset class="join-step" data-join-step="3" hidden>
		<legend class="join-step-heading" tabindex="-1"><?php esc_html_e( 'Last step: what’s the best way to reach you?', 'assemble' ); ?></legend>
		<p class="join-lead"><?php esc_html_e( 'Share your contact details and a member of our team will reach out to set up a brief membership tour and overview.', 'assemble' ); ?></p>
		<div class="join-fields">
			<?php
			foreach ( $assemble_contact as $assemble_field ) :
				list( $assemble_name, $assemble_label, $assemble_type, $assemble_auto, $assemble_required ) = $assemble_field;
				$assemble_id = $assemble_uid . '-' . sanitize_html_class( str_replace( '/', '-', $assemble_name ) );
				$assemble_wide = in_array( $assemble_type, array( 'email', 'textarea' ), true );
				?>
				<div class="join-field<?php echo $assemble_wide ? ' is-wide' : ''; ?>">
					<label class="field-label" for="<?php echo esc_attr( $assemble_id ); ?>">
						<?php echo esc_html( $assemble_label ); ?>
						<?php if ( ! $assemble_required ) : ?>
							<span class="join-optional"><?php esc_html_e( '(optional)', 'assemble' ); ?></span>
						<?php endif; ?>
					</label>
					<?php if ( 'textarea' === $assemble_type ) : ?>
						<textarea class="field-input" id="<?php echo esc_attr( $assemble_id ); ?>" name="<?php echo esc_attr( $assemble_name ); ?>" rows="3"></textarea>
					<?php else : ?>
						<input class="field-input" id="<?php echo esc_attr( $assemble_id ); ?>" type="<?php echo esc_attr( $assemble_type ); ?>" name="<?php echo esc_attr( $assemble_name ); ?>" autocomplete="<?php echo esc_attr( $assemble_auto ); ?>" <?php echo $assemble_required ? 'required aria-required="true"' : ''; ?>>
					<?php endif; ?>
					<p class="field-error" id="<?php echo esc_attr( $assemble_id ); ?>-error" data-join-error="<?php echo esc_attr( $assemble_name ); ?>" hidden></p>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="field-error join-form-error" data-join-error="form" role="alert" hidden></p>
		<div class="join-actions">
			<button type="button" class="btn-outline" data-join-back><?php esc_html_e( 'Back', 'assemble' ); ?></button>
			<button type="submit" class="btn-solid" data-join-submit><?php esc_html_e( 'Submit', 'assemble' ); ?></button>
		</div>
	</fieldset>

	<div class="join-done" data-join-done tabindex="-1" hidden>
		<h3><?php esc_html_e( 'Thank you.', 'assemble' ); ?></h3>
		<p><?php esc_html_e( 'A member of our team will be in touch shortly to set up a brief membership tour and overview.', 'assemble' ); ?></p>
	</div>
</form>
