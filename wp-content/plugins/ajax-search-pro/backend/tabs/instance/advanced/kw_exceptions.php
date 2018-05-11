<p class="infoMsg">Keyword exceptions will be replaced with an empty string "" in the search phrase.</p>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("kw_exceptions", "Keyword exceptions - replace anywhere", $sd['kw_exceptions']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><strong>Comma separated list</strong> of keywords you want to remove or ban. Matching anything, even partial words.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("kw_exceptions_e", "Keyword exceptions - replace whole words only", $sd['kw_exceptions_e']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg"><strong>Comma separated list</strong> of keywords you want to remove or ban. Only matching whole words between word boundaries.</p>
</div>