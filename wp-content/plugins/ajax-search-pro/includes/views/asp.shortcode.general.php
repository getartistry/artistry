<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

$field_visible = count($_st['frontend_fields']['selected']) > 0;

// Search redirection, memorize options
if ( isset($style['_fo']) ) {
    $_checked = array(
        "exact" => in_array('exact', $_st['_fo']['asp_gen']) ? ' checked="checked"' : "",
        "title" => in_array('title', $_st['_fo']['asp_gen']) ? ' checked="checked"' : "",
        "content" => in_array('content', $_st['_fo']['asp_gen']) ? ' checked="checked"' : "",
        "comments" => in_array('comments', $_st['_fo']['asp_gen']) ? ' checked="checked"' : "",
        "excerpt" => in_array('excerpt', $_st['_fo']['asp_gen']) ? ' checked="checked"' : ""
    );
} else if( $settingsFullyHidden ) {
    $_checked = array(
        "exact" => $_st['exactonly'] == 1 ? ' checked="checked"' : "",
        "title" =>  $_st['searchintitle'] == 1 ? ' checked="checked"' : "",
        "content" => $_st['searchincontent'] == 1 ? ' checked="checked"' : "",
        "comments" => $_st['searchincomments'] == 1 ? ' checked="checked"' : "",
        "excerpt" => $_st['searchinexcerpt'] == 1 ? ' checked="checked"' : ""
    );
} else {
    $_checked = array(
        "exact" => in_array('exact', $_st['frontend_fields']['checked']) ? ' checked="checked"' : "",
        "title" => in_array('title', $_st['frontend_fields']['checked']) ? ' checked="checked"' : "",
        "content" => in_array('content', $_st['frontend_fields']['checked']) ? ' checked="checked"' : "",
        "comments" => in_array('comments', $_st['frontend_fields']['checked']) ? ' checked="checked"' : "",
        "excerpt" => in_array('excerpt', $_st['frontend_fields']['checked']) ? ' checked="checked"' : ""
    );
}


if ( function_exists('qtranxf_getLanguage') ) {
    $qtr_lg = qtranxf_getLanguage();
} else if ( function_exists('qtrans_getLanguage') ) {
    $qtr_lg = qtrans_getLanguage();
} else {
    $qtr_lg = 0;
}

do_action('asp_layout_settings_before_first_item', $id);
?>
<fieldset class="<?php echo ($field_visible) ? "" : " hiddend"; ?>">
    <?php if ($_st['generic_filter_label'] != ''): ?>
        <legend><?php echo asp_icl_t("Generic filter label" . " ($real_id)", $_st['generic_filter_label']);  ?></legend>
    <?php endif; ?>

    <div class="asp_option_inner hiddend">
        <input type='hidden' name='qtranslate_lang'
               value='<?php echo $qtr_lg; ?>'/>
    </div>

    <?php if ( function_exists("pll_current_language") ): ?>
    <div class="asp_option_inner hiddend">
        <input type='hidden' name='polylang_lang'
               value='<?php echo pll_current_language(); ?>'/>
    </div>
    <?php endif; ?>

	<?php if (defined('ICL_LANGUAGE_CODE')
	          && ICL_LANGUAGE_CODE != ''
	          && defined('ICL_SITEPRESS_VERSION')
	): ?>
	<div class="asp_option_inner hiddend">
		<input type='hidden' name='wpml_lang'
		       value='<?php echo ICL_LANGUAGE_CODE; ?>'/>
	</div>
	<?php endif; ?>


    <?php if($_st['frontend_fields']['display_mode'] == 'checkboxes'): ?>
        <?php foreach ( $_st['frontend_fields']['selected'] as $fe_field ): ?>
        <div class="asp_option">
            <div class="asp_option_inner">
                <input type="checkbox" value="<?php echo $fe_field; ?>" id="set_<?php echo $fe_field.$id; ?>"
                       name="asp_gen[]" <?php echo $_checked[$fe_field]; ?>/>
                <label for="set_<?php echo $fe_field.$id; ?>"></label>
            </div>
            <div class="asp_option_label">
                <?php echo asp_icl_t("Generic field[".$fe_field."]" . " ($real_id)", $_st['frontend_fields']['labels'][$fe_field]); ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php elseif ($_st['frontend_fields']['display_mode'] == 'radio'): ?>
        <div>
        <?php foreach ( $_st['frontend_fields']['selected'] as $fe_field ): ?>
            <label class="asp_label">
                <input type="radio" class="asp_radio" name="asp_gen[]"
                    <?php echo !empty($_checked[$fe_field]) ? "checked='checked'" : ""; ?> value="<?php echo $fe_field; ?>">
                <?php echo asp_icl_t("Generic field[".$fe_field."]" . " ($real_id)", $_st['frontend_fields']['labels'][$fe_field]); ?>
            </label><br>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class='asp_select_label asp_select_single'>
        <select name="asp_gen[]">
        <?php foreach ( $_st['frontend_fields']['selected'] as $fe_field ): ?>
            <option value="<?php echo $fe_field; ?>" class="asp_option"
                <?php echo !empty($_checked[$fe_field]) ? "selected='selected'" : ""; ?>>
                <?php echo asp_icl_t("Generic field[".$fe_field."]" . " ($real_id)", $_st['frontend_fields']['labels'][$fe_field]); ?>
            </option>
        <?php endforeach; ?>
        </select>
        </div>
    <?php endif; ?>
    <?php foreach ( $_st['frontend_fields']['unselected'] as $fe_field ): ?>
        <?php
        $_chkd = '';
        switch ($fe_field) {
            case 'title':
                $_chkd = $_st['searchintitle'] == 1 ? ' checked="checked"' : "";
                break;
            case 'content':
                $_chkd = $_st['searchincontent'] == 1 ? ' checked="checked"' : "";
                break;
            case 'excerpt':
                $_chkd = $_st['searchinexcerpt'] == 1 ? ' checked="checked"' : "";
                break;
            case 'exact':
                $_chkd = $_st['exactonly'] == 1 ? ' checked="checked"' : "";
                break;
            case 'comments':
                $_chkd = $_st['searchincomments'] == 1 ? ' checked="checked"' : "";
                break;
        }
        ?>
        <div class="asp_option hiddend">
            <div class="asp_option_inner">
                <input type="checkbox" value="<?php echo $fe_field; ?>" id="set_<?php echo $fe_field.$id; ?>"
                       name="asp_gen[]" <?php echo $_chkd; ?>/>
                <label for="set_<?php echo $fe_field.$id; ?>"></label>
            </div>
        </div>
    <?php endforeach; ?>
</fieldset>