<?php function mo_ga_sso_support_form() { 
	?>
	<div class="mo_ga_sso_support_layout">
		<div>
			<h3>Support</h3>
			<p>Need any help? We can help you with configuring your Identity Provider. Just send us a query and we will get back to you soon.</p>
			<form method="post" action="">
				<input type="hidden" name="option" value="mo_ga_sso_contact_us_query_option" />
				<table class="mo_ga_sso_settings_table">
					<tr>
						<td><input style="width:95%" type="email" class="mo_ga_sso_table_textbox" required name="mo_ga_sso_contact_us_email" value="<?php echo get_option("mo_ga_sso_admin_email"); ?>" placeholder="Enter your email"></td>
					</tr>
					<tr>
						<td><input type="tel" style="width:95%" id="contact_us_phone" pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" class="mo_ga_sso_table_textbox" name="mo_ga_sso_contact_us_phone" value="<?php echo get_option('mo_ga_sso_admin_phone');?>" placeholder="Enter your phone"></td>
					</tr>
					<tr>
						<td><textarea class="mo_ga_sso_table_textbox" style="width:95%" onkeypress="mo_ga_sso_valid_query(this)" onkeyup="mo_ga_sso_valid_query(this)" onblur="mo_ga_sso_valid_query(this)" required name="mo_ga_sso_contact_us_query" rows="4" style="resize: vertical;" placeholder="Write your query here"></textarea></td>
					</tr>
				</table>
				<div style="text-align:center;">
					<input type="submit" name="submit" style="margin:15px; width:120px;" class="button button-primary button-large" />
				</div>
			</form>
		</div>
	</div>
	<script>
		jQuery("#contact_us_phone").intlTelInput();
		jQuery("#phone_contact").intlTelInput();
		function mo_ga_sso_valid_query(f) {
			!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
					/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
		}
		function showTestWindow() {
		var myWindow = window.open("<?php echo mo_ga_sso_get_test_url(); ?>", "TEST SAML IDP", "scrollbars=1 width=800, height=600");	
		}
	</script>
<?php }