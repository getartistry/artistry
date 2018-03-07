<?php

class USIN_Gravity_Forms_User_Registration{
	
	protected $addon_slug = 'gravityformsuserregistration';
	protected $gf_fields;
	protected $prefix;
	protected $json_fields = array();
	
	public function __construct($prefix){
		$this->prefix = $prefix;
	}
	
	/**
	 * Loads all of the registered Gravity Form user form fields.
	 * @return array containing the fields data, formatted as Users Insights fields
	 */
	public function get_form_fields(){
		
		if(!isset($this->gf_fields)){
			$fields = array();
			
			if(method_exists('GFAPI', 'get_feeds') && method_exists('GFAPI', 'get_form')){
				$feeds = GFAPI::get_feeds(null, null, $this->addon_slug);
				
				if(!empty($feeds)){
					foreach ($feeds as $feed ) {
						
						if(isset($feed['meta']) && !empty($feed['meta']['userMeta']) && isset($feed['form_id'])){
							$meta_fields = $feed['meta']['userMeta'];
							$form = GFAPI::get_form($feed['form_id']);
							
							if(!empty($form) && !empty($form['fields'])){
								foreach ($meta_fields as $mf) {
									$meta_key = $mf['key'] == 'gf_custom' ? $mf['custom_key'] : $mf['key'];
									
									if(!isset($fields[$meta_key])){
										//make sure that one meta key is added only once
										//even if the field exists in different forms
										
										if(is_numeric($mf['value'])){
											//find the form field by ID
											$matches = wp_list_filter($form['fields'], array('id'=>(int)$mf['value']));
											
											if(sizeof($matches)>0){
												$gf_field = array_shift($matches);
												
												$field = array('id'=>$meta_key);
												$field['name'] = !empty($gf_field->adminLabel) ? $gf_field->adminLabel : $gf_field->label;
											
												if($this->is_subfield($mf['value'])){
													$field['name'].= $this->get_list_subfield_name($gf_field, $mf['value']);
												}elseif($gf_field->type == 'list' && !empty($gf_field->choices)){
													$this->json_fields[]= $meta_key;
												}
												
												$field['type'] = $this->get_usin_field_type($gf_field->type);
												
												if($field['type'] == 'select'){
													//set the select fields options
													$field['filter'] = array('type'=>'select', 'options' => $this->get_field_options($gf_field));
												}elseif($field['type'] == 'date'){
													//set the years range options
													$field['filter'] = array('type'=>'date', 'yearsRange' => array(-100, 20));
												}
												
												$fields[$meta_key]= $field;
												
											}
											
										}
									}
								}
							}
						}
					}
				}
			}
			
			$this->gf_fields = $fields;
		}
		
		return $this->gf_fields;
	}
	
	public function get_json_fields(){
		return $this->json_fields;
	}
	
	
	/**
	 * Matches an Gravity Forms field type to Users Insights field type.
	 * @param  string $um_type the Gravity Form field type
	 * @return string          the corresponding Users Insights field type
	 */
	protected function get_usin_field_type($gf_type){
		switch ($gf_type) {
			case 'number':
			case 'quantity':
			case 'total':
				return 'number';
				break;
			case 'date':
				return 'date';
				break;
			case 'select':
			case 'radio':
				return 'select';
				break;
			case 'list':
			case 'multiselect':
			case 'checkbox':
				return 'multioption_text';
				break;
			default:
				return 'text';
				break;
		}
	}
	
	/**
	 * For select type fields (drop-down, radio, etc.), retrieves the registered
	 * options and converts them into a Users Insights option format.
	 * @param  object $gf_field The Gravity Form field object
	 * @return array           the options in a Users Insights format
	 */
	protected function get_field_options($gf_field){
		$options = array();
		
		if(!empty($gf_field->choices)){
			foreach ($gf_field->choices	as $ch) {
				$options[]=array('key'=>$ch['value'], 'val'=>$ch['text']);
			}
		}
		
		return $options;
	}
	
	/**
	 * For fields that contain subfields (such as List fields), when the subfield
	 * is registered as a separate field, retrieve the name of this subfield, as
	 * otherwise it would show the parent field name only.
	 * @param  object $gf_field the Gravity Form field object
	 * @param  string $field_id the subfield ID
	 * @return string           the subfield name if found or empty string otherwise
	 */
	protected function get_list_subfield_name($gf_field, $field_id){
		if(isset($gf_field->inputs)){
			//find the input element with the same id as $field_id
			$options = $gf_field->inputs;
			foreach ($gf_field->inputs as $input) {
				if($input['id'] == $field_id){
					return ' ('.$input['label'].')';
				}
			}
			
		}elseif(isset($gf_field->choices)){
			$options = $gf_field->choices;
			//get the index of the choice item
			//if the field ID is 20.3 , the index would be 3
			list($int,$dec)=explode('.', $field_id);
			if(isset($options[$dec])){
				return ' ('.$options[$dec]['text'].')';
			}
		}
		
		return '';
	}
	
	/**
	 * If the field ID is in a float format, such as 20.1 , it means that it is a subfield
	 * @param  [type]  $field_id [description]
	 * @return boolean           [description]
	 */
	protected function is_subfield($field_id) {
	    return is_float($field_id) || is_numeric($field_id) && ((float) $field_id != (int) $field_id);
	}
	
	public function format_json_field_data($val){
		$dec = json_decode($val);
		$vals = array();
		if(is_array($dec)){
			foreach ($dec as $val ) {
				if(is_object($val)){
					$subvals = array();
					foreach ($val as $k => $v) {
						if(!empty($v)){
							$subvals[]= "$k: $v";
						}
					}
					$vals[]=implode(', ', $subvals);
				}
			}
		}
		if(!empty($vals)){
			return implode(' | ', $vals);
		}
		return $val;
	}
	
}