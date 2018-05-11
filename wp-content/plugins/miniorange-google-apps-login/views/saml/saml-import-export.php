<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 15-02-2018
 * Time: 17:19
 */

echo '
	<div class="mo_registration_divided_layout">
		<div class="mo_gsuite_registration_table_layout">';
is_gsuite_customer_registered();

		echo '<div>
			<h3>Import /Export Configuration</h3>
			<form method="post" action="" name="mo_export" id="mo_export">
				<input type="hidden" name="option" value="mo_saml_export" />
				<table>

				<hr class="header"/>
        <p>This tab will help you to transfer your plugin configurations when you change your Wordpress instance</p>
        <p>Example: When you switch from test environment to production. Follow these 3 simple steps to do that:
            <ol>
                <li>Download plugin configuration file by clicking on the link given below.</li>
                <li>Install the plugin on new Wordpress instance.</li>
                <li>Upload the configuration file in Import Plugin Configurations section.</li>
            </ol></p>
        <p> And just like that, all your plugin configurations will be transferred! </p>
        <p>You can also send us this file along with your support query.</p>

        <hr class="header"/>
        
       				<tr><td><h3>Export Configurations</h3></td></tr>
				<tr><td>';
if ( mo_saml_is_sp_configured() ) {
	echo '<a href="#" id="configuration_anchor" onclick="document.forms[\'mo_export\'].submit();">Click Here</a> to download the plugin configurations</td></tr>';
} else {
	echo '<h4>Please configure the plugin to export the configurations.</h4>';
}
echo '</table>
				<div>
				</div>
			</form>
		<hr class="header">
			<form method="post" action="" enctype="multipart/form-data">
				<input type="hidden" name="option" value="mo_saml_import" />
				<table>
				<tr><td><h3><span style="color:red;">*</span>Import Configurations</h3></td></tr>
				<tr><td><input type="file" name="configuration_file" disabled="disabled"></td>
				<td><input type="submit" disabled="disabled" name="submit" style="width: auto" class="button button-primary button-large" value="Import"/></td></tr>
				
				</table>
				<br><br>
				<div>
				</div>';
if ( get_option( 'mo_saml_free_version' ) ) { ?>
    <span style="color:red;">*</span>These options are configurable in the <a
            href="<?php echo admin_url( 'admin.php?page=gsuitepricing' ); ?>"><b>standard,
            premium and enterprise</b></a> version of the plugin.</h3>
    <br/><br/>
<?php } ?>
</form>
</div>
</div>
</div>

