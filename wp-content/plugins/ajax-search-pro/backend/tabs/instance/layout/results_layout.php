<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("resultstype", "Results layout type", array(
        'selects'=>array(
            array('option' => 'Vertical Results', 'value' => 'vertical'),
            array('option' => 'Horizontal Results', 'value' => 'horizontal'),
            array('option' => 'Isotopic Results', 'value' => 'isotopic'),
            array('option' => 'Polaroid style Results', 'value' => 'polaroid')
        ),
        'value'=>$sd['resultstype']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<p class='infoMsg'>If you are using <b>Polaroid</b> layout type, then <b>block</b> position is highly recommended!</p>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("resultsposition", "Results layout position", array(
        'selects'=>array(
            array('option' => 'Hover - over content', 'value' => 'hover'),
            array('option' => 'Block - pushes content', 'value' => 'block')
        ),
        'value'=>$sd['resultsposition']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsYesNo("showmoreresults", "Show 'More results..' text in the bottom of the search box?", $sd['showmoreresults']);
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsCustomSelect("more_results_action", " action", array(
        'selects'=>array(
            array('option' => 'Load more ajax results', 'value' => 'ajax'),
            array('option' => 'Redirect to Results Page', 'value' => 'results_page'),
            array('option' => 'Redirect to WooCommerce Results Page', 'value' => 'woo_results_page'),
            array('option' => 'Redirect to custom URL', 'value' => 'redirect')
        ),
        'value'=>$sd['more_results_action']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
    <div class="descMsg" style="min-width: 100%;flex-wrap: wrap;flex-basis: auto;flex-grow: 1;box-sizing: border-box;">
        "Load more ajax results" option will not work if Polaroid layout or Grouping is activated, or if results are removed when no images are present.
    </div>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("more_redirect_url", "' Show more results..' url", $sd['more_redirect_url']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsText("showmoreresultstext", "' Show more results..' text", $sd['showmoreresultstext']);
    $params[$o->getName()] = $o->getData();
    $o = new wpdreamsCustomSelect("more_redirect_location", " location: ",
        array(
            'selects' => array(
                array('option' => 'Use same tab', 'value' => 'same'),
                array('option' => 'Open new tab', 'value' => 'new')
            ),
            'value' => $sd['more_redirect_location']
        ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showauthor", "Show author in results?", $sd['showauthor']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("author_field", "Author field",
		array(
			'selects' => array(
				array('option' => 'Display name', 'value' => 'display_name'),
				array('option' => 'Login name', 'value' => 'user_login')
			),
			'value' => $sd['author_field']
		));
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showdate", "Show date in results?", $sd['showdate']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item item-flex-nogrow item-conditional" style="flex-wrap: wrap;">
    <?php
        $o = new wpdreamsYesNo("custom_date", "Use custom date format?",
            $sd['custom_date']);
        $params[$o->getName()] = $o->getData();
    ?>
    <?php
        $o = new wpdreamsText("custom_date_format", " format",
            $sd['custom_date_format']);
        $params[$o->getName()] = $o->getData();
    ?>
    <div class='descMsg' style="min-width: 100%;
    flex-wrap: wrap;
    flex-basis: auto;
    flex-grow: 1;
    box-sizing: border-box;">If turned OFF, it will use WordPress defaults. Default custom value: <b>Y-m-d H:i:s</b></div>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("showdescription", "Show description (content) in results?", $sd['showdescription']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextSmall("descriptionlength", "Description (content) length", $sd['descriptionlength']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Content length in characters.</p>
</div>
<div class="item item item-flex-nogrow" style="flex-wrap: wrap;">
    <?php
    $o = new wpdreamsYesNo("description_context", "Display the description context?", $sd['description_context']);
    $params[$o->getName()] = $o->getData();

    $o = new wpdreamsTextSmall("description_context_depth", " ..depth", $sd['description_context_depth']);
    $params[$o->getName()] = $o->getData();
    ?><div>characters.</div>
    <div class='descMsg' style="min-width: 100%;
    flex-wrap: wrap;
    flex-basis: auto;
    flex-grow: 1;
    box-sizing: border-box;">Will display the description from around the search phrase, not from the beginning.</div>
</div>
<script>
    jQuery(function($) {
        $('select[name="more_results_action"]').change(function(){
            if ($(this).val() == 'ajax') {
                $('input[name="more_redirect_url"]').parent().parent().css("display", "none");
            } else {
                $('input[name="more_redirect_url"]').parent().parent().css("display", "block");
            }
        });
        $('select[name="more_results_action"]').change();
    });
</script>