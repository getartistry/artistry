<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Big widget template used in pages
 *
 * @version	1.1
 * @since 1.4
 * @package	Wordpress Social Invitations
 * @author Timersys
 */
?>


<div class="wsi_widget">
	<?php do_action('wsi/widget/before_content');?>
	<h2 class="wsi-title"><?php echo apply_filters('wsi/widget/title', $title);?></h2>

	<div class="service-filter-content" id="<?php echo $id;?>" data-locker="<?php echo $locker;?>" data-hook="<?php echo $hook;?>">
		<div class="service-filters ">
		<?php
			foreach ( $providers as $p => $p_name ):
				if( $options['enable_'.$p] ) :
					?><div id="<?php echo $p;?>-provider" data-li-origin="<?php echo $p;?>" class="divprovider"><span class="ready-label hidden">Ready</span>
					<a title="<?php echo $p_name;?>" href="#-service-<?php echo $p;?>" class="" data-provider="<?php echo $p;?>"><i class="wsiicon-<?php echo $p;?>"></i></a>
					</div><?php
				endif;
			endforeach;
		?>
	    </div>
		<div class="wsi_success"><?php echo sprintf( __('Thanks for inviting your %s friends. Please try other network if you wish.', 'wsi'),'<span id="wsi_provider"></span>');?></div>
	</div>
	<?php if( !empty($options['custom_css']) ){ ?>
		<style type="text/css">
			<?php echo $options['custom_css'];?>
		</style>
	<?php } ?>
	<?php do_action('wsi/widget/after_content');?>
</div>