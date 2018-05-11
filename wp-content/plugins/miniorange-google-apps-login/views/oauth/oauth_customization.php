<?php

$custom_CSS=format_custom_css_value($custom_CSS);
function format_custom_css_value( $textarea){
	$lines = explode(";", $textarea);
	$newline='';
	for($i=0;$i<count($lines);$i++) {
		{
			if ( $i < count( $lines ) - 1 ) {

				$newline .= $lines[ $i ] . ";\r\n";
			} else if ( $i == count( $lines ) - 1 ) {
				$newline .= $lines[ $i ] . "\r\n";
			}
		}
	}
	return $newline;
}
echo '
<div class="mo_registration_divided_layout">
			<div class="mo_gsuite_registration_table_layout">';
is_gsuite_customer_registered();
echo' <form id="customization_form" name="form-common" method="post" action="">
		<input value="mo_oauth_app_customization" type="hidden" name="option"  />
		
		<input id="action_value" type="hidden" name="action"  />
		<h2>Customize Icons</h2>
		<table class="mo_settings_table">
			<tr>
				<td><strong>Icon Width:</strong></td>
				<td><input  '.$disabled.'  type="text" id="mo_oauth_icon_width" name="mo_oauth_icon_width" value="' . $icon_width . '"> e.g. 200px or 100%</td>
			</tr>
			<tr>
				<td><strong>Icon Height:</strong></td>
				<td><input  '.$disabled.'  type="text" id="mo_oauth_icon_height" name="mo_oauth_icon_height" value="' . $icon_height . '"> e.g. 50px or auto</td>
			</tr>
			<tr>
				<td><strong>Icon Margins:</strong></td>
				<td><input  '.$disabled.'  type="text" id="mo_oauth_icon_margin" name="mo_oauth_icon_margin" value="' . $icon_margin . '"> e.g. 2px 0px or auto</td>
			</tr>
			<tr>
				<td><strong>Custom CSS:</strong></td>
				<td><textarea type="text" id="mo_oauth_icon_configure_css" style="resize: vertical; width:400px; height:180px;  margin:5% auto;" rows="6" name="mo_oauth_icon_configure_css" value="">'.$custom_CSS.'
				</textarea>
				
				<br/><b>Example CSS:</b> 
<pre>
.oauthloginbutton{
	background: #7272dc;
	height:40px;
	padding:8px;
	text-align:center;
	color:#fff;
}
</pre>
			</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input  '.$disabled.' type="button" name="btnSubmit" value="Save settings" onclick="submit_function(\'mo_oauth_app_customization_submit\')" class="button button-primary button-large" />
					<input  '.$disabled.' type="button"
					style=" padding-left: 20px;margin-left:  10px;width: 80px; " onclick="submit_function(\'mo_oauth_customization_reset\')" name="btnSubmit" value="Reset"
					class="button button-primary button-large" />
				</td>
			</tr>
		</table>
	</form>
	</div>
	</div>
	';
echo '<script>
function submit_function(actionval) {
    //alert(actionval);
    document.getElementById("action_value").setAttribute(\'value\',actionval);
	document.getElementById("customization_form").submit();
}
</script>';
//ToDo  here on line 24 custom css. Load;