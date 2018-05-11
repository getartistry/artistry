<div class="item"><?php
	$o = new wpdreamsCustomSelect("attachments_use_index", "Search engine for attachments",
		array(
			'selects' => array(
				array('option' => 'Regular engine', 'value' => 'regular'),
				array('option' => 'Index table engine', 'value' => 'index')
			),
			'value' => $sd['attachments_use_index']
		));
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">Index table engine will only work if you have the
		<a href="<?php echo get_admin_url() . "admin.php?page=asp_index_table"; ?>">index table</a>
		generated. To learn more about the pros. and cons. of the index table read the
		<a href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/index_table.html" target="_blank">documentation about the index table</a>.
	</p>
</div>
<div class="item">
	<?php
	$o = new wpdreamsYesNo("return_attachments", "Return attachments as results?",
		$sd['return_attachments']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item hide_on_att_index">
	<?php
	$o = new wpdreamsYesNo("search_attachments_title", "Search in attachment titles?",
		$sd['search_attachments_title']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item hide_on_att_index">
	<?php
	$o = new wpdreamsYesNo("search_attachments_content", "Search in attachment description?",
		$sd['search_attachments_content']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item hide_on_att_index">
    <?php
    $o = new wpdreamsYesNo("search_attachments_caption", "Search in attachment captions?",
        $sd['search_attachments_caption']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item hide_on_att_index">
	<?php
	$o = new wpdreamsYesNo("search_attachments_ids", "Search in attachment IDs?",
			$sd['search_attachments_ids']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item hide_on_att_index">
    <?php
    $o = new wpdreamsYesNo("search_attachments_terms", "Search in attachment terms (tags, etc..)?",
        $sd['search_attachments_terms']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Will search in terms (categories, tags) related to the attachments.</p>
    <p class="errorMsg">WARNING: <strong>Search in terms</strong> can be database heavy operation. Not recommended for big databases.</p>
</div>
<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("attachment_link_to", "Link the results to",
			array(
					'selects' => array(
							array("option" => "attachment page", "value" => "page"),
							array("option" => "attachment file directly", "value" => "file")
					),
					'value' => $sd['attachment_link_to']
			));
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item hide_on_att_index">
	<?php
	$o = new wd_Textarea_B64("attachment_mime_types", "Allowed mime types",
		$sd['attachment_mime_types']);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg"><strong>Comma separated list</strong> of allowed mime types. List of <a href="https://codex.wordpress.org/Function_Reference/get_allowed_mime_types"
	target="_blank">default allowed mime types</a> in WordPress.</p>
</div>
<div class="item">
	<?php
	$o = new wpdreamsYesNo("attachment_use_image", "Use the image of image mime types as the result image?",
		$sd['attachment_use_image']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item">
	<?php
	$o = new wpdreamsYesNo("search_attachments_cf_filters", "Allow custom field filters to apply on Attachment results as well?",
		$sd['search_attachments_cf_filters']);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">This only has effect if you have use any custom field filters.</p>
</div>
<div class="item">
	<?php
	$o = new wpdreamsTextarea("attachment_exclude", "Exclude attachment IDs",
		$sd['attachment_exclude']);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg"><strong>Comma separated list</strong> of attachment IDs to exclude.</p>
</div>