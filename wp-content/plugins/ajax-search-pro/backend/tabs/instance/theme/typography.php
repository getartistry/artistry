<div class="item"><?php
    $o = new wpdreamsFontComplete("titlefont", "Results title link font", $sd['titlefont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<!--
<div class="item"><?php
    $o = new wpdreamsFontComplete("titlehoverfont", "Results title hover link font", $sd['titlehoverfont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
-->
<div class="item"><?php
    $o = new wpdreamsFontComplete("authorfont", "Author text font", $sd['authorfont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("datefont", "Date text font", $sd['datefont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("descfont", "Description text font", $sd['descfont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("exsearchincategoriesboxcolor","Grouping box header background color", $sd['exsearchincategoriesboxcolor']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("groupingbordercolor","Grouping box border color", $sd['groupingbordercolor']);
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("groupbytextfont", "Grouping font color", $sd['groupbytextfont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsColorPicker("showmorefont_bg","'Show more results' background color", $sd['showmorefont_bg']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsFontComplete("showmorefont", "'Show more results' font", $sd['showmorefont']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>