<div class='item'>
    <p class='infoMsg'>
        If you not choose any site, then the <strong>currently active</strong> blog will be used!<br />
        Also, you can use the same search on multiple blogs!
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("searchinblogtitles", "Search in blog titles?",
         $sd['searchinblogtitles']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("blogtitleorderby", "Result ordering", array(
        'selects'=> array(
            array('option' => 'Blog titles descending', 'value' => 'desc'),
            array('option' => 'Blog titles ascending', 'value' => 'asc')
        ),
        'value'=> $sd['blogtitleorderby']
    ) );
    $params[$o->getName()] = $o->getData();
    ?></div>
<div class="item">
    <?php
    $o = new wpdreamsText("blogresultstext", "Blog results group default text",
         $sd['blogresultstext']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsBlogselect("blogs", "Blogs",
         $sd['blogs']);
    $params[$o->getName()] = $o->getData();
    $params['selected-'.$o->getName()] = $o->getSelected();
    ?>
</div>
<div class="item">
    <input name="reset_<?php echo $search['id']; ?>" class="asp_submit asp_submit_transparent asp_submit_reset" type="button" value="Restore defaults">
    <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
</div>