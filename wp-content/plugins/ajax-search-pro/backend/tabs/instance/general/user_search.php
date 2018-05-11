<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_search", "Enable search in users?",
        $sd['user_search']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_login_search", "Search in user login names?",
        $sd['user_login_search']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_display_name_search", "Search in user display names?",
        $sd['user_display_name_search']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_first_name_search", "Search in user first names?",
        $sd['user_first_name_search']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_last_name_search", "Search in user last names?",
        $sd['user_last_name_search']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_bio_search", "Search in user bio?",
        $sd['user_bio_search']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_email_search", "Search in user email addresses?",
        $sd['user_email_search']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsUserRoleSelect("user_search_exclude_roles", "User roles exclude",
        $sd['user_search_exclude_roles']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wd_UserSelect("user_search_exclude_users", "Exclude or Include users from results", array(
        "value"=>$sd['user_search_exclude_users'],
        'args'=> array(
            'show_type' => 1,
            'show_all_users_option' => 0
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_search_display_images", "Display user images?",
        $sd['user_search_display_images']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("user_search_image_source", "Image source",
        array(
            'selects' => array(
                array('option' => 'Default', 'value' => 'default'),
                array('option' => 'BuddyPress avatar', 'value' => 'buddypress')
            ),
            'value' => $sd['user_search_image_source']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wd_UserMeta("user_search_meta_fields", "Search in following user meta fields", array(
        "value"=>$sd['user_search_meta_fields'],
        'args'=> array()
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBP_XProfileFields("user_bp_fields", "Search in these BP Xprofile fields",
        $sd['user_bp_fields']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("user_search_title_field", "Title field",
        array(
            'selects' => array(
                array('option' => 'Login Name', 'value' => 'login'),
                array('option' => 'Display Name', 'value' => 'display_name')
            ),
            'value' => $sd['user_search_title_field']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("user_search_description_field", "Description field",
        array(
            'selects' => array(
                array('option' => 'Biography', 'value' => 'bio'),
                array('option' => 'BuddyPress Last Activity', 'value' => 'buddypress_last_activity'),
                array('option' => 'Nothing', 'value' => 'nothing')
            ),
            'value' => $sd['user_search_description_field']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("user_search_advanced_title_field", "Advanced title field",
        $sd['user_search_advanced_title_field']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Variable {titlefield} will be replaced with the Title field value. Use the format {meta_field} to get user meta. <br><a href="https://wp-dreams.com/go/?to=asp-doc-advanced-title-content" target="_blank">More possibilities explained here!</a></p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("user_search_advanced_description_field", "Advanced description field",
        $sd['user_search_advanced_description_field']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Variable {descriptionfield} will be replaced with the Description field value. Use the format {meta_field} to get user meta.<br><a href="https://wp-dreams.com/go/?to=asp-doc-advanced-title-content" target="_blank">More possibilities explained here!</a></p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("user_search_redirect_to_custom_url", "Redirect to custom url when clicking on a result?",
        $sd['user_search_redirect_to_custom_url']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("user_search_url_source", "Result url source",
        array(
            'selects' => array(
                array('option' => 'Default', 'value' => 'default'),
                array('option' => 'BuddyPress profile', 'value' => 'bp_profile'),
                array('option' => 'Custom scheme', 'value' => 'custom')
            ),
            'value' => $sd['user_search_url_source']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">This is the result URL destination. By default it's the author profile link.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("user_search_custom_url", "Custom url scheme",
        $sd['user_search_custom_url']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">You can use these variables: {USER_ID}, {USER_LOGIN}, {USER_NICENAME}, {USER_DISPLAYNAME}</p>
</div>