<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

if (!function_exists('wtfdivi011_media_queries')) { 
	function wtfdivi011_media_queries() {
		return array(
			'all' => array('name'=>'All screen widths', 'css'=>''), 
			'standard-all' => array('name'=>'Desktops (981px upwards)', 'css'=>'@media only screen and ( min-width: 981px )'), 
			'tablets-all' => array('name'=>'Tablets (768-980px)', 'css'=>'@media only screen and ( min-width: 768px ) and ( max-width: 980px )'), 
			'mobiles-all' => array('name'=>'Mobiles (0-767px)', 'css'=>'@media only screen and ( max-width: 767px )'), 
			'mobiles-and-tablets' => array('name'=>'Mobiles and tablets (0-980px)', 'css'=>'@media only screen and ( max-width: 980px )'), 
			
			'large-screens' => array('name'=>'Large screens (1405px upwards)', 'css'=>'@media only screen and ( min-width: 1405px )'), 
			'standard-screens' => array('name'=>'Standard Desktops (1100-1405px)', 'css'=>'@media only screen and ( min-width: 1100px ) and ( max-width: 1405px)'), 
			'tablets-landscape' => array('name'=>'Laptops (981-1100px)', 'css'=>'@media only screen and ( min-width: 981px ) and ( max-width: 1100px )'), 
			'tablets-portrait' => array('name'=>'Standard Tablets (768-980px)', 'css'=>'@media only screen and ( min-width: 768px ) and ( max-width: 980px )'), 
			'smartphones-landscape' => array('name'=>'Smartphones, landscape (480-768px)', 'css'=>'@media only screen and ( min-width: 480px ) and ( max-width: 767px )'), 
			'smartphones-portrait' => array('name'=>'Smartphones, portrait (0-479px)', 'css'=>'@media only screen and ( max-width: 479px )')			
		);
	}
}
?>