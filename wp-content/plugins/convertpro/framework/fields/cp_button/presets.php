<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

/**
 * Add Field.
 *
 * @param string $presets presets.
 * @since 1.0.0
 */
function add_cp_button_presets( $presets ) {

	$button_presets = array(
		'START_HERE' => array(
			'title'                   => array(
				'value' => __( 'START HERE', 'convertpro' ),
			),
			'button_text_color'       => array(
				'value'     => '#fff',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_text_hover_color' => array(
				'value'     => '#fff',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_back_color'       => array(
				'value'     => '#3498DB',
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'button_back_color_hover' => array(
				'value'     => '#2980B9',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'font_size'               => array(
				'value'     => '14px',
				'map_style' => array(
					'parameter' => 'font-size',
				),
			),
			'border_style'            => array(
				'value'     => 'none',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
			'border_width'            => array(
				'value'     => '1|1|1|1|px',
				'map_style' => array(
					'parameter' => 'border-width',
				),
			),
		),
		'SIGN_UP!'   => array(
			'title'                   => array(
				'value' => __( 'SIGN UP!', 'convertpro' ),
			),
			'button_text_color'       => array(
				'value'     => '#fff',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_text_hover_color' => array(
				'value'     => '#fff',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_back_color'       => array(
				'value'     => '#34495E',
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'button_back_color_hover' => array(
				'value'     => '#2C3E50',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'border_style'            => array(
				'value'     => 'none',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
			'border_width'            => array(
				'value'     => '1|1|1|1|px',
				'map_style' => array(
					'parameter' => 'border-width',
				),
			),
			'border_radius'           => array(
				'value'     => '20|20|20|20|px',
				'map_style' => array(
					'parameter' => 'border-radius',
				),
			),
			'font_size'               => array(
				'value'     => '14px',
				'map_style' => array(
					'parameter' => 'font-size',
				),
			),
		),
		'SUBSCRIBE'  => array(
			'title'                   => array(
				'value' => __( 'SUBSCRIBE', 'convertpro' ),
			),
			'button_text_color'       => array(
				'value'     => '#1ABC9C',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_text_hover_color' => array(
				'value'     => '#fff',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_back_color'       => array(
				'value'     => 'transparent',
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'button_back_color_hover' => array(
				'value'     => '#16A085',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'border_style'            => array(
				'value'     => 'solid',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
			'border_color'            => array(
				'value'     => '#1ABC9C',
				'map_style' => array(
					'parameter' => 'border-color',
				),
			),
			'border_hover_color'      => array(
				'value'     => '#16A085',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'border-color',
				),
			),
			'border_width'            => array(
				'value'     => '2|2|2|2|px',
				'map_style' => array(
					'parameter' => 'border-width',
				),
			),
			'font_size'               => array(
				'value'     => '14px',
				'map_style' => array(
					'parameter' => 'font-size',
				),
			),
		),
		'DOWNLOAD'   => array(
			'title'                   => array(
				'value' => __( 'DOWNLOAD', 'convertpro' ),
			),
			'button_text_color'       => array(
				'value'     => '#9B59B6',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_text_hover_color' => array(
				'value'     => '#fff',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_back_color'       => array(
				'value'     => 'transparent',
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'button_back_color_hover' => array(
				'value'     => '#8E44AD',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'border_style'            => array(
				'value'     => 'solid',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
			'border_color'            => array(
				'value'     => '#9B59B6',
				'map_style' => array(
					'parameter' => 'border-color',
				),
			),
			'border_hover_color'      => array(
				'value'     => '#8E44AD',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'border-color',
				),
			),
			'border_width'            => array(
				'value'     => '2|2|2|2|px',
				'map_style' => array(
					'parameter' => 'border-width',
				),
			),
			'border_radius'           => array(
				'value'     => '20|20|20|20|px',
				'map_style' => array(
					'parameter' => 'border-radius',
				),
			),
			'font_size'               => array(
				'value'     => '14px',
				'map_style' => array(
					'parameter' => 'font-size',
				),
			),
		),
		'UPGRADE'    => array(
			'title'                   => array(
				'value' => __( 'UPGRADE', 'convertpro' ),
			),
			'button_text_color'       => array(
				'value'     => '#2ECC71',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_back_color'       => array(
				'value'     => 'transparent',
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'button_text_hover_color' => array(
				'value'     => '#27AE60',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'border_style'            => array(
				'value'     => 'solid',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
			'border_width'            => array(
				'value'     => '2|2|2|2|px',
				'map_style' => array(
					'parameter' => 'border-width',
				),
			),
			'border_color'            => array(
				'value'     => '#2ECC71',
				'map_style' => array(
					'parameter' => 'border-color',
				),
			),
			'border_hover_color'      => array(
				'value'     => '#27AE60',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'border-color',
				),
			),
			'button_back_color_hover' => array(
				'value'     => 'transparent',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'font_size'               => array(
				'value'     => '14px',
				'map_style' => array(
					'parameter' => 'font-size',
				),
			),
		),
		'UPLOAD'     => array(
			'title'                   => array(
				'value' => __( 'UPLOAD', 'convertpro' ),
			),
			'button_text_color'       => array(
				'value'     => '#E67E22',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'button_back_color'       => array(
				'value'     => 'transparent',
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'button_text_hover_color' => array(
				'value'     => '#D35400',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'border_hover_color'      => array(
				'value'     => '#D35400',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'border-color',
				),
			),
			'border_style'            => array(
				'value'     => 'solid',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
			'border_color'            => array(
				'value'     => '#E67E22',
				'map_style' => array(
					'parameter' => 'border-color',
				),
			),
			'border_width'            => array(
				'value'     => '2|2|2|2|px',
				'map_style' => array(
					'parameter' => 'border-width',
				),
			),
			'button_back_color_hover' => array(
				'value'     => 'transparent',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'background',
				),
			),
			'border_radius'           => array(
				'value'     => '20|20|20|20|px',
				'map_style' => array(
					'parameter' => 'border-radius',
				),
			),
			'font_size'               => array(
				'value'     => '14px',
				'map_style' => array(
					'parameter' => 'font-size',
				),
			),
		),
	);

	$presets = array_merge( $button_presets, $presets );

		return $presets;
}

add_filter( 'cp_button_presets', 'add_cp_button_presets', 9, 1 );
