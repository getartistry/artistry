<div class="item item-rlayout item-rlayout-vertical">
    <p>These options are hidden, because the <span>vertical</span> results layout is selected.</p>
    <p>You can change that under the <a href="#402" data-asp-os-highlight="resultstype" tabid="402">Layout Options -> Results layout</a> panel,
        <br>..or choose a <a href="#601" tabid="601">different theme</a> with a different pre-defined layout.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("resultitemheight", "One result item height", $sd['resultitemheight']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Use with units (70px or 50% or auto). Default: <strong>auto</strong></p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("v_res_max_height", "Result box maximum height", $sd['v_res_max_height']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">If this value is reached, the scrollbar will definitely trigger. <strong>none</strong> or pixel units, like <strong>800px</strong>. Default: <strong>none</strong></p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("itemscount", "Results box viewport (in item numbers)", $sd['itemscount']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Used to calculate the box height. Result box height = (this option) x (average item height)</p>
</div>
<div class="item item-flex-nogrow">
    <?php
    $option_name = "image_width";
    $option_desc = "Image width (px)";
    $o = new wpdreamsTextSmall($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();

    $option_name = "image_height";
    $option_desc = "Image height (px)";
    $o = new wpdreamsTextSmall($option_name, $option_desc,
        $sd[$option_name]);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBorder("resultsborder", "Results box border", $sd['resultsborder']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("resultshadow", "Results box Shadow", $sd['resultshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("resultsbackground","Results box background color", $sd['resultsbackground']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("resultscontainerbackground","Result items container box background color", $sd['resultscontainerbackground']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsGradient("vresulthbg", "Result item mouse hover box background gradient", $sd['vresulthbg']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("spacercolor","Spacer color between results", $sd['spacercolor']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("arrowcolor","Resultbar arrow color", $sd['arrowcolor']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("overflowcolor","Resultbar overflow color", $sd['overflowcolor']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>