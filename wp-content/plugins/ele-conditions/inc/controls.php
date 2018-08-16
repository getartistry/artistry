<?php

require_once ELECONDITIONS_DIR.'inc/parse_conditions.php';

add_action( 'elementor/element/before_section_start', function( $element, $section_id, $args ) {
   /** @var \Elementor\Element_Base $element */
   if ( '_section_responsive' === $section_id ) {

    $element->start_controls_section(
      'conditional_section',
      [
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'label' => __( 'Condition', 'elementor' ),
      ]
    );

    $element->add_control(
      'element_condition',
      [
      'type' => \Elementor\Controls_Manager::TEXTAREA,
      'label' => __( 'Write your conditions:', 'elementor' ),
      ]
    );
     
    $element->add_control(
    'element_condition_info',
    [
      'label' => __( 'Currently the conditions work with 2 operands and 1 operator ( == , != , === , !== , < , > , <= , >= )' ),
      'type' => \Elementor\Controls_Manager::RAW_HTML,
    ]
    );
     
    $element->add_control(
      'element_condition_debug',
      [
        'label' => __( 'Debug mode', 'elementor-pro' ),
        'type' =>  \Elementor\Controls_Manager::SWITCHER,
        'default' => '',
        'label_on' => __( 'On', 'elementor' ),
        'label_off' => __( 'Off', 'elementor' ),
      ]
    );

    $element->add_control(
      'element_condition_debug_info',
      [
        'label' => __( 'For change to be visible unfocus the element (click on another element) and focus again.<ul><li style="background:lightpink">Red - unchanged</li> <li style="background:lightgreen">Green - changed</lu></ul>You can add your own variables, ie. hello: <small><br/>add_filter( "eleconditions_vars",function($custom_vars){<br/>$custom_vars["hello"]="Hello World!";<br/> return $custom_vars;<br/>});</small>' ),
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'condition' => [
          'element_condition_debug!' => '',
        ],
      ]
    );

    $element->end_controls_section();
   }
}, 10, 3 );

/* if the condition is false we delete the content. Later maybe this should be stoped sooner, but for now I'll use it like this.*/
add_action( 'elementor/widget/render_content', function( $content, $widget ) {
  $settings = $widget->get_active_settings(); 
  if ( !isset($settings['element_condition']) || $settings['element_condition']=='' ) return $content;
  if ( elecond_parse_condition($settings['element_condition'],$settings['element_condition_debug']) ) return $content;
  return '';
}, 10, 2 );

// CSS hide the sections and widget wrappers

function elecond_hide_element( \Elementor\Element_Base $element ) {

  $settings = $element->get_active_settings();

  if ( !isset($settings['element_condition']) || $settings['element_condition']=='' ) return ;
    if ( elecond_parse_condition($settings['element_condition'], $settings['element_condition_debug']) ) {
          return;
    }else{
        $style = $settings['element_condition_debug'] && ( current_user_can('editor') || current_user_can('administrator') ) ? "opacity:0.5; border: 3px solid red;" : "display:none;";
        $element->add_render_attribute( '_wrapper', [
            'style' => $style ,
        ]);
    }
}

add_action( 'elementor/frontend/widget/before_render', 'elecond_hide_element' );
add_action( 'elementor/frontend/section/before_render', 'elecond_hide_element' );