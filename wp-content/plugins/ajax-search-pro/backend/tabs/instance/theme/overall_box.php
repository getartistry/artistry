<div class="item">
    <?php
    $o = new wpdreamsThemeChooser("themes", "Theme Chooser", array(
        "themes"     => $_themes,
        "value"   => $sd['themes']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("box_width", "Search box width", $sd['box_width']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Use with units (10px or 50% or auto). Default: <strong>100%</strong></p>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("boxheight", "Search box height", array(
        'value' => $sd['boxheight'],
        'units'=>array('px'=>'px')
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("boxmargin", "Search box margin", array(
        'value' => $sd['boxmargin'],
        'units'=>array('px'=>'px', '%'=>'%')
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("boxbackground", "Search box background gradient", $sd['boxbackground']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBorder("boxborder", "Search box border", $sd['boxborder']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("boxshadow", "Search box Shadow", $sd['boxshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>