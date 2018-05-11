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
function add_cp_dual_color_shape_presets( $presets ) {

	$shape_presets = array(
		array(
			'name' => 'shape_1',
			'tags' => 'shape,dual',
		),
		array(
			'name'           => 'shape_3',
			'tags'           => 'shape,dual',
			'preset_setting' => array(
				'width'  => array(
					'value' => '50',
				),
				'height' => array(
					'value' => '60',
				),
			),
		),
		array(
			'name' => 'shape_4',
			'tags' => 'shape,dual',
		),
		array(
			'name' => 'shape_5',
			'tags' => 'shape,dual',
		),
		array(
			'name' => 'shape_6',
			'tags' => 'shape,dual',
		),
		array(
			'name' => 'shape_7',
			'tags' => 'shape,dual',
		),
	);

	$presets = array_merge( $shape_presets, $presets );

		return $presets;
}

add_filter( 'cp_dual_color_shape_presets', 'add_cp_dual_color_shape_presets', 9, 1 );
