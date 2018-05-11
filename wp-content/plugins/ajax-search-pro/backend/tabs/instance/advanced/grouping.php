<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("group_by", "Group results by", array(
			'selects'=> array(
				array("value" => "none", "option" => "No grouping"),
				array("value" => "post_type", "option" => "Post Type"),
				array("value" => "categories_terms", "option" => "Categories/Terms"),
				array("value" => "content_type", "option" => "Content Type")
			),
			'value'=>$sd['group_by']) );
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">Only works with <b>Vertical</b> results layout.</p>
</div>
<div class="item wd_groupby wd_groupby_categories_terms">
	<?php
	$o = new wd_TaxonomyTermSelect("groupby_terms", "Category/Term grouping options", array(
			"value" => $sd['groupby_terms'],
			"args"  => array(
					"show_type" => 0,
					"show_checkboxes" => 0
			)
	));
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item wd_groupby wd_groupby_content_type">
	<?php
	$o = new wd_Sortable_Editable("groupby_content_type", "Content type grouping options", $sd['groupby_content_type']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item wd_groupby wd_groupby_post_type">
	<?php
	$o = new wd_CPT_Editable("groupby_cpt", "Custom Post Type grouping options", $sd['groupby_cpt']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item wd_groupby_op">
	<?php
	$o = new wpdreamsText("group_header_prefix", "Group header prefix text", $sd['group_header_prefix']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item wd_groupby_op">
	<?php
	$o = new wpdreamsText("group_header_suffix", "Group header suffix text", $sd['group_header_suffix']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item wd_groupby_op">
	<?php
	$o = new wpdreamsCustomSelect("group_result_no_group", "If result does not match any group?", array(
		'selects'=> array(
				array("value" => "remove", "option" => "Remove it"),
				array("value" => "display", "option" => "Display in Other results group")
		),
		'value'=>$sd['group_result_no_group']) );
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item wd_groupby_op item-flex-nogrow" style="flex-wrap: wrap;">
	<?php
	$o = new wpdreamsText("group_other_results_head", "Other results group header text", $sd['group_other_results_head']);
	$params[$o->getName()] = $o->getData();
	$o = new wpdreamsCustomSelect("group_other_location", " location ", array(
			'selects'=> array(
					array("value" => "top", "option" => "Top of results"),
					array("value" => "bottom", "option" => "Bottom of results")
			),
			'value'=>$sd['group_other_location']) );
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item wd_groupby_op">
	<?php
	$o = new wpdreamsYesNo("group_exclude_duplicates", "Display duplicates only in the first group match?", $sd['group_exclude_duplicates']);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">For example posts in multiple categories will be displayed in the first matching group only.</p>
</div>
<div class="item wd_groupby_op item-flex-nogrow" style="flex-wrap: wrap;">
	<?php
	$o = new wpdreamsYesNo("group_show_empty", "Display empty groups with the 'No results!' text?", $sd['group_show_empty']);
	$params[$o->getName()] = $o->getData();

	$o = new wpdreamsCustomSelect("group_show_empty_position", " ..emtpy group location ", array(
		'selects'=> array(
			array("value" => "default", "option" => "Leave the default"),
			array("value" => "bottom", "option" => "Move to the bottom"),
			array("value" => "top", "option" => "Move to the top")
		),
		'value'=>$sd['group_show_empty_position']) );
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item wd_groupby_op">
	<?php
	$o = new wpdreamsYesNo("group_result_count", "Show results count in group headers", $sd['group_result_count']);
	$params[$o->getName()] = $o->getData();
	?>
</div>