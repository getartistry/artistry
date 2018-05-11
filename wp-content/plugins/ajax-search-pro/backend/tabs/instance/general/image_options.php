<div class="item">
    <?php
    $option_name = "show_images";
    $option_desc = "Show images in results?";
    $o = new wpdreamsYesNo($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $option_name = "image_transparency";
    $option_desc = "Preserve image transparency?";
    $o = new wpdreamsYesNo($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $option_name = "image_bg_color";
    $option_desc = "Image background color?";
    $o = new wpdreamsColorPicker($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Only works if NOT the BFI Thumb library is used. You can change it on the <a href="admin.php?page=asp_cache_settings">Cache Settings</a> submenu.</p>
</div>
<div class="item">
    <?php
    $option_name = "image_display_mode";
    $option_desc = "Image display mode";
    $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
        'selects'=>array(
            array("option" => "Cover the space", "value" => "cover"),
            array("option" => "Contain the image", "value" => "contain")
        ),
        'value'=>$sd[$option_name]
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $option_name = "image_apply_content_filter";
    $option_desc = "Execute shortcodes when looking for images in content?";
    $o = new wpdreamsYesNo($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        Will execute shortcodes and apply the content filter before looking for images in the post content.<br>
        If you have <strong>missing images in results</strong>, try turning ON this option. <strong>Can cause lower performance!</strong>
    </p>
</div>
<fieldset>
    <legend>Image source settings</legend>
    <div class="item">
        <?php
        $option_name = "image_source1";
        $option_desc = "Primary image source";
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source2";
        $option_desc = "Alternative image source 1";
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source3";
        $option_desc = "Alternative image source 2";
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source4";
        $option_desc = "Alternative image source 3";
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source5";
        $option_desc = "Alternative image source 4";
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$sd['image_sources'],
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_source_featured";
        $option_desc = "Featured image size source";
        $_feat_image_sizes = get_intermediate_image_sizes();
        $feat_image_sizes = array(
            array(
                "option" => "Original size",
                'value' => "original"
            )
        );
        foreach ($_feat_image_sizes as $k => $v)
            $feat_image_sizes[] = array(
                "option" => $v,
                "value"  => $v
            );
        $o = new wpdreamsCustomSelect($option_name, $option_desc, array(
            'selects'=>$feat_image_sizes,
            'value'=>$sd[$option_name]
        ));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_default";
        $option_desc = "Default image url";
        $o = new wpdreamsUpload($option_name, $option_desc,
            $sd[$option_name]);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $option_name = "image_custom_field";
        $option_desc = "Custom field containing the image";
        $o = new wpdreamsText($option_name, $option_desc,
            $sd[$option_name]);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>