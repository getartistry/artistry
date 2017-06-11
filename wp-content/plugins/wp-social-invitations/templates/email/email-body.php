<?php
/**
 * email template content
 *
 * @version	1.0
 * @since 1.4
 * @package	Wordpress Social Invitations
 * @author Timersys */
if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php wsi_get_template('email/header.php', array( 'email_subject' => $email_subject ) ); ?>

<?php print $emailContent; ?>

<?php wsi_get_template('email/footer.php', array( 'email_footer' => $email_footer ) ); ?>