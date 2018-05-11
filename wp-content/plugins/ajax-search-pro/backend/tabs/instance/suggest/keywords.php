<div class="item">
    <?php
    $o = new wpdreamsYesNo("keywordsuggestions", "Keyword suggestions on no results?",
        $sd['keywordsuggestions']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsDraggable("keyword_suggestion_source", "Keyword suggestion sources", array(
        'selects'=> $sugg_select_arr,
        'value'=>$sd['keyword_suggestion_source'],
        'description'=>'Select which sources you prefer for keyword suggestions. Order counts.'
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item hiddend"><?php
    $o = new wpdreamsText("kws_google_places_api", "Google places API key", $sd['kws_google_places_api']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="errorMsg">This is required for the Google Places API to work. You can <a href="https://developers.google.com/places/web-service/autocomplete" target="_blank">get your API key here</a>.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("keyword_suggestion_count", "Max. suggestion count",
        $sd['keyword_suggestion_count']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">The number of possible suggestions.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsTextSmall("keyword_suggestion_length", "Max. suggestion length",
        $sd['keyword_suggestion_length']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">The length of each suggestion in characters. 30-50 is a good number to avoid too long suggestions.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsLanguageSelect("keywordsuggestionslang", "Google keyword suggestions language",
        $sd['keywordsuggestionslang']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>