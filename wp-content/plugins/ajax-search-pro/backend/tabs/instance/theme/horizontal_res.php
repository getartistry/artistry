<div class="item item-rlayout item-rlayout-horizontal">
    <p>These options are hidden, because the <span>vertical</span> results layout is selected.</p>
    <p>You can change that under the <a href="#402" data-asp-os-highlight="resultstype" tabid="402">Layout Options -> Results layout</a> panel,
        <br>..or choose a <a href="#601" tabid="601">different theme</a> with a different pre-defined layout.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("hhidedesc", "Hide description if images are available", $sd['hhidedesc']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("hreswidth", "Result width", array(
        'value' => $sd['hreswidth'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("hor_img_height", "Result image height", array(
        'value' => $sd['hor_img_height'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">The image width is calcualted from the Result width option.</p>
</div>
<div class="item"><?php
    /*$o = new wpdreamsNumericUnit("hresheight", "Result height", array(
        'value' => $sd['hresheight'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();*/
    $o = new wpdreamsTextSmall("horizontal_res_height", "Result height", $sd['horizontal_res_height']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Use with units (70px or 50% or auto). Default: <strong>auto</strong></p>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("hressidemargin", "Result side margin", array(
        'value' => $sd['hressidemargin'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("hrespadding", "Result padding", array(
        'value' => $sd['hrespadding'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("hboxbg", "Result container background gradient", $sd['hboxbg']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBorder("hboxborder", "Results container border", $sd['hboxborder']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("hboxshadow", "Results container box shadow", $sd['hboxshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsAnimations("hresultinanim", "Result item incoming animation", $sd['hresultinanim']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("hresultbg", "Result item background gradient", $sd['hresultbg']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("hresulthbg", "Result item mouse hover background gradient", $sd['hresulthbg']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBorder("hresultborder", "Results item border", $sd['hresultborder']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("hresultshadow", "Results item box shadow", $sd['hresultshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBorder("hresultimageborder", "Results image border", $sd['hresultimageborder']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("hresultimageshadow", "Results image box shadow", $sd['hresultimageshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("harrowcolor","Resultbar arrow color", $sd['harrowcolor']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("hoverflowcolor","Resultbar overflow color", $sd['hoverflowcolor']);
    $params[$o->getName()] = $o->getData();
    ?></div>