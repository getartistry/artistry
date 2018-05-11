<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 08-02-2018
 * Time: 10:03
 */

echo '
<div class="mo_registration_divided_layout">
		<div class="mo_gsuite_registration_table_layout">
			

<div class="mo_oauth_premium_option_text"><span style="color:red;">*</span>This is a premium feature. 
		<a href="admin.php?page=gsuitepricing">Click Here</a> to see our full list of Premium Features.</div>
		<div class="mo_table_layout mo_oauth_premium_option">
		<div class="mo_oauth_client_small_layout">';
echo '<h2>Login Transactions Report</h2>
			<div class="mo_oauth_client_small_layout hidden">	
				<div style="float:right;margin-top:10px">
					<input type="submit" ' . $disabled . ' name="printcsv" style="width:100px;" value="Print PDF" class="button button-success button-large">
					<input type="submit" ' . $disabled . ' name="printpdf" style="width:100px;" value="Print CSV" class="button button-success button-large">
				</div>
				<h3>Advanced Report</h3>
				
				<form id="mo_oauth_client_advanced_reports" method="post" action="">
					<input type="hidden" name="option" value="mo_oauth_client_advanced_reports">
					<table style="width:100%">
					<tr>
					<td width="33%">WordPress Username : <input class="mo_oauth_client_table_textbox" type="text" ' . $disabled . ' name="username" required="" placeholder="Search by username" value=""></td>
					<td width="33%">IP Address :<input class="mo_oauth_client_table_textbox" type="text" ' . $disabled . ' name="ip" required="" placeholder="Search by IP" value=""></td>
					<td width="33%">Status : <select ' . $disabled . ' name="status" style="width:100%;">
						  <option value="success" selected="">Success</option>
						  <option value="failed">Failed</option>
						</select>
					</td>
					</tr>
					<tr><td><br></td></tr>
					<tr>
					<td width="33%">User Action : <select ' . $disabled . ' name="action" style="width:100%;">
						  <option value="login" selected="">User Login</option>
						  <option value="register">User Registeration</option>
						</select>
					</td>
					<td width="33%">From Date : <input ' . $disabled . ' class="mo_oauth_client_table_textbox" type="date"  name="fromdate"></td>
					<td width="33%">To Date :<input ' . $disabled . ' class="mo_oauth_client_table_textbox" type="date"  name="todate"></td>
					</tr>
					</table>
					<br><input type="submit" ' . $disabled . ' name="Search" style="width:100px;" value="Search" class="button button-primary button-large">
				</form>
				<br>
			</div>
			
			<table id="login_reports" class="display" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		                <th>IP Address</th>
						<th>Username</th>
						<th>Status</th>
		                <th>TimeStamp</th>
		            </tr>
		        </thead>
		        <tbody>';

echo '	        </tbody>
		    </table>
		</div>
		
	</div>
	</div>
	</div>
<script>
	jQuery(document).ready(function() {
	    
		jQuery("#login_reports").DataTable({
			"order": [[ 3, "desc" ]]
		});
		jQuery("#error_reports").DataTable({
			"order": [[ 4, "desc" ]]
		});
	} );
</script>';