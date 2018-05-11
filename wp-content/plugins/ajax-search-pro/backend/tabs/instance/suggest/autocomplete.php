<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("autocomplete", "Autocomplete status", array(
        'selects'=>array(
            array("option"=>"Disabled", "value" => 0),
            array("option"=>"Enabled for all devices", "value" => 1),
            array("option"=>"Enabled for Desktop only", "value" => 2),
            array("option"=>"Enabled for Mobile only", "value" => 3)
        ),
        'value'=>$sd['autocomplete']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item" style="display: none !important;">
    <?php
    // @TODO 4.10.5
    $o = new wpdreamsCustomSelect("autocomplete_mode", "Autocomplete layout mode", array(
        'selects'=>array(
            array('option'=>'Input autocomplete', 'value' => 'input'),
            array('option'=>'Drop-down (like google)', 'value' => 'dropdown')
        ),
        'value'=>$sd['autocomplete_mode']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item" style="display: none !important;">
    <?php
    // @TODO 4.10.5
    $o = new wpdreamsCustomSelect("autocomplete_instant", "<strong>Instant</strong> autocomplete", array(
        'selects'=>array(
            array('option'=>'Automatic (enabled)', 'value' => 'auto', 'disabled' => 1),
            array('option'=>'Enabled', 'value' => 'enabled'),
            array('option'=>'Disabled', 'value' => 'disabled')
        ),
        'value'=>$sd['autocomplete_instant']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg">
        <a href="">What is <strong>instant autocomplete</strong> and how it works?</a>
    </div>
</div>
<div class="item" style="padding-right:20px;display:none !important;">
    <!-- @TODO 4.10.5 -->
    <label>Instant Autocomplete Database</label>
    <input type="button" id="asp_inst_generate" class="asp_inst_generate wd_button_green asp_submit" value="Generate">
    <input type="button" id="asp_inst_generate_save" class="asp_inst_generate wd_button_red asp_submit" value="Generate & Save options">
    <input type="button" id="asp_inst_generate_cancel" class="asp_inst_generate wd_button_red asp_submit hiddend" value="Cancel">
    <input type="button" id="asp_inst_generate_d" class="asp_inst_generate wd_button_green asp_submit hiddend" value="DB up to date for this configuration!" disabled>
    <div class="wd_progress wd_progress_75 hiddend"><span style="width:0%;"></span></div>
    <div class="descMsg">In order for the instant suggestions to work, the suggestions database must be generated.</div>
    <br>
    <?php
    $o = new wpdreamsTextSmall("autocomplete_instant_limit", "<strong>Instant</strong> autocomplete item count per source", $sd['autocomplete_instant_limit']);
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg">1500 is an optimal count. Changing this to higher numbers may reduce the initial page load time.</div>
    <?php
    $o = new wpdreamsHidden("autocomplete_instant_status", "", $sd['autocomplete_instant_status']);
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsHidden("autocomplete_instant_gen_config", "", $sd['autocomplete_instant_gen_config']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsDraggable("autocomplete_source", "Autocomplete suggestion sources", array(
        'selects'=>$sugg_select_arr,
        'value'=>$sd['autocomplete_source'],
        'description'=>'Select which sources you prefer for autocomplete. Order counts.'
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item hiddend"><?php
    $o = new wpdreamsText("autoc_google_places_api", "Google places API key", $sd['autoc_google_places_api']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="errorMsg">This is required for the Google Places API to work. You can <a href="https://developers.google.com/places/web-service/autocomplete" target="_blank">get your API key here</a>.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("autocomplete_length", "Max. suggestion length",
        $sd['autocomplete_length']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">The length of each suggestion in characters. 30-60 is a good number to avoid too long suggestions.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsLanguageSelect("autocomplete_google_lang", "Google autocomplete suggestions language",
        $sd['autocomplete_google_lang']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("autocompleteexceptions", "Keyword exceptions (comma separated)", $sd['autocompleteexceptions']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>