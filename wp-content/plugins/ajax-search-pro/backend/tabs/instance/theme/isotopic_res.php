<style>
    .wpdreamsTextSmall {
        display: inline-block;
    }
</style>
<div class="item item-rlayout item-rlayout-isotopic">
    <p>These options are hidden, because the <span>vertical</span> results layout is selected.</p>
    <p>You can change that under the <a href="#402" data-asp-os-highlight="resultstype" tabid="402">Layout Options -> Results layout</a> panel,
        <br>..or choose a <a href="#601" tabid="601">different theme</a> with a different pre-defined layout.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("i_ifnoimage", "If no image found",  array(
        'selects'=>array(
            array('option' => 'Show the default image', 'value' => 'defaultimage'),
            array('option' => 'Show the description', 'value' => 'description'),
            array('option' => 'Show the background', 'value' => 'background'),
            array('option' => 'Dont show that result', 'value' => 'removeres')
        ),
        'value'=>$sd['i_ifnoimage']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("i_res_item_background", "Result content background", $sd['i_res_item_background']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Background color under the image. Not visible by default, unless the image is opaque.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("i_item_width", "Result width", $sd['i_item_width']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Use with units (200px or 32%). The search will try to stick close to this value when filling the width of the results list.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("i_item_height", "Result height", $sd['i_item_height']);
    $params[$o->getName()] = $o->getData();
    ?>px
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("i_item_margin", "Result margin space", $sd['i_item_margin']);
    $params[$o->getName()] = $o->getData();
    ?>px
    <p class="descMsg">Margin (gutter) between the items on the isotope grid.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("i_res_item_content_background", "Result content/title background", $sd['i_res_item_content_background']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">The background color of the title/content overlay.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsImageRadio("i_res_magnifierimage", "Hover background icon", array(
            'images'  => array(
                "/ajax-search-pro/img/svg/magnifiers/magn1.svg",
                "/ajax-search-pro/img/svg/magnifiers/magn2.svg",
                "/ajax-search-pro/img/svg/magnifiers/magn3.svg",
                "/ajax-search-pro/img/svg/magnifiers/magn4.svg",
                "/ajax-search-pro/img/svg/magnifiers/magn5.svg",
                "/ajax-search-pro/img/svg/magnifiers/magn6.svg",
                "/ajax-search-pro/img/svg/magnifiers/magn7.svg",
                "/ajax-search-pro/img/svg/arrows/arrow1.svg",
                "/ajax-search-pro/img/svg/arrows/arrow2.svg",
                "/ajax-search-pro/img/svg/arrows/arrow3.svg",
                "/ajax-search-pro/img/svg/arrows/arrow4.svg",
                "/ajax-search-pro/img/svg/arrows/arrow5.svg",
                "/ajax-search-pro/img/svg/arrows/arrow6.svg",
                "/ajax-search-pro/img/svg/arrows/arrow7.svg",
                "/ajax-search-pro/img/svg/arrows/arrow8.svg",
                "/ajax-search-pro/img/svg/arrows/arrow9.svg",
                "/ajax-search-pro/img/svg/arrows/arrow10.svg",
                "/ajax-search-pro/img/svg/arrows/arrow11.svg",
                "/ajax-search-pro/img/svg/arrows/arrow12.svg",
                "/ajax-search-pro/img/svg/arrows/arrow13.svg",
                "/ajax-search-pro/img/svg/arrows/arrow14.svg",
                "/ajax-search-pro/img/svg/arrows/arrow15.svg",
                "/ajax-search-pro/img/svg/arrows/arrow16.svg"
            ),
            'value'=> $sd['i_res_magnifierimage']
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsUpload("i_res_custom_magnifierimage", "Custom hover background icon", $sd['i_res_custom_magnifierimage']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("i_overlay", "Show overlay on mouseover?", $sd['i_overlay']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("i_overlay_blur", "Blur overlay image on mouseover?", $sd['i_overlay_blur']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">This might not work on some browsers.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("i_hide_content", "Hide the content when overlay is active?", $sd['i_hide_content']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsAnimations("i_animation", "Display animation", $sd['i_animation']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("i_rows", "Rows count", $sd['i_rows']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">If the item would exceed the row limit, it gets placed to a new page.</p>
</div>
<div class="item">
    <?php
    $option_name = "i_res_container_padding";
    $option_desc = "Result container padding";
    $option_expl = "Include the unit as well, example: 10px or 1em or 90%";
    $o = new wpdreamsFour($option_name, $option_desc,
        array(
            "desc" => $option_expl,
            "value" => $sd[$option_name]
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $option_name = "i_res_container_margin";
    $option_desc = "Result container margin";
    $option_expl = "Include the unit as well, example: 10px or 1em or 90%";
    $o = new wpdreamsFour($option_name, $option_desc,
        array(
            "desc" => $option_expl,
            "value" => $sd[$option_name]
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("i_res_container_bg", "Result box background", $sd['i_res_container_bg']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>