<div class="item"><?php
    $o = new wpdreamsGradient("inputbackground", "Search input field background gradient", $sd['inputbackground']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item">
    <?php
    $o = new wpdreamsBorder("inputborder", "Search input field border", $sd['inputborder']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsBoxShadow("inputshadow", "Search input field Shadow", $sd['inputshadow']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("inputfont", "Search input font", $sd['inputfont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>