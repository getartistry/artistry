<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/* Option not set or deactivated, return */
if (
    empty($style["selected-show_frontend_tags"]) ||
    $style["selected-show_frontend_tags"]['active'] == 0
) return;

/* Let us make it a bit more accessible */
$_sfto = $style["selected-show_frontend_tags"];

if ($_sfto['source'] == "all") {
    // Limit all tags to 400. I mean that should be more than enough..
    $_sftags = get_terms("post_tag", array("number"=>400));
} else {
    $_sftags = asp_get_terms_ordered_by_ids("post_tag", $_sfto['tag_ids']);
}

if ( empty($_sftags) ) return;

if ($_sfto['source'] == "all") {
    $_sel_tags = $_sfto['default_tag'] != "" ? array($_sfto['default_tag']) : array();
} else {
    $_sel_tags = $_sfto["checked_tag_ids"];
}

if ( isset($style['_fo']) && !isset($style['_fo']['post_tag_set']) )
    $style['_fo']['post_tag_set'] = array();

?>
<fieldset>

    <?php if ( w_isset_def($style['frontend_tags_header'], "Filter by Tags") != "" ): ?>

    <legend><?php echo asp_icl_t( "Post tags filter header" . " ($real_id)", w_isset_def($style['frontend_tags_header'], "Filter by Tags") ); ?></legend>

    <?php endif; ?>

    <?php if ($_sfto['display_mode'] == "checkboxes"): ?>

        <div class="tag_filter_box asp_sett_scroll">
        <?php
        if ( $style['display_all_tags_check_opt'] == 1) {
            ?>
            <div class="asp_option asp_option_cat asp_option_cat_level-0 asp_option_selectall">
                <div class="asp_option_inner">
                    <input id="<?php echo $id; ?>_post_tag_set_all" type="checkbox" data-targetclass="asp_post_tag_checkbox"
                        <?php echo( ( $style['all_tags_check_opt_state'] == "checked" ) ? 'checked="checked"' : '' ); ?>/>
                    <label for="<?php echo $id; ?>_post_tag_set_all"></label>
                </div>
                <div class="asp_option_label"><?php echo asp_icl_t("Select all text (post_tag) " . " ($real_id)", $style['all_tags_check_opt_text']); ?></div>
            </div><div class="asp_select_spacer"></div>
            <?php
        }
        ?>

        <?php foreach($_sftags as $_sftag): ?>

        <?php
        if ( isset($style['_fo']) ) {
            $selected = in_array($_sftag->term_id, $style['_fo']['post_tag_set']) ? ' checked="checked"' : '';
        } else {
            if ( $_sfto['source'] == "all" ) {
                if ($_sfto['all_status'] == "checked")
                    $selected = 'checked="checked"';
                else
                    $selected = '';
            } else {
                $selected = in_array($_sftag->term_id, $_sel_tags) ? 'checked="checked"' : '';
            }
        }
        ?>
        <div class="asp_option asp_option_cat">
            <div class="asp_option_inner">
                <input type="checkbox" value="<?php echo $_sftag->term_id; ?>"
                       id="<?php echo $id; ?>post_tag_set_<?php echo $_sftag->term_id; ?>"
                       class="asp_post_tag_checkbox"
                       name="post_tag_set[]" <?php echo $selected; ?>/>
                <label for="<?php echo $id; ?>post_tag_set_<?php echo $_sftag->term_id; ?>"></label>
            </div>
            <div class="asp_option_label">
                <?php echo $_sftag->name; ?>
            </div>
        </div>

        <?php endforeach; ?>

        </div>

    <?php elseif (
        $_sfto['display_mode'] == "dropdown" ||
        $_sfto['display_mode'] == "dropdownsearch" ||
        $_sfto['display_mode'] == "multisearch"
    ): ?>

        <div class="asp_select_label asp_select_<?php echo $_sfto['display_mode'] == 'multisearch' ? 'multiple' : 'single'; ?>">

        <select name="post_tag_set[]" <?php echo $_sfto['display_mode'] == 'multisearch' ? ' multiple' : ''; ?>
                data-placeholder="<?php echo asp_icl_t("Tags filter multiselect placeholder" . " ($real_id)", $style["frontend_tags_placeholder"]); ?>"
                class="<?php echo $_sfto['display_mode'] == "dropdownsearch" || $_sfto['display_mode'] == "multisearch" ? 'asp_gochosen' : 'asp_nochosen';?>">
        <?php
        if ( $style['display_all_tags_option'] == 1) {
            ?>
            <option value="-1" class="asp_option_cat asp_option_cat_level-0">
                <?php echo asp_icl_t("All tags option" . " ($real_id)", $style['all_tags_opt_text']); ?>
            </option>
            <?php
        }
        ?>
        <?php foreach($_sftags as $_sftag): ?>
            <?php
            if ( isset($style['_fo']) )
                $selected = in_array($_sftag->term_id, $style['_fo']['post_tag_set']) ? ' selected' : '';
            else
                $selected = in_array($_sftag->term_id, $_sel_tags) ? ' selected' : '';
            ?>
            <option value="<?php echo $_sftag->term_id; ?>"<?php echo $selected; ?>>
                <?php echo $_sftag->name; ?>
            </option>
        <?php endforeach; ?>
        </select>

        </div>

    <?php elseif ($_sfto['display_mode'] == "radio"): ?>

        <div class="tag_filter_box asp_sett_scroll">
            <?php
            $_tmpp = new stdClass();
            $_tmpp->term_id = -1;
            $_tmpp->name = asp_icl_t("Chose one option" . " ($real_id)", $style['all_tags_opt_text']);
            array_unshift($_sftags, $_tmpp);

            foreach($_sftags as $k => $_sftag): ?>
                <?php
                if ( isset($style['_fo']) )
                    $selected = in_array($_sftag->term_id, $style['_fo']['post_tag_set']) ? ' checked="checked"' : '';
                else
                    $selected = in_array($_sftag->term_id, $_sel_tags) || $k == 0 ? ' checked="checked"' : '';
                ?>
                <label class="asp_label">
                <input type="radio" class="asp_radio" name="post_tag_set[]" value="<?php echo $_sftag->term_id; ?>"
                    <?php echo $selected; ?>>
                <?php echo $_sftag->name; ?></label><br>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</fieldset>