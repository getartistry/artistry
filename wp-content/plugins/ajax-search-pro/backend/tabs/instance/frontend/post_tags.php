<div class="item">
    <?php
    $o = new wpdreamsSelectTags("show_frontend_tags", "Show the tag selectors?", $sd['show_frontend_tags']);
    ?>
</div>
<div class="item item-flex-nogrow wd_tag_mode_dropdown wd_tag_mode_radio">
    <?php
    $o = new wpdreamsYesNo("display_all_tags_option", "Show all tags option?", $sd['display_all_tags_option']);
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsText("all_tags_opt_text", "text ", $sd['all_tags_opt_text']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow wd_tag_mode_checkbox">
    <?php
    $o = new wpdreamsYesNo("display_all_tags_check_opt", "Show select/deselect all option?", $sd['display_all_tags_check_opt']);
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsText("all_tags_check_opt_text", "text ", $sd['all_tags_check_opt_text']);
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsCustomSelect("all_tags_check_opt_state", "state ", array(
        "selects" => array(
            array("option" => "Checked", "value" => "checked"),
            array("option" => "Unchecked", "value" => "unchecked")
        ),
        "value" => $sd['all_tags_check_opt_state']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("frontend_tags_placeholder", "Placeholder text", $sd['frontend_tags_placeholder']);
    ?>
    <p class="descMsg">Placeholder text for the multiselect search layout, in case nothing is selected.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("frontend_tags_header", "Tags header text", $sd['frontend_tags_header']);
    ?>
    <p class="descMsg">Leave empty if you don't want to display the header.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("frontend_tags_logic", "Tags logic (only used for checkboxes!)",
        array(
            'selects' => array(
                array('option' => 'At least one selected tag should match', 'value' => 'or'),
                array('option' => 'All of the selected tags should match', 'value' => 'and')
            ),
            'value' => $sd['frontend_tags_logic']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">This determines the rule how the selections should be treated. Only affects the <strong>checkbox</strong> layout!</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("frontend_tags_empty", "Display posts/pages/CPT as results, which have no tags?", $sd['frontend_tags_empty']);
    ?>
    <p class="descMsg">When turned OFF, any custom post type (post, page etc..) without tags <strong>will not be displayed</strong> as results.</p>
</div>