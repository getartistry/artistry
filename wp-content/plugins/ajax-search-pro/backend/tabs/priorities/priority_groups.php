<?php
if ( isset($_POST['priority_groups']) ) {
    wd_asp()->priority_groups->setEncoded($_POST['priority_groups'], true);
}
//wd_asp()->priority_groups->debug();
?>

<?php if (ASP_DEMO): ?>
    <p class="infoMsg">DEMO MODE ENABLED - Please note, that these options are read-only</p>
<?php endif; ?>
<p id="pg_no_pg">There are no priority groups yet. Click the <strong>Add New</strong> button to create one!</p>
<div id="pg_container">
    <div class="pg_rule_group" data-groupid="-1">
        <img title="Click on this icon for search settings!"
             class="pg_rg_edit"
             src="<?php echo plugins_url('/backend/settings/assets/icons/settings.png', ASP_FILE) ?>"/>
        <img title="Click here if you want to delete this search!"
             class="pg_rg_delete"
             src="<?php echo plugins_url('/backend/settings/assets/icons/delete.png', ASP_FILE) ?>"/>
        <span class="pg_name">Rule Group #1</span>
        <span class="pg_info"></span>
    </div>
</div>
<p id="pg_information">If you don't know what priority groups are, check the <a href="https://wp-dreams.com/go/?to=asp-doc-result-priority" target="_blank">Priority</a> and the
    <a href="https://wp-dreams.com/go/?to=asp-doc-result-priority-group" target="_blank">Priority groups</a> documentations first.</p>
<p class="noticeMsg">PLEASE NOTE: Always create <strong>as few rules as possible</strong>, as they may affect the search performance negatively.</p>
<form method="POST">
    <p style="text-align: right">
        <input type="button" id="pg_remove_all" value="Remove all" style="float: left;" class="submit wd_button_opaque">
        <input type="button" id="pg_add_new" value="Add new!" class="submit wd_button_green">
        <input type="button" id="pg_save" value="Save Groups" class="submit wd_button wd_button_blue">
    </p>
    <input name="priority_groups" id="priority_groups" type="hidden" value="<?php echo wd_asp()->priority_groups->getForDisplayEncoded(); ?>"/>
</form>

<!-- SAMPLE ITEM STARTS HERE -->
<div class="asp_pg_item asp_pg_item_sample hiddend" id="asp_pg_editor">
    <form name="pg_item_form">
        <div class="pg_rule_info">
            <div class="pg_line">
                <label for="ruleset_name">
                    Ruleset name
                </label>
                <input name="ruleset_name" id="ruleset_name" value="Ruleset">
            </div>
            <div class="pg_line">
                <label for="pg_priority">
                    Set results priority matching the rules to
                </label>
                <input type="number" name="pg_priority" id="pg_priority" value="100" min="1" max="5000">
            </div>
            <div class="pg_line">
                <label for="pg_instance">
                    Apply to
                </label>
                <select name="pg_instance" id="pg_instance">
                    <option value="0">Every search instance</option>
                    <?php foreach( wd_asp()->instances->getWithoutData() as $search_instance ): ?>
                        <option value="<?php echo $search_instance['id']; ?>"><?php echo $search_instance['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="pg_line">
                <label for="pg_phrase_logic">Apply on</label>
                <select name="pg_phrase_logic" id="pg_phrase_logic">
                    <option value="disabled">any search phrase</option>
                    <option value="any">phrase matching anywhere</option>
                    <option value="exact">phrase matching exactly</option>
                    <option value="start">phrase starting with</option>
                    <option value="end">phrase ending with</option>
                </select>
                <label for="pg_phrase"></label>
                <input type="text" name="pg_phrase" id="pg_phrase" value="" placeholder="Enter search phrase..">
            </div>
            <div class="pg_line">
                <label for="pg_rule_logic">Apply if</label>
                <select name="pg_rule_logic" id="pg_rule_logic">
                    <option value="and">all rules match</option>
                    <option value="or">any of the rules match</option>
                </select>
            </div>
        </div>
        <div class="pg_rules">
            <label for="pg_add_rule">
                <input type='button' name="pg_add_rule" id="pg_add_rule" value="New rule" class="wd_button wd_button_blue">
            </label>
            <div class="pg_rules_container" id="pg_rules_container">
                <p class="pg_rule" data-id="1">
                    <img title="Click on this icon for search settings!"
                         class="pg_edit_rule"
                         src="<?php echo plugins_url('/backend/settings/assets/icons/settings.png', ASP_FILE) ?>"/>
                    <img title="Click here if you want to delete this search!"
                         class="pg_delete_rule"
                         src="<?php echo plugins_url('/backend/settings/assets/icons/delete.png', ASP_FILE) ?>"/>
                    <span>Rule #1</span></p>
            </div>
        </div>
        <div class="pg_rule_editor hiddend" id="pg_rule_editor" data-rule-id="1">
            <span class="re_label">Rule #1</span>
            <div class="re_line">
                <label for="rule_name">
                    Rule name
                </label>
                <input type="text" name="rule_name" value="Rule name">
            </div>
            <div class="re_line">
                <label for="rule_field">
                    Rule type
                </label>
                <select name="rule_field">
                    <option value="tax">Taxonomy term</option>
                    <option value="cf">Custom field</option>
                    <!-- <option value="title">Post title</option> -->
                </select>
            </div>
            <div class="pg_rule_tax re_line hiddend">
                <label for="term_operator">Operator</label>
                <select name="term_operator">
                    <option value="in">IN</option>
                    <option value="not in">NOT IN</option>
                </select>
            </div>
            <div class="pg_rule_tax re_line hiddend">
                <div style="display:none" id="_tax_search_field"></div>
                <?php
                new wd_TaxTermSearchCallBack('pg_search_taxterms', 'Select taxonomy terms',
                    array(
                        'value' => '',
                        //'args' => array('callback' => 'wd_cf_ajax_callback'),
                        'limit' => 12
                    )
                );
                ?>
                <ul id="pg_selected_tax_terms">
                    <!-- <li data-taxonomy='taxonomy' data-id=1>Term name</li> -->
                </ul>
            </div>
            <div class="pg_rule_cf re_line hiddend">
                <div style="display:none" id="_cf_search_field"></div>
                <label for="pg_search_cf">Choose custom field</label>
                <?php
                new wd_CFSearchCallBack('pg_search_cf', '',
                    array(
                        'value' => '',
                        //'args' => array('callback' => 'wd_cf_ajax_callback'),
                        'limit' => 12
                    )
                );
                ?>
            </div>
            <div class="pg_rule_cf re_line hiddend">
                <label for="cf_operator">
                    Operator:
                </label>
                <select name="cf_operator">
                    <optgroup label="String operators">
                        <option value="like">CONTAINS</option>
                        <option value="not like">DOES NOT CONTAIN</option>
                        <option value="elike">IS EXACTLY</option>
                    </optgroup>
                    <optgroup label="Numeric operators">
                        <option value="=">=</option>
                        <option value="<>"><></option>
                        <option value="<"><</option>
                        <option value="<="><=</option>
                        <option value=">">></option>
                        <option value=">=">>=</option>
                        <option value="between">Between</option>
                    </optgroup>
                </select>
            </div>
            <div class="pg_rule_cf re_line hiddend">
                <label for="cf_val1">
                    Value(s)
                </label>
                <input type="text" name="cf_val1" value="" placeholder="Enter value here..">
                <input style="display: none;" type="text" name="cf_val2" value="" placeholder="Enter value 2 here..">
            </div>
            <div class="pg_rule_title re_line hiddend">
                <label for="title_operator">
                    Operator
                </label>
                <select name="title_operator">
                    <option value="like">CONTAINS</option>
                    <option value="not like">DOES NOT CONTAIN</option>
                    <option value="elike">IS EXACTLY</option>
                </select>
            </div>
            <div class="pg_rule_title re_line hiddend">
                <label for="title_value">
                    Text
                </label>
                <input type="text" name="title_value" value="" placeholder="Keyword..">
            </div>
            <div class="pg_rule_buttons">
                <input type="button" id="pg_editor_save_rule" value="Save rule" class="wd_button wd_button_blue">
                <input type="button" id="pg_editor_delete_rule" value="Delete rule" class="wd_button">
            </div>
        </div>
    </form>
</div>
<!-- SAMPLE ITEM ENDS HERE -->