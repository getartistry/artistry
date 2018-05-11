<div class="item item-flex-nogrow" style="flex-wrap:wrap;">
    <?php
    $o = new wpdreamsYesNo("mob_display_search", "Display the search bar on <strong>mobile</strong> devices?",
        $sd['mob_display_search']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsYesNo("desktop_display_search", " .. and on <strong>desktop</strong> devices?",
        $sd['desktop_display_search']);
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
        If you want to hide this search bar on mobile/desktop devices then turn OFF these option.
    </div>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("mob_trigger_on_type", "Trigger search when typing on mobile keyboard?",
        $sd['mob_trigger_on_type']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $_red_opts = array_merge(
        array(array('option'=>'Same as on desktop', 'value'=>'same')),
        $_red_opts
    );
    $o = new wpdreamsCustomSelect("mob_click_action", "Action when tapping <strong>the magnifier</strong> icon",
        array(
            'selects' => $_red_opts,
            'value' => $sd['mob_click_action']
        ));
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsCustomSelect("mob_click_action_location", " location: ",
        array(
            'selects' => array(
                array('option' => 'Use same tab', 'value' => 'same'),
                array('option' => 'Open new tab', 'value' => 'new')
            ),
            'value' => $sd['mob_click_action_location']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsCustomSelect("mob_return_action", "Action when tapping <strong>the return</strong> button (search icon on virtual keyboard)<br>",
        array(
            'selects' => $_red_opts,
            'value' => $sd['mob_return_action']
        ));
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsCustomSelect("mob_return_action_location", " location: ",
        array(
            'selects' => array(
                array('option' => 'Use same tab', 'value' => 'same'),
                array('option' => 'Open new tab', 'value' => 'new')
            ),
            'value' => $sd['mob_return_action_location']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("mob_redirect_url", "Custom redirect URL",
        $sd['mob_redirect_url']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">You can use the <string>asp_redirect_url</string> filter to add more variables. See <a href="http://wp-dreams.com/go/?to=kb-redirecturl" target="_blank">this tutorial</a>.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("mob_hide_keyboard", "Hide the mobile keyboard when displaying the results?",
        $sd['mob_hide_keyboard']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("mob_force_res_hover", "Force 'hover' results layout on mobile devices?",
        $sd['mob_force_res_hover']);
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg">This will force to display the results below the search bar (floating above the content) on mobile devices, even if it's configured otherwise (or if the results shortcode is used).</div>
</div>
<div class="item item-flex-nogrow" style="flex-wrap:wrap;">
    <?php
    $o = new wpdreamsYesNo("mob_force_sett_hover", "Force 'hover' settings layout on mobile devices?",
        $sd['mob_force_sett_hover']);
    $params[$o->getName()] = $o->getData();
    ?>
    <?php
    $o = new wpdreamsCustomSelect("mob_force_sett_state", " and force state ", array(
        'selects' => array(
            array("option" => "hidden settings", "value" => "closed"),
            array("option" => "visible settings", "value" => "open")
        ),
        "value" => $sd['mob_force_sett_state']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
        This will force to display the settings below the search bar (floating above the content) on mobile devices, even if the settings shortcode is used.
    </div>
</div>