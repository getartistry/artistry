<div class="item">
    <?php
    $o = new wpdreamsText("custom_types_label", "Custom post types label text", $sd['custom_types_label']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">If empty, the label is not displayed.</p>
</div>
<div class="item item-flex-nogrow">
    <?php
    $o = new wpdreamsCustomSelect("cpt_display_mode", "Filter display mode", array(
        "selects" => array(
            array("value" => "checkboxes", "option" => "Checkboxes"),
            array("value" => "dropdown", "option" => "Drop-down"),
            array("value" => "radio", "option" => "Radio buttons")
        ),
        "value" => $sd['cpt_display_mode']));
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsCustomSelect("cpt_filter_default", "default selected", array(
        "selects" => get_post_types(array(
            "public" => true,
            "_builtin" => false
        ), "names", "OR"),
        "value" => $sd['cpt_filter_default']));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow">
    <?php
    $o = new wpdreamsYesNo("cpt_cbx_show_select_all", "Display the select all option?", $sd['cpt_cbx_show_select_all']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsText("cpt_cbx_show_select_all_text", "text", $sd['cpt_cbx_show_select_all_text']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomPostTypesEditable("showcustomtypes", "Show search in custom post types selectors", $sd['showcustomtypes']);
    $params[$o->getName()] = $o->getData();
    $params['selected-' . $o->getName()] = $o->getSelected();
    ?>
</div>