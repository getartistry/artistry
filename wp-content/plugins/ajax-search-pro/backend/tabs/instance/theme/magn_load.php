<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("magnifier_position", "Magnifier position", array(
        'selects'=>array(
            array('option' => 'Left', 'value' => 'left'),
            array('option' => 'Right', 'value' => 'right')
        ),
        'value'=>$sd['magnifier_position']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsImageRadio("magnifierimage", "Magnifier image", array(
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
            'value'=> $sd['magnifierimage']
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("magnifierimage_color", "Magnifier icon color", $sd['magnifierimage_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsUpload("magnifierimage_custom", "Custom magnifier icon", $sd['magnifierimage_custom']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("magnifierbackground", "Magnifier background gradient", $sd['magnifierbackground']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBorder("magnifierbackgroundborder", "Magnifier-icon border", $sd['magnifierbackgroundborder']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("magnifierboxshadow", "Magnifier-icon box-shadow", $sd['magnifierboxshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<fieldset>
    <legend>Close icon</legend>
    <div class="item">
        <?php
        $o = new wpdreamsYesNo("show_close_icon", "Show the close icon?", $sd['show_close_icon']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item item-flex-nogrow" style="flex-wrap: wrap;">
        <?php
        $o = new wpdreamsColorPicker("close_icon_background", "Close icon background", $sd['close_icon_background']);
        $params[$o->getName()] = $o->getData();

        $o = new wpdreamsColorPicker("close_icon_fill", ".. icon color", $sd['close_icon_fill']);
        $params[$o->getName()] = $o->getData();

        $o = new wpdreamsColorPicker("close_icon_outline", "..icon outline", $sd['close_icon_outline']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>

<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("loader_display_location", "Loading animation display location", array(
        'selects'=>array(
            array("option" => "Auto", "value" => "auto"),
            array("option" => "In search bar", "value" => "search"),
            array("option" => "In results box", "value" => "results"),
            array("option" => "Both", "value" => "both"),
            array("option" => "None", "value" => "none")
        ),
        'value'=>$sd['loader_display_location']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">By default the loader displays in the search bar. If the search bar is hidden, id displays in the results box instead.</p>
</div>
<div class="item" id="magn_ajaxsearchpro_1">
    <div class="probox">
    <?php
    /*$o = new wpdreamsImageRadio("loadingimage", "Loading image", array(
            'images'  => $sd['loadingimage_selects'],
            'value'=> $sd['loadingimage']
        )
    );
    $params[$o->getName()] = $o->getData();*/

    $o = new wpdreamsLoaderSelect( "loader_image", "Loading image", $sd['loader_image'] );
    $params[$o->getName()] = $o->getData();
    ?>
    </div>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("loadingimage_color", "Loader color", $sd['loadingimage_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsUpload("loadingimage_custom", "Custom magnifier icon", $sd['loadingimage_custom']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>