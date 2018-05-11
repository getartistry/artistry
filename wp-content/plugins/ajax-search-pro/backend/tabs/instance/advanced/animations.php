<fieldset>
    <legend>Advanced Visual Options</legend>
    <div class="item">
        <?php
        $o = new wpdreamsYesNo("visual_detect_visbility", "Hide the search box if it gets invisible?", $sd['visual_detect_visbility']);
        $params[$o->getName()] = $o->getData();
        ?>
        <p class="descMsg">
            In case the search is placed into an interactive element, which hides on certain events, enable this option.
            The plugin will try to detect it's visibility, and hide the settings and the results container if needed.
        </p>
    </div>
</fieldset>
<fieldset>
    <legend>Other options</legend>
    <div class="item">
        <?php
        $o = new wpdreamsText("jquery_chosen_nores", "'No matches' text for searchable select and multiselect filters", $sd['jquery_chosen_nores']);
        $params[$o->getName()] = $o->getData();
        ?>
        <p class="descMsg">
            When using the 'searchable select' and 'searchable multiselect' fields in category, taxonomy, tag or custom field filters - this text is used
            when no results match the searched value.
        </p>
    </div>
</fieldset>
<fieldset>
    <legend>Desktop browsers</legend>
    <div class="item item-flex-nogrow" style="flex-wrap: wrap;">
        <?php
        $o = new wpdreamsCustomSelect("sett_box_animation", "Settings drop-down box animation", array(
            'selects'=>array(
                array('option' => 'None', 'value' => 'none'),
                array('option' => 'Fade', 'value' => 'fade'),
                array('option' => 'Fade and Drop', 'value' => 'fadedrop')
            ),
            'value'=>$sd['sett_box_animation']) );
        $params[$o->getName()] = $o->getData();
        ?>
        <?php
        $o = new wpdreamsTextSmall("sett_box_animation_duration", ".. animation duration (ms)",
            $sd['sett_box_animation_duration']);
        $params[$o->getName()] = $o->getData();
        ?>
        <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
            The animation of the appearing settings box when clicking on the settings icon.
        </div>
    </div>
    <div class="item item-flex-nogrow" style="flex-wrap: wrap;">
        <?php
        $o = new wpdreamsCustomSelect("res_box_animation", "Results container box animation", array(
            'selects'=>array(
                array('option' => 'None', 'value' => 'none'),
                array('option' => 'Fade', 'value' => 'fade'),
                array('option' => 'Fade and Drop', 'value' => 'fadedrop')
            ),
            'value'=>$sd['res_box_animation']) );
        $params[$o->getName()] = $o->getData();
        ?>
        <?php
        $o = new wpdreamsTextSmall("res_box_animation_duration", ".. animation duration (ms)",
            $sd['res_box_animation_duration']);
        $params[$o->getName()] = $o->getData();
        ?>
        <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
            The animation of the appearing results box when finishing the search.
        </div>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsAnimations("res_items_animation", "Result items animation", $sd['res_items_animation']);
        $params[$o->getName()] = $o->getData();
        ?>
        <div class="descMsg">
            The animation of each result when the results box is opening.
        </div>
    </div>
</fieldset>
<fieldset>
    <legend>Mobile browsers</legend>
    <div class="item item-flex-nogrow" style="flex-wrap: wrap;">
        <?php
        $o = new wpdreamsCustomSelect("sett_box_animation_m", "Settings drop-down box animation", array(
            'selects'=>array(
                array('option' => 'None', 'value' => 'none'),
                array('option' => 'Fade', 'value' => 'fade'),
                array('option' => 'Fade and Drop', 'value' => 'fadedrop')
            ),
            'value'=>$sd['sett_box_animation_m']) );
        $params[$o->getName()] = $o->getData();
        ?>
        <?php
        $o = new wpdreamsTextSmall("sett_box_animation_duration_m", ".. animation duration (ms)",
            $sd['sett_box_animation_duration_m']);
        $params[$o->getName()] = $o->getData();
        ?>
        <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
            The animation of the appearing settings box when clicking on the settings icon.
        </div>
    </div>
    <div class="item item-flex-nogrow" style="flex-wrap: wrap;">
        <?php
        $o = new wpdreamsCustomSelect("res_box_animation_m", "Results container box animation", array(
            'selects'=>array(
                array('option' => 'None', 'value' => 'none'),
                array('option' => 'Fade', 'value' => 'fade'),
                array('option' => 'Fade and Drop', 'value' => 'fadedrop')
            ),
            'value'=>$sd['res_box_animation_m']) );
        $params[$o->getName()] = $o->getData();
        ?>
        <?php
        $o = new wpdreamsTextSmall("res_box_animation_duration_m", ".. animation duration (ms)",
            $sd['res_box_animation_duration_m']);
        $params[$o->getName()] = $o->getData();
        ?>
        <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
            The animation of the appearing results box when finishing the search.
        </div>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsAnimations("res_items_animation_m", "Result items animation", $sd['res_items_animation_m']);
        $params[$o->getName()] = $o->getData();
        ?>
        <div class="descMsg">
            The animation of each result when the results box is opening.
        </div>
    </div>
</fieldset>