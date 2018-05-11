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
function add_cp_gradient_button_presets( $presets ) {

	$button_presets = array(
		'SUBSCRIBE' => array(
			'title'                           => array(
				'value' => __( 'SUBSCRIBE', 'convertpro' ),
			),
			'btn_title_color'                 => array(
				'value'     => '#fff',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'btn_title_color_hover'           => array(
				'value'     => '#fff',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'btn_back_color'                  => array(
				'value'     => '#ef4040',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg1',
				),
			),
			'sec_btn_back_color'              => array(
				'value'     => '#8e0000',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg2',
				),
			),
			'btn_loc_color'                   => array(
				'value'     => '0',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc1',
				),
			),
			'secloc_back_color'               => array(
				'value'     => '100',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc2',
				),
			),
			'btn_gradient_types'              => array(
				'value'     => 'lineargradient',
				'map_style' => array(
					'parameter' => 'btn-gradient-type',
				),
			),
			'btn_gradient_radial_dir'         => array(
				'value'     => 'center_center',
				'map_style' => array(
					'parameter' => 'btn-gradient-rad-dir',
				),
			),
			'btn_gradient_angle'              => array(
				'value'     => 180,
				'map_style' => array(
					'parameter' => 'btn-gradient-angle',
				),
			),

			'btn_back_color_hover'            => array(
				'value'     => '#ef4040',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg1-hover',
				),
			),
			'sec_btn_back_color_hover'        => array(
				'value'     => '#8e0000',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg2-hover',
				),
			),
			'btn_loc_color_hover'             => array(
				'value'     => '0',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc1-hover',
				),
			),
			'secloc_back_color_hover'         => array(
				'value'     => '100',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc2-hover',
				),
			),
			'btn_gradient_types_hover'        => array(
				'value'     => 'lineargradient',
				'map_style' => array(
					'parameter' => 'btn-gradient-type-hover',
				),
			),
			'btn_gradient_linear_angle_hover' => array(
				'value'     => 0,
				'map_style' => array(
					'parameter' => 'btn-gradient-angle-hover',
				),
			),

			'border_style'                    => array(
				'value'     => 'none',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
		),
		'DOWNLOAD'  => array(
			'title'                           => array(
				'value' => __( 'DOWNLOAD', 'convertpro' ),
			),
			'btn_title_color'                 => array(
				'value'     => '#fff',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'btn_title_color_hover'           => array(
				'value'     => '#fff',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'btn_back_color'                  => array(
				'value'     => '#426c84',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg1',
				),
			),
			'sec_btn_back_color'              => array(
				'value'     => '#2b354f',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg2',
				),
			),
			'btn_loc_color'                   => array(
				'value'     => '0',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc1',
				),
			),
			'secloc_back_color'               => array(
				'value'     => '100',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc2',
				),
			),
			'btn_gradient_types'              => array(
				'value'     => 'lineargradient',
				'map_style' => array(
					'parameter' => 'btn-gradient-type',
				),
			),
			'btn_gradient_angle'              => array(
				'value'     => 180,
				'map_style' => array(
					'parameter' => 'btn-gradient-angle',
				),
			),

			'btn_back_color_hover'            => array(
				'value'     => '#426c84',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg1-hover',
				),
			),
			'sec_btn_back_color_hover'        => array(
				'value'     => '#2b354f',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg2-hover',
				),
			),
			'btn_loc_color_hover'             => array(
				'value'     => '0',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc1-hover',
				),
			),
			'secloc_back_color_hover'         => array(
				'value'     => '100',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc2-hover',
				),
			),
			'btn_gradient_types_hover'        => array(
				'value'     => 'lineargradient',
				'map_style' => array(
					'parameter' => 'btn-gradient-type-hover',
				),
			),
			'btn_gradient_linear_angle_hover' => array(
				'value'     => 0,
				'map_style' => array(
					'parameter' => 'btn-gradient-angle-hover',
				),
			),

			'border_style'                    => array(
				'value'     => 'none',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
		),
		'UPGRADE'   => array(
			'title'                         => array(
				'value' => __( 'UPGRADE', 'convertpro' ),
			),
			'btn_title_color'               => array(
				'value'     => '#fff',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'btn_title_color_hover'         => array(
				'value'     => '#fff',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'btn_back_color'                => array(
				'value'     => '#3498db',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg1',
				),
			),
			'sec_btn_back_color'            => array(
				'value'     => '#006cb5',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg2',
				),
			),
			'btn_loc_color'                 => array(
				'value'     => '0',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc1',
				),
			),
			'secloc_back_color'             => array(
				'value'     => '100',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc2',
				),
			),
			'btn_gradient_types'            => array(
				'value'     => 'radialgradient',
				'map_style' => array(
					'parameter' => 'btn-gradient-type',
				),
			),
			'btn_gradient_radial_dir'       => array(
				'value'     => 'bottom_right',
				'map_style' => array(
					'parameter' => 'btn-gradient-rad-dir',
				),
			),
			'btn_gradient_angle'            => array(
				'value'     => 0,
				'map_style' => array(
					'parameter' => 'btn-gradient-angle',
				),
			),

			'btn_back_color_hover'          => array(
				'value'     => '#3498db',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg1-hover',
				),
			),
			'sec_btn_back_color_hover'      => array(
				'value'     => '#006cb5',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg2-hover',
				),
			),
			'btn_loc_color_hover'           => array(
				'value'     => '0',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc1-hover',
				),
			),
			'secloc_back_color_hover'       => array(
				'value'     => '100',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc2-hover',
				),
			),
			'btn_gradient_types_hover'      => array(
				'value'     => 'radialgradient',
				'map_style' => array(
					'parameter' => 'btn-gradient-type-hover',
				),
			),
			'btn_gradient_radial_dir_hover' => array(
				'value'     => 'top_left',
				'map_style' => array(
					'parameter' => 'btn-gradient-rad-dir-hover',
				),
			),

			'border_style'                  => array(
				'value'     => 'none',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
		),
		'UPLOAD'    => array(
			'title'                         => array(
				'value' => __( 'UPLOAD', 'convertpro' ),
			),
			'btn_title_color'               => array(
				'value'     => '#fff',
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'btn_title_color_hover'         => array(
				'value'     => '#fff',
				'onhover'   => true,
				'map_style' => array(
					'parameter' => 'color',
				),
			),
			'btn_back_color'                => array(
				'value'     => '#2ecc71',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg1',
				),
			),
			'sec_btn_back_color'            => array(
				'value'     => '#1c8e48',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg2',
				),
			),
			'btn_loc_color'                 => array(
				'value'     => '0',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc1',
				),
			),
			'secloc_back_color'             => array(
				'value'     => '100',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc2',
				),
			),
			'btn_gradient_types'            => array(
				'value'     => 'radialgradient',
				'map_style' => array(
					'parameter' => 'btn-gradient-type',
				),
			),
			'btn_gradient_radial_dir'       => array(
				'value'     => 'bottom_left',
				'map_style' => array(
					'parameter' => 'btn-gradient-rad-dir',
				),
			),
			'btn_gradient_angle'            => array(
				'value'     => 270,
				'map_style' => array(
					'parameter' => 'btn-gradient-angle',
				),
			),

			'btn_back_color_hover'          => array(
				'value'     => '#2ecc71',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg1-hover',
				),
			),
			'sec_btn_back_color_hover'      => array(
				'value'     => '#1c8e48',
				'map_style' => array(
					'parameter' => 'btn-gradient-bg2-hover',
				),
			),
			'btn_loc_color_hover'           => array(
				'value'     => '0',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc1-hover',
				),
			),
			'secloc_back_color_hover'       => array(
				'value'     => '100',
				'map_style' => array(
					'parameter' => 'btn-gradient-loc2-hover',
				),
			),
			'btn_gradient_types_hover'      => array(
				'value'     => 'radialgradient',
				'map_style' => array(
					'parameter' => 'btn-gradient-type-hover',
				),
			),
			'btn_gradient_radial_dir_hover' => array(
				'value'     => 'top_right',
				'map_style' => array(
					'parameter' => 'btn-gradient-rad-dir-hover',
				),
			),

			'border_style'                  => array(
				'value'     => 'none',
				'map_style' => array(
					'parameter' => 'border-style',
				),
			),
		),
	);

	$presets = array_merge( $button_presets, $presets );

		return $presets;
}

add_filter( 'cp_gradient_button_presets', 'add_cp_gradient_button_presets', 9, 1 );
