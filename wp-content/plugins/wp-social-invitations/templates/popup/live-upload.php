<div id="upload_container">	
    <span class="btn btn-success fileinput-button">
        <h2>Live, Hotmail, Msn</h2>
        <p><?php _e('To import your contacts emails addresses please follow these two simple steps',$WPB_PREFIX);?></p>
        <ol>
        	<li><?php _e('Download WLMContacts.csv to your computer by clicking',$WPB_PREFIX);?> :<a href="https://mail.live.com/mail/GetContacts.aspx" title="Download" class="button">Download</a></li>
        	<li><?php _e('Find WLMContacts.csv in your computer, usually located in downloads folder and drag and drop it the "drag & drop" zone ',$WPB_PREFIX);?></li>
        </ol>
        
        <div id="dropzone" class="fade well"><?php _e('Drop your files here',$WPB_PREFIX);?></div>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
        <div id="progress" class="progress progress-animated progress-striped">
	        <div class="bar"></div>
	    </div>
	    <div class="errors alert-error alert" style="display:none;">
	    
	    </div>
    </span>
</div>