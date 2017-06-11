<?php

class ITSEC_Help_Admin {

	function run() {

		add_action( 'itsec_add_admin_meta_boxes', array( $this, 'add_admin_meta_boxes' ) ); //add meta boxes to admin page

	}

	/**
	 * Add meta boxes to primary options pages
	 *
	 */
	public function add_admin_meta_boxes() {

		add_meta_box(
			'itsec_help_info',
			__( 'Help', 'it-l10n-ithemes-security-pro' ),
			array( $this, 'add_help_intro' ),
			'security_page_toplevel_page_itsec_help',
			'normal',
			'core'
		);

	}

	/**
	 * Build and echo the away mode description
	 *
	 * @return void
	 */
	public function add_help_intro() {

		$content = '<p>' . __( 'Website security is a complicated subject, but we have experts that can help.', 'it-l10n-ithemes-security-pro' ) . '</p>';

		$content .= '<p><strong>' . __( 'iThemes Security Pro Support', 'it-l10n-ithemes-security-pro' ) . '</strong><br />';
		$content .= __( 'As an iThemes Security Pro customer, you can create a support ticket now. Our team of experts is ready to help.', 'it-l10n-ithemes-security-pro' ) . '</p>';
		$content .= '<p><a class="button-secondary" href="http://ithemes.com/member/support.php" target="_blank">' . __( 'Create a support ticket', 'it-l10n-ithemes-security-pro' ) . '</a></p>';
		$content .= '<hr>';

		$content .= '<p><strong>' . __( 'Hack Repair', 'it-l10n-ithemes-security-pro' ) . '</strong><br />';
		$content .= __( 'Has your site been hacked? Contact Sucuri, our recommended hack repair partner, to get things back in order.', 'it-l10n-ithemes-security-pro' ) . '</p>';
		$content .= '<p><a class="button-secondary" href="https://ithemes.com/sucuri" target="_blank">' . __( 'Get hack repair', 'it-l10n-ithemes-security-pro' ) . '</a></p>';

		echo $content;

	}

}
