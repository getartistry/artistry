<?php

class ITSEC_Version_Management_Validator extends ITSEC_Validator {

	public function get_id() {
		return 'version-management';
	}

	protected function sanitize_settings() {
		$this->vars_to_skip_validate_matching_fields[] = 'update_details';
		$this->vars_to_skip_validate_matching_fields[] = 'is_software_outdated';
		$this->vars_to_skip_validate_matching_fields[] = 'old_site_details';
		$this->vars_to_skip_validate_matching_fields[] = 'email_contacts';
		$this->vars_to_skip_validate_matching_fields[] = 'automatic_update_emails';

		$this->set_previous_if_empty( array( 'email_contacts', 'automatic_update_emails' ) );

		$this->sanitize_setting( 'bool', 'wordpress_automatic_updates', __( 'WordPress Automatic Updates', 'it-l10n-ithemes-security-pro' ) );
		$this->sanitize_setting( 'bool', 'plugin_automatic_updates', __( 'Plugin Automatic Updates', 'it-l10n-ithemes-security-pro' ) );
		$this->sanitize_setting( 'bool', 'theme_automatic_updates', __( 'Theme Automatic Updates', 'it-l10n-ithemes-security-pro' ) );
		$this->sanitize_setting( 'bool', 'strengthen_when_outdated', __( 'Strengthen Site When Running Outdated Software', 'it-l10n-ithemes-security-pro' ) );
		$this->sanitize_setting( 'bool', 'scan_for_old_wordpress_sites', __( 'Scan For Old WordPress Sites', 'it-l10n-ithemes-security-pro' ) );
	}

	public function get_validated_contact( $contact ) {
		_deprecated_function( __METHOD__, '3.9.0' );

		return false;
	}

	public function get_available_admin_users_and_roles() {
		_deprecated_function( __METHOD__, '3.9.0' );

		return array(
			'users' => array(),
			'roles' => array(),
		);
	}
}

ITSEC_Modules::register_validator( new ITSEC_Version_Management_Validator() );
