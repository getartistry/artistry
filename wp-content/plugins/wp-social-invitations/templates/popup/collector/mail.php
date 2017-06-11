<?php
/**
 * Mail provider collector template
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/templates/popup/collector
 */
?>
<h2><?php _e("Invite your friends", 'wsi');?></h2>
<div class="mail-wrapper">
	<label for="subject"><?php _e('Enter your friends emails, one per line', 'wsi');?></label>
	<textarea name="friend" id="emails" style="height:120px;"  class="form-control" required="required"></textarea>
</div>