<div class="item">
    <?php
    $o = new wpdreamsYesNo("frontend_show_suggestions", "Show the Suggested phrases?", $sd['frontend_show_suggestions']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Will show the "Try these" as seen on the demo.</p>
</div>
<div class="item item-flex-nogrow item-conditional">
    <?php
    $o = new wpdreamsText("frontend_suggestions_text", "Suggestion text", $sd['frontend_suggestions_text']);
    $params[$o->getName()] = $o->getData();
    ?>
    <?php
    $o = new wpdreamsColorPicker("frontend_suggestions_text_color", " color ", $sd['frontend_suggestions_text_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("frontend_suggestions_keywords", "Keywords", $sd['frontend_suggestions_keywords']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Comma separated!</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsColorPicker("frontend_suggestions_keywords_color", "Keywords color ", $sd['frontend_suggestions_keywords_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>