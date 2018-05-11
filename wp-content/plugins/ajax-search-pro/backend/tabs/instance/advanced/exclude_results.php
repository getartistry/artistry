<div class="item" style="border-bottom: 0;">
    <?php
    $o = new wpdreamsYesNo("exclude_dates_on", "Exclude Post/Page/CPT by date", $sd['exclude_dates_on']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wd_DateInterval("exclude_dates", "posts", $sd['exclude_dates']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wd_UserSelect("exclude_content_by_users", "Exclude or Include content by users", array(
        "value"=>$sd['exclude_content_by_users'],
        "args"=> array(
            "show_type" => 1
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsSearchTags("exclude_post_tags", "Exclude posts by tags", $sd['exclude_post_tags']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    /*$o = new wpdreamsCategories("excludecategories", "Exclude posts by categories", $sd['excludecategories']);
    $params[$o->getName()] = $o->getData();
    $params['selected-'.$o->getName()] = $o->getSelected();*/
    $o = new wd_TaxonomyTermSelect("exclude_by_terms", "<span style='color: red; font-weight: bold'>Exclude</span> posts (or cpt, attachments, comments) by categories/taxonomy terms", array(
        "value"=>$sd['exclude_by_terms'],
        "args"  => array(
            "show_type" => 0,
            "op_type" => "exclude",
            "show_checkboxes" => 0,
            "show_display_mode" => 0,
            "show_separate_filter_boxes" => 0,
            "show_more_options" => 0,
            'show_taxonomy_all' => 0,
            "built_in" => true,
            "exclude_taxonomies" => array("post_tag", "nav_menu", "link_category")
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">An object is excluded if matches <strong>any</strong> of the selected items.</p>
</div>
<div class="item">
    <?php
    $o = new wd_TaxonomyTermSelect("include_by_terms", "<span style='color: red; font-weight: bold;'>Include</span> posts (or cpt, attachments, comments) only from selected categories/taxonomy terms", array(
        "value"=>$sd['include_by_terms'],
        "args"  => array(
            "show_type" => 0,
            "op_type" => "include",
            "show_checkboxes" => 0,
            "show_display_mode" => 0,
            "show_separate_filter_boxes" => 0,
            "show_more_options" => 0,
            'show_taxonomy_all' => 0,
            "built_in" => true,
            "exclude_taxonomies" => array("nav_menu", "link_category")
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">The exclusions from the above option <strong>still apply!</strong></p>
</div>
<div class="item">
    <?php
    $o = new wd_CPTSelect("exclude_cpt", "Exclude posts/pages/cpt", array(
        "value"=>$sd['exclude_cpt'],
        "args"=> array(
            "show_parent_checkbox" => 1
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">The "Exclude direct children too?" option only works with <strong>DIRECT</strong> parent-child relationships. (1 level down)</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("excludeposts", "Exclude Posts/Pages/CPT by ID's (comma separated post ID-s)", $sd['excludeposts']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">If you wish to exclude Posts, Pages and custom post types (like products etc..) by ID here. Comma separated list.</p>
</div>