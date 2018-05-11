<?php

echo '	<div class="mo_gsuite_registration_support_layout">
			<h3>' . mo_gsuite_( "Support" ) . '</h3>
			<p>' . mo_gsuite_( "Need any help? Just send us a query so we can help you." ) . '</p>
				<form name="f" method="post" action="">
					<input type="hidden" name="option" value="mo_gal_validation_contact_us_query_option"/>
					<table class="mo_registration_settings_table">
						<tr><td>
							<input type="email" class="mo_registration_table_textbox" id="query_email" name="query_email" value="' . $email . '" 
								placeholder="' . mo_gsuite_( "Enter your Email" ) . '" required />
							</td>
						</tr>
						<tr><td>
							<input type="text" class="mo_registration_table_textbox" name="query_phone" id="query_phone" value="' . $phone . '" 
								placeholder="' . mo_gsuite_( "Enter your phone" ) . '"/>
							</td>
						</tr>
						<tr>
							<td>
								<textarea id="query" name="query" class="mo_registration_settings_textarea" style="resize: vertical;width:100%" 
									cols="52" rows="7" onkeyup="mo_gal_valid_query(this)" onblur="mo_gal_valid_query(this)" 
									onkeypress="mo_gal_valid_query(this)" 
									placeholder="' . mo_gsuite_( "Write your query here" ) . '"></textarea>
							</td>
						</tr>
					</table>
					<input type="submit" name="send_query" id="send_query" value="' . mo_gsuite_( "Submit Query" ) . '" 
						style="margin-bottom:3%;" class="button button-primary button-large" />
				</form>
				<br />			
		</div>

		<script>
		  	
			function moSharingSizeValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:10>t&&(e.value=10)
			}
			function moSharingSpaceValidate(e){
				var t=parseInt(e.value.trim());t>50?e.value=50:0>t&&(e.value=0)
			}
			function moLoginSizeValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:20>t&&(e.value=20)
			}
			function moLoginSpaceValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
			}
			function moLoginWidthValidate(e){
				var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
			}
			function moLoginHeightValidate(e){
				var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
			}
		</script>';