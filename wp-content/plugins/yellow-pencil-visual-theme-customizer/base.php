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
    
    if ($default != false) {
        $defaultLink = "<a class='yp-btn-action yp-none-btn'>" . $default . "</a>";
    } else {
        $defaultLink = '';
    }

    $notice_last = null;

    if(!defined("WTFV")){
	    if($cssName == 'opacity'){
	    	$notice_last = "<p class='yp-alert-warning yp-notice-last'>".ucfirst(strtolower($name))." ".__('property is not available in Lite.','yp')." <a target='_blank' href='http://waspthemes.com/yellow-pencil/buy'>".__('Go Pro','yp')."?</a></p>";
	    }
    }
    
    return "<div id='" . $cssName . "-group' class='yp-option-group yp-slider-option' data-css='" . $cssName . "' data-decimals='" . $decimals . "' data-pxv='" . $pxv . "' data-pcv='" . $pcv . "' data-emv='" . $emv . "'>
                
        <div class='yp-part'>
        
            <label class='yp-option-label'><span".$tooltip.">" . strtoupper($name) . "</span>: " . $defaultLink . " <a class='yp-btn-action yp-disable-btn'></a></label>
            <div id='yp-" . $cssName . "'></div>
                
            <div class='yp-after'>
                <input type='text' id='" . $cssName . "-value' class='yp-after-css yp-after-css-val' autocomplete='off' />
                <input type='text' id='" . $cssName . "-after' class='yp-after-css yp-after-prefix' autocomplete='off' /><small>(px vh % em)</small>
            </div>
                
            </div>
            ".$notice_last."
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
        $defaultLink = "<a class='yp-btn-action yp-none-btn'>" . $default . "</a>";
    } else {
        $defaultLink = '';
    }

    if ($cssName == 'animation-name' && $default != false) {
        $defaultLink = "<a class='yp-visual-editor-link'>Animator</a><a class='yp-btn-action yp-none-btn'>" . $default . "</a>";
    }

    $notice_last = null;
    if(!defined("WTFV")){
	    if($cssName == 'font-family'){
	    	$notice_last = "<p class='yp-alert-warning yp-notice-last'>".ucfirst(strtolower($name))." ".__('property is not available in Lite.','yp')." <a target='_blank' href='http://waspthemes.com/yellow-pencil/buy'>".__('Go Pro','yp')."?</a></p>";
	    }
    }
    
    $return = "<div id='" . $cssName . "-group' class='yp-option-group yp-select-option' data-css='" . $cssName . "'>
                
                <div class='yp-part'>
                    <label class='yp-option-label'><span".$tooltip.">" . strtoupper($name) . "</span>: " . $defaultLink . " <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <select id='yp-" . $cssName . "-data' class='yp-select-data'>";
    
    foreach ($values as $key => $value) {

    	$id = explode(",",$key);
    	if(isset($id[0])){
    		$id = str_replace(" ","", $id[0]);
    		$id = str_replace("'","", $id);
    		$id = str_replace('"',"", $id);
    		$id = strtolower($id);
    	}
    	
        $return .= '<option value="' . $key . '" data-text="' . $id . '">' . $value . '</option>';
        
    }
    
    $return .= "</select>

    			<input id='yp-" . $cssName . "' type='text' class='input-autocomplete' value='' />
                <div id='yp-autocomplete-place-" . $cssName . "' class='autocomplete-div'></div>
                
            </div>
            ".$notice_last."
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
        $defaultLink = "<a class='yp-btn-action yp-none-btn'>" . $default . "</a>";
    } else {
        $defaultLink = '';
    }

    $notice_last = null;
    
    $return = "<div id='" . $cssName . "-group' class='yp-option-group yp-radio-option' data-css='" . $cssName . "'>
                
                <div class='yp-part'>
                    <label class='yp-option-label'><span".$tooltip.">" . strtoupper($name) . "</span>: " . $defaultLink . " <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <div class='yp-radio-grid-" . count($values) . " yp-radio-content' id='yp-" . $cssName . "'>
                    ";
    
    foreach ($values as $key => $value) {
        
        if ($cssName != 'position' && $cssName != 'float' && $cssName != 'display' && $cssName != 'overflow-x' && $cssName != 'overflow-y' && $cssName != 'border-style' && $cssName != 'border-top-style' && $cssName != 'border-left-style' && $cssName != 'border-right-style' && $cssName != 'border-bottom-style' && $cssName != 'visibility') {
            $style_tag = 'style="' . $cssName . ':' . $key . '"';
        } else {
            $style_tag = '';
        }
        
        $return .= '<div class="yp-radio"><input type="radio" name="' . $cssName . '" value="' . $key . '" id="s-'.$cssName.'-' . $key . '"><label id="'.$cssName.'-' . $key . '" data-for="s-'.$cssName.'-' . $key . '" ' . $style_tag . ' class="yp-update">' . $value . '</label></div>';
        
    }
    
    $return .= "
                
                <div class='yp-clearfix'></div>
                
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

	$notice_last = null;
    if(!defined("WTFV")){
	    if($cssName == 'color' || $cssName == 'background-color'){
	    	$notice_last = "<p class='yp-alert-warning yp-notice-last'>".ucfirst(strtolower($name))." ".__('property is not available in Lite.','yp')." <a target='_blank' href='http://waspthemes.com/yellow-pencil/buy'>".__('Go Pro','yp')."?</a></p>";
	    }
    }
    
    $return = "<div id='" . $cssName . "-group' class='yp-option-group yp-color-option' data-css='" . $cssName . "'>
                
                <div class='yp-part'>
                    <label class='yp-option-label'><span".$tooltip.">" . strtoupper($name) . "</span>: <a class='yp-btn-action yp-none-btn'>".__('transparent','yp')."</a> <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <div class='yp-color-input-box'>
                    <input id='yp-" . $cssName . "' type='text' maxlength='22' size='22' class='wqcolorpicker' value='' />
                	<span class='wqminicolors-swatch-color'></span>
                	</div>

                <div class='yp-after'>
					<a class='yp-flat-colors'>".__('Flat','yp')."</a> <a class='yp-meterial-colors'>".__('Material','yp')."</a> <a class='yp-nice-colors'>".__('Nice','yp')."</a> <a class='yp-element-picker'>".__('Picker','yp')."</a>
					<div class='yp-clearfix'></div>
					
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
            ".$notice_last."
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
                    <label class='yp-option-label'><span".$tooltip.">" . strtoupper($name) . "</span>: <a class='yp-btn-action yp-none-btn'>".$none."</a> <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <input autocomplete='off' id='yp-" . $cssName . "' type='text' class='yp-input' value='' />";


    $notice_last = null;
    if(!defined("WTFV")){
	    if($cssName == 'background-image'){
	    	$notice_last = "<p class='yp-alert-warning yp-notice-last'>".ucfirst(strtolower($name))." ".__('property is not available in Lite.','yp')." <a target='_blank' href='http://waspthemes.com/yellow-pencil/buy'>".__('Go Pro','yp')."?</a></p>";
	    }
    }
	
	if($cssName == "background-image"){
		$images = glob(WT_PLUGIN_DIR.'/assets/*.{png,jpg,jpeg}', GLOB_BRACE);
		$return .= "<div style='clear:both;'></div><a class='yp-gallery-btn yp-upload-btn'>".__('Upload','yp')."</a><a class='yp-gradient-btn'>".__('Gradient','yp')."</a><a class='yp-bg-img-btn'>".__('Patterns','yp')."</a><div style='clear:both;'></div>";

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


		// Background gradient section starts
		$return .= '<div class="yp-gradient-section"><div class="yp-gradient-bar-background"><div class="yp-gradient-bar"></div></div><div class="yp-gradient-pointer-area"></div><input id="iris-gradient-color" type="text" /><div class="yp-gradient-orientation" data-degree="90"><b>Orientation</b><i></i></div></div>';
		// Background gradient section end


	}

    $return .= "                
            </div>
            ".$notice_last."
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
                    <label class='yp-option-label'><span".$tooltip.">" . strtoupper($name) . "</span>: <a class='yp-btn-action yp-none-btn'>".$none."</a> <a class='yp-btn-action yp-disable-btn'></a></label>
                    
                    <textarea autocomplete='off' id='yp-" . $cssName . "' type='text' class='yp-textarea'></textarea>";
                
    $return .= "
            </div>
            
            </div>";
    
    return $return;
    
}