<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("settingsimagepos", "Settings icon position", array(
        'selects'=>array(
            array('option' => 'Left', 'value' => 'left'),
            array('option' => 'Right', 'value' => 'right')
        ),
        'value'=>$sd['settingsimagepos']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsImageRadio("settingsimage", "Settings icon", array(
            'images'  => array(
                "/ajax-search-pro/img/svg/menu/menu1.svg",
                "/ajax-search-pro/img/svg/menu/menu2.svg",
                "/ajax-search-pro/img/svg/menu/menu3.svg",
                "/ajax-search-pro/img/svg/menu/menu4.svg",
                "/ajax-search-pro/img/svg/menu/menu5.svg",
                "/ajax-search-pro/img/svg/menu/menu6.svg",
                "/ajax-search-pro/img/svg/menu/menu7.svg",
                "/ajax-search-pro/img/svg/menu/menu8.svg",
                "/ajax-search-pro/img/svg/menu/menu9.svg",
                "/ajax-search-pro/img/svg/menu/menu10.svg",
                "/ajax-search-pro/img/svg/arrows-down/arrow1.svg",
                "/ajax-search-pro/img/svg/arrows-down/arrow2.svg",
                "/ajax-search-pro/img/svg/arrows-down/arrow3.svg",
                "/ajax-search-pro/img/svg/arrows-down/arrow4.svg",
                "/ajax-search-pro/img/svg/arrows-down/arrow5.svg",
                "/ajax-search-pro/img/svg/arrows-down/arrow6.svg",
                "/ajax-search-pro/img/svg/control-panel/cp1.svg",
                "/ajax-search-pro/img/svg/control-panel/cp2.svg",
                "/ajax-search-pro/img/svg/control-panel/cp3.svg",
                "/ajax-search-pro/img/svg/control-panel/cp4.svg",
                "/ajax-search-pro/img/svg/control-panel/cp5.svg",
                "/ajax-search-pro/img/svg/control-panel/cp6.svg",
                "/ajax-search-pro/img/svg/control-panel/cp7.svg",
                "/ajax-search-pro/img/svg/control-panel/cp8.svg",
                "/ajax-search-pro/img/svg/control-panel/cp9.svg",
                "/ajax-search-pro/img/svg/control-panel/cp10.svg",
                "/ajax-search-pro/img/svg/control-panel/cp11.svg",
                "/ajax-search-pro/img/svg/control-panel/cp12.svg",
                "/ajax-search-pro/img/svg/control-panel/cp13.svg",
                "/ajax-search-pro/img/svg/control-panel/cp14.svg",
                "/ajax-search-pro/img/svg/control-panel/cp15.svg",
                "/ajax-search-pro/img/svg/control-panel/cp16.svg"
            ),
            'value'=> $sd['settingsimage']
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("settingsimage_color", "Settings icon color", $sd['settingsimage_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsUpload("settingsimage_custom", "Custom settings icon", $sd['settingsimage_custom']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("settingsbackground", "Settings-icon background gradient", $sd['settingsbackground']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item">
    <?php
    $o = new wpdreamsBorder("settingsbackgroundborder", "Settings-icon border", $sd['settingsbackgroundborder']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("settingsboxshadow", "Settings-icon box-shadow", $sd['settingsboxshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("settingsdropbackground", "Settings drop-down background gradient", $sd['settingsdropbackground']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("settingsdropboxshadow", "Settings drop-down box-shadow", $sd['settingsdropboxshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("settingsdropfont", "Settings drop down font", $sd['settingsdropfont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("exsearchincategoriestextfont", "Settings box header text font", $sd['exsearchincategoriestextfont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("settingsdroptickcolor","Settings drop-down tick color", $sd['settingsdroptickcolor']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsGradient("settingsdroptickbggradient", "Settings drop-down tick background", $sd['settingsdroptickbggradient']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>