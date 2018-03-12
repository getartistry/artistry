<?php
class DLK_Builder_Module_Topic_List extends DLK_Builder_Module_Template {
	function init() {
		$this->name       = esc_html__( 'Topic List', 'et_builder' );
		$this->slug       = 'et_pb_ld_topic_list';
		$this->fb_support = false;

		$this->whitelisted_fields = array(
			'title',
			'num_topics',
			'topic_order',
			'topic_order_by',
			'col',
			'wp_cat',
			'topic_cat',
			'topic_categoryselector',
			'wp_tag',
			'topic_tag',
			'background_layout',
			'admin_label',
			'module_id',
			'module_class',
		);

		$this->fields_defaults = array(
			'topic_order'      => array('DESC'),
			'topic_order_by'      => array('ID'),
			'topic_categoryselector' => array('off'),
			'background_layout' => array( 'light' ),
			'topic_cat' => array( 'all' ),
			'wp_cat' => array( 'all' ),
			'topic_tag' => array( 'all' ),
			'wp_tag' => array( 'all' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_ld_topic_list';

		$this->options_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'General', 'et_builder' ),
					'topic_filters'     => esc_html__( 'Topic Filters', 'et_builder' ),
					'topic_order'     => esc_html__( 'Topic Order', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text' => array(
						'title'    => esc_html__( 'General', 'et_builder' ),
						'priority' => 49,
					),
				),
			),
		);

		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => esc_html__( 'Title', 'et_builder' ),
					'css'      => array(
						'main'      => "{$this->main_css_element} h3",
						'important' => 'plugin_only',
					),
				),
			),
			'background' => array(
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'border' => array(),
			'custom_margin_padding' => array(
				'use_margin' => false,
				'css' => array(
					'important' => 'all',
				),
			),
		);

		if ( et_is_builder_plugin_active() ) {
			$this->advanced_options['fonts']['number']['css']['important'] = 'all';
		}
	}

	function get_fields() {
			
		$fields = array(
			'title' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input a title for the module.', 'et_builder' ),
				'toggle_slug'     => 'main_content',
			),
			
			'num_topics' => array(
				'label'       => esc_html__( 'Num Topics', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'The max number of topics to display.', 'et_builder' ),
				'toggle_slug' => 'main_content',
			),
			
			'topic_order' => array(
				'label'           => esc_html__( 'Topic Order', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'DESC' => esc_html__( 'Descending', 'et_builder' ),
					'ASC'  => esc_html__( 'Ascending', 'et_builder' ),
				),
				'toggle_slug'     => 'topic_order',
				'description'     => esc_html__( 'Choose the order of topics in the user profile.', 'et_builder' ),
			),
			'topic_order_by' => array(
				'label'           => esc_html__( 'Topic Order By', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'ID' => esc_html__( 'ID', 'et_builder' ),
					'title'  => esc_html__( 'Title', 'et_builder' ),
				),
				'toggle_slug'     => 'topic_order',
				'description'     => esc_html__( 'Choose the field to order topics by.', 'et_builder' ),
			),
			
			'col' => array(
				'label'       => esc_html__( 'Num Cols', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Number of columns to show when using topic grid addon.', 'et_builder' ),
				'toggle_slug' => 'main_content',
			),
			
			'topic_cat' => array(
				'label'           => esc_html__( 'Topic Category', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => dlk_get_category_select_options('ld_topic_category'),
				'toggle_slug'     => 'topic_filters',
				'description'     => esc_html__( 'Show topics in given topic category.', 'et_builder' ),
				'default'=>'all'
			),
			
			'wp_cat' => array(
				'label'           => esc_html__( 'WordPress Category', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => dlk_get_category_select_options(),
				'toggle_slug'     => 'topic_filters',
				'description'     => esc_html__( 'Show topics in given WordPress category.', 'et_builder' ),
				'default'=>'all'
			),
			
			'topic_categoryselector' => array(
				'label'           => esc_html__( 'Category Selector', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Off', 'et_builder' ),
					'on'  => esc_html__( 'On', 'et_builder' ),
				),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__("Show a category dropdown menu.", 'et_builder' ),
			),
			
			
			
			'topic_tag' => array(
				'label'           => esc_html__( 'Topic Tag', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => dlk_get_tag_select_options('ld_topic_tag'),
				'toggle_slug'     => 'topic_filters',
				'description'     => esc_html__( 'Show topics with given topic tag.', 'et_builder' ),
				'default'=>'all'
			),
			'wp_tag' => array(
				'label'           => esc_html__( 'WordPress Tag', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => dlk_get_tag_select_options(),
				'toggle_slug'     => 'topic_filters',
				'description'     => esc_html__( 'Show topics with given WordPress tag.', 'et_builder' ),
				'default'=>'all'
			),
			'background_layout' => array(
				'label'           => esc_html__( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'         => array(
					'light' => esc_html__( 'Dark', 'et_builder' ),
					'dark'  => esc_html__( 'Light', 'et_builder' ),
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'text',
				'description'     => esc_html__( 'Here you can choose whether your title text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'disabled_on' => array(
				'label'           => esc_html__( 'Disable on', 'et_builder' ),
				'type'            => 'multiple_checkboxes',
				'options'         => array(
					'phone'   => esc_html__( 'Phone', 'et_builder' ),
					'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
					'desktop' => esc_html__( 'Desktop', 'et_builder' ),
				),
				'additional_att'  => 'disable_on',
				'option_category' => 'configuration',
				'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'visibility',
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
				'toggle_slug' => 'admin_label',
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'classes',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'classes',
				'option_class'    => 'et_pb_custom_css_regular',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {

		$num_topics      = $this->shortcode_atts['num_topics'];
		$topic_order      = $this->shortcode_atts['topic_order'];
		$topic_order_by      = $this->shortcode_atts['topic_order_by'];
		$col      = $this->shortcode_atts['col'];
		$wp_cat      = $this->shortcode_atts['wp_cat'];
		$topic_cat      = $this->shortcode_atts['topic_cat'];
		$topic_categoryselector      = $this->shortcode_atts['topic_categoryselector'];
		$wp_tag      = $this->shortcode_atts['wp_tag'];
		$topic_tag      = $this->shortcode_atts['topic_tag'];
		$title             = $this->shortcode_atts['title'];
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];
		$background_layout = $this->shortcode_atts['background_layout'];

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$video_background = $this->video_background();
		$parallax_image_background = $this->get_parallax_image_background();

		$classes = esc_attr(implode(' ', array(
			'et_pb_module',
			'et_pb_ld_module', 
			'et_pb_ld_topic_list',
			"et_pb_bg_layout_{$background_layout}",
			$module_class,
			('' !== $video_background ? 'et_pb_section_video et_pb_preload' : ''),
			('' !== $parallax_image_background ? 'et_pb_section_parallax' : '')
		)));
		
		$topic_categoryselector = ($topic_categoryselector == 'on')?'true':'false';
		$col = empty($col)?'':' col="'.esc_attr($col).'"';
		$wp_cat = ('all' === $wp_cat)?'':' cat="'.esc_attr($wp_cat).'"';
		$topic_cat = ('all' === $topic_cat)?'':' topic_cat="'.esc_attr($topic_cat).'"';
		$wp_tag = ('all' === $wp_tag)?'':' tag_id="'.esc_attr($wp_tag).'"';
		$topic_tag = ('all' === $topic_tag)?'':' topic_tag_id="'.esc_attr($topic_tag).'"';
	
		$id = ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' );
		$title = ( '' !== $title ? '<h3>' . esc_html( $title ) . '</h3>' : '' );
		$shortcode = '[ld_topic_list num="'.esc_attr($num_topics).'" order="'.esc_attr($topic_order).'" orderby="'.esc_attr($topic_order_by).'" '.$col.$topic_cat.$wp_cat.' topic_categoryselector="'.esc_attr($topic_categoryselector).'"'.$topic_tag.$wp_tag.']';

		$shortcode_output = do_shortcode($shortcode);
		
		$output = "<div{$id} class=\"{$classes}\">{$video_background} {$parallax_image_background} {$title}  {$shortcode_output} </div>";

		return $output;
	}
}
new DLK_Builder_Module_Topic_List;