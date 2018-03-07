<?php 

class USIN_Field_Defaults{
	
	public static function get_fields(){
		$fields = array(
			array(
				'name' => __('Username', 'usin'),
				'id' => 'username',
				'order' => 'ASC',
				'show' => true,
				'disableHide' => true,
				'fieldType' => 'personal',
				'filter' => array(
					'type' => 'text',
					'disallow_null' => true
				)
			),
			array(
				'name' => __('User Group', 'usin'),
				'id' => 'user_groups',
				'order' => 'DESC',
				'order' => false,
				'show' => true,
				'fieldType' => 'none',
				'filter' => array(
					'type' => 'include_exclude_with_nulls',
					'options' => USIN_Groups::get_all_groups()
				)
			),
			array(
				'name' => __('Display Name', 'usin'),
				'id' => 'name',
				'order' => 'ASC',
				'show' => false,
				'fieldType' => 'personal',
				'filter' => array(
					'type' => 'text'
				)
			),
			array(
				'name' => __('First Name', 'usin'),
				'id' => 'first_name',
				'order' => 'ASC',
				'show' => false,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text'
				)
			),
			array(
				'name' => __('Last Name', 'usin'),
				'id' => 'last_name',
				'order' => 'ASC',
				'show' => false,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text'
				)
			),
			array(
				'name' => __('E-mail', 'usin'),
				'id' => 'email',
				'order' => 'ASC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text',
					'disallow_null' => true
				)
			),
			array(
				'name' => __('Date Registered', 'usin'),
				'id' => 'registered',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'date',
					'yearsRange' => array(-10, 0)
				)
			),
			array(
				'name' => __('Role', 'usin'),
				'id' => 'role',
				'order' => false,
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'select',
					'options' => USIN_Helper::get_roles(),
					'disallow_null' => true
				)
			),
			array(
				'name' => __('Website', 'usin'),
				'id' => 'website',
				'order' => false,
				'show' => false,
				'filter' => array(
					'type' => 'text'
				)
			),
			array(
				'name' => __('Posts Created', 'usin'),
				'id' => 'posts',
				'order' => 'DESC',
				'show' => true,
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				)
			),
			array(
				'name' => __('Comments', 'usin'),
				'id' => 'comments',
				'order' => 'DESC',
				'show' => false,
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				)
			),
			array(
				'name' => __('Notes', 'usin'),
				'id' => 'notes_count',
				'order' => 'DESC',
				'show' => false,
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				)
			),
			array(
				'name' => __('Last seen', 'usin'),
				'id' => 'last_seen',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'date',
					'yearsRange' => array(-10, 0)
				)
			),
			array(
				'name' => __('Sessions', 'usin'),
				'id' => 'sessions',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'number'
				)
			),
			array(
				'name' => __('Browser', 'usin'),
				'id' => 'browser',
				'order' => 'ASC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text'
				),
				'module'=>'devices'
			),
			array(
				'name' => __('Browser Version', 'usin'),
				'id' => 'browser_version',
				'order' => 'DESC',
				'show' => false,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text'
				),
				'module'=>'devices'
			),
			array(
				'name' => __('Platform', 'usin'),
				'id' => 'platform',
				'order' => 'ASC',
				'show' => false,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text'
				),
				'module'=>'devices'
			),
			array(
				'name' => __('Country', 'usin'),
				'id' => 'country',
				'order' => 'ASC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text'
				),
				'module' => 'geolocation'
			),
			array(
				'name' => __('City', 'usin'),
				'id' => 'city',
				'order' => 'ASC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text'
				),
				'module' => 'geolocation'
			),
			array(
				'name' => __('Region', 'usin'),
				'id' => 'region',
				'order' => 'ASC',
				'show' => false,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'text'
				),
				'module' => 'geolocation'
			)
		);
		
		return $fields;
	}
	
	public static function get_field_types(){
		$field_types = array(
			'text' => array(
				'operators' => array(
					array('key' => 'contains' , 'val' => __('contains', 'usin')),
					array('key' => 'notcontains' , 'val' => __('does not contain', 'usin')),
					array('key' => 'is' , 'val' => __('is', 'usin')),
					array('key' => 'not' , 'val' => __('is not', 'usin')),
					array('key' => 'starts' , 'val' => __('starts with', 'usin')),
					array('key' => 'ends' , 'val' => __('ends with', 'usin')),
					array('key' => 'notnull' , 'val' => __('is set', 'usin')),
					array('key' => 'isnull' , 'val' => __('is not set', 'usin'))
				),
				'type' => 'text'
			),
			'number' => array(
				'operators' => array(
					array('key' => 'equals' , 'val' => __('is', 'usin')),
					array('key' => 'bigger' , 'val' => __('is bigger than', 'usin')),
					array('key' => 'smaller' , 'val' => __('is smaller than', 'usin')),
					array('key' => 'notnull' , 'val' => __('is set', 'usin')),
					array('key' => 'isnull' , 'val' => __('is not set', 'usin'))
				),
				'type' => 'number'
			),
			'select' => array(
				'operators' => array(
					array('key' => 'is' , 'val' => __('is', 'usin')),
					array('key' => 'not' , 'val' => __('is not', 'usin')),
					array('key' => 'notnull' , 'val' => __('is set', 'usin')),
					array('key' => 'isnull' , 'val' => __('is not set', 'usin'))
				),
				'type' => 'option'
			),
			'select_option' => array(
				'operators' => array(
					array('key' => 'custom' , 'val' => '')
				),
				'type' => 'option'
			),
			'include_exclude' => array(
				'operators' => array(
					array('key' => 'include' , 'val' => __('include', 'usin')),
					array('key' => 'exclude' , 'val' => __('exclude', 'usin'))
				),
				'type' => 'option'
			),
			'include_exclude_is' => array(
				'operators' => array(
					array('key' => 'include' , 'val' => __('is', 'usin')),
					array('key' => 'exclude' , 'val' => __('is not', 'usin'))
				),
				'type' => 'option'
			),
			'include_exclude_with_nulls' => array(
				'operators' => array(
					array('key' => 'include_wn' , 'val' => __('is', 'usin')),
					array('key' => 'exclude_wn' , 'val' => __('is not', 'usin')),
					array('key' => 'isset' , 'val' => __('is set', 'usin')),
					array('key' => 'notset' , 'val' => __('is not set', 'usin'))
				),
				'type' => 'option'
			),
			//multioption_text: can be used for fields that store the data in a
			//serialized format. A text field can be used to filter
			'multioption_text' => array(
				'operators' => array(
					array('key' => 'contains' , 'val' => __('contains', 'usin')),
					array('key' => 'notcontains' , 'val' => __('does not contain', 'usin')),
					array('key' => 'notnull' , 'val' => __('is set', 'usin')),
					array('key' => 'isnull' , 'val' => __('is not set', 'usin'))
				),
				'type' => 'text'
			),
			//serialized_option: can be used for option fields that store the data
			//in either plain text or serialized format. Filters will include a
			//set of available options to choose from.
			'serialized_option' => array(
				'operators' => array(
					array('key' => 'contains_ser' , 'val' => __('is', 'usin')),
					array('key' => 'notcontains_ser' , 'val' => __('is not', 'usin')),
					array('key' => 'notnull' , 'val' => __('is set', 'usin')),
					array('key' => 'isnull' , 'val' => __('is not set', 'usin'))
				),
				'type' => 'option'
			),
			//serialized_multioption: same as serialized_option, but is used for
			//fields that store one or more options. The only difference is the texts
			//of the operators in the filters.
			'serialized_multioption' => array(
				'operators' => array(
					array('key' => 'contains_ser' , 'val' => __('includes', 'usin')),
					array('key' => 'notcontains_ser' , 'val' => __('does not include', 'usin')),
					array('key' => 'notnull' , 'val' => __('is set', 'usin')),
					array('key' => 'isnull' , 'val' => __('is not set', 'usin'))
				),
				'type' => 'option'
			),
			'date' => array(
				'operators' => array(
					array('key' => 'lessthan' , 'val' => __('is less than', 'usin')),
					array('key' => 'morethan', 'val' => __('is more than', 'usin')),
					array('key' => 'exactly' , 'val' => __('is exactly', 'usin')),
					array('key' => 'bigger' , 'val' => __('is after', 'usin')),
					array('key' => 'equals' , 'val' => __('is on', 'usin')),
					array('key' => 'smaller' , 'val' => __('is before', 'usin')),
					array('key' => 'notnull' , 'val' => __('is set', 'usin')),
					array('key' => 'isnull' , 'val' => __('is not set', 'usin'))
				),
				'type' => 'date'
			)
		);
		
		return $field_types;
	}
	
}