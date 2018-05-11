<div class="item">
    <?php
    $o = new wpdreamsText("defaultsearchtext", "Search box placeholder text", $sd['defaultsearchtext']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Default search placeholder text appearing in the search bar.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("box_alignment", "Search box alignment",
        array(
            'selects' => array(
                array('option' => 'Inherit', 'value' => 'inherit'),
                array('option' => 'Center', 'value' => 'center'),
                array('option' => 'Left', 'value' => 'left'),
                array('option' => 'Right', 'value' => 'right')
            ),
            'value' => $sd['box_alignment']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">By default the plugin follows the parent element alignment. This option might not have an effect if the parent element is displayed as "table" or "flex".</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("box_sett_hide_box", "Hide the search box completely, display settings only?", $sd['box_sett_hide_box']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">If enabled, the search box will be hidden, and the Frontend settings will be displayed instead.</p>
</div>
<fieldset>
    <legend>Auto Populate</legend>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("auto_populate", "Display results by default when the page loads (auto populate)?",
            array(
                'selects' => array(
                    array('option' => 'Disabled', 'value' => 'disabled'),
                    array('option' => 'Enabled - Results for a search phrase', 'value' => 'phrase'),
                    array('option' => 'Enabled - Latest results', 'value' => 'latest'),
                    array('option' => 'Enabled - Random results', 'value' => 'random')
                ),
                'value' => $sd['auto_populate']
            ));
        $params[$o->getName()] = $o->getData();
        ?>
        <p class="descMsg">If enabled, the search will automatically populate on page load based on the selected configuration. The configuration and the
        frontend search options <strong>WILL be taken into account</strong> as if a normal search was made!</p>
    </div>
    <div class="item item-flex-nogrow" style="flex-wrap: wrap;">
        <?php
        $o = new wpdreamsText("auto_populate_phrase", "Phrase", $sd['auto_populate_phrase']);
        $params[$o->getName()] = $o->getData();
        ?>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php
        $o = new wpdreamsTextSmall("auto_populate_count", " Results count", $sd['auto_populate_count']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>