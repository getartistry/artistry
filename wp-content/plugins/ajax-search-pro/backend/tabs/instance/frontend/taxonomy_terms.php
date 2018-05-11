<div class="item">
    <?php
    $o = new wpdreamsYesNo("showsearchintaxonomies", "Display the category/terms selectors?", $sd['showsearchintaxonomies']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wd_TaxonomyTermSelect("show_terms", "Show the following taxonomy term selectors on frontend", array(
        "value"=>$sd['show_terms'],
        "args"  => array(
            "show_type" => 0,
            "show_checkboxes" => 1,
            "show_display_mode" => 1,
            "show_separate_filter_boxes" => 1,
            "show_more_options" => 1,
            "built_in" => true,
            "exclude_taxonomies" => array("post_tag", "nav_menu", "link_category")
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("frontend_term_hierarchy", "Maintain term hierarchy?", $sd['frontend_term_hierarchy']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Shows child terms hierarchically under their parents with padding. Supports multiple term levels.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomArraySelect("frontend_term_order",
        array(
            "Default term order",
            ""
        ),
        array(
            'optionsArr' => array(
                array(
                    array('option' => 'Name', 'value' => 'name'),
                    array('option' => 'Item count', 'value' => 'count'),
                    array('option' => 'ID', 'value' => 'id')
                ),
                array(
                    array('option' => 'Ascending', 'value' => 'ASC'),
                    array('option' => 'Descending', 'value' => 'DESC')
                )
            ),
            'value' => $sd['frontend_term_order']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>