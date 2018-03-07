<?php
/**
 * Bulk edit page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $iconic_woothumbs_class;

echo '<div class="wrap">';

	echo '<h2 style="margin-bottom: 20px;">'.__('Bulk Edit Variation Images', 'iconic-woothumbs').'</h2>';

	echo '<p>'.sprintf( __( 'From this page you can edit all variation images from one place. Simply enter a comma separated list (no spaces) of image IDs for each variation and click Update. You can find your image IDs in the <a href="%s" target="_blank">Media Library</a>.', 'iconic-woothumbs'), admin_url('upload.php') ).'</p>';

	include('bulk-edit-parent-filter.php');

	include('bulk-edit-ppp.php');

	$paged = ( isset($_GET['paged']) ) ? $_GET['paged'] : 1;

	$args = array(
		'post_type' => 'product_variation',
		'posts_per_page' => 15,
		'paged' => $paged
	);

	if( !empty( $_GET['parent'] ) )
	    $args['post_parent'] = (int)$_GET['parent'];

    if( !empty( $_GET['ppp'] ) )
	    $args['posts_per_page'] = (int)$_GET['ppp'];

	$variations = new WP_Query( $args );

	if ( $variations->have_posts() ) {

    	include('bulk-edit-pagination.php');

		echo '<table class="widefat wp-list-table">';

			echo '<thead>';
			    echo '<tr>';
			        echo '<th>Variation Name</th>';
			        echo '<th>Parent Product</th>';
			        echo '<th>Images</th>';
			        echo '<th>&nbsp;</th>';
			    echo '</tr>';
			echo '</thead>';

			echo '<tbody>';

				$i = 0; while ( $variations->have_posts() ) {
					$variations->the_post();

					$variation = new WC_Product_Variation( get_the_id() );
					$variationAtts = $variation->get_variation_attributes();

					$images = get_post_meta(get_the_id(), 'variation_image_gallery', true);
					$parents = get_post_ancestors(get_the_id());
					$parentId = $parents[0];
					$parentEditUrl = admin_url('post.php?post='.$parentId.'&action=edit');
					$parentName = get_the_title($parentId);

					// List Variation Attributes

					$atts = "";

					if(is_array($variationAtts) && !empty($variationAtts))
					{

						$atts = '<ul class="variationAtts">';

						foreach($variationAtts as $attName => $variationAtt)
						{
							if($variationAtt != "")
							{
								$atts .= '<li><em>'.$attName.'</em>: '.$variationAtt.'</li>';
							}
						}

						$atts .= '</ul>';

					}

					// Echo Variation row

					echo '<tr class="'.($i % 2 == 0 ? 'alternate' : '').'">';
						echo '<td>'.get_the_title().$atts.'</td>';
						echo '<td><a href="'.$parentEditUrl.'" target="_blank">'.$parentName.' ('.$parentId.')</a></td>';
						echo '<td><input id="images-'.$i.'" value="'.$images.'"></td>';
						echo '<td><input class="button-primary saveVariationImages" type="submit" value="'.__('Update', 'iconic-woothumbs').'" data-updating="'.__('Updating...', 'iconic-woothumbs').'" data-updated="'.__('Updated', 'iconic-woothumbs').'" data-update="'.__('Update', 'iconic-woothumbs').'" data-varid="'.get_the_id().'" data-input="#images-'.$i.'" data-error="'.__('Error', 'iconic-woothumbs').'"> <p class="updateMsg"></p></td>';
					echo '</tr>';

					$i++;
				}

			echo '</tbody>';

		echo '</table>';

        include('bulk-edit-pagination.php');

	} else {

    	echo sprintf('<strong>%s</strong>', __('Sorry, no variations were found.', 'iconic-woothumbs'));

	}

	wp_reset_postdata();

echo '</div>';