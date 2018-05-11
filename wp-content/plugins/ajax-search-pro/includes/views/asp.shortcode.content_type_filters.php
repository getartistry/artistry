<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if ( count($_st['content_type_filter']['selected']) > 0 ) {

// Search redirection, memorize options
if ( isset($style['_fo']) ) {
    $_carr = $_st['_fo']['asp_gen'];
} else {
    $_carr = $_st['content_type_filter']['checked'];
}
$_checked = array(
    "any" => in_array('any', $_carr) ? ' checked="checked"' : "",
    "cpt" => in_array('cpt', $_carr) ? ' checked="checked"' : "",
    "taxonomies" => in_array('taxonomies', $_carr) ? ' checked="checked"' : "",
    "users" => in_array('users', $_carr) ? ' checked="checked"' : "",
    "blogs" => in_array('blogs', $_carr) ? ' checked="checked"' : "",
    "buddypress" => in_array('buddypress', $_carr) ? ' checked="checked"' : "",
    "attachments" => in_array('attachments', $_carr) ? ' checked="checked"' : ""
);
if (($akey = array_search('any', $_st['content_type_filter']['selected'])) !== false) {
    unset($_st['content_type_filter']['selected'][$akey]);
    $_st['content_type_filter']['selected'] = array_merge(array('any'), $_st['content_type_filter']['selected']);
}
?>
<fieldset class="asp_content_type_filters">
    <?php if ($_st['content_type_filter_label'] != ''): ?>
        <legend><?php echo asp_icl_t("Content type filter label" . " ($real_id)", $_st['content_type_filter_label']);  ?></legend>
    <?php endif; ?>
    <?php if($_st['content_type_filter']['display_mode'] == 'checkboxes'): ?>
        <?php foreach ( $_st['content_type_filter']['selected'] as $fe_field ): ?>
            <?php
            $_extra_class = '';
            $_o_val = $fe_field;
            $_extra_attr = ' class="asp_ctf_cbx" ';
            if ( $fe_field == 'any' ) {
                $_o_val = -1;
                $_extra_class = ' asp_option_selectall';
                $_extra_attr = ' data-targetclass="asp_ctf_cbx" ';
            }
            ?>
            <div class="asp_option asp_option_cat <?php echo $_extra_class; ?>">
                <div class="asp_option_inner">
                    <input type="checkbox" value="<?php echo $_o_val; ?>" id="set_<?php echo $fe_field.$id; ?>"
                           <?php echo $_extra_attr; ?>
                           name="asp_ctf[]" <?php echo $_checked[$fe_field]; ?>/>
                    <label for="set_<?php echo $fe_field.$id; ?>"></label>
                </div>
                <div class="asp_option_label">
                    <?php echo asp_icl_t("Content Type field[".$fe_field."]" . " ($real_id)", $_st['content_type_filter']['labels'][$fe_field]); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php elseif ($_st['content_type_filter']['display_mode'] == 'radio'): ?>
        <div>
            <?php foreach ( $_st['content_type_filter']['selected'] as $fe_field ): ?>
                <?php
                $_o_val = $fe_field;
                if ( $fe_field == 'any' ) {
                    $_o_val = -1;
                }
                ?>
                <label class="asp_label">
                    <input type="radio" class="asp_radio" name="asp_ctf[]"
                        <?php echo !empty($_checked[$fe_field]) ? "checked='checked'" : ""; ?> value="<?php echo $_o_val; ?>">
                    <?php echo asp_icl_t("Content Type field[".$fe_field."]" . " ($real_id)", $_st['content_type_filter']['labels'][$fe_field]); ?>
                </label><br>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class='asp_select_label asp_select_single'>
            <select name="asp_ctf[]">
                <?php foreach ( $_st['content_type_filter']['selected'] as $fe_field ): ?>
                    <?php
                    $_o_val = $fe_field;
                    if ( $fe_field == 'any' ) {
                        $_o_val = -1;
                    }
                    ?>
                    <option value="<?php echo $_o_val; ?>" class="asp_option"
                        <?php echo !empty($_checked[$fe_field]) ? "selected='selected'" : ""; ?>>
                        <?php echo asp_icl_t("Content Type field[".$fe_field."]" . " ($real_id)", $_st['content_type_filter']['labels'][$fe_field]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
</fieldset>
<?php }