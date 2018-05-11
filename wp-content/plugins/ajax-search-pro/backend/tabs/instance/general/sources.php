<div class="item"><?php
	$_it_engine_val = isset($_POST['search_engine']) ? $_POST['search_engine'] : $sd['search_engine'];
	$o = new wpdreamsCustomSelect("search_engine", "Search engine",
		array(
			'selects' => array(
				array('option' => 'Regular engine', 'value' => 'regular'),
				array('option' => 'Index table engine', 'value' => 'index')
			),
			'value' => $sd['search_engine']
		));
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">Index table engine will only work if you have the
		<a href="<?php echo get_admin_url() . "admin.php?page=asp_index_table"; ?>">index table</a>
	generated. To learn more about the pros. and cons. of the index table read the
		<a href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/index_table.html" target="_blank">documentation about the index table</a>.
	</p>
</div>
<?php
	$it_options_visibility = $_it_engine_val == 'index' ? ' hiddend' : '';
?>
<div class="item it_engine_index_d" style="text-align: center;">
	Since you have the Index table engine selected, some options here are disabled,<br> because they are available
	on the <a href="<?php echo get_admin_url() . "admin.php?page=asp_index_table"; ?>" target="_blank">index table</a>
	options page.
</div>
<div class="item"><?php
    $o = new wpdreamsCustomPostTypes("customtypes", "Search in custom post types",
        $sd['customtypes']);
    $params[$o->getName()] = $o->getData();
    $params['selected-'.$o->getName()] = $o->getSelected();
    ?></div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("searchincomments", "Return comments as results?",
        $sd['searchincomments']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item it_engine_index">
    <?php
    $o = new wpdreamsYesNo("searchintitle", "Search in title?",
        $sd['searchintitle']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item it_engine_index">
    <?php
    $o = new wpdreamsYesNo("searchincontent", "Search in content?",
        $sd['searchincontent']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item it_engine_index">
    <?php
    $o = new wpdreamsYesNo("searchinexcerpt", "Search in post excerpts?",
        $sd['searchinexcerpt']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item it_engine_index">
    <?php
    $o = new wpdreamsYesNo("search_in_permalinks", "Search in post permalinks?",
        $sd['search_in_permalinks']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Might not work correctly in some cases unfortunately.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_in_ids", "Search in post (and CPT) IDs?",
        $sd['search_in_ids']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item it_engine_index" style="position:relative;">
    <div class='asp-setting-search-all-cf' style="position: absolute; right: 253px; top: 18px; z-index: 1000000;">
    <?php
    $o = new wpdreamsYesNo("search_all_cf", "Search all custom fields?",
        $sd['search_all_cf']);
    $params[$o->getName()] = $o->getData();
    ?></div><?php
    $o = new wpdreamsCustomFields("customfields", "..or search in selected custom fields?",
        $sd['customfields']);
    $params[$o->getName()] = $o->getData();
    $params['selected-'.$o->getName()] = $o->getSelected();
    ?>
</div>
<div class="item it_engine_index">
    <?php $o = new wpdreamsText("post_status", "Post statuses to search", $sd['post_status']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Comma separated list. WP Defaults: publish, future, draft, pending, private, trash, auto-draft</p>
</div>
<div class="item it_engine_index">
    <?php
    $o = new wpdreamsYesNo("searchinterms", "Search in terms? (categories, tags)",
        $sd['searchinterms']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">Will search in terms (categories, tags) related to posts.</p>
    <p class="errorMsg">WARNING: <strong>Search in terms</strong> can be database heavy operation. Not recommended for big databases.</p>
</div>
<script>
jQuery(function($) {
	$('select[name="search_engine"]').change(function() {
		if ($(this).val() == 'index') {
			$("#wpdreams .item.it_engine_index").css('display', 'none');
			$("#wpdreams .item.it_engine_index_d").css('display', 'block');
		} else {
			$("#wpdreams .item.it_engine_index").css('display', 'block');
			$("#wpdreams .item.it_engine_index_d").css('display', 'none');
		}
	});
	$('select[name="search_engine"]').change();
});
</script>