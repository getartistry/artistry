<?php
/**
 * Live provider collector template
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/templates/popup/collector
 */
?>
<div id="upload_container">
    <span class="btn btn-success fileinput-button">
        <h2>Live, Hotmail, Msn</h2>
        <p><?php _e('To import your contacts emails addresses please follow these two simple steps','wsi');?></p>
        <ol>
	        <li><?php echo sprintf(__('Go to Outlook contacts by clicking <a href="%s" target="_blank">here</a>.','wsi'), 'https://mail.live.com/mail/GetContacts.aspx');?></li>
	        <li><?php _e('Once there, select all your contacts and click on "Export for Outlook and other services" to download OutlookContacts.csv into your computer.','wsi');?></li>
	        <li><?php _e('Find OutlookContacts.csv in your computer, usually located in downloads folder and drag and drop it the "drag & drop" zone ','wsi');?></li>
        </ol>

        <div id="dropzone" class="fade well"><?php _e('Drop your files here','wsi');?></div>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
        <div id="progress" class="progress progress-animated progress-striped">
	        <div class="bar"></div>
        </div>
	    <div class="errors alert-error alert" style="display:none;">

	    </div>
    </span>
</div>
<div class="friends-wrapper">
	<?php include_once('hybridauth.php');?>
</div>
