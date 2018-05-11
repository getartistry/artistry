<div class="item">
    <?php
    $o = new wpdreamsYesNo("box_compact_layout", "Compact layout mode", $sd['box_compact_layout']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">In compact layout only the search magnifier is visible, and the user has to click on the magnifier first to show the search bar.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("box_compact_close_on_magn", "Close on magnifier click", $sd['box_compact_close_on_magn']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Closes the box when the magnifier is clicked.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("box_compact_close_on_document", "Close on document click", $sd['box_compact_close_on_document']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Closes the box when the document is clicked.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("box_compact_width", "Compact layout final width", $sd['box_compact_width']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Use with units (10px or 50% or auto). Default: <strong>100%</strong><br>
    You might need to adjust this to a static value like 200px, as 100% is not always working in compact mode.
    </p>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsYesNo("box_compact_overlay", "Display background overlay?", $sd['box_compact_overlay']);
    $params[$o->getName()] = $o->getData();
    ?>
    <?php
    $o = new wpdreamsColorPicker("box_compact_overlay_color", " color ", $sd['box_compact_overlay_color']);
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
        Overlay only works with if the <strong>box position is set to Fixed</strong> below.
    </div>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("box_compact_float", "Compact layout alignment",
        array(
            'selects' => array(
                array('option' => 'No floating', 'value' => 'none'),
                array('option' => 'Left', 'value' => 'left'),
                array('option' => 'Right', 'value' => 'right')
            ),
            'value' => $sd['box_compact_float']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">By default the search box floats with the theme default (none). You can change that here.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("box_compact_position", "Compact search box position",
        array(
            'selects' => array(
                array('option' => 'Static (default)', 'value' => 'static'),
                array('option' => 'Fixed', 'value' => 'fixed'),
                array('option' => 'Absolute', 'value' => 'absolute')
            ),
            'value' => $sd['box_compact_position']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">In absolute position the search can not affect it's parent element height as absolutely positioned elements are removed from the flow, thus ignored by other elements.</p>
</div>
<div class="item">
    <?php
    $option_name = "box_compact_screen_position";
    $option_desc = "Position values";
    $option_expl = "You can use auto or include the unit as well, example: 10px or 1em or 90%";
    $o = new wpdreamsFour($option_name, $option_desc,
        array(
            "desc" => $option_expl,
            "value" => $sd[$option_name]
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("box_compact_position_z", "z-index", $sd['box_compact_position_z']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        In case you have some other elements floating above/below the search icon, you can adjust it's position with the z-index.
    </p>
</div>

