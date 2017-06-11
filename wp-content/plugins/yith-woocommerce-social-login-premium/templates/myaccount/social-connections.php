<?php
/**
 * Show Social Connection in My Account Page
 *
 * @package YITH WooCommerce Social Login
 * @since   1.0.0
 * @author  Yithemes
 */

$s = '';
?>
<h2><?php echo apply_filters( 'ywsl_my_account_social_connection_title', __( 'Social Connections', 'yith-woocommerce-social-login' ) ); ?></h2>
<?php if( !empty($user_connections) ):
    $s = __( 'also','yith-woocommerce-social-login');
    ?>
<table class="shop_table shop_table_responsive my_account_social">

    <tbody>
        <?php foreach( $user_connections as $provider=>$social): ?>
        <tr class="order">
            <td class="sl-username" data-title="<?php _e('Username', 'yith-woocommerce-social-login')  ?>"><?php echo $social['button'] ?>
                <?php if( $social['profileURL'] ): ?>
                    <a href="<?php echo  $social['profileURL'] ?>" target="_blank">
                <?php endif ?>
                    <?php echo $social['displayName'] ?>
                <?php if( $social['profileURL'] ): ?>
                   </a>
                <?php endif ?>
            </td>
            <td class="sl-unlink" data-title="<?php _e('Unlink', 'yith-woocommerce-social-login')  ?>"><?php echo $social['unlink_button'] ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>

</table>
<?php endif;
if( !empty($user_unlinked_social) ):
    ?>
    <p><?php printf( __('You can %s login with:','yith-woocommerce-social-login'), $s) ?></p>
    <?php foreach( $user_unlinked_social as $key => $value): ?>
        <a class="ywsl-social ywsl-<?php echo $key  ?>" href="<?php echo  esc_url(add_query_arg( array('ywsl_social'=>$key, 'redirect'=> urlencode(ywsl_curPageURL())),site_url('wp-login.php'))) ?>">
            <img src="<?php echo apply_filters('ywsl_custom_icon_'.$key, YITH_YWSL_ASSETS_URL.'/images/'.$key.'.png', $key ) ?>" alt="<?php echo $value['label']  ?>"/>
        </a>
    <?php endforeach ?>
<?php endif ?>