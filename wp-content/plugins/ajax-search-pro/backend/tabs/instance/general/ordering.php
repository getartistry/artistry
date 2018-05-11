<style>
    .wd-primary-order input,
    .wd-secondary-order input {
        width: 120px !important;
    }
</style>
<div class="item wd-primary-order item-flex-nogrow" style="flex-wrap: wrap;"><?php
    $o = new wpdreamsCustomSelect("orderby_primary", "Primary result ordering",
        array(
            'selects' => array(
                array('option' => 'Relevance', 'value' => 'relevance DESC'),
                array('option' => 'Title descending', 'value' => 'post_title DESC'),
                array('option' => 'Title ascending', 'value' => 'post_title ASC'),
                array('option' => 'Date descending', 'value' => 'post_date DESC'),
                array('option' => 'Date ascending', 'value' => 'post_date ASC'),
                array('option' => 'Custom Field descending', 'value' => 'customfp DESC'),
                array('option' => 'Custom Field  ascending', 'value' => 'customfp ASC')
            ),
            'value' => $sd['orderby_primary']
        ));
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsText("orderby_primary_cf", "custom field name", $sd['orderby_primary_cf']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsCustomSelect("orderby_primary_cf_type", "type",
        array(
            'selects' => array(
                array('option' => 'numeric', 'value' => 'numeric'),
                array('option' => 'string', 'value' => 'string')
            ),
            'value' => $sd['orderby_primary_cf_type']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item wd-secondary-order item-flex-nogrow" style="flex-wrap: wrap;"><?php
    $o = new wpdreamsCustomSelect("orderby", "Secondary result ordering",
        array(
            'selects' => array(
                array('option' => 'Relevance', 'value' => 'relevance DESC'),
                array('option' => 'Title descending', 'value' => 'post_title DESC'),
                array('option' => 'Title ascending', 'value' => 'post_title ASC'),
                array('option' => 'Date descending', 'value' => 'post_date DESC'),
                array('option' => 'Date ascending', 'value' => 'post_date ASC'),
                array('option' => 'Custom Field descending', 'value' => 'customfs DESC'),
                array('option' => 'Custom Field  ascending', 'value' => 'customfs ASC')
            ),
            'value' => $sd['orderby']
        ));
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsText("orderby_secondary_cf", "custom field name", $sd['orderby_secondary_cf']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsCustomSelect("orderby_secondary_cf_type", "type",
        array(
            'selects' => array(
                array('option' => 'numeric', 'value' => 'numeric'),
                array('option' => 'string', 'value' => 'string')
            ),
            'value' => $sd['orderby_secondary_cf_type']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
        If two elements match the primary ordering criteria, the <b>Secondary ordering</b> is used.
    </div>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("use_post_type_order", "Use separate ordering for each post type group?", $sd['use_post_type_order']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wd_Post_Type_Sortalbe("post_type_order", "Post type results ordering", $sd['post_type_order']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $fields = $sd['results_order'];

    // For updating to 4.5
    if (strpos($fields, "attachments") === false) $fields = $fields . "|attachments";

    $o = new wpdreamsSortable("results_order", "Mixed results order", $fields);
    $params[$o->getName()] = $o->getData();
    ?>
</div>