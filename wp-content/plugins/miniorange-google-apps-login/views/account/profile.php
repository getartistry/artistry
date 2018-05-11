<?php

echo '<div class="mo_registration_divided_layout">
	<div class="mo_gsuite_registration_table_layout">';

if ( ! $vl ) {
	echo '	
		<div>
			<div style="width:50%;float:left;"><h4>Thank you for registering with us.</h4></div>
			<span style="width:50%;float:left;text-align:right;margin: 1em 0 1.33em 0">
				<input type="button" ' . $disabled . ' name="check_btn" id="check_btn" class="button button-primary button-large" value="' . mo_gsuite_( "Ok, Got It" ) . ' " 
				onclick="window.location.href=\'' . site_url().'/wp-admin/admin.php?page=mogalsettings' . '\'"/>
			</span>';
} else {
	echo '<div>
		<div style="width:100%;float:left;"><h4>' . mo_gsuite_( "Thank you for registering with us." ) . '</h4></div>';
}

echo '
	</div>
		<h3>' . mo_gsuite_( "Your Profile" ) . '</h3>
		<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:100%">
			<tr>
				<td style="width:45%; padding: 10px;"><b>' . mo_gsuite_( "Registered Email" ) . '</b></td>
				<td style="width:55%; padding: 10px;">' . $email . '</td>
			</tr>
			<tr>
				<td style="width:45%; padding: 10px;"><b>' . mo_gsuite_( "Customer ID" ) . '</b></td>
				<td style="width:55%; padding: 10px;">' . $customer_id . '</td>
			</tr>
			<tr>
				<td style="width:45%; padding: 10px;"><b>' . mo_gsuite_( "API Key" ) . '</b></td>
				<td style="width:55%; padding: 10px;">' . $api_key . '</td>
			</tr>
			<tr>
				<td style="width:45%; padding: 10px;"><b>' . mo_gsuite_( "Token Key" ) . '</b></td>
				<td style="width:55%; padding: 10px;">' . $token . '</td>
			</tr>
		</table>
		
		<br/>
		
	</div>
</div>';