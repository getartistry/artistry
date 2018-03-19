<?php
if (!defined('ABSPATH')) {
    exit;
}?>
<div class='wrap' id='wrap_table' style="padding:10px;position:relative">
<?php
    eh_spg_list_stripe_table();
?>
</div>
<?php
function eh_spg_list_stripe_table()
{

    $obj= new Eh_Stripe_Datatables();
    $obj->input();
    $obj->prepare_items();
    $obj->search_box('search', 'search_id');
    ?>
    <label>Table Row</label>
    <input id='display_count_stripe' style="width:132px" type='number' value="<?php $count=get_option('eh_stripe_table_row');if($count){echo $count;}?>" placeholder="<?php _e( 'Number of Rows','eh-stripe-gateway' ); ?>">
    <button id='save_dislay_count_stripe'class='button button-primary'><?php _e('Save', 'eh-stripe-gateway'); ?></button>
    <form id="orders-filter" method="get">
        <input type="hidden" name="action" value="all" />
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
        <?php $obj->display(); ?>
    </form>
    <?php
}