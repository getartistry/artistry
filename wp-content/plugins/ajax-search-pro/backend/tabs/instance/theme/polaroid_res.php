<div class="item item-rlayout item-rlayout-polaroid">
    <p>These options are hidden, because the <span>vertical</span> results layout is selected.</p>
    <p>You can change that under the <a href="#402" data-asp-os-highlight="resultstype" tabid="402">Layout Options -> Results layout</a> panel,
        <br>..or choose a <a href="#601" tabid="601">different theme</a> with a different pre-defined layout.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("pifnoimage", "If no image found",  array(
        'selects'=>array(
            array('option' => 'Show description instead', 'value' => 'descinstead'),
            array('option' => 'Show only the title', 'value' => 'titleonly'),
            array('option' => 'Dont show that result', 'value' => 'removeres')
        ),
        'value'=>$sd['pifnoimage']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("pshowdesc", "Show descripton on the back of the polaroid", $sd['pshowdesc']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("prescontainerheight", "Container height", array(
        'value' => $sd['prescontainerheight'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("preswidth", "Result width", array(
        'value' => $sd['preswidth'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("presheight", "Result max. height", array(
        'value' => $sd['presheight'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsNumericUnit("prespadding", "Result padding", array(
        'value' => $sd['prespadding'],
        'units'=>array('px'=>'px')));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("pshowsubtitle", "Show date/author", $sd['pshowsubtitle']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("prestitlefont", "Result title font", $sd['prestitlefont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("pressubtitlefont", "Result sub-title font", $sd['pressubtitlefont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>

<div class="item"><?php
    $o = new wpdreamsFontComplete("presdescfont", "Result description font", $sd['presdescfont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("prescontainerbg", "Container background", $sd['prescontainerbg']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("pdotssmallcolor", "Nav dot colors", $sd['pdotssmallcolor']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("pdotscurrentcolor", "Nav active dot color", $sd['pdotscurrentcolor']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsGradient("pdotsflippedcolor", "Nav flipped dot color", $sd['pdotsflippedcolor']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>