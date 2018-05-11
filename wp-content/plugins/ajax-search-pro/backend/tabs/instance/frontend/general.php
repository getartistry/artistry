<div class="item">
    <?php
    $o = new wpdreamsYesNo("show_frontend_search_settings", "Show search settings switch on the frontend?", $sd['show_frontend_search_settings']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">This will hide the switch icon, so the user can't open/close the settings.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("frontend_search_settings_visible", "Set the search settings to visible by default?", $sd['frontend_search_settings_visible']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">If set to Yes, then the settings will be visible/opened by default.</p>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsCustomSelect("frontend_search_settings_position", "Search settings position", array(
        'selects'=>array(
            array('option' => 'Hovering (default)', 'value' => 'hover'),
            array('option' => 'Block or custom', 'value' => 'block')
        ),
        'value'=>$sd['frontend_search_settings_position']
    ));
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsCustomSelect("fss_hover_columns", " max. columns ", array(
        'selects'=>array(
            array("option"=>"1", "value" => 1),
            array("option"=>"2", "value" => 2),
            array("option"=>"3", "value" => 3),
            array("option"=>"4", "value" => 4),
            array("option"=>"5", "value" => 5),
            array("option"=>"6", "value" => 6)
        ),
        'value'=>$sd['fss_hover_columns']
    ));
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsCustomSelect("fss_block_columns", " max. columns ", array(
        'selects'=>array(
            array("option"=>"Auto", "value" => "auto"),
            array("option"=>"1", "value" => 1),
            array("option"=>"2", "value" => 2),
            array("option"=>"3", "value" => 3),
            array("option"=>"4", "value" => 4),
            array("option"=>"5", "value" => 5),
            array("option"=>"6", "value" => 6)
        ),
        'value'=>$sd['fss_block_columns']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
        The position is automatically set to Block if you use the settings shortcode.<br><strong>Columns WRAP</strong> if they reach the edge of the screen, or container element!
    </div>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("fss_hide_on_results", "Hide the settings when the results list show up?", $sd['fss_hide_on_results']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">This will hide the settings (hover mode only), when the result list comes on screen.</p>
</div>
<div class="item">
    <?php
    $o = new wd_imageRadio("fss_column_layout", "Column layout", array(
        'images' => array(
            'flex' => "/ajax-search-pro/backend/settings/assets/img/fss_flex.jpg",
            'column' => "/ajax-search-pro/backend/settings/assets/img/fss_column.jpg",
            'masonry' => "/ajax-search-pro/backend/settings/assets/img/fss_masonry.jpg"
        ),
        'value' => $sd['fss_column_layout']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("fss_column_width", "Column width (in pixels)", $sd['fss_column_width']);
    $params[$o->getName()] = $o->getData();
    ?>px
    <p class="descMsg">Only numeric value please.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("settings_boxes_height", "Filter boxes max-height each", $sd['settings_boxes_height']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Height of each filter box within the search settings drop-down. Use with units (220px or auto). Default: 220px</p>
</div>
<div class="item">
    <label class="shortcode">Custom Settings position shortcode:</label>
    <input type="text" class="quick_shortcode" value="[wpdreams_asp_settings id=<?php echo $search['id']; ?>]" readonly="readonly" />
</div>