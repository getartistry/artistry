<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/* Options not set or deactivated, return - if one is set, other is definitely set too */
if ( empty($style["selected-date_filter_from"]) ) return;

$_dff = &$style["selected-date_filter_from"];
$_dft = &$style["selected-date_filter_to"];

if ( $_dff['state'] == "disabled" && $_dft['state'] == "disabled" ) return;
$_dfrom_t = w_isset_def($style['date_filter_from_t'], "Content from");
$_dto_t = w_isset_def($style['date_filter_to_t'], "Content to");

if ( isset($style['_fo']) && isset($style['_fo']['post_date_from']) ) {
    $_dff_v = $style['_fo']['post_date_from'];
    $_dft_v = $style['_fo']['post_date_to'];
} else {
    if ( $_dff['state'] == "rel_date" ) {
        $_dff_v = "-" . $_dff["rel_date"][0] . "y -".$_dff["rel_date"][1]."m -".$_dff["rel_date"][2] . "d";
    } else {
        $_dff_v = $_dff['date'];
    }

    if ( $_dft['state'] == "rel_date" ) {
        $_dft_v = "-" . $_dft["rel_date"][0] . "y -".$_dft["rel_date"][1]."m -".$_dft["rel_date"][2] . "d";
    } else {
        $_dft_v = $_dft['date'];
    }
}


?>
<fieldset>
    <?php if ( $_dff['state'] != 'disabled' ): ?>
        <div>
        <?php if ( $_dfrom_t != "" ): ?>
        <legend><?php echo asp_icl_t( "Post date filter: Content from" . " ($real_id)", $_dfrom_t ); ?></legend>
        <?php endif; ?>
        <textarea class="asp_datepicker_format" style="display:none !important;"><?php echo $style["date_filter_from_format"]; ?></textarea>
        <input type="text" class="asp_datepicker" name="post_date_from_real" value="<?php echo $_dff_v; ?>">
        <input type="hidden" class="asp_datepicker_hidden" name="post_date_from" value="">
        </div>
    <?php endif; ?>

    <?php if ( $_dft['state'] != 'disabled' ): ?>
        <div>
        <?php if ( $_dto_t != "" ): ?>
        <legend style="margin-top: 10px;"><?php echo asp_icl_t( "Post date filter: Content to" . " ($real_id)", $_dto_t ); ?></legend>
        <?php endif; ?>
        <textarea class="asp_datepicker_format" style="display:none !important;"><?php echo $style["date_filter_to_format"]; ?></textarea>
        <input type="text" class="asp_datepicker" name="post_date_to_real" value="<?php echo $_dft_v; ?>">
        <input type="hidden" class="asp_datepicker_hidden" name="post_date_to" value="">
        </div>
    <?php endif; ?>
</fieldset>