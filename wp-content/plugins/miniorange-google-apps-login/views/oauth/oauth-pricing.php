<?php
?>
<div class="mo_registration_divided_layout" style="width: 95%">
    <div class="mo_gsuite_registration_table_layout">
		<span style="float:right;margin-top:5px">

			<input type="button" name="ok_btn" id="ok_btn" class="button button-primary button-large" value="OK, Got It"
                   onclick="window.location.href='admin.php?page=mogalsettings'"/></span>
        <h2>Licensing Plans</h2>
        <hr>

        <table class="table mo_table-bordered mo_table-striped">

            <thead>
            <tr style="background-color:#0085ba">
                <th width="25%"><br><br><br>
                    <h3><font color="#FFFFFF">Features \ Plans</font></h3>
                </th>

                <th width="18%"><br><br><br>
                    <h3><font color="#FFFFFF">Free</font></h3>
                </th>


                <th class="text-center" width="18%"><h3><font color="#FFFFFF">Standard<br></font></h3>
                    <p class="mo_plan-desc"></p>
                    <h3><b class="tooltip"><font color="#FFFFFF">$99</font><span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>
                <input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now"
                       onclick="getupgradelicensesform('wp_oauth_client_standard_plan')"/>


                </span></h3></th>

                <th class="text-center" width="18%"><h3><font color="#FFFFFF">Premium<br></font></h3>
                    <p class="mo_plan-desc"></p>
                    <h3><b class="tooltip"><font color="#FFFFFF">$149</font><span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>

                <input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now"
                       onclick="getupgradelicensesform('wp_oauth_client_premium_plan')"/>


                </span></h3></th>

                <th class="text-center" width="18%"><h3><font color="#FFFFFF">Enterprise
                        </font></h3>
                    <p></p>
                    <p class="mo_plan-desc"></p>
                    <h3><b class="tooltip"><font color="#FFFFFF">$249</font><span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>
      <input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now"
             onclick="getupgradelicensesform('wp_oauth_client_enterprise_plan')"/>

            </tr>
            </thead>
            <tbody class="mo_align-center mo-fa-icon">

            <tr>
                <td>OAuth provider support</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>Unlimited</td>
            </tr>

            <tr>
                <td>Auto register users</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Auto Fill OAuth Server Configuration</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Basic Attribute Mapping(Email,Firstname)</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Login using link</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>


            <tr>
                <td>Auto Create User</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Advanced Attribute Mapping(Username, Firstname, Lastname,Group Name)</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Login widget/Shortcode</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Custom Login button and CSS</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Custom Redirect URL after Login and Logout</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Basic Role Mapping (Support for default role for new user)</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Advanced Role Mapping</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Custom Attribute Mapping</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>


            <tr>
                <td>Force Authentication / Project Complete site</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>OpenId Connect Support (Login using OpenId Connect Server)</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Multiple User info endpoint Support</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Account Linking</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Domain Specific Registration</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Multisite Support</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Reverse Proxy Support</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>


            <tr>
                <td>BuddyPress Attribute Mapping</td>
                <td></td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Dynamic CallBack URL</td>
                <td></td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Page Restriction</td>
                <td></td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Wp Hooks for Different Events</td>
                <td></td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

            <tr>
                <td>Login Reports Analysis</td>
                <td></td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
            </tr>

        </table>
        <form style="display:none;" id="loginform" action="<?php echo get_option( 'host_name' ) . '/moas/login'; ?>"
              target="_blank" method="post">
            <input type="email" name="username" value="<?php echo get_option( 'mo_gsuite_customer_validation_admin_email' ); ?>"/>
            <input type="text" name="redirectUrl"
                   value="<?php echo get_option( 'host_name' ) . '/moas/viewlicensekeys'; ?>"/>
            <input type="text" name="requestOrigin" id="requestOrigin1"/>
        </form>
        <form style="display:none;" id="licenseform" action="<?php echo get_option( 'host_name' ) . '/moas/login'; ?>"
              target="_blank" method="post">
            <input type="email" name="username" value="<?php echo get_option( 'mo_gsuite_customer_validation_admin_email' ); ?>"/>
            <input type="text" name="redirectUrl"
                   value="<?php echo get_option( 'host_name' ) . '/moas/initializepayment'; ?>"/>
            <input type="text" name="requestOrigin" id="requestOrigin2"/>
        </form>
        <script>


            function getupgradelicensesform(planType) {
                jQuery('#requestOrigin2').val(planType);
                jQuery('#licenseform').submit();
            }

            jQuery('.mo_oauth_content').css("width", "100%");
        </script>
        <br>
        <h3>* Steps to upgrade to premium plugin -</h3>
        <p>1. You will be redirected to miniOrange Login Console. Enter your password with which you created an account
            with us. After that you will be redirected to payment page.</p>
        <p>2. Enter you card details and complete the payment. On successful payment completion, you will see the link
            to download the premium plugin.</p>
    </div>
</div>
<style>
    .mo-fa-icon>tr>td>i.fa{
        color:#5b8a0f;

    }

    .mo_align-center>tr>td{
        text-align:center !important;
    }

    .mo_table-bordered, .mo_table-bordered>tbody>tr>td{
        border: 1px solid #ddd;
    }

    .mo_table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .mo_table-bordered>thead>tr>th{
        vertical-align:top !important;
    }

</style>