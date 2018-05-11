<p class='infoMsg'>
    This css will be added before the plugin as inline CSS so it has a precedence
    over plugin CSS. (you can override existing rules)
</p>
<div class="item">
    <?php
    $option_name = "custom_css";
    $option_desc = "Custom CSS";
    $o = new wd_Textarea_B64($option_name, $option_desc, $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item" style="display:none !important;">
    <?php
    $option_name = "custom_css_h";
    $option_desc = "";
    $o = new wd_Textarea_B64($option_name, $option_desc, $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("res_z_index", "Results box z-index", $sd['res_z_index']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        In case you have some other elements floating above/below the results, you can adjust it's position with the z-index.
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("sett_z_index", "Settings drop-down box z-index", $sd['sett_z_index']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        In case you have some other elements floating above/below the settings drop-down, you can adjust it's position with the z-index.
    </p>
</div>