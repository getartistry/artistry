<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

$term_content = '';
$term_ordering = w_isset_def($style['selected-frontend_term_order'], array('name', 'ASC'));

// State memory for taxonomy term select all checkbox
$select_all_displayed = false;
$have_selected = false;

/* Terms */
if ($style['showsearchintaxonomies'] == 1) {

    $_close_fieldset = false;

    $_terms = array();
    $visible_terms = array();
    $term_buff = "";

    // Re-group by taxonomy
    $tax_terms = array();
    foreach ($style['show_terms']['terms'] as $t) {
        if ( !isset($tax_terms[$t['taxonomy']]) )
            $tax_terms[$t['taxonomy']] = array();
        $tax_terms[$t['taxonomy']][$t['id']] = $t;
    }

    ob_start();

    if ( isset($style['_fo']) &&
        !isset($style['_fo']['termset']) ) {
        $style['_fo']['termset'] = array();
    }

    foreach ($tax_terms as $taxonomy => $terms) {

        $term_ids = array();
        foreach( $terms as $k => $t) {
            $term_ids[] = $t['id'];
        }

        if ( !empty($terms) ) {

            if ( isset($style['_fo']) &&
                !isset($style['_fo']['termset'][$taxonomy]) )
            {
                $style['_fo']['termset'][$taxonomy] = array();
            }

            // Need all terms
            if ( count($terms) == 1 && $terms[-1]["id"] == -1 ) {
                $_needed_all = true;
                $_needed_terms_full = get_terms($taxonomy, array(
                    'taxonomy'=> $taxonomy,
                    'orderby' => $term_ordering[0],
                    'order'   => $term_ordering[1],
                    'hide_empty' => 0,
                    'exclude' => isset($terms[-1]["ex_ids"]) ? $terms[-1]["ex_ids"] : array()
                ));
            } else {
                $_needed_all = false;
                $_needed_terms_full = get_terms($taxonomy, array(
                    'taxonomy'=> $taxonomy,
                    'orderby' => 'include',
                    'order' => 'ASC',
                    'include' => $term_ids,
                    'hide_empty' => 0
                ));
            }

            if ( is_wp_error($_needed_terms_full) )
                continue;

            $_needed_terms_full = apply_filters('asp_fontend_get_taxonomy_terms',
                $_needed_terms_full,
                $taxonomy,
                array(
                    'orderby' => $term_ordering[0],
                    'order'   => $term_ordering[1],
                    'include' => $terms,
                    'include_ids' => $term_ids
                ),
                $_needed_all
            );

	        $_needed_terms_sorted = array();
	        $needed_terms_flat = array();

            $display_mode = array(
                "type"    => "checkbox",
                "default" => "checked",
                "select_all" => 0,
                "select_all_text" => "",
                "box_header_text" => ""
            );

            if ( count($style['show_terms']['display_mode']) > 0) {
                if ( $style['show_terms']['separate_filter_boxes'] != 1 ) {
                    $display_mode = $style['show_terms']['display_mode']['all'];
                } else if (isset($style['show_terms']['display_mode'][$taxonomy])) {
                    $display_mode = $style['show_terms']['display_mode'][$taxonomy];
                }
            }

	        if (w_isset_def($style['frontend_term_hierarchy'], 1) == 1) {
		        wd_sort_terms_hierarchicaly( $_needed_terms_full, $_needed_terms_sorted );
		        wd_flatten_hierarchical_terms( $_needed_terms_sorted, $needed_terms_flat );
	        } else {
		        $needed_terms_flat = $_needed_terms_full;
	        }

            if ($style['show_terms']['separate_filter_boxes'] != 0) {
                $_x_term = get_taxonomies(array("name" => $taxonomy), "objects");
                if (isset($_x_term[$taxonomy]))
                    $_tax_name = $_x_term[$taxonomy]->label;
                ?>
                <fieldset class="asp_tax_filter asp_<?php echo $display_mode['type']; ?>_filter_box">
                <legend><?php echo asp_icl_t("Taxonomy [$taxonomy] filter box text" . " ($real_id)",  $display_mode["box_header_text"]); ?></legend>
                <div class='categoryfilter<?php echo $display_mode['type'] != 'checkboxes' ? '' : ' asp_sett_scroll'; ?>'>
            <?php
            }

            if ($display_mode['type'] == "checkboxes") {
                $ch_class = "terms";

                if ( $display_mode['select_all'] == 1 && !$select_all_displayed) {
                    if ($style['show_terms']['separate_filter_boxes'] == 0)
                        $ch_class = "terms";
                    else
                        $ch_class = preg_replace("/[^a-zA-Z0-9]+/", "", $taxonomy);
                    ?>
                    <div class="asp_option_cat asp_option asp_option asp_option_cat_level-0 asp_option_selectall">
                        <div class="asp_option_inner">
                            <input id="asp_<?php echo $ch_class; ?>_all<?php echo $id; ?>" type="checkbox" data-targetclass="asp_<?php echo $ch_class; ?>_checkbox"
                                <?php echo( ( $display_mode['select_all'] == 1 ) ? 'checked="checked"' : '' ); ?>/>
                            <label for="asp_<?php echo $ch_class; ?>_all<?php echo $id; ?>"></label>
                        </div>
                        <div class="asp_option_label"><?php echo asp_icl_t("Select all text [$taxonomy]" . " ($real_id)", $display_mode['select_all_text']); ?></div>
                    </div><div class="asp_select_spacer"></div>
                    <?php
                    if ($style['show_terms']['separate_filter_boxes'] == 0)
                        $select_all_displayed = true;
                }

                $chb_default_state = 'checked="checked"';
                if ( $display_mode["default"] == "unchecked" )
                    $chb_default_state = '';

                foreach ($needed_terms_flat as $k => $term) {
                    if ( isset($style['_fo']) ) {
                        $checked = in_array($term->term_id, $style['_fo']['termset'][$taxonomy]) ? " checked=checked" : "";
                    } else {
                        /**
                         * Explanation: $tax_terms contains the originals sorted by taxonomy.
                         * If the current term is in the array, then it was excplicitly selected, not by "Use all from.."
                         * In this case it is only unchecked when in the un_checked array. Otherwise the default state applies.
                         */
                        if ( isset($tax_terms[$term->taxonomy][$term->term_id]) ) {
                            $checked = in_array($term->term_id, $style['show_terms']['un_checked']) ? '' : " checked=checked";
                        } else {
                            $checked = $chb_default_state;
                        }
                    }

                    $basic_name = "termset[".$term->taxonomy."]";
                    /*if ($term->taxonomy == "category")
                        $basic_name = "categoryset";*/
                    ?>
                    <div class="asp_option_cat asp_option asp_option asp_option_cat_level-<?php echo $term->level; ?>"
                         data-lvl="<?php echo $term->level; ?>"
                         asp_cat_parent="<?php echo $term->parent; ?>">
                        <div class="asp_option_inner">
                            <input type="checkbox" value="<?php echo $term->term_id; ?>" class="asp_<?php echo $ch_class; ?>_checkbox"
                                   id="<?php echo $id; ?>termset_<?php echo $term->term_id; ?>"
                                   name="<?php echo $basic_name; ?>[]" <?php echo $checked; ?>/>
                            <label for="<?php echo $id; ?>termset_<?php echo $term->term_id; ?>"></label>
                        </div>
                        <div class="asp_option_label">
                            <?php echo $term->name; ?>
                        </div>
                    </div>
                    <?php
                }

            } else if (
                $display_mode['type'] == "dropdown" ||
                $display_mode['type'] == "dropdownsearch" ||
                $display_mode['type'] == "multisearch"
            ) {
                ob_start();

                if ( $display_mode['select_all'] == 1 && !$select_all_displayed && $display_mode['type'] != "multisearch") {
                    ?>
                    <option value="-1" class="asp_option_cat asp_option_cat_level-0"
                        <?php echo $display_mode['default'] == "all" ? "selected='selected'" : ""; ?>>
                        <?php echo asp_icl_t("Chose one option [$taxonomy]" . " ($real_id)", $display_mode['select_all_text']); ?>
                    </option>
                    <?php
                    if ($style['show_terms']['separate_filter_boxes'] == 0)
                        $select_all_displayed = true;
                }

                $name = "";
                $len = count($needed_terms_flat);
                $i = 0;
                $selected = false;
                if ($style['show_terms']['separate_filter_boxes'] == 1)
                    $have_selected = false;
                foreach ($needed_terms_flat as $k => $term) {
                    if ( isset($style['_fo']) ) {
                        if ( !$have_selected ) {
                            $selected = in_array($term->term_id, $style['_fo']['termset'][$taxonomy]);
                            if ( $selected && $display_mode['type'] != 'multisearch' )
                                $have_selected = true;
                        } else {
                            $selected = false;
                        }
                    } else if (  $display_mode['type'] == 'multisearch' ) {
                        if ( isset($tax_terms[$term->taxonomy][$term->term_id]) )
                            $selected = !in_array($term->term_id, $style['show_terms']['un_checked']);
                    } else {
                        if (
                            !$have_selected && (
                                ($i == 0 && $display_mode['default'] == "first") ||
                                (($i == $len -1) && $display_mode['default'] == "last") ||
                                $term->term_id == $display_mode['default']
                            )
                        ) {
                            $selected = true;
                            $have_selected = true;
                        } else {
                            $selected = false;
                        }
                    }

                    $val = $term->name;
                    if ( ! isset( $term->level ) )
                        $term->level = 0;
                    $name = $style['show_terms']['separate_filter_boxes'] == 1 ? "termset[".$term->taxonomy."][]" : "termset_single";;
                    ?>
                    <option class="asp_option_cat  asp_option_cat_level-<?php echo $term->level; ?>"
                            asp_cat_parent="<?php echo $term->parent; ?>"
                            value="<?php echo $term->term_id; ?>"
                        <?php echo $selected ? "selected='selected'" : ""; ?>>
                        <?php echo str_repeat("&nbsp;&nbsp;", $term->level) . $val; ?>
                    </option>
                <?php
                    $i++;
                }

                $_sel_class = 'asp_nochosen';
                $_sel_multi = '';
                $_sel_placeholder = '';
                if (
                    $display_mode['type'] == "dropdownsearch" ||
                    $display_mode['type'] == "multisearch"
                )
                    $_sel_class = 'asp_gochosen';
                if ( $display_mode['type'] == "multisearch" ) {
                    $_sel_multi = ' multiple';
                    $_sel_placeholder = !empty($display_mode['box_placeholder_text']) ?
                        asp_icl_t("Multiselect placeholder [$taxonomy]" . " ($real_id)", $display_mode['box_placeholder_text']) : '';
                }

                if ( $style['show_terms']['separate_filter_boxes'] == 0 ) {
                    $term_buff .= ob_get_clean();
                    end($tax_terms);
                    if ($taxonomy === key($tax_terms)) {
                        echo "<div class='asp_select_label asp_select_single'><select data-placeholder='$_sel_placeholder' $_sel_multi class='$_sel_class' name='".$name."'>";
                        echo $term_buff;
                        echo "</select></div>";
                    }
                } else {
                    $term_buff = ob_get_clean();
                    echo "<div class='asp_select_label asp_select_single'><select data-placeholder='$_sel_placeholder' $_sel_multi class='$_sel_class' name='".$name."'>";
                    echo $term_buff;
                    echo "</select></div>";
                }

            } else if ($display_mode['type'] == "radio") {
                echo "<div class='term_filter_box asp_sett_scroll'>";
                $name = $style['show_terms']['separate_filter_boxes'] == 1 ? "termset[".$taxonomy."][]" : "termset_single";

                if ( $display_mode['select_all'] == 1 && !$select_all_displayed) {
                    ?>
                    <label class="asp_label">
                        <input type="radio" class="asp_radio" name="<?php echo $name; ?>"
                            <?php echo $display_mode['default'] == "all" ? "checked='checked'" : ""; ?> value="-1">
                        <?php echo asp_icl_t("Chose one option [$taxonomy]" . " ($real_id)", $display_mode['select_all_text']); ?>
                    </label><br>
                    <?php
                    if ($style['show_terms']['separate_filter_boxes'] == 0)
                        $select_all_displayed = true;
                }

                $len = count($needed_terms_flat);
                $i = 0;
                $selected = false;
                if ($style['show_terms']['separate_filter_boxes'] == 1)
                    $have_selected = false;
                foreach ($needed_terms_flat as $k => $term) {
                    if ( isset($style['_fo']) ) {
                        if ( !$have_selected ) {
                            $selected = in_array($term->term_id, $style['_fo']['termset'][$taxonomy]);
                            if ( $selected )
                                $have_selected = true;
                        } else {
                            $selected = false;
                        }
                    } else {
                        if (
                            !$have_selected && (
                                ($i == 0 && $display_mode['default'] == "first") ||
                                (($i == $len -1) && $display_mode['default'] == "last") ||
                                $term->term_id == $display_mode['default']
                            )
                        ) {
                            $selected = true;
                            $have_selected = true;
                        } else {
                            $selected = false;
                        }
                    }

                    $val = $term->name;
                    if ( ! isset( $term->level ) )
                        $term->level = 0;
                    ?>
                    <label class="asp_label">
                        <input type="radio" class="asp_radio" name="<?php echo $name; ?>" value="<?php echo $term->term_id; ?>"
                            <?php echo $selected ? ' checked="checked"' : '' ; ?>>
                        <?php echo $val; ?>
                    </label><br>
                <?php
                }
                echo "</div>";
            }

            if ($style['show_terms']['separate_filter_boxes'] != 0) {
                ?>
                </div>
                </fieldset>
            <?php
            }

        }
    }

    $term_content = ob_get_clean();
}
?>
<?php if ( $term_content != '' ): ?>
    <?php if ($style['show_terms']['separate_filter_boxes'] == 0): ?>
        <?php
        $_need_scroll = $display_mode['type'] == 'checkboxes' || $display_mode['type'] == 'radio' ? ' asp_sett_scroll' : '';
        ?>
        <fieldset class="asp_cat_filter_field<?php echo count($style['show_terms']['terms']) > 0 ? '' : ' hiddend'; ?><?php echo $display_mode['type'] == 'checkboxes' ? ' asp_checkboxes_filter_box' : ''; ?>">
            <?php if ( !empty($display_mode["box_header_text"]) ): ?>
            <legend><?php echo $display_mode["box_header_text"]; ?></legend>
            <?php endif; ?>
            <div class='categoryfilter<?php echo $_need_scroll; ?>'>
            <?php echo $term_content; ?>
            </div>
        </fieldset>
    <?php else: ?>
        <?php echo $term_content; ?>
    <?php endif; ?>
<?php endif; ?>
