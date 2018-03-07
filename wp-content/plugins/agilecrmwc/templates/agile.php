<div class='wrap' style='background: white; padding: 20px'>
    <?php echo '<img src="' . plugins_url('static/agile500.png', dirname(__FILE__)) . '" > '; ?>
    <h3>
        Do not have an account with Agile CRM? <span style="font-size: 75%;">It's
            fast and free for two users</span>
    </h3>
    <div
        style="width: auto; height: auto; color: #8a6d3b; background-color: #fcf8e3; border: 1px solid #faebcc; border-radius: 5px">
        <div>
            <a href="https://www.agilecrm.com/pricing?utm_source=woocommerce&utm_medium=website&utm_campaign=integration" target="_blank"> <?php submit_button('Create a new account', 'secondary', 'create_account', false); ?>
            </a>
        </div>
        <p style="margin-left: 50px; margin-top: 15px;"
           id="create_account_text">Once you have created, please come back and
            fill in the details below</p>
    </div>
    <br>
    <h3>
        Already have an account? <span style="font-size: 75%;">Enter your
            details</span>
    </h3>
    <div
        style="width: auto; height: auto; border: 1px solid #e3e3e3; background-color: #f5f5f5; border-radius: 5px">
        <form action="options.php" method="POST"
              style="margin-left: 25px; margin-top: 20px;" id="settings_form">
                  <?php settings_fields('agile-settings-group'); ?>
                  <?php do_settings_sections('agile-plugin'); ?>
            <table class="form-table">			
                <tr><th scope="row">Settings</th>
                    <td>
                        <?php
                        $AgileSettingsOptions = get_option('agile-sync-settings');
                        if ($AgileSettingsOptions === false) {
                            $AgileSettingsOptions['sync_customers'] = 1; //default
                            $AgileSettingsOptions['sync_orders'] = 1; //default
                            $AgileSettingsOptions['track_visitors'] = 0;
                            $AgileSettingsOptions['web_rules'] = 1;
                        } elseif (!is_array($AgileSettingsOptions)) {
                            $AgileSettingsOptions['sync_customers'] = 0;
                            $AgileSettingsOptions['sync_orders'] = 0;
                            $AgileSettingsOptions['sync_product_tags'] = 0;
                            $AgileSettingsOptions['sync_category_tags'] = 0;
                            $AgileSettingsOptions['track_visitors'] = 0;
                            $AgileSettingsOptions['web_rules'] = 0;
                        }
                        ?>			
                        <input type="checkbox" id="sync_customers" name="agile-sync-settings[sync_customers]" value="1" <?php checked(1, $AgileSettingsOptions['sync_customers'], true); ?>/><label for="sync_customers">Sync customers</label><br>
                        <small>Adds all new customers as contacts in Agile</small>
                        <div style="margin-left:20px;margin-top:8px;">
                            <div style="margin-bottom: 5px;"><input type="checkbox" id="sync_orders" name="agile-sync-settings[sync_orders]" value="1" <?php checked(1, $AgileSettingsOptions['sync_orders'], true); ?>/><label for="sync_orders">Attach order data & notes to contacts</label></div>
                            <div style="margin-bottom: 5px;"><input type="checkbox" id="sync_product_tags" name="agile-sync-settings[sync_product_tags]" value="1" <?php checked(1, $AgileSettingsOptions['sync_product_tags'], true); ?>/><label for="sync_product_tags">Add ordered product names as tags</label></div>
                            <div style="margin-bottom: 5px;"><input type="checkbox" id="sync_category_tags" name="agile-sync-settings[sync_category_tags]" value="1" <?php checked(1, $AgileSettingsOptions['sync_category_tags'], true); ?>/><label for="sync_category_tags">Add ordered product categories as tags</label></div>
                        </div>
                        <input type="checkbox" id="track_visitors" name="agile-sync-settings[track_visitors]" value="1" <?php checked(1, $AgileSettingsOptions['track_visitors'], true); ?>/><label for="track_visitors">Log web activity of customers</label><br><br>
                        <input type="checkbox" id="web_rules" name="agile-sync-settings[web_rules]" value="1" <?php checked(1, $AgileSettingsOptions['web_rules'], true); ?>/><label for="web_rules">Enable web rules</label>
                    </td></tr>			
            </table><br>
            <span><?php submit_button('Save Changes', 'primary', 'submit_button', false); ?>&nbsp;<span
                    style="vertical-align: sub;" id="error_text"></span>

        </form>
    </div>
</div>
<div>
    <p>Like Agile CRM? Share the love.</p>
    <span style="display: inline-block; width: 225px;">&nbsp;&nbsp;<a
            href="https://twitter.com/share" class="twitter-share-button"
            data-url="https://www.agilecrm.com"
            data-text="Sell like Fortune 500 with #AgileCRM">Tweet</a>
        <div id="fb-root" style='display: inline-block;'></div>
        <div class="fb-like" style='display: inline-block; float: left;'
             data-href="https://www.facebook.com/CRM.Agile" data-layout="button"
             data-action="like" data-show-faces="false" data-share="true"></div>
    </span>
</div>
<?php echo '<script type="text/javascript" src="' . plugins_url('static/agile_js.js?v=1', dirname(__FILE__)) . '"></script>'; ?>