<div class="item">
    <?php
    $o = new wpdreamsText("content_type_filter_label", "Content type filter label text", $sd['content_type_filter_label']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wd_DraggableFields("content_type_filter", "Content Type filter", array(
        "value"=>$sd['content_type_filter'],
        "args" => array(
            "show_checkboxes" => 1,
            "show_display_mode" => 1,
            "show_labels" => 1,
            'fields' => array(
                'any'           => 'Choose One/Select all',
                'cpt'           => 'Custom post types',
                'taxonomies'    => 'Taxonomy terms',
                'users'         => 'Users',
                'blogs'         => 'Multisite blogs',
                'buddypress'    => 'BuddyPress content',
                'attachments'   => 'Attachments'
            ),
            'checked' => array()
        )
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>