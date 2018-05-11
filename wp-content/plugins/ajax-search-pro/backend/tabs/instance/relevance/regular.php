<?php
$sd_wht_def = array(
    array('option' => '10 - Highest weight', 'value' => 10),
    array('option' => '9', 'value' => 9),
    array('option' => '8', 'value' => 8),
    array('option' => '7', 'value' => 7),
    array('option' => '6', 'value' => 6),
    array('option' => '5', 'value' => 5),
    array('option' => '4', 'value' => 4),
    array('option' => '3', 'value' => 3),
    array('option' => '2', 'value' => 2),
    array('option' => '1 - Lowest weight', 'value' => 1)
);
?>
<div class='item'>
    <p class='infoMsg'>
        These options work with the <b>Regular Engine</b>. If you are using the Index table engine, you can adjust the values on the Index Table panel on this page.
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("userelevance", "Use relevance?", $sd['userelevance']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<fieldset>
    <legend>Exact matches weight</legend>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("etitleweight", "Title weight", array('selects' => $sd_wht_def, 'value' => $sd['etitleweight']));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("econtentweight", "Content weight", array('selects' => $sd_wht_def, 'value' => $sd['econtentweight']));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("eexcerptweight", "Excerpt weight", array('selects' => $sd_wht_def, 'value' => $sd['eexcerptweight']));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("etermsweight", "Terms weight", array('selects' => $sd_wht_def, 'value' => $sd['etermsweight']));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>
<fieldset>
    <legend>Random matches weight</legend>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("titleweight", "Title weight", array('selects' => $sd_wht_def, 'value' => $sd['titleweight']));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("contentweight", "Content weight", array('selects' => $sd_wht_def, 'value' => $sd['contentweight']));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("excerptweight", "Excerpt weight", array('selects' => $sd_wht_def, 'value' => $sd['excerptweight']));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsCustomSelect("termsweight", "Terms weight", array('selects' => $sd_wht_def, 'value' => $sd['termsweight']));
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>