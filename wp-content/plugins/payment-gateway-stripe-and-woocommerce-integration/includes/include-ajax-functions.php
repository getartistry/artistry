<?php
if (!defined('ABSPATH')) {
    exit;
}
function eh_stripe_analytics()
{
    $start=sanitize_text_field($_POST['start']);
    $end=sanitize_text_field($_POST['end']);
    $order_id=  eh_stripe_overview_get_order_ids();
    $temp_json=array();
    for($i=0,$j=0;$i<count($order_id);$i++)
    {
        $id_data      =  get_post_meta($order_id[$i],'_eh_stripe_payment_charge',true);
        if($id_data['captured']==='Captured')
        {
            $id_date      =  date('Y-m-d',strtotime($id_data['created']));
            if(strtotime($id_date)>=strtotime($start)&&strtotime($id_date)<=strtotime($end))
            {
                $temp_json[$j]['label']=$id_date;
                $temp_json[$j]['value']=  floatval($id_data['order_amount']);
                $j++;
            }
        }
    }
    $a[0]=array(
        'label' =>date('Y-m-d', strtotime('-1 day', strtotime($start))),
        'value' =>0
    );
    $c[0]=array(
        'label' =>date('Y-m-d', strtotime('+1 day', strtotime($end))),
        'value' =>0
    );
    $sum = array_reduce($temp_json, function ($a, $b) {
        isset($a[$b['label']]) ? $a[$b['label']]['value'] += $b['value'] : $a[$b['label']] = $b;  
        return $a;
    });
    $b=array_values($sum);
    if($b!=null)
    {
        $charge=  array_merge_recursive($a,$b,$c);
    }
    else 
    {
        $charge=  array_merge_recursive($a,$c);
    }
    die(json_encode(array_values($charge)));
}
function eh_order_status_update_callback()
{
    check_ajax_referer('ajax-eh-spg-nonce', '_ajax_eh_spg_nonce');
    $order_id =  ($_POST['order_id'] != '') ? explode(',', $_POST['order_id']) : '';
    $order_action= $_POST['order_action'];
    if(count($order_id)!=1)
    {
        for($i=0;$i<count($order_id);$i++)
        {
            $wc_order=  wc_get_order($order_id[$i]);
            switch ($order_action)
            {
                case 'processing':
                    $wc_order->update_status('processing');
                    break;
                case 'completed' :
                    $wc_order->update_status('completed');
                    break;
                case 'on-hold' :
                    $wc_order->update_status('on-hold');
                    break;
            }
        }
    }
    else
    {
        $wc_order=  wc_get_order($order_id[0]);
        switch ($order_action)
        {
            case 'processing':
                $wc_order->update_status('processing');
                break;
            case 'completed' :
                $wc_order->update_status('completed');
                break;
        }
    }    
    die('sucesss');
}
function eh_spg_list_order_all_callback()
{
    check_ajax_referer('ajax-eh-spg-nonce', '_ajax_eh_spg_nonce');
    $page = sanitize_text_field($_POST['paged']);
    $obj                     = new Eh_Stripe_Order_Datatables();
    $obj->input();
    $obj->ajax_response($page);
 //  wp_enqueue_script('eh-custom');
}
function eh_spg_list_stripe_all_callback()
{
    check_ajax_referer('ajax-eh-spg-nonce', '_ajax_eh_spg_nonce');
    $page = sanitize_text_field($_POST['paged']);
    $obj                     = new Eh_Stripe_Datatables();
    $obj->input();
    $obj->ajax_response($page);
//    wp_enqueue_script('eh-custom');
}
function eh_order_display_count_callback()
{
    $value=  sanitize_text_field($_POST['row_count']);
    update_option('eh_order_table_row', $value);
    die('success');
}
function eh_stripe_display_count_callback()
{
    $value=  sanitize_text_field($_POST['row_count']);
    update_option('eh_stripe_table_row', $value);
    die('success');
}
add_action('wp_ajax_eh_spg_analytics', 'eh_stripe_analytics');
add_action('wp_ajax_eh_order_display_count', 'eh_order_display_count_callback');
add_action('wp_ajax_eh_stripe_display_count', 'eh_stripe_display_count_callback');
add_action('wp_ajax_eh_order_status_update', 'eh_order_status_update_callback');
add_action('wp_ajax_eh_spg_get_all_order', 'eh_spg_list_order_all_callback');
add_action('wp_ajax_eh_spg_get_all_stripe', 'eh_spg_list_stripe_all_callback');

