<?php
/**
 * Registration message 
 *
 * @version	1.1
 * @since 1.4
 * @package	Wordpress Social Invitations
 * @author Timersys
 */
?>
<div class="message <?php echo $box_class;?>" style="margin:10px 0;">
    <p><?php   printf( __( 'Welcome! You\'ve been invited %s to join the site. Please fill out the information to create your account.', 'wsi' ), $inviter_text );?></p>
</div>