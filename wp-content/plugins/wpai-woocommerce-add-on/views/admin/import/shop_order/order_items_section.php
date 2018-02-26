<div class="wpallimport-collapsed closed">
	<div class="wpallimport-content-section">
		<div class="wpallimport-collapsed-header">
			<h3><?php _e('Order Items','wpai_woocommerce_addon_plugin');?></h3>	
		</div>
		<div class="wpallimport-collapsed-content" style="padding:0;">
			<div class="wpallimport-collapsed-content-inner">
				<table class="form-table" style="max-width:none;">
					<tr>
						<td colspan="3">
							<div class="postbox woocommerce-order-data" id="woocommerce-product-data">								
								<div class="clear"></div>
								<div style="min-height: 295px;">
									<div class="panel-wrap product_data">										

										<ul class="product_data_tabs wc-tabs shop_order_tabs">

											<li class="woocommerce-order-products-data active">
												<a href="javascript:void(0);" rel="order_products"><?php _e('Products','wpai_woocommerce_addon_plugin');?></a>
											</li>

											<li class="woocommerce-order-fees-data">
												<a href="javascript:void(0);" rel="order_fees"><?php _e('Fees', 'wpai_woocommerce_addon_plugin');?></a>
											</li>

											<li class="woocommerce-order-coupons-data">
												<a href="javascript:void(0);" rel="order_coupons"><?php _e('Coupons', 'wpai_woocommerce_addon_plugin');?></a>
											</li>											

											<li class="woocommerce-order-shipping-data">
												<a href="javascript:void(0);" rel="order_shipping"><?php _e('Shipping', 'wpai_woocommerce_addon_plugin');?></a>
											</li>

											<li class="woocommerce-order-taxes-data">
												<a href="javascript:void(0);" rel="order_taxes"><?php _e('Taxes', 'wpai_woocommerce_addon_plugin');?></a>
											</li>

											<li class="woocommerce-order-refunds-data">
												<a href="javascript:void(0);" rel="order_refunds"><?php _e('Refunds', 'wpai_woocommerce_addon_plugin');?></a>
											</li>											

											<li class="woocommerce-order-total-data">
												<a href="javascript:void(0);" rel="order_total"><?php _e('Total', 'wpai_woocommerce_addon_plugin');?></a>
											</li>											

										</ul>

										<!-- PRODUCTS -->

										<?php include( '_tabs/_order_item_products.php' ); ?>		

										<!-- FEES -->

										<?php include( '_tabs/_order_item_fees.php' ); ?>													
										
										<!-- COUPONS -->

										<?php include( '_tabs/_order_item_coupons.php' ); ?>													

										<!-- SHIPPING -->

										<?php include( '_tabs/_order_item_shipping.php' ); ?>													

										<!-- TAXES -->

										<?php include( '_tabs/_order_item_taxes.php' ); ?>													

										<!-- REFUNDS -->

										<?php include( '_tabs/_order_item_refunds.php' ); ?>	

										<!-- TOTAL -->
																															
										<?php include( '_tabs/_order_total.php' ); ?>	

									</div>
								</div>
							</div>

							<div class="clear"></div>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>