<?php

	// Return option value from db 
	function get_setting_value($setting, $sub_add=''){
		$value = '';
		$main = get_option($setting['main_setting_name']);
		$sub = $setting['sub_setting_name'];
		if($sub_add != '') $sub = $setting['sub_setting_name'] . '_' . $sub_add;
		$value = $main[$sub];
		/*if( isset($setting['std']) ){
            if(empty($value)) $value = $setting['std'];
        }*/
		return $value;
	}

	function save_setting_value($main_setting_name, $sub_setting_name, $new_value){
		$main_option = get_option( $main_setting_name );
		if( $main_option && (!empty($main_option)) ){
			$old_value = $main_option[$sub_setting_name];
			if( $old_value !== $new_value ){
				$main_option[$sub_setting_name] = $new_value;
				update_option($main_setting_name, $main_option); // updates the options in db
			}
			return 0;
		}else{
			return -1;
		}
	}

	// Save form fields from post
	// Args array $global settings , array $_POST
	function save_form_settings($settings, $post_array){
		foreach ( $settings as $setting ) {
			if( $setting['type'] !== 'label-header' ){
                save_setting_value($setting['main_setting_name'], 
                    $setting['sub_setting_name'], 
                    $post_array[ $setting['id'] ]
                );
                // Save [B] [I] [TT] [U] buttons values
                if( $setting['type'] == 'font-select' ){
                    save_setting_value($setting['main_setting_name'], 
                        $setting['sub_setting_name'].'_b', 
                        $post_array[ $setting['id'].'_b' ]
                    );
                    save_setting_value($setting['main_setting_name'], 
                        $setting['sub_setting_name'].'_i', 
                        $post_array[ $setting['id'].'_i' ]
                    );
                    save_setting_value($setting['main_setting_name'], 
                        $setting['sub_setting_name'].'_tt', 
                        $post_array[ $setting['id'].'_tt' ]
                    );
                    save_setting_value($setting['main_setting_name'], 
                        $setting['sub_setting_name'].'_u', 
                        $post_array[ $setting['id'].'_u' ]
                    );
                }
            }
		}
	}

	function render_field($setting){
            
            switch ( $setting['type'] ) {

                // Select
                case 'select':
                    printf(
                        '<select name="%1$s" id="%1$s">',
                        esc_attr( $setting['id'] )
                    );

                    if ( is_array( $setting['options'] ) && ! empty( $setting['options'] ) ) {
                        foreach ( $setting['options'] as $option_value ) {
                            printf(
                                '<option value="%1$s" %3$s>%2$s</option>',
                                esc_attr( $option_value ),
                                esc_attr( $option_value ),
                                "{$option_value}" === get_setting_value($setting) ? 'selected="selected"' : ''
                            );
                        }
                    }

                    echo '</select>';

                	break;

                // Toggle
                case 'checkbox':
                	
                    printf(
                        '<select name="%1$s" id="%1$s" class="et-pb-toggle-select" data-type="checkbox">',
                        esc_attr( $setting['id'] )
                    );

                    $toggle_options = array('off', 'on');

                    $selected_value = get_setting_value($setting);

                    foreach ( $toggle_options as $option_value ) {
                        printf(
                            '<option value="%1$s" %2$s>%1$s</option>',
                            esc_attr( $option_value ),
                            "{$option_value}" === $selected_value ? 'selected="selected"' : ''
                        );
                    }

                    echo '</select>';

                    echo sprintf(
                        '<div class="et_pb_yes_no_button et_pb_%1$s_state" style="max-width: 195px;">
                            <span class="et_pb_value_text et_pb_on_value">%2$s</span>
                            <span class="et_pb_button_slider"></span>
                            <span class="et_pb_value_text et_pb_off_value">%3$s</span>
                        </div>',
                        esc_attr( $selected_value ),
                        esc_html__( 'Enabled' ),
                        esc_html__( 'Disabled' )
                    );

                    echo '</select>';

                    break;

                // Colorpicker
                case 'color':
                    printf(
                        '<button class="reset-color" data-for="%1$s">%2$s</button>',
                        esc_attr( $setting['id'] ),
                        esc_html__( 'Reset Color' )
                    );

                    printf(
                        '<input type="text" id="%1$s" name="%1$s" placeholder="%2$s" value="%3$s" class="regular-text color-picker" data-default="%4$s" data-alpha="true" data-default-color="%4$s"/>',
                        esc_attr( $setting['id'] ),
                        esc_attr( $setting['std'] ),
                        esc_attr( get_setting_value($setting) ),
                        esc_attr( $setting['std'] )
                    );

                    break;

                // URL
                case 'url':
                    printf(
                        '<input type="text" id="%1$s" name="%1$s" placeholder="%2$s" value="%3$s" />',
                        esc_attr( $setting['id'] ),
                        esc_attr( $setting['std'] ),
                        esc_attr( get_setting_value($setting) )
                    );
                    break;

                // Range
                case 'range':
                    printf(
                        '<input type="range" id="%1$s" name="%1$s" placeholder="%2$s" value="%3$s" 
                        class="tm-range-input" min="%4$s" max="%5$s" step="%6$s" onchange="updateTextOut(this.value,this.name)"/><span id="%1$s_text_out" class="tm-range-text-out">%7$spx</span>',
                        esc_attr( $setting['id'] ),
                        esc_attr( isset($setting['std']) ? $setting['std'] : ''),
                        esc_attr( get_setting_value($setting) ),
                        $setting['min'],
                        $setting['max'],
                        $setting['step'],
                        get_setting_value($setting)
                    );
                    break;
                
                // Font
                case 'font-select':
                	
                	// Font list for select fields
					$font_list = array(
				    	"Default","Georgia","Times New Roman","Arial","Trebuchet","Verdana","Abel","Amatic SC","Arimo","Arvo","Bevan","Bitter","Black Ops One","Boogaloo","Bree Serif","Calligraffitti","Cantata One","Cardo","Changa One","Cherry Cream Soda","Chewy","Comfortaa","Coming Soon","Covered By Your Grace","Crafty Girls","Crete Round","Crimson Text","Cuprum","Dancing Script","Dosis","Droid Sans","Droid Serif","Francois One","Fredoka One","The Girl Next Door","Gloria Hallelujah","Happy Monkey","Indie Flower","Josefin Slab","Judson","Kreon","Lato","Lato Light","Leckerli One","Lobster","Lobster Two","Lora","Luckiest Guy","Merriweather","Metamorphous","Montserrat","Noticia Text","Nova Square","Nunito","Old Standard TT","Open Sans","Open Sans Condensed","Open Sans Light","Oswald","Pacifico","Passion One","Patrick Hand","Permanent Marker","Play","Playfair Display","Poiret One","PT Sans","PT Sans Narrow","PT Serif","Raleway","Raleway Light","Reenie Beanie","Righteous","Roboto","Roboto Condensed","Roboto Mono","Rock Salt","Rokkitt","Sanchez","Satisfy","Schoolbell","Shadows Into Light","Source Sans Pro","Source Sans Pro Light","Special Elite","Squada One","Tangerine","Ubuntu","Unkempt","Vollkorn","Walter Turncoat","Yanone Kaffeesatz"
					);

                	printf(
                        '<select name="%1$s" id="%1$s">',
                        esc_attr( $setting['id'] )
                    );
                	print_r($font_list); //
                    if ( is_array( $font_list ) && ! empty( $font_list ) ) {
                    	echo 'EN EL IF --------------------------------';
                        foreach ( $font_list as $option_value ) {
                            printf(
                                '<option value="%1$s" %3$s>%2$s</option>',
                                esc_attr( $option_value ),
                                esc_attr( $option_value ),
                                "{$option_value}" === get_setting_value($setting) ? 'selected="selected"' : ''
                            ); 
                    	}
                    }

                    echo '</select>';
                    
                    $b_option_id = $setting['main_setting_name'].'_'.$setting['sub_setting_name'].'_b';
					$i_option_id = $setting['main_setting_name'].'_'.$setting['sub_setting_name'].'_i';
					$tt_option_id = $setting['main_setting_name'].'_'.$setting['sub_setting_name'].'_tt';
					$u_option_id = $setting['main_setting_name'].'_'.$setting['sub_setting_name'].'_u';

                    ?>
					<div class="tm-bittu-buttons" style="display:inline-block;">
						<span class="tm-bittu-button">
							<button type="button" id="<?php echo $b_option_id; ?>" onclick="updateBittuInput(this.getAttribute('id'))" down="<?php echo get_setting_value($setting,'b');?>">B</button>
							<input id="<?php echo $b_option_id.'_input'; ?>" type="hidden" name="<?php echo $b_option_id;?>" value="<?php echo get_setting_value($setting,'b');?>">
						</span>
						<span class="tm-bittu-button">
							<button type="button" id="<?php echo $i_option_id; ?>" onclick="updateBittuInput(this.getAttribute('id'))" down="<?php echo get_setting_value($setting,'i');?>">I</button>
							<input id="<?php echo $i_option_id.'_input'; ?>" type="hidden" name="<?php echo $i_option_id;?>" value="<?php echo get_setting_value($setting,'i');?>">
						</span>
						<span class="tm-bittu-button">
							<button type="button" id="<?php echo $tt_option_id; ?>" onclick="updateBittuInput(this.getAttribute('id'))" down="<?php echo get_setting_value($setting,'tt');?>">TT</button>
							<input id="<?php echo $tt_option_id.'_input'; ?>" type="hidden" name="<?php echo $tt_option_id;?>" value="<?php echo get_setting_value($setting,'tt');?>">
						</span>
						<span class="tm-bittu-button">
							<button type="button" id="<?php echo $u_option_id; ?>" onclick="updateBittuInput(this.getAttribute('id'))" down="<?php echo get_setting_value($setting,'u');?>">U</button>
							<input id="<?php echo $u_option_id.'_input'; ?>" type="hidden" name="<?php echo $u_option_id;?>" value="<?php echo get_setting_value($setting,'u');?>">
						</span>
					</div>

					<?php
                    
                	break;

                // Text
                default:
                    printf(
                        '<input type="text" id="%1$s" name="%1$s" placeholder="%2$s" value="%3$s" />',
                        esc_attr( $setting['id'] ),
                        esc_attr( $setting['std'] ),
                        esc_attr( get_setting_value($setting) )
                    );
                    break;
            }     
	}


	// Form Processing
	$post = (!empty($_POST)) ? true : false;
	if($post){
		save_form_settings($settings, $_POST);
	}
?>