<fieldset>
    <legend>Global loading options</legend>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("load_mcustom_js", "Load the scrollbar script?", array(
                'selects'=>array(
                    array('option'=>'Yes', 'value'=>'yes'),
                    array('option'=>'No', 'value'=>'no')
                ),
                'value'=>$com_options['load_mcustom_js']
            )
        );
        $params[$o->getName()] = $o->getData();
        ?>
        <p class='descMsg'>
        <ul>
            <li>When set to <strong>No</strong>, the custom scrollbar will <strong>not be used at all</strong>.</li>
        </ul>
        </p>
    </div>
    <div class="item">
        <p class='infoMsg'>You can turn some of these off, if you are not using them.</p>
        <?php $o = new wpdreamsYesNo("loadpolaroidjs", "Load the polaroid gallery JS?",
            $com_options['loadpolaroidjs']
        ); ?>
        <p class='descMsg'>Don't turn this off if you are using the POLAROID layout.</p>
    </div>
    <div class="item">
        <?php $o = new wpdreamsYesNo("load_isotope_js", "Load the isotope JS?",
            $com_options['load_isotope_js']
        ); ?>
        <p class='descMsg'>Don't turn this off if you are using the ISOTOPIC layout.</p>
    </div>
    <div class="item">
        <?php $o = new wpdreamsYesNo("load_noui_js", "Load the NoUI slider JS?",
            $com_options['load_noui_js']
        ); ?>
        <p class='descMsg'>Don't turn this off if you are using SLIDERS in the custom field filters.</p>
    </div>
    <div class="item">
        <?php $o = new wpdreamsYesNo("load_datepicker_js", "Load the DatePicker UI script?",
            $com_options['load_datepicker_js']
        ); ?>
        <p class='descMsg'>Don't turn this off if you are using date picker on the search front-end.</p>
    </div>
    <div class="item">
        <?php $o = new wpdreamsYesNo("load_chosen_js", "Load the Chosen jQuery script?",
            $com_options['load_chosen_js']
        ); ?>
        <p class='descMsg'>Used with dropdown and multiselect fields to add a <a href="https://harvesthq.github.io/chosen/" target="_blank">search feature</a> to them if used.</p>
    </div>
</fieldset>
<fieldset>
    <legend>Selective loading options</legend>
    <div class="item">
        <?php $o = new wpdreamsYesNo("selective_enabled", "Enable selective script & style loading?",
            $com_options['selective_enabled']
        ); ?>
        <p class='descMsg'>It enables the rules below, so the scritps and styles can be excluded from specific parts of your website.</p>
    </div>
    <div class="item item_selective_load">
        <?php $o = new wpdreamsYesNo("selective_front", "Load scripts & styles on the front page?",
            $com_options['selective_front']
        ); ?>
    </div>
    <div class="item item_selective_load">
        <?php $o = new wpdreamsYesNo("selective_archive", "Load scripts & styles on archive pages?",
            $com_options['selective_front']
        ); ?>
    </div>
    <div class="item item_selective_load item-flex-nogrow" style="flex-wrap: wrap;">
        <div style="margin: 0;">
        <?php
        $o = new wpdreamsCustomSelect("selective_exin_logic", "",
            array(
                'selects' => array(
                    array('option' => 'Exclude on pages', 'value' => 'exclude'),
                    array('option' => 'Include on pages', 'value' => 'include')
                ),
                'value' => $com_options['selective_exin_logic']
            ));
        ?>
        </div>
        <?php
        $o = new wpdreamsTextarea("selective_exin", " ids ", $com_options['selective_exin']);
        ?>
        <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
            Comma separated list of Post/Page/CPT IDs.
        </div>
    </div>
</fieldset>