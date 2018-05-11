<fieldset>
    <legend>Logic Options</legend>
    <div class="item"><?php
        $o = new wpdreamsCustomSelect("term_logic", "Category/Taxonomy terms logic",
            array(
                'selects' => array(
                    array('option' => 'At least one selected terms should match', 'value' => 'or'),
                    array('option' => 'All of the selected terms must match, exclude unselected (default)', 'value' => 'and'),
                    array('option' => "All of the selected terms must match EXACTLY, but unselected ones are not excluded.", 'value' => 'andex')
                ),
                'value' => $sd['term_logic']
            ));
        $params[$o->getName()] = $o->getData();
        ?>
        <div id='term_logic_MSG' class="errorMsg hiddend"><sgrong>WARNING:</sgrong> This is a very strict configuration - only results <strong>matching exactly ALL</strong> of the selected terms will show up. If you don't get any results, it is probably because of this option.</div>
        <p class="descMsg">This determines the rule how the selections should be treated within each taxonomy group.</p>
    </div>
    <div class="item"><?php
        $o = new wpdreamsCustomSelect("taxonomy_logic", "Logic between taxonomy groups",
            array(
                'selects' => array(
                    array('option' => 'AND (default)', 'value' => 'and'),
                    array('option' => 'OR', 'value' => 'or')
                ),
                'value' => $sd['taxonomy_logic']
            ));
        $params[$o->getName()] = $o->getData();
        ?>
        <p class="descMsg">This determines the connection between each taxonomy term filter group.</p>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsYesNo("frontend_terms_empty", "Show posts/CPM with empty (missing) taxonomy terms?", $sd['frontend_terms_empty']);
        ?>
        <p class="descMsg">This decides what happens if the posts does not have any terms from the selected taxonomies. For example posts with no categories, when using a category filter.</p>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsYesNo("frontend_terms_ignore_empty", "Ignore checkbox filters that have nothing selected?", $sd['frontend_terms_ignore_empty']);
        ?>
        <p class="descMsg">When turned <strong>ON</strong> and nothing is checked within a checkbox filter - then the search will ignore it completely - instead of excluding everything unchecked.</p>
    </div>
    <div class="item"><?php
        $o = new wpdreamsCustomSelect("cf_logic", "Custom Fields connection Logic",
            array(
                'selects' => array(
                    array('option' => 'AND', 'value' => 'AND'),
                    array('option' => 'OR', 'value' => 'OR')
                ),
                'value' => $sd['cf_logic']
            ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsYesNo("cf_allow_null", "Allow results with missing custom fields, when using custom field selectors?", $sd['cf_allow_null']);
        ?>
        <p class="descMsg">
            When using custom field selectors (filters), this option will allow displaying posts/pages/cpm where the given custom field is not defined.
            <br>For example: You have a custom field filter on "location" custom field, but some posts does not have the "location" custom field defined. This option
            will allow displaying them as results regardless.
        </p>
    </div>
</fieldset>
<div class="item">
    <?php
    $fields = $sd['field_order'];

    if (strpos($fields, "general") === false) $fields = "general|" . $fields;
    if (strpos($fields, "post_tags") === false) $fields .= "|post_tags";
    if (strpos($fields, "date_filters") === false) $fields .= "|date_filters";
    if (strpos($fields, "content_type_filters") === false) $fields .= "|content_type_filters";

    $o = new wpdreamsSortable("field_order", "Field order",
        $fields);
    $params[$o->getName()] = $o->getData();
    ?>
</div>