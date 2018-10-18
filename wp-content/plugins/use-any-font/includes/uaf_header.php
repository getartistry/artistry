<?php 
if (isset($_POST['ucf_api_key_submit'])){
	$uaf_api_key 	= trim($_POST['uaf_api_key']);
	$api_key_return = wp_remote_get($uaf_font_convert_server_url.'/font-convertor/api/edd_validate_key.php?license_key='.$uaf_api_key.'&url='.home_url(), array('timeout'=>300,'sslverify'=>false,'user-agent'=>get_bloginfo( 'url' )));
	if ( is_wp_error( $api_key_return ) ) {
	   $error_message 	= $api_key_return->get_error_message();
	   $api_message 	= "Something went wrong: $error_message";
	   $api_msg_type    = 'error';
	} else {
	    $api_key_return = json_decode($api_key_return['body']);
		if ($api_key_return->status == 'success'){
			update_option('uaf_api_key', $uaf_api_key);
			update_option('uaf_api_package', $api_key_return->package);
		}
		$api_msg_type   = $api_key_return->status;
		$api_message 	= $api_key_return->msg;
	}
}

if (isset($_POST['ucf_api_key_remove'])){
	$uaf_api_key		= get_option('uaf_api_key');
	$api_key_return 	= wp_remote_get($uaf_font_convert_server_url.'/font-convertor/api/edd_deactivate_key.php?license_key='.$uaf_api_key.'&url='.home_url(), array('timeout'=>300,'sslverify'=>false,'user-agent'=>get_bloginfo( 'url' )));
	if ( is_wp_error( $api_key_return ) ) {
	   $error_message 	= $api_key_return->get_error_message();
	   $api_message 	= "Something went wrong: $error_message";
	   $api_msg_type    = 'error';
	} else {
	    $api_key_return = json_decode($api_key_return['body']);
		if ($api_key_return->status == 'success'){
			delete_option('uaf_api_key');
			delete_option('uaf_api_package');
		}
		$api_msg_type   = $api_key_return->status;
		$api_message 	= $api_key_return->msg;
	}	
}

$uaf_api_key					=	get_option('uaf_api_key');
$uaf_api_package				=	get_option('uaf_api_package');
$delete_confirmation_msg		= 	'Are you sure ?';

?>
<?php if (!empty($api_message)):?>
	<div class="updated <?php echo $api_msg_type; ?>" id="message"><p><?php echo $api_message ?></p></div>
<?php endif; ?>
<div class="wrap">
<h2>Use Any Font</h2>
<table width="100%">
	<tr>
    	<td valign="top">
            <table class="wp-list-table widefat fixed bookmarks">
                <thead>
                    <tr>
                        <th><strong>API KEY</strong></th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                    	<form action="admin.php?page=uaf_settings_page" method="post" id="uaf_api_key_form" >
                        API KEY :
                    	<?php if (empty($uaf_api_key)): ?>
                        <input name="uaf_api_key" id="uaf_api_key" type="text" style="width:350px; margin-left:50px;" />
                        <input type="submit" name="ucf_api_key_submit" class="button-primary" value="Verify" style="padding:2px;" />
                        <input type="button" name="uaf_api_key_generate" id="uaf_api_key_generate" class="button-primary" value="Generate Test API Key" style="padding:2px;" onclick="uaf_lite_api_key_generate();" />
                        <br/> <br/>
                        Use Any Font need API key to upload the font. You can get the premium key from <a href="https://dineshkarki.com.np/use-any-font/api-key" target="_blank">here</a>. You can also generate Lite / Test API key from button above. <strong>Note : </strong> Lite / Test API only allow single font conversion. 
                        <br/>
                        <?php else: ?>
                        	<span class="active_key"><?php echo $uaf_api_key;  ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Active</span>							<input type="submit" name="ucf_api_key_remove" class="button-primary" value="Remove Key" style="padding:2px; margin-left:20px;" onclick="if(!confirm('<?php echo $delete_confirmation_msg; ?>')){return false;}" />
                        <?php endif;?>
                        </form>
                        <br/>                        
                        <strong>Note</strong> : API key is need to connect to our server for font conversion. Our server converts your fonts to required types and sends it back.
                        <br/><br/>
                   	</td>
                    
                </tr>
                </tbody>
            </table>
            <br/>
<script>
function uaf_lite_api_key_generate(){	
	jQuery.ajax({url: "<?php echo $uaf_font_convert_server_url.'/font-convertor/convertor/generate_lite_key.php'; ?>",
	beforeSend : function(){
		jQuery('#uaf_api_key_generate').val('Generating...');
	},
	error: function(){
		jQuery('#uaf_api_key_generate').val('Error!');		
	},
	success: function(result){
        var dataReturn 	= JSON.parse(result);
		key 			= dataReturn.key;
		jQuery('#uaf_api_key').val(key);
		jQuery('#uaf_api_key_generate').val('Click Verify to Complete');
    }});
}
</script>