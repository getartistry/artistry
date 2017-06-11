<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 *  Widget template used in sidebar
 *
 * @version	1.0
 * @since 1.4
 * @package	Wsi
 * @author Timersys
 */
?>

<div class="service-filter-content wsi_sidebar_widget" id="<?php echo $id;?>" data-locker="<?php echo $locker;?>">
  <div class="service-filters wsi-sidebar ">
	<?php

	foreach ( $providers as $p => $p_name ):
		if( $options['enable_'.$p] ) :
			?><div id="<?php echo $p;?>-provider" data-li-origin="<?php echo $p;?>" class="divprovider">
	             <a title="<?php echo $p_name;?>" href="#-service-<?php echo $p;?>" class="" data-provider="<?php echo $p;?>"><i class="wsiicon-<?php echo $p;?>"></i></a>
	        </div><?php
		endif;
	endforeach;
	?>
  </div>
   <div class="wsi_success small"><?php echo sprintf( __('Thanks for inviting your %s friends. Please try other network if you wish.', 'wsi'),'<span id="wsi_provider"></span>');?></div>
</div>
