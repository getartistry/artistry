<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

$x_in = 0;
?>

<?php foreach($asp_f_items as $key=>$item): ?>

<?php
    $item = apply_filters("asp_cf_item_view", $item);

    $x_in++;
    $_in = $id.$x_in;
    $_field_name = $item->asp_f_field . "_" . $x_in;

    if ( isset($style['_fo']) && !isset($style['_fo']['aspf']) )
        $style['_fo']['aspf'] = array();
?>

<?php if ($item->asp_f_type != 'hidden'): ?>
<fieldset class="asp_custom_f<?php echo in_array($item->asp_f_type, array('checkboxes', 'radio')) ? ' asp_sett_scroll' : ''; ?>">
<?php endif; ?>

<?php if (
	w_isset_def($item->asp_f_show_title, 'asp_checked') == 'asp_checked' &&
	$item->asp_f_type != 'hidden'
	): ?>
    <legend><?php echo $item->asp_f_title; ?></legend>
<?php endif; ?>
<?php switch($item->asp_f_type) { case "radio": ?>

        <?php $item->asp_f_radio_value = apply_filters("asp_cf_radio_values", $item->asp_f_radio_value, $item); ?>

        <?php foreach($item->asp_f_radio_value as $radio): ?>
            <?php
            if ( isset($style['_fo']) && isset($style['_fo']['aspf'][$_field_name]) )
                $checked = $style['_fo']['aspf'][$_field_name] == $radio[0] ? ' checked="checked"' : "";
            else
                $checked = strpos($radio[1], '**') > 0 ? ' checked="checked"':'';
            ?>
            <label class="asp_label">
                <input type="radio" class="asp_radio" name="aspf[<?php echo $_field_name; ?>]"
                       value="<?php echo str_replace('"', '&#34;', $radio[0]); ?>" <?php echo $checked; ?>/>
                <?php echo str_replace('**', '', $radio[1]); ?>
            </label><br>
        <?php endforeach; ?>
    <?php break; ?>
    <?php case "dropdown": ?>
        <div class="asp_select_label<?php echo w_isset_def($item->asp_f_dropdown_multi, 'asp_unchecked') == 'asp_checked'?' asp_select_multiple':' asp_select_single'; ?>">
            <select class="<?php echo w_isset_def($item->asp_f_dropdown_search, 'asp_unchecked') == 'asp_checked' ? 'asp_gochosen' : 'asp_nochosen'; ?>"
                    data-placeholder="<?php echo w_isset_def($item->asp_f_dropdown_search_text, 'Select..'); ?>"
                <?php echo w_isset_def($item->asp_f_dropdown_multi, 'asp_unchecked') == 'asp_checked'?' multiple name="aspf['.$_field_name.'][]"':'name="aspf['.$_field_name.']"'; ?> >
                <?php $item->asp_f_dropdown_value = apply_filters("asp_cf_dropdown_values", $item->asp_f_dropdown_value, $item); ?>

                <?php foreach($item->asp_f_dropdown_value as $dropdown): ?>
                    <?php
                    // Special case because of the multi-select
                    if ( isset($style['_fo']) && isset($style['_fo']['aspf'][$_field_name]) ) {
                        $style['_fo']['aspf'][$_field_name] = is_array($style['_fo']['aspf'][$_field_name]) ?
                            $style['_fo']['aspf'][$_field_name] : array($style['_fo']['aspf'][$_field_name]);
                        $checked = in_array($dropdown[0], $style['_fo']['aspf'][$_field_name]) ? ' selected="selected"' : "";
                    } else {
                        $checked = strpos($dropdown[1], '**') > 0 ? ' selected':'';
                    }
                    ?>
                    <option value="<?php echo str_replace('"', '&#34;', $dropdown[0]); ?>"<?php echo $checked; ?>><?php echo str_replace('**', '', $dropdown[1]); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php break; ?>
    <?php case "checkboxes": ?>
        <?php
        $item->asp_f_checkboxes_value = apply_filters("asp_cf_checkbox_values", $item->asp_f_checkboxes_value, $item);
        $ccfi = 0;
        ?>
        <?php foreach($item->asp_f_checkboxes_value as $checkbox): ?>
            <?php
            $ccfi++;
            if ( isset($style['_fo']) )
                $checked = isset($style['_fo']['aspf'][$_field_name][$ccfi]) ? ' checked="checked"' : "";
            else
                $checked = strpos($checkbox[1], '**') > 0 ? ' checked="checked"':'';
            ?>
            <div class="asp_option">
                <div class="asp_option_inner">
                    <input type="checkbox" value="<?php echo str_replace('"', '&#34;', $checkbox[0]); ?>" id="aspf<?php echo $_in; ?>[<?php echo $_field_name; ?>][<?php echo $ccfi; ?>]"
                           name="aspf[<?php echo $_field_name; ?>][<?php echo $ccfi; ?>]" <?php echo $checked; ?>>
                    <label for="aspf<?php echo $_in; ?>[<?php echo $_field_name; ?>][<?php echo $ccfi; ?>]"></label>
                </div>
                <div class="asp_option_label"><?php echo str_replace('**', '', $checkbox[1]); ?></div>
            </div>
        <?php endforeach; ?>
    <?php break; ?>
    <?php
        // Hidden values are not passed through for security reasons. Just passing random value to have the $_POST[aspf] defined
        case "hidden": ?>
		<input type="hidden" value="x" id="aspf<?php echo $_in; ?>[<?php echo $item->asp_f_field; ?>]" name="aspf[<?php echo $_field_name; ?>]">
    <?php break; ?>
    <?php case "text": ?>
        <input type="text"
               value="<?php echo isset($style['_fo']) ? (isset($style['_fo']['aspf'][$_field_name]) ? stripslashes(htmlentities($style['_fo']['aspf'][$_field_name])) : $item->asp_f_text_value) : str_replace('"', '&#34;', $item->asp_f_text_value); ?>"
               id="aspf<?php echo $_in; ?>[<?php echo $item->asp_f_field; ?>]" name="aspf[<?php echo $_field_name; ?>]">
    <?php break; ?>
    <?php case "datepicker":
              if ( isset($style['_fo']) && isset($style['_fo']['aspf'][$_field_name."_real"]) ) {
                  $date_val = $style['_fo']['aspf'][$_field_name."_real"];
              } else {
                  switch ($item->asp_f_datepicker_defval) {
                      case "current":
                          $date_val = "";
                          break;
                      case "relative":
                          $date_val = $item->asp_f_datepicker_from_months . "m " . $item->asp_f_datepicker_from_days . "d";
                          break;
                      default:
                          $date_val = $item->asp_f_datepicker_value;
                          break;
                  }
              }
        ?>
        <textarea class="asp_datepicker_format" style="display:none !important;"><?php echo $item->asp_f_datepicker_format; ?></textarea>
        <input type="text" class="asp_datepicker_field" value="<?php echo $date_val; ?>" id="aspf<?php echo $_in; ?><?php echo $_field_name; ?>_real" name="aspf[<?php echo $_field_name; ?>_real]">
        <input type="hidden" class="asp_datepicker_hidden" value="" id="aspf<?php echo $_in; ?>[<?php echo $_field_name; ?>]" name="aspf[<?php echo $_field_name; ?>]">
    <?php break; ?>
    <?php case "slider": ?>
        <?php
        if ( isset($style['_fo']) && isset($style['_fo']['aspf'][$_field_name]) )
            $_s_value = ASP_Helpers::force_numeric($style['_fo']['aspf'][$_field_name]);
        else
            $_s_value = $item->asp_f_slider_default;
        ?>
        <div id="slider-handles-<?php echo $_in; ?>"></div>
        <div class="asp_noui_lu">

            <span class="asp_noui_l_pre"><?php echo $item->asp_f_slider_prefix; ?></span>
            <span class="slider-handles-low" id="slider-handles-low-<?php echo $_in; ?>"></span>
            <span class="asp_noui_l_suff"><?php echo $item->asp_f_slider_suffix; ?></span>

            <div class="clear"></div>
        </div>
        <input type="hidden" id="slider-values-low-<?php echo $_in; ?>" name="aspf[<?php echo $_field_name; ?>]" value="<?php echo $_s_value; ?>">
        <?php ob_start(); ?>
        {
            "node": "#slider-handles-<?php echo $_in; ?>",
            "main": {
                "start": [ <?php echo $_s_value; ?> ],
                "step": <?php echo $item->asp_f_slider_step; ?>,
                "range": {
                    "min": [  <?php echo $item->asp_f_slider_from; ?> ],
                    "max": [  <?php echo $item->asp_f_slider_to; ?> ]
                }
            },
            "links": [
                {
                    "handle": "lower",
                    "target": "#slider-handles-low-<?php echo $_in; ?>",
                    "wNumb": {
                        "decimals": <?php echo w_isset_def($item->asp_f_slider_decimals, 0); ?>,
                        "thousand": "<?php echo isset($item->asp_f_slider_t_separator) ? $item->asp_f_slider_t_separator : " "; ?>"
                    }
                },
                {
                    "handle": "lower",
                    "target": "#slider-values-low-<?php echo $_in; ?>",
                    "wNumb": {
                        "decimals": <?php echo w_isset_def($item->asp_f_slider_decimals, 0); ?>,
                        "thousand": "<?php echo isset($item->asp_f_slider_t_separator) ? $item->asp_f_slider_t_separator : " "; ?>"
                    }
                }
            ]
        }
        <?php $_asp_noui_out = ob_get_clean(); ?>
        <div id="noui-slider-json<?php echo $_in; ?>" class="noui-slider-json<?php echo $id; ?>" data-aspnoui="<?php echo base64_encode($_asp_noui_out); ?>" style="display: none !important;"></div>

    <?php break; ?>
    <?php case "range": ?>
    <?php
    if ( isset($style['_fo']) && isset($style['_fo']['aspf'][$_field_name]['lower']) ) {
        $_s_value_low = ASP_Helpers::force_numeric($style['_fo']['aspf'][$_field_name]['lower']);
        $_s_value_up = ASP_Helpers::force_numeric($style['_fo']['aspf'][$_field_name]['upper']);
    } else {
        $_s_value_low = $item->asp_f_range_default1;
        $_s_value_up = $item->asp_f_range_default2;
    }
    ?>
    <div id="range-handles-<?php echo $_in; ?>"></div>
    <div class="asp_noui_lu">

        <span class="asp_noui_l_pre"><?php echo $item->asp_f_range_prefix; ?></span>
        <span class="slider-handles-low" id="slider-handles-low-<?php echo $_in; ?>"></span>
        <span class="asp_noui_l_suff"><?php echo $item->asp_f_range_suffix; ?></span>

        <span class="asp_noui_u_suff"><?php echo $item->asp_f_range_suffix; ?></span>
        <span class="slider-handles-up" id="slider-handles-up-<?php echo $_in; ?>"></span>
        <span class="asp_noui_u_pre"><?php echo $item->asp_f_range_prefix; ?></span>

        <div class="clear"></div>
    </div>
    <input type="hidden" id="slider-values-low-<?php echo $_in; ?>" name="aspf[<?php echo $_field_name; ?>][lower]" value="<?php echo $_s_value_low; ?>">
    <input type="hidden" id="slider-values-up-<?php echo $_in; ?>" name="aspf[<?php echo $_field_name; ?>][upper]" value="<?php echo $_s_value_up; ?>">
    <?php ob_start(); ?>
    {
        "node": "#range-handles-<?php echo $_in; ?>",
        "main": {
            "start": [ <?php echo $_s_value_low; ?>, <?php echo $_s_value_up; ?> ],
            "step": <?php echo $item->asp_f_range_step; ?>,
            "range": {
                "min": [  <?php echo $item->asp_f_range_from; ?> ],
                "max": [  <?php echo $item->asp_f_range_to; ?> ]
            }
        },
        "links": [
            {
                "handle": "lower",
                "target": "#slider-handles-low-<?php echo $_in; ?>",
                "wNumb": {
                    "decimals": <?php echo w_isset_def($item->asp_f_range_decimals, 0); ?>,
                    "thousand": "<?php echo isset($item->asp_f_range_t_separator) ? $item->asp_f_range_t_separator : " "; ?>"
                }
            },
            {
                "handle": "upper",
                "target": "#slider-handles-up-<?php echo $_in; ?>",
                "wNumb": {
                    "decimals": <?php echo w_isset_def($item->asp_f_range_decimals, 0); ?>,
                    "thousand": "<?php echo isset($item->asp_f_range_t_separator) ? $item->asp_f_range_t_separator : " "; ?>"
                }
            },
            {
                "handle": "lower",
                "target": "#slider-values-low-<?php echo $_in; ?>",
                "wNumb": {
                    "decimals": <?php echo w_isset_def($item->asp_f_range_decimals, 0); ?>,
                    "thousand": "<?php echo isset($item->asp_f_range_t_separator) ? $item->asp_f_range_t_separator : " "; ?>"
                }
            },
            {
                "handle": "upper",
                "target": "#slider-values-up-<?php echo $_in; ?>",
                "wNumb": {
                    "decimals": <?php echo w_isset_def($item->asp_f_range_decimals, 0); ?>,
                    "thousand": "<?php echo isset($item->asp_f_range_t_separator) ? $item->asp_f_range_t_separator : " "; ?>"
                }
            }
        ]
    }
    <?php $_asp_noui_out = ob_get_clean(); ?>
    <div id="noui-slider-json<?php echo $_in; ?>" class="noui-slider-json<?php echo $id; ?>" data-aspnoui="<?php echo base64_encode($_asp_noui_out); ?>" style="display: none !important;"></div>
    <?php break; ?>
<?php } //endswitch ?>

<?php if ($item->asp_f_type != 'hidden'): ?>
</fieldset>
<?php endif; ?>

<?php endforeach; ?>