<?php
/**
 * Options Framework.
 *
 * @author 		WaspThemes
 * @category 	Core
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/* ---------------------------------------------------- */
/* Slider Option                                        */
/* ---------------------------------------------------- */
function yp_get_slider_markup($cssName, $name, $default = 'inherit', $decimals, $pxv, $pcv, $emv,$note = null){
	
	$tooltip = '';
	if($note != null && $note != false){
		$tooltip = " data-toggle='tooltip' data-placement='left' title='".$note."'";
	}
    
    if ($default !== false) {
        $defaultLink = "<a class='yp-btn-action yp-none-btn' data-default='".$default."'>" . $default . "</a>";
    } else {
        $defaultLink = '';
    }
    
    return "<div id='" . $cssName . "-group' class='yp-option-group yp-slider-option' data-css='" . $cssName . "' data-decimals='" . $decimals . "' data-pxv='" . $pxv . "' data-pcv='" . $pcv . "' data-emv='" . $emv . "'>
                
        <div class='yp-part'>
        
            <label class='yp-option-label'><a target='_blank' href='http://waspthemes.com/yellow-pencil/buy' class='yp-lite yp-pro-label'>GO PRO</a><span".$tooltip.">" . $name . ":</span><i class='phone-icon'></i> " . $defaultLink . " <a class='yp-btn-action yp-disable-btn'></a></label>
            <div id='yp-" . $cssName . "'></div>
                
            <div class='yp-after'>
                <input type='text' id='" . $cssName . "-value' class='yp-after-css yp-after-css-val' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' />
                <input type='text' id='" . $cssName . "-after' class='yp-after-css yp-after-prefix' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' /><small>(px vh % em)</small>
            </div>
                
            </div>
    </div>";
    
}



/* ---------------------------------------------------- */
/* Select Option                                        */
/* ---------------------------------------------------- */
function yp_get_select_markup($cssName, $name, $values, $default = 'none',$note = null){
	
	$tooltip = '';
	if($note != null && $note != false){
		$tooltip = " data-toggle='tooltip' data-placement='left' title='".$note."'";
	}
    
    if ($default != false) {
        $defaultLink = "<a class='yp-btn-action yp-none-btn' data-default='".$default."'>" . $default . "</a>";
    } else {
        $defaultLink = '';
    }

    if ($cssName == 'animation-name' && $default != false) {
        $defaultLink = "<a class='yp-visual-editor-link'>Animator</a><a class='yp-btn-action yp-none-btn' data-default='".$default."'>" . $default . "</a>";
    }
    
    $return = "<div id='" . $cssName . "-group' class='yp-option-group yp-select-option' data-css='" . $cssName . "'>
                
                <div class='yp-part'>
                    <label class='yp-option-label'><a target='_blank' href='http://waspthemes.com/yellow-pencil/buy' class='yp-lite yp-pro-label'>GO PRO</a><span".$tooltip.">" . $name . ":</span><i class='phone-icon'></i> " . $defaultLink . " <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <select id='yp-" . $cssName . "-data' class='yp-select-data'>";
    
    foreach ($values as $key => $value) {

    	$id = explode(",",$key);
    	if(isset($id[0])){
    		$id = str_replace(" ","", $id[0]);
    		$id = str_replace("'","", $id);
    		$id = str_replace('"',"", $id);
    		$id = strtolower($id);
    	}
    	
        $return .= '<option value="' . $key . '" data-text="' . $id . '" data-content="' . $value . '">' . $value . '</option>';
        
    }
    
    $return .= "</select>

    			<input id='yp-" . $cssName . "' type='text' class='input-autocomplete' value='' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' />
                <div id='yp-autocomplete-place-" . $cssName . "' class='autocomplete-div'></div>
                
            </div>
            </div>";
    
    return $return;
    
}



/* ---------------------------------------------------- */
/* Radio Option                                         */
/* ---------------------------------------------------- */
function yp_get_radio_markup($cssName, $name, $values, $default = 'none',$note = null){
	
	$tooltip = '';
	if($note != null && $note != false){
		$tooltip = " data-toggle='tooltip' data-placement='left' title='".$note."'";
	}
    
    if ($default != false) {
        $defaultLink = "<a class='yp-btn-action yp-none-btn' data-default='".$default."'>" . $default . "</a>";
    } else {
        $defaultLink = '';
    }

    $notice_last = null;
    
    $return = "<div id='" . $cssName . "-group' class='yp-option-group yp-radio-option' data-css='" . $cssName . "'>
                
                <div class='yp-part'>
                    <label class='yp-option-label'><a target='_blank' href='http://waspthemes.com/yellow-pencil/buy' class='yp-lite yp-pro-label'>GO PRO</a><span".$tooltip.">" . $name . ":</span><i class='phone-icon'></i> " . $defaultLink . " <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <div class='yp-radio-grid-" . count($values) . " yp-radio-content' id='yp-" . $cssName . "'>
                    ";
    
    foreach ($values as $key => $value) {
        
        $return .= '<div class="yp-radio"><input type="radio" name="' . $cssName . '" value="' . $key . '" id="s-'.$cssName.'-' . $key . '"><label id="'.$cssName.'-' . $key . '" data-for="s-'.$cssName.'-' . $key . '" class="yp-update">' . $value . '</label></div>';
        
    }
    
    $return .= "
                
                <div class='yp-clear'></div>
                
                </div>
                
                </div>
            ".$notice_last."
            </div>";
    
    return $return;
    
}



/* ---------------------------------------------------- */
/* Colorpicker Option                                    */
/* ---------------------------------------------------- */
function yp_get_color_markup($cssName, $name,$note = null){

	// Flat colors
	$flatArray = array(
		"1abc9c",
		"2ecc71",
		"3498db",
		"9b59b6",
		"34495e",
		"16a085",
		"27ae60",
		"2980b9",
		"8e44ad",
		"2c3e50",
		"f1c40f",
		"e67e22",
		"e74c3c",
		"ecf0f1",
		"95a5a6",
		"f39c12",
		"d35400",
		"c0392b",
		"bdc3c7",
		"7f8c8d"
	);

	// Meterial colors
	$meterialArray = array(
		"F44336",
		"E91E63",
		"9C27B0",
		"673AB7",
		"3F51B5",
		"2196F3",
		"03A9F4",
		"00BCD4",
		"009688",
		"4CAF50",
		"8BC34A",
		"CDDC39",
		"FFEB3B",
		"FFC107",
		"FF9800",
		"FF5722",
		"795548",
		"9E9E9E",
		"607D8B",
		"BEC2C3"
	);

	// nice color array
	$niceArray = array(
		"69D2E7",
		"A7DBD8",
		"E0E4CC",
		"F38630",
		"FA6900",
		"ECD078",
		"D95B43",
		"C02942",
		"542437",
		"53777A",
		"CFF09E",
		"A8DBA8",
		"79BD9A",
		"3B8686",
		"0B486B",
		"556270",
		"4ECDC4",
		"C7F464",
		"FF6B6B",
		"C44D58",
		"490A3D",
		"BD1550",
		"E97F02",
		"F8CA00",
		"8A9B0F",
		"594F4F",
		"547980",
		"45ADA8",
		"9DE0AD",
		"E5FCC2",
		"00A0B0",
		"6A4A3C",
		"CC333F",
		"EB6841",
		"EDC951"
	);
	
	$tooltip = '';
	if($note != null && $note != false){
		$tooltip = " data-toggle='tooltip' data-placement='left' title='".$note."'";
	}
    
    $return = "<div id='" . $cssName . "-group' class='yp-option-group yp-color-option' data-css='" . $cssName . "'>
                
                <div class='yp-part'>
                    <label class='yp-option-label'><a target='_blank' href='http://waspthemes.com/yellow-pencil/buy' class='yp-lite yp-pro-label'>GO PRO</a><span".$tooltip.">" . $name . ":</span><i class='phone-icon'></i> <a class='yp-btn-action yp-none-btn'>transparent</a> <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <div class='yp-color-input-box'>
                    <input id='yp-" . $cssName . "' type='text' maxlength='22' size='22' class='wqcolorpicker' value='' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' />
                	<span class='yp-color-background'><span class='wqminicolors-swatch-color'></span></span>
                	<span class='color-picker-icon yp-element-picker'></span>
                	</div>

                <div class='yp-after'>
					<a class='yp-flat-colors'>Flat</a> <a class='yp-meterial-colors'>Material</a> <a class='yp-nice-colors'>Trend</a>

					<div class='yp-clear'></div>
					
					<div class='yp_flat_colors_area'>";

					foreach ($flatArray as $color) {
						$return .= "<div class='yp-flat-c' data-color='#".$color."' style='background:#".$color."'></div>";
					}

					$return .= "</div>";
					
					$return .= "<div class='yp_meterial_colors_area'>";
						
						foreach ($meterialArray as $color) {
							$return .= "<div class='yp-meterial-c' data-color='#".$color."' style='background:#".$color."'></div>";
						}

					$return .= "</div>";
					
					$return .= "<div class='yp_nice_colors_area'>";

						foreach ($niceArray as $color) {
							$return .= "<div class='yp-nice-c' data-color='#".$color."' style='background:#".$color."'></div>";
						}
						
					$return .= "</div>
				</div>
				
            </div>
            </div>";
    
    return $return;
    
}




/* ---------------------------------------------------- */
/* Input Option   		                                */
/* ---------------------------------------------------- */
function yp_get_input_markup($cssName, $name, $none = null, $note = null){
	
	$tooltip = '';
	if($note != null && $note != false){
		$tooltip = " data-toggle='tooltip' data-placement='left' title='".$note."'";
	}
    
    $return = "<div id='" . $cssName . "-group' class='yp-option-group yp-input-option' data-css='" . $cssName . "'>
                
                <div class='yp-part'>
                    <label class='yp-option-label'><a target='_blank' href='http://waspthemes.com/yellow-pencil/buy' class='yp-lite yp-pro-label'>GO PRO</a><span".$tooltip.">" . $name . ":</span><i class='phone-icon'></i> <a class='yp-btn-action yp-none-btn' data-default='".$none."'>".$none."</a> <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <input autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' id='yp-" . $cssName . "' type='text' class='yp-input' value='' />";

    if($cssName == 'list-style-image'){
    	$return .= "<a class='yp-gallery-btn yp-upload-btn'>Upload Image</a><div style='clear:both;'></div>";
    }
	
	if($cssName == "background-image"){
		$images = glob(WT_PLUGIN_DIR.'/assets/*.{png,jpg,jpeg}', GLOB_BRACE);
		$return .= "<div style='clear:both;'></div><a class='yp-gallery-btn yp-upload-btn'>Upload Image</a><a class='yp-gradient-btn'>Gradients</a><a class='yp-bg-img-btn'>Patterns</a><div style='clear:both;'></div>";

		// Background patterns section starts
		$return .= "<div class='yp_background_assets'>";

		// A list of black patterns, we dont like to show black patterns to up.
		$blackPatterns = array(
			"tex2res4.png",
			"darth_stripe.png",
			"dark_wood.png",
			"concrete_wall.png",
			"black_linen_v2.png",
			"blackmamba.png",
			"wood_1.jpg.png",
			"navy_blue.png",
			"vertical_cloth.png",
			"black-Linen.png",
			"broken_noise.png",
			"black_paper.png",
			"dark_leather.png",
			"cartographer.png",
			"pinstriped_suit.png",
			"irongrip.png",
			"pool_table.png",
			"asfalt.png",
			"blackorchid.png",
			"burried.png",
			"inflicted.png",
			"green-fibers.png",
			"crissXcross.png",
			"dirty_old_shirt.png",
			"binding_dark.png",
			"txture.png",
			"gray_sand.png",
			"denim.png",
			"noisy_net.png",
			"type.png",
			"stressed_linen.png",
			"black_denim.png",
			"dark_brick_wall.png",
			"darkdenim3.png",
			"padded.png",
			"twinkle_twinkle.png",
			"dark_mosaic.png",
			"random_grey_variations.png",
			"flowers.png",
			"classy_fabric.png",
			"dvsup.png",
			"assault.png",
			"robots.png",
			"office.png",
			"use_your_illusion.png",
			"argyle.png",
			"px_by_Gre3g.png",
			"woven.png",
			"carbon_fibre_big.png",
			"dark_stripes.png",
			"tactile_noise.png",
			"bo_play_pattern.png",
			"diagmonds.png",
			"dark_geometric.png",
			"squares.png",
			"escheresque_ste.png",
			"starring.png",
			"hixs_pattern_evolution.png",
			"triangles.png",
			"soft_kill.png",
			"real_cf.png",
			"black_mamba.png",
			"black_thread.png",
			"carbon_fibre_v2.png",
			"pw_maze_black.png",
			"always_grey.png",
			"black_scales.png",
			"black_lozenge.png",
			"nami.png",
			"fake_brick.png",
			"hexabump.png",
			"dark_circles.png",
			"black_twill.png",
			"dark_matter.png",
			"moulin.png",
			"dark_exa.png",
			"gun_metal.png",
			"rubber_grip.png",
			"zigzag.png",
			"carbon_fibre.png",
			"subtle_carbon.png",
			"dark_fish_skin.png",
			"tasky_pattern.png",
			"micro_carbon.png",
			"crossed_stripes.png",
			"simple_dashed.png",
			"slash_it.png",
			"dark_dotted.png",
			"dark_dotted2.png",
			"outlets.png",
			"wild_oliva.png"
		);

		// Not black patterns.
		foreach ($images as $image) {
			
			$n = basename($image);

			if(!in_array($n, $blackPatterns)){
				$return .= '<div class="yp_bg_assets" data-url="'.WT_PLUGIN_URL.'assets/'.basename($image).'"></div>';
			}

		}

		foreach ($blackPatterns as $image) {
			$return .= '<div class="yp_bg_assets" data-url="'.WT_PLUGIN_URL.'assets/'.$image.'"></div>';
		}
		
		$return .= "</div>";
		// Background patterns section end.

		// CSS list of gradients.
		$gradients = array(
			"linear-gradient(to right, #355c7d 0%, #6c5b7b 33%, #c06c84 100%)",
			"linear-gradient(to right, #bc4e9c 0%, #f80759 100%)",
			"linear-gradient(to right, #40e0d0 0%, #ff8c00 33%, #ff0080 100%)",
			"linear-gradient(to right, #3e5151 0%, #decba4 100%)",
			"linear-gradient(to right, #11998e 0%, #38ef7d 100%)",
			"linear-gradient(to right, #108dc7 0%, #ef8e38 100%)",
			"linear-gradient(to right, #fc5c7d 0%, #6a82fb 100%)",
			"linear-gradient(to right, #fc466b 0%, #3f5efb 100%)",
			"linear-gradient(to right, #c94b4b 0%, #4b134f 100%)",
			"linear-gradient(to right, #23074d 0%, #cc5333 100%)",
			"linear-gradient(to right, #fffbd5 0%, #b20a2c 100%)",
			"linear-gradient(to right, #0f0c29 0%, #302b63 33%, #24243e 100%)",
			"linear-gradient(to right, #00b09b 0%, #96c93d 100%)",
			"linear-gradient(to right, #d3cce3 0%, #e9e4f0 100%)",
			"linear-gradient(to right, #3c3b3f 0%, #605c3c 100%)",
			"linear-gradient(to right, #cac531 0%, #f3f9a7 100%)",
			"linear-gradient(to right, #800080 0%, #ffc0cb 100%)",
			"linear-gradient(to right, #00f260 0%, #0575e6 100%)",
			"linear-gradient(to right, #fc4a1a 0%, #f7b733 100%)",
			"linear-gradient(to right, #e1eec3 0%, #f05053 100%)",
			"linear-gradient(to right, #74ebd5 0%, #acb6e5 100%)",
			"linear-gradient(to right, #6d6027 0%, #d3cbb8 100%)",
			"linear-gradient(to right, #03001e 0%, #7303c0 25%, #ec38bc 50%, #fdeff9 100%)",
			"linear-gradient(to right, #667db6 0%, #0082c8 25%, #0082c8 50%, #667db6 100%)",
			"linear-gradient(to right, #ada996 0%, #f2f2f2 25%, #dbdbdb 50%, #eaeaea 100%)",
			"linear-gradient(to right, #e1eec3 0%, #f05053 100%)",
			"linear-gradient(to right, #1a2a6c 0%, #b21f1f 33%, #fdbb2d 100%)",
			"linear-gradient(to right, #22c1c3 0%, #fdbb2d 100%)",
			"linear-gradient(to right, #ff9966 0%, #ff5e62 100%)",
			"linear-gradient(to right, #7f00ff 0%, #e100ff 100%)",
			"linear-gradient(to right, #c9d6ff 0%, #e2e2e2 100%)",
			"linear-gradient(to right, #396afc 0%, #2948ff 100%)",
			"linear-gradient(to right, #d9a7c7 0%, #fffcdc 100%)",
			"linear-gradient(to right, #070000 0%, #4c0001 33%, #070000 100%)",
			"linear-gradient(to right, #000000 0%, #e5008d 33%, #ff070b 100%)",
			"linear-gradient(to right, #0cebeb 0%, #20e3b2 33%, #29ffc6 100%)",
			"linear-gradient(to right, #06beb6 0%, #48b1bf 100%)",
			"linear-gradient(to right, #642b73 0%, #c6426e 100%)",
			"linear-gradient(to right, #1c92d2 0%, #f2fcfe 100%)",
			"linear-gradient(to right, #000000 0%, #0f9b0f 100%)",
			"linear-gradient(to right, #36d1dc 0%, #5b86e5 100%)",
			"linear-gradient(to right, #cb356b 0%, #bd3f32 100%)",
			"linear-gradient(to right, #3a1c71 0%, #d76d77 33%, #ffaf7b 100%)",
			"linear-gradient(to right, #283c86 0%, #45a247 100%)",
			"linear-gradient(to right, #ef3b36 0%, #ffffff 100%)",
			"linear-gradient(to right, #c0392b 0%, #8e44ad 100%)",
			"linear-gradient(to right, #159957 0%, #155799 100%)",
			"linear-gradient(to right, #000046 0%, #1cb5e0 100%)",
			"linear-gradient(to right, #007991 0%, #78ffd6 100%)",
			"linear-gradient(to right, #56ccf2 0%, #2f80ed 100%)",
			"linear-gradient(to right, #f2994a 0%, #f2c94c 100%)",
			"linear-gradient(to right, #eb5757 0%, #000000 100%)",
			"linear-gradient(to right, #e44d26 0%, #f16529 100%)",
			"linear-gradient(to right, #4ac29a 0%, #bdfff3 100%)",
			"linear-gradient(to right, #b2fefa 0%, #0ed2f7 100%)",
			"linear-gradient(to right, #30e8bf 0%, #ff8235 100%)",
			"linear-gradient(to right, #d66d75 0%, #e29587 100%)",
			"linear-gradient(to right, #20002c 0%, #cbb4d4 100%)",
			"linear-gradient(to right, #c33764 0%, #1d2671 100%)",
			"linear-gradient(to right, #f7971e 0%, #ffd200 100%)",
			"linear-gradient(to right, #34e89e 0%, #0f3443 100%)",
			"linear-gradient(to right, #6190e8 0%, #a7bfe8 100%)",
			"linear-gradient(to right, #44a08d 0%, #093637 100%)",
			"linear-gradient(to right, #200122 0%, #6f0000 100%)",
			"linear-gradient(to right, #0575e6 0%, #021b79 100%)",
			"linear-gradient(to right, #4568dc 0%, #b06ab3 100%)",
			"linear-gradient(to right, #43c6ac 0%, #191654 100%)",
			"linear-gradient(to right, #093028 0%, #237a57 100%)",
			"linear-gradient(to right, #43c6ac 0%, #f8ffae 100%)",
			"linear-gradient(to right, #ffafbd 0%, #ffc3a0 100%)",
			"linear-gradient(to right, #f0f2f0 0%, #000c40 100%)",
			"linear-gradient(to right, #e8cbc0 0%, #636fa4 100%)",
			"linear-gradient(to right, #dce35b 0%, #45b649 100%)",
			"linear-gradient(to right, #c0c0aa 0%, #1cefff 100%)",
			"linear-gradient(to right, #dbe6f6 0%, #c5796d 100%)",
			"linear-gradient(to right, #3494e6 0%, #ec6ead 100%)",
			"linear-gradient(to right, #67b26f 0%, #4ca2cd 100%)",
			"linear-gradient(to right, #f3904f 0%, #3b4371 100%)",
			"linear-gradient(to right, #ee0979 0%, #ff6a00 100%)",
			"linear-gradient(to right, #a770ef 0%, #cf8bf3 33%, #fdb99b 100%)",
			"linear-gradient(to right, #41295a 0%, #2f0743 100%)",
			"linear-gradient(to right, #f4c4f3 0%, #fc67fa 100%)",
			"linear-gradient(to right, #00c3ff 0%, #ffff1c 100%)",
			"linear-gradient(to right, #ff7e5f 0%, #feb47b 100%)",
			"linear-gradient(to right, #fffc00 0%, #ffffff 100%)",
			"linear-gradient(to right, #ff00cc 0%, #333399 100%)",
			"linear-gradient(to right, #de6161 0%, #2657eb 100%)",
			"linear-gradient(to right, #ef32d9 0%, #89fffd 100%)",
			"linear-gradient(to right, #3a6186 0%, #89253e 100%)",
			"linear-gradient(to right, #4ecdc4 0%, #556270 100%)",
			"linear-gradient(to right, #a1ffce 0%, #faffd1 100%)",
			"linear-gradient(to right, #be93c5 0%, #7bc6cc 100%)",
			"linear-gradient(to right, #bdc3c7 0%, #2c3e50 100%)",
			"linear-gradient(to right, #ffd89b 0%, #19547b 100%)",
			"linear-gradient(to right, #808080 0%, #3fada8 100%)",
			"linear-gradient(to right, #fceabb 0%, #f8b500 100%)",
			"linear-gradient(to right, #f85032 0%, #e73827 100%)",
			"linear-gradient(to right, #f79d00 0%, #64f38c 100%)",
			"linear-gradient(to right, #cb2d3e 0%, #ef473a 100%)",
			"linear-gradient(to right, #56ab2f 0%, #a8e063 100%)",
			"linear-gradient(to right, #000428 0%, #004e92 100%)",
			"linear-gradient(to right, #42275a 0%, #734b6d 100%)",
			"linear-gradient(to right, #141e30 0%, #243b55 100%)",
			"linear-gradient(to right, #f00000 0%, #dc281e 100%)",
			"linear-gradient(to right, #2c3e50 0%, #fd746c 100%)",
			"linear-gradient(to right, #2c3e50 0%, #4ca1af 100%)",
			"linear-gradient(to right, #e96443 0%, #904e95 100%)",
			"linear-gradient(to right, #0b486b 0%, #f56217 100%)",
			"linear-gradient(to right, #3a7bd5 0%, #3a6073 100%)",
			"linear-gradient(to right, #00d2ff 0%, #928dab 100%)",
			"linear-gradient(to right, #2196f3 0%, #f44336 100%)",
			"linear-gradient(to right, #ff5f6d 0%, #ffc371 100%)",
			"linear-gradient(to right, #ff4b1f 0%, #ff9068 100%)",
			"linear-gradient(to right, #16bffd 0%, #cb3066 100%)",
			"linear-gradient(to right, #eecda3 0%, #ef629f 100%)",
			"linear-gradient(to right, #1d4350 0%, #a43931 100%)",
			"linear-gradient(to right, #a80077 0%, #66ff00 100%)",
			"linear-gradient(to right, #f7ff00 0%, #db36a4 100%)",
			"linear-gradient(to right, #ff4b1f 0%, #1fddff 100%)",
			"linear-gradient(to right, #ba5370 0%, #f4e2d8 100%)",
			"linear-gradient(to right, #e0eafc 0%, #cfdef3 100%)",
			"linear-gradient(to right, #4ca1af 0%, #c4e0e5 100%)",
			"linear-gradient(to right, #000000 0%, #434343 100%)",
			"linear-gradient(to right, #4b79a1 0%, #283e51 100%)",
			"linear-gradient(to right, #834d9b 0%, #d04ed6 100%)",
			"linear-gradient(to right, #0099f7 0%, #f11712 100%)",
			"linear-gradient(to right, #2980b9 0%, #2c3e50 100%)",
			"linear-gradient(to right, #5a3f37 0%, #2c7744 100%)",
			"linear-gradient(to right, #4da0b0 0%, #d39d38 100%)",
			"linear-gradient(to right, #5614b0 0%, #dbd65c 100%)",
			"linear-gradient(to right, #2f7336 0%, #aa3a38 100%)",
			"linear-gradient(to right, #1e3c72 0%, #2a5298 100%)",
			"linear-gradient(to right, #114357 0%, #f29492 100%)",
			"linear-gradient(to right, #fd746c 0%, #ff9068 100%)",
			"linear-gradient(to right, #eacda3 0%, #d6ae7b 100%)",
			"linear-gradient(to right, #6a3093 0%, #a044ff 100%)",
			"linear-gradient(to right, #457fca 0%, #5691c8 100%)",
			"linear-gradient(to right, #b24592 0%, #f15f79 100%)",
			"linear-gradient(to right, #c02425 0%, #f0cb35 100%)",
			"linear-gradient(to right, #403a3e 0%, #be5869 100%)",
			"linear-gradient(to right, #c2e59c 0%, #64b3f4 100%)",
			"linear-gradient(to right, #ffb75e 0%, #ed8f03 100%)",
			"linear-gradient(to right, #8e0e00 0%, #1f1c18 100%)",
			"linear-gradient(to right, #76b852 0%, #8dc26f 100%)",
			"linear-gradient(to right, #673ab7 0%, #512da8 100%)",
			"linear-gradient(to right, #00c9ff 0%, #92fe9d 100%)",
			"linear-gradient(to right, #f46b45 0%, #eea849 100%)",
			"linear-gradient(to right, #005c97 0%, #363795 100%)",
			"linear-gradient(to right, #e53935 0%, #e35d5b 100%)",
			"linear-gradient(to right, #fc00ff 0%, #00dbde 100%)",
			"linear-gradient(to right, #2c3e50 0%, #3498db 100%)",
			"linear-gradient(to right, #ccccb2 0%, #757519 100%)",
			"linear-gradient(to right, #304352 0%, #d7d2cc 100%)",
			"linear-gradient(to right, #ee9ca7 0%, #ffdde1 100%)",
			"linear-gradient(to right, #ba8b02 0%, #181818 100%)",
			"linear-gradient(to right, #525252 0%, #3d72b4 100%)",
			"linear-gradient(to right, #004ff9 0%, #fff94c 100%)",
			"linear-gradient(to right, #6a9113 0%, #141517 100%)",
			"linear-gradient(to right, #f1f2b5 0%, #135058 100%)",
			"linear-gradient(to right, #d1913c 0%, #ffd194 100%)",
			"linear-gradient(to right, #7b4397 0%, #dc2430 100%)",
			"linear-gradient(to right, #8e9eab 0%, #eef2f3 100%)",
			"linear-gradient(to right, #136a8a 0%, #267871 100%)",
			"linear-gradient(to right, #00bf8f 0%, #001510 100%)",
			"linear-gradient(to right, #ff0084 0%, #33001b 100%)",
			"linear-gradient(to right, #833ab4 0%, #fd1d1d 33%, #fcb045 100%)",
			"linear-gradient(to right, #feac5e 0%, #c779d0 33%, #4bc0c8 100%)",
			"linear-gradient(to right, #6441a5 0%, #2a0845 100%)",
			"linear-gradient(to right, #ffb347 0%, #ffcc33 100%)",
			"linear-gradient(to right, #43cea2 0%, #185a9d 100%)",
			"linear-gradient(to right, #ffa17f 0%, #00223e 100%)",
			"linear-gradient(to right, #360033 0%, #0b8793 100%)",
			"linear-gradient(to right, #948e99 0%, #2e1437 100%)",
			"linear-gradient(to right, #1e130c 0%, #9a8478 100%)",
			"linear-gradient(to right, #d38312 0%, #a83279 100%)",
			"linear-gradient(to right, #73c8a9 0%, #373b44 100%)",
			"linear-gradient(to right, #abbaab 0%, #ffffff 100%)",
			"linear-gradient(to right, #fdfc47 0%, #24fe41 100%)",
			"linear-gradient(to right, #83a4d4 0%, #b6fbff 100%)",
			"linear-gradient(to right, #485563 0%, #29323c 100%)",
			"linear-gradient(to right, #52c234 0%, #061700 100%)",
			"linear-gradient(to right, #fe8c00 0%, #f83600 100%)",
			"linear-gradient(to right, #00c6ff 0%, #0072ff 100%)",
			"linear-gradient(to right, #70e1f5 0%, #ffd194 100%)",
			"linear-gradient(to right, #556270 0%, #ff6b6b 100%)",
			"linear-gradient(to right, #9d50bb 0%, #6e48aa 100%)",
			"linear-gradient(to right, #780206 0%, #061161 100%)",
			"linear-gradient(to right, #b3ffab 0%, #12fff7 100%)",
			"linear-gradient(to right, #aaffa9 0%, #11ffbd 100%)",
			"linear-gradient(to right, #000000 0%, #e74c3c 100%)",
			"linear-gradient(to right, #f0c27b 0%, #4b1248 100%)",
			"linear-gradient(to right, #ff4e50 0%, #f9d423 100%)",
			"linear-gradient(to right, #add100 0%, #7b920a 100%)",
			"linear-gradient(to right, #fbd3e9 0%, #bb377d 100%)",
			"linear-gradient(to right, #000000 0%, #53346d 100%)",
			"linear-gradient(to right, #606c88 0%, #3f4c6b 100%)",
			"linear-gradient(to right, #c9ffbf 0%, #ffafbd 100%)",
			"linear-gradient(to right, #649173 0%, #dbd5a4 100%)",
			"linear-gradient(to right, #b993d6 0%, #8ca6db 100%)",
			"linear-gradient(to right, #870000 0%, #190a05 100%)",
			"linear-gradient(to right, #00d2ff 0%, #3a7bd5 100%)",
			"linear-gradient(to right, #d3959b 0%, #bfe6ba 100%)",
			"linear-gradient(to right, #dad299 0%, #b0dab9 100%)",
			"linear-gradient(to right, #e6dada 0%, #274046 100%)",
			"linear-gradient(to right, #5d4157 0%, #a8caba 100%)",
			"linear-gradient(to right, #ddd6f3 0%, #faaca8 100%)",
			"linear-gradient(to right, #616161 0%, #9bc5c3 100%)",
			"linear-gradient(to right, #50c9c3 0%, #96deda 100%)",
			"linear-gradient(to right, #215f00 0%, #e4e4d9 100%)",
			"linear-gradient(to right, #c21500 0%, #ffc500 100%)",
			"linear-gradient(to right, #efefbb 0%, #d4d3dd 100%)",
			"linear-gradient(to right, #ffeeee 0%, #ddefbb 100%)",
			"linear-gradient(to right, #666600 0%, #999966 100%)",
			"linear-gradient(to right, #de6262 0%, #ffb88c 100%)",
			"linear-gradient(to right, #e9d362 0%, #333333 100%)",
			"linear-gradient(to right, #d53369 0%, #cbad6d 100%)",
			"linear-gradient(to right, #a73737 0%, #7a2828 100%)",
			"linear-gradient(to right, #f857a6 0%, #ff5858 100%)",
			"linear-gradient(to right, #4b6cb7 0%, #182848 100%)",
			"linear-gradient(to right, #fc354c 0%, #0abfbc 100%)",
			"linear-gradient(to right, #414d0b 0%, #727a17 100%)",
			"linear-gradient(to right, #e43a15 0%, #e65245 100%)",
			"linear-gradient(to right, #c04848 0%, #480048 100%)",
			"linear-gradient(to right, #5f2c82 0%, #49a09d 100%)",
			"linear-gradient(to right, #ec6f66 0%, #f3a183 100%)",
			"linear-gradient(to right, #7474bf 0%, #348ac7 100%)",
			"linear-gradient(to right, #ece9e6 0%, #ffffff 100%)",
			"linear-gradient(to right, #dae2f8 0%, #d6a4a4 100%)",
			"linear-gradient(to right, #ed4264 0%, #ffedbc 100%)",
			"linear-gradient(to right, #dc2424 0%, #4a569d 100%)",
			"linear-gradient(to right, #24c6dc 0%, #514a9d 100%)",
			"linear-gradient(to right, #283048 0%, #859398 100%)",
			"linear-gradient(to right, #3d7eaa 0%, #ffe47a 100%)",
			"linear-gradient(to right, #1cd8d2 0%, #93edc7 100%)",
			"linear-gradient(to right, #232526 0%, #414345 100%)",
			"linear-gradient(to right, #757f9a 0%, #d7dde8 100%)",
			"linear-gradient(to right, #5c258d 0%, #4389a2 100%)",
			"linear-gradient(to right, #134e5e 0%, #71b280 100%)",
			"linear-gradient(to right, #2bc0e4 0%, #eaecc6 100%)",
			"linear-gradient(to right, #085078 0%, #85d8ce 100%)",
			"linear-gradient(to right, #4776e6 0%, #8e54e9 100%)",
			"linear-gradient(to right, #614385 0%, #516395 100%)",
			"linear-gradient(to right, #1f1c2c 0%, #928dab 100%)",
			"linear-gradient(to right, #16222a 0%, #3a6073 100%)",
			"linear-gradient(to right, #ff8008 0%, #ffc837 100%)",
			"linear-gradient(to right, #1d976c 0%, #93f9b9 100%)",
			"linear-gradient(to right, #eb3349 0%, #f45c43 100%)",
			"linear-gradient(to right, #dd5e89 0%, #f7bb97 100%)",
			"linear-gradient(to right, #4cb8c4 0%, #3cd3ad 100%)",
			"linear-gradient(to right, #1fa2ff 0%, #12d8fa 33%, #a6ffcb 100%)",
			"linear-gradient(to right, #1d2b64 0%, #f8cdda 100%)",
			"linear-gradient(to right, #ff512f 0%, #f09819 100%)",
			"linear-gradient(to right, #1a2980 0%, #26d0ce 100%)",
			"linear-gradient(to right, #aa076b 0%, #61045f 100%)",
			"linear-gradient(to right, #ff512f 0%, #dd2476 100%)",
			"linear-gradient(to right, #f09819 0%, #edde5d 100%)",
			"linear-gradient(to right, #403b4a 0%, #e7e9bb 100%)",
			"linear-gradient(to right, #e55d87 0%, #5fc3e4 100%)",
			"linear-gradient(to right, #003973 0%, #e5e5be 100%)",
			"linear-gradient(to right, #cc95c0 0%, #dbd4b4 33%, #7aa1d2 100%)",
			"linear-gradient(to right, #3ca55c 0%, #b5ac49 100%)",
			"linear-gradient(to right, #348f50 0%, #56b4d3 100%)",
			"linear-gradient(to right, #da22ff 0%, #9733ee 100%)",
			"linear-gradient(to right, #02aab0 0%, #00cdac 100%)",
			"linear-gradient(to right, #ede574 0%, #e1f5c4 100%)",
			"linear-gradient(to right, #d31027 0%, #ea384d 100%)",
			"linear-gradient(to right, #16a085 0%, #f4d03f 100%)",
			"linear-gradient(to right, #603813 0%, #b29f94 100%)",
			"linear-gradient(to right, #e52d27 0%, #b31217 100%)",
			"linear-gradient(to right, #ff6e7f 0%, #bfe9ff 100%)",
			"linear-gradient(to right, #77a1d3 0%, #79cbca 33%, #e684ae 100%)",
			"linear-gradient(to right, #314755 0%, #26a0da 100%)",
			"linear-gradient(to right, #2b5876 0%, #4e4376 100%)",
			"linear-gradient(to right, #e65c00 0%, #f9d423 100%)",
			"linear-gradient(to right, #2193b0 0%, #6dd5ed 100%)",
			"linear-gradient(to right, #cc2b5e 0%, #753a88 100%)",
			"linear-gradient(to right, #ec008c 0%, #fc6767 100%)",
			"linear-gradient(to right, #1488cc 0%, #2b32b2 100%)",
			"linear-gradient(to right, #00467f 0%, #a5cc82 100%)",
			"linear-gradient(to right, #076585 0%, #ffffff 100%)",
			"linear-gradient(to right, #bbd2c5 0%, #536976 100%)",
			"linear-gradient(to right, #b79891 0%, #94716b 100%)",
			"linear-gradient(to right, #bbd2c5 0%, #536976 33%, #292e49 100%)",
			"linear-gradient(to right, #536976 0%, #292e49 100%)",
			"linear-gradient(to right, #acb6e5 0%, #86fde8 100%)",
			"linear-gradient(to right, #ffe259 0%, #ffa751 100%)"
		);

		// Gradient names
		$gradientNames = array(
			"Mango",
			"Windy",
			"Royal Blue",
			"Royal Blue + Petrol",
			"Copper",
			"Petrol",
			"Sky",
			"Sel",
			"Skyline",
			"DIMIGO",
			"Purple Love",
			"Sexy Blue",
			"Blooker20",
			"Sea Blue",
			"Nimvelo",
			"Hazel",
			"Noon to Dusk",
			"YouTube",
			"Cool Brown",
			"Harmonic Energy",
			"Playing with Reds",
			"Sunny Days",
			"Green Beach",
			"Intuitive Purple",
			"Emerald Water",
			"Lemon Twist",
			"Monte Carlo",
			"Horizon",
			"Rose Water",
			"Frozen",
			"Mango Pulp",
			"Bloody Mary",
			"Aubergine",
			"Aqua Marine",
			"Sunrise",
			"Purple Paradise",
			"Stripe",
			"Sea Weed",
			"Pinky",
			"Cherry",
			"Mojito",
			"Juicy Orange",
			"Mirage",
			"Steel Gray",
			"Kashmir",
			"Electric Violet",
			"Venice Blue",
			"Bora Bora",
			"Moss",
			"Shroom Haze",
			"Mystic",
			"Midnight City",
			"Sea Blizz",
			"Opa",
			"Titanium",
			"Mantle",
			"Dracula",
			"Peach",
			"Moonrise",
			"Clouds",
			"Stellar",
			"Bourbon",
			"Calm Darya",
			"Influenza",
			"Shrimpy",
			"Army",
			"Miaka",
			"Pinot Noir",
			"Day Tripper",
			"Namn",
			"Blurry Beach",
			"Vasily",
			"A Lost Memory",
			"Petrichor",
			"Jonquil",
			"Sirius Tamed",
			"Kyoto",
			"Misty Meadow",
			"Aqualicious",
			"Moor",
			"Almost",
			"Forever Lost",
			"Winter",
			"Autumn",
			"Candy",
			"Reef",
			"The Strain",
			"Dirty Fog",
			"Earthly",
			"Virgin",
			"Ash",
			"Shadow Night",
			"Cherryblossoms",
			"Parklife",
			"Dance To Forget",
			"Starfall",
			"Red Mist",
			"Teal Love",
			"Neon Life",
			"Man of Steel",
			"Amethyst",
			"Cheer Up Emo Kid",
			"Shore",
			"Facebook Messenger",
			"SoundCloud",
			"Behongo",
			"ServQuick",
			"Friday",
			"Martini",
			"Metallic Toad",
			"Between The Clouds",
			"Crazy Orange I",
			"Hersheys",
			"Talking To Mice Elf",
			"Purple Bliss",
			"Predawn",
			"Endless River",
			"Pastel Orange at the Sun",
			"Twitch",
			"Atlas",
			"Instagram",
			"Flickr",
			"Vine",
			"Turquoise flow",
			"Portrait",
			"Virgin America",
			"Koko Caramel",
			"Fresh Turboscent",
			"Green to dark",
			"Ukraine",
			"Curiosity blue",
			"Dark Knight",
			"Piglet",
			"Lizard",
			"Sage Persuasion",
			"Between Night and Day",
			"Timber",
			"Passion",
			"Clear Sky",
			"Master Card",
			"Back To Earth",
			"Deep Purple",
			"Little Leaf",
			"Netflix",
			"Light Orange",
			"Green and Blue",
			"Poncho",
			"Back to the Future",
			"Blush",
			"Inbox",
			"Purplin",
			"Pale Wood",
			"Haikus",
			"Pizelex",
			"Joomla",
			"Christmas",
			"Minnesota Vikings",
			"Miami Dolphins",
			"Forest",
			"Nighthawk",
			"Superman",
			"Suzy",
			"Dark Skies",
			"Deep Space",
			"Decent",
			"Colors Of Sky",
			"Purple White",
			"Ali",
			"Alihossein",
			"Shahabi",
			"Red Ocean",
			"Tranquil",
			"Transfile",
			"Sylvia",
			"Sweet Morning",
			"Politics",
			"Bright Vault",
			"Solid Vault",
			"Sunset",
			"Grapefruit Sunset",
			"Deep Sea Space",
			"Dusk",
			"Minimal Red",
			"Royal",
			"Mauve",
			"Frost",
			"Lush",
			"Firewatch",
			"Sherbert",
			"Blood Red",
			"Sun on the Horizon",
			"IIIT Delhi",
			"Dusk",
			"50 Shades of Grey",
			"Dania",
			"Limeade",
			"Disco",
			"Love Couple",
			"Azure Pop",
			"Nepal",
			"Cosmic Fusion",
			"Snapchat",
			"Ed's Sunset Gradient",
			"Brady Brady Fun Fun",
			"Black Rosé",
			"80's Purple",
			"Radar",
			"Ibiza Sunset",
			"Dawn",
			"Mild",
			"Vice City",
			"Jaipur",
			"Cocoaa Ice",
			"EasyMed",
			"Rose Colored Lenses",
			"What lies Beyond",
			"Roseanna",
			"Honey Dew",
			"Under the Lake",
			"The Blue Lagoon",
			"Can You Feel The Love Tonight",
			"Very Blue",
			"Love and Liberty",
			"Orca",
			"Venice",
			"Pacific Dream",
			"Learning and Leading",
			"Celestial",
			"Purplepine",
			"Sha la la",
			"Mini",
			"Maldives",
			"Cinnamint",
			"Html",
			"Coal",
			"Sunkist",
			"Blue Skies",
			"Chitty Chitty Bang Bang",
			"Visions of Grandeur",
			"Crystal Clear",
			"Mello",
			"Compare Now",
			"Meridian",
			"Relay",
			"Alive",
			"Scooter",
			"Terminal",
			"Telegram",
			"Crimson Tide",
			"Socialive",
			"Subu",
			"Shift",
			"Clot",
			"Broken Hearts",
			"Kimoby Is The New Blue",
			"Dull",
			"Purpink",
			"Orange Coral",
			"Summer",
			"King Yna",
			"Velvet Sun",
			"Zinc",
			"Hydrogen",
			"Argon",
			"Lithium",
			"Digital Water",
			"Velvet Sun",
			"Orange Fun",
			"Rainbow Blue",
			"Pink Flavour",
			"Sulphur",
			"Selenium",
			"Delicate",
			"Ohhappiness",
			"Lawrencium",
			"Relaxing red",
			"Taran Tado",
			"Bighead",
			"Sublime Vivid",
			"Sublime Light",
			"Pun Yeta",
			"Quepal",
			"Sand to Blue",
			"Wedding Day Blues",
			"Shifter",
			"Red Sunset"
		);

		// Getting gradients
		$gradientList = '';
		$i = 0;
		foreach ($gradients as $gradient) {
			$gradientList .= '<div class="yp-gradient-demo" data-gradient="'.$gradient.'"><span style="background-image:'.$gradient.'"></span> '.$gradientNames[$i].'</div>';
			$i++;

		}

		// Background gradient section starts
		$return .= '<div class="yp-gradient-section"><div class="yp-gradient-list">'.$gradientList.'</div><div class="uigradient-api">by <a href="https://uigradients.com">uiGradients</a></div><div class="yp-gradient-bar-background"><div class="yp-gradient-bar"></div></div><div class="yp-gradient-pointer-area"></div><input id="iris-gradient-color" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" /><div class="yp-gradient-orientation" data-degree="90"><b>Orientation</b><i></i></div></div>';
		// Background gradient section end


	}

    $return .= "                
            </div>
            </div>";
    
    return $return;
    
}



/* ---------------------------------------------------- */
/* Input Option   		                                */
/* ---------------------------------------------------- */
function yp_get_textarea_markup($cssName, $name, $none = null,$note = null){
	
	$tooltip = '';
	if($note != null && $note != false){
		$tooltip = " data-toggle='tooltip' data-placement='left' title='".$note."'";
	}
    
    $return = "<div id='" . $cssName . "-group' class='yp-option-group yp-input-option' data-css='" . $cssName . "'>
                
                <div class='yp-part'>
                    <label class='yp-option-label'><a target='_blank' href='http://waspthemes.com/yellow-pencil/buy' class='yp-lite yp-pro-label'>GO PRO</a><span".$tooltip.">" . $name . ":</span><i class='phone-icon'></i> <a class='yp-btn-action yp-none-btn' data-default='".$none."'>".$none."</a> <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <textarea autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' id='yp-" . $cssName . "' type='text' class='yp-textarea'></textarea>";
                
    $return .= "
            </div>
            
            </div>";
    
    return $return;
    
}