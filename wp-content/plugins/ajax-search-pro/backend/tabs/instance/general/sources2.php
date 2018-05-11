<div class="item">
    <?php
    $o = new wpdreamsYesNo("return_categories", "Return post categories as results?",
        $sd['return_categories']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
	<?php
	$o = new wpdreamsYesNo("return_tags", "Return post tags as results?",
		$sd['return_tags']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTaxonomySelect("return_terms", "Return taxonomy terms as results", array(
        "value"=>$sd['return_terms'],
        "type"=>"include"));
    $params[$o->getName()] = $o->getData();
    $params['selected-'.$o->getName()] = $o->getSelected();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_term_descriptions", "Search term descriptions?",
        $sd['search_term_descriptions']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<!--
ON HOLD - term meta is not used yet widely
ACF uses wp_options table to store taxonomy term meta...
<div class="item">
    <?php
    //$o = new wpdreamsTermMeta("search_term_meta", "Search in term meta?", $sd['search_term_meta']);
    //$params[$o->getName()] = $o->getData();
    ?>
</div>
-->
<div class="item">
	<?php
	$o = new wpdreamsYesNo("display_number_posts_affected", "Display the number of posts associated with the terms?",
		$sd['display_number_posts_affected']);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">Will display the number of associated posts in a bracket after the term.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsTextarea("return_terms_exclude", "Exclude categories/terms by ID",
        $sd['return_terms_exclude']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Comma "," separated list of category/term IDs.</p>
</div>