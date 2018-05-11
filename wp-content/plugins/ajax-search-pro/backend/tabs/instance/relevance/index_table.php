<fieldset>
    <legend>Matches weight</legend>
    <p class='infoMsg'>
        Please use numbers between <b>0 - 100</b>
    </p>
    <div class="item">
        <?php
        $o = new wpdreamsTextSmall("it_title_weight", "Title weight", $sd['it_title_weight']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsTextSmall("it_content_weight", "Content weight", $sd['it_content_weight']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsTextSmall("it_excerpt_weight", "Excerpt weight", $sd['it_excerpt_weight']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsTextSmall("it_terms_weight", "Terms weight", $sd['it_terms_weight']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsTextSmall("it_cf_weight", "Custom fields weight", $sd['it_cf_weight']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
    <div class="item">
        <?php
        $o = new wpdreamsTextSmall("it_author_weight", "Author weight", $sd['it_author_weight']);
        $params[$o->getName()] = $o->getData();
        ?>
    </div>
</fieldset>