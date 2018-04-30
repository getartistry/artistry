<?php
/**
 * Change default footer text, asking to review our plugin
 **/
function my_footer_text($default) {
    return 'If you like our <strong>WooCommerce Product Feed PRO</strong> plugin please leave us a <a href="https://wordpress.org/support/plugin/woo-product-feed-pro/reviews?rate=5#new-post" target="_blank" class="woo-product-feed-pro-ratingRequest">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Thanks in advance!';
}
add_filter('admin_footer_text', 'my_footer_text');

/**
 * Create notification object and get message and message type as WooCommerce is inactive
 * also set variable allowed on 0 to disable submit button on step 1 of configuration
 */
$notifications_obj = new WooSEA_Get_Admin_Notifications;
if (!in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        $notifications_box = $notifications_obj->get_admin_notifications ( "9", "false" );
} else {
        $notifications_box = $notifications_obj->get_admin_notifications ( '10', 'false' );
}

if (array_key_exists('project_hash', $_GET)){
	$project = WooSEA_Update_Project::get_project_data(sanitize_text_field($_GET['project_hash']));	
	$project_hash = $_GET['project_hash'];
	$step = $_GET['step'];
	if(isset($project['history_products'])){
		$project_history = $project['history_products'];
	} else {
		$project_history = array();
	}
	$projectname = ucfirst($project['projectname']);

	$labels = array();
	$data = array();

	foreach($project_history as $key => $value){
		array_push($labels, $key);
		array_push($data, $value);
	}
}

?>
<div class="wrap">
        <div class="woo-product-feed-pro-form-style-2">
   	<table class="woo-product-feed-pro-table"> 
 		<tbody class="woo-product-feed-pro-body">
                        <div class="woo-product-feed-pro-form-style-2-heading">Feed statistics</div>

                        <div class="<?php _e($notifications_box['message_type']); ?>">
                                <p><?php _e($notifications_box['message'], 'sample-text-domain' ); ?></p>
                        </div>
	
			<tr>
				<td align="center">
					<input type="hidden" id="project_hash" name="project_hash" value="<?php print "$project_hash";?>">
					<input type="hidden" id="step" name="step" value="<?php print "$step";?>">

					<span style="background: white; width: 99%; display:block;">
						<canvas id="myChart" width="150" height="75"></canvas>
					</span>

					<script type="text/javascript">
						var labels = <?php echo json_encode($labels); ?>;
						var data = <?php echo json_encode($data); ?>;
        					var projectname = <?php echo json_encode($projectname); ?>;
						var ctx = document.getElementById("myChart");

        					var myChart = new Chart(ctx, {
                					type: 'line',
                					data: {
                        					labels: labels,
                        					datasets: [{
                                					label: "Number of products in feed",
                                					data: data,
                                						backgroundColor: [
                                        						'rgba(255, 99, 132, 0.2)',
                                        						'rgba(54, 162, 235, 0.2)',
                                        						'rgba(255, 206, 86, 0.2)',
                                        						'rgba(75, 192, 192, 0.2)',
                                        						'rgba(153, 102, 255, 0.2)',
                                        						'rgba(255, 159, 64, 0.2)'
                                						],
                                						borderColor: [
                                        						'rgba(255,99,132,1)',
                                        						'rgba(54, 162, 235, 1)',
                                        						'rgba(255, 206, 86, 1)',
                                        						'rgba(75, 192, 192, 1)',
                                        						'rgba(153, 102, 255, 1)',
                                        						'rgba(255, 159, 64, 1)'
                                						],
                                					borderWidth: 1
                        					}]
                					}, 
							options: {
                						responsive: true,
                						title:{
                    							display:true,
                    							text:projectname,
                						},
                						tooltips: {
                    							mode: 'index',
                    							intersect: false,
                						},
                						hover: {
                    							mode: 'nearest',
                    							intersect: true
                						},
                						scales: {
                    							xAxes: [{
                        							display: true,
                        							scaleLabel: {
                            								display: true,
                            								labelString: 'Date / Time'
                        							}
                    							}],
                    							yAxes: [{
                        							display: true,
                        							scaleLabel: {
                            								display: true,
                            								labelString: 'Products'
                        							},
										ticks: {
											beginAtZero:true
										}
                    							}]
                						}
            						}
   
        					});
  					</script>
				</td>
			</tr>
		</tbody>
	</table>
	</div>
</div>
