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
function add_cp_shape_presets( $presets ) {

	$shape_presets = array(
		array(
			'name'           => 'line05',
			'tags'           => 'shape,line,straight line',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'       => array(
					'value' => '200',
				),
				'shape_width' => array(
					'value' => '2',
				),
				'height'      => array(
					'value' => '2',
				),
			),
		),
		array(
			'name'           => 'line06',
			'tags'           => 'shape,line,straight line',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'       => array(
					'value' => '200',
				),
				'shape_width' => array(
					'value' => '2',
				),
				'height'      => array(
					'value' => '2',
				),
			),
		),
		array(
			'name'           => 'line07',
			'tags'           => 'shape,line,straight line',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'       => array(
					'value' => '200',
				),
				'shape_width' => array(
					'value' => '2',
				),
				'height'      => array(
					'value' => '2',
				),
			),
		),
		array(
			'name'           => 'triangle04',
			'tags'           => 'shape,triangle',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'square01',
			'tags'           => 'shape,square',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'       => array(
					'value' => '30',
				),
				'height'      => array(
					'value' => '30',
				),
				'shape_width' => array(
					'value' => '4',
				),
			),
		),
		array(
			'name'           => 'triangle01',
			'tags'           => 'shape,triangle',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'right_angle_triangle',
			'tags'           => 'shape,triangle,right,angle',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'square02',
			'tags'           => 'shape,square',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'hexagon01',
			'tags'           => 'shape,hexagon',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'circle',
			'tags'           => 'shape, circle, round',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'circle_thin',
			'tags'           => 'shape, circle,round',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'star_o',
			'tags'           => 'shape, star',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '31',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'star_half_empty',
			'tags'           => 'shape, star',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '32',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'star',
			'tags'           => 'shape, star',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '31',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'heart_o',
			'tags'           => 'shape, heart, love',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '34',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'heart',
			'tags'           => 'shape, heart, love',
			'section'        => 'shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '34',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'banner01',
			'tags'           => 'shape,banner,rectangle banner',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '34',
				),
				'height' => array(
					'value' => '21',
				),
			),
		),
		array(
			'name'           => 'banner02',
			'tags'           => 'shape,banner,circular banner',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'cloud_circle02',
			'tags'           => 'shape,cloud,circle,cloud circle',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'solid_circle01',
			'tags'           => 'shape,solid,circle,solid circle',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'certificate',
			'tags'           => 'shape, certificate',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tag04',
			'tags'           => 'shape,tag',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'banner04',
			'tags'           => 'shape,rectangular banner',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '34',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'speech_bubble01',
			'tags'           => 'shape,speech,bubble,speech bubble',
			'section'        => 'banner',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'speech_bubble02',
			'tags'           => 'shape,speech,bubble,speech bubble',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'speech_bubble03',
			'tags'           => 'shape,speech,bubble,speech bubble',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'speech_bubble04',
			'tags'           => 'shape,speech,bubble,speech bubble',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'speech_bubble05',
			'tags'           => 'shape,speech,bubble,speech bubble',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'comment',
			'tags'           => 'shape, comment, discussion',
			'section'        => 'banners',
			'preset_setting' => array(
				'width'  => array(
					'value' => '34',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'comment_o',
			'tags'           => 'shape, comment, discussion',
			'section'        => 'banners',
			'preset_setting' => array(
				'width'  => array(
					'value' => '34',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'comments',
			'tags'           => 'shape, comment, discussion',
			'section'        => 'banners',
			'preset_setting' => array(
				'width'  => array(
					'value' => '39',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'comments_o',
			'tags'           => 'shape, comment, discussion',
			'section'        => 'banners',
			'preset_setting' => array(
				'width'  => array(
					'value' => '39',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tag05',
			'tags'           => 'shape,tag',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tag07',
			'tags'           => 'shape,tag',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tag03',
			'tags'           => 'shape,tag',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '40',
				),
				'height' => array(
					'value' => '21',
				),
			),
		),
		array(
			'name'           => 'ribbon',
			'tags'           => 'shape,ribbon',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '40',
				),
				'height' => array(
					'value' => '21',
				),
			),
		),
		array(
			'name'           => 'banner03',
			'tags'           => 'shape,rectangular banner',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'bookmark',
			'tags'           => 'shape,banner, ribbon, bookmark',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '26',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'bookmark_o',
			'tags'           => 'shape,banner, ribbon, bookmark',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '26',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'corner_baner',
			'tags'           => 'shape,corner,banner,corner banner',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'banner05',
			'tags'           => 'shape,banner',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '40',
				),
				'height' => array(
					'value' => '21',
				),
			),
		),
		array(
			'name'           => 'tag01',
			'tags'           => 'shape,tag',
			'section'        => 'banners',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tags',
			'tags'           => 'shape, tag, label',
			'section'        => 'banners',
			'preset_setting' => array(
				'width'  => array(
					'value' => '38',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tag',
			'tags'           => 'shape,tag',
			'section'        => 'banner',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'facebook',
			'tags'           => 'shape, facebook, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '16',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'facebook_square',
			'tags'           => 'shape, facebook, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'snapchat_ghost',
			'tags'           => 'shape, snapchat, social, ghost',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '32',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'snapchat_square',
			'tags'           => 'shape, snapchat, social, ghost',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tumblr',
			'tags'           => 'shape, tumblr, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '17',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tumblr_square',
			'tags'           => 'shape, tumblr, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'google_plus',
			'tags'           => 'shape, google plus, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '47',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'google_plus_square',
			'tags'           => 'shape, google plus, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'pinterest_p',
			'tags'           => 'shape, pinterest, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '24',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'pinterest_square',
			'tags'           => 'shape, pinterest, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'twitter',
			'tags'           => 'shape, twitter, social, bird',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '36',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'twitter_square',
			'tags'           => 'shape, twitter, social, bird',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'youtube',
			'tags'           => 'shape, youtube, social, video',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '25',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'youtube_square',
			'tags'           => 'shape, youtube, social, video',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'linkedin',
			'tags'           => 'shape, linkedin, linked in, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '31',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'linkedin_square',
			'tags'           => 'shape, linkedin, linked in, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'share_alt',
			'tags'           => 'shape, share, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'share_alt_square',
			'tags'           => 'shape, share, social',
			'section'        => 'social-media',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'angle_up',
			'tags'           => 'shape, arrow, angle',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '18',
				),
			),
		),
		array(
			'name'           => 'angle_double_up',
			'tags'           => 'shape, arrow, angle',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '31',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'arrow_up',
			'tags'           => 'shape, arrow',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '32',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'arrow_circle_up',
			'tags'           => 'shape, arrow',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'arrow_circle_o_up',
			'tags'           => 'shape, arrow',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'chevron_circle_up',
			'tags'           => 'shape, chevron',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'mail_forward',
			'tags'           => 'shape, reply, forward, arrow, right arrow',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '33',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'mail_reply',
			'tags'           => 'shape, reply, forward, arrow, left arrow',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '33',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'long_arrow_up',
			'tags'           => 'shape, arrow, direction',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '14',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'caret_up',
			'tags'           => 'shape,caret',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '32',
				),
				'height' => array(
					'value' => '18',
				),
			),
		),
		array(
			'name'           => 'mouse_pointer',
			'tags'           => 'shape, select, pointer, mouse',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '19',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'repeat',
			'tags'           => 'shape, repeat, cycle',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'hand_o_up',
			'tags'           => 'shape, arrow, point, hand',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '26',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'hand_o_down',
			'tags'           => 'shape, arrow, point, hand',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '26',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'thumbs_o_up',
			'tags'           => 'shape, thumbs, thumbs up',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '28',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'thumbs_o_down',
			'tags'           => 'shape, thumbs, thumbs down',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '28',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'thumbs_up',
			'tags'           => 'shape, thumbs, thumbs up',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '31',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'thumbs_down',
			'tags'           => 'shape, thumbs, thumbs down',
			'section'        => 'arrow-shape',
			'preset_setting' => array(
				'width'  => array(
					'value' => '31',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'shopping_bag',
			'tags'           => 'shape, shopping, bag, cart',
			'section'        => 'ecommerce-payment',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'shopping_cart',
			'tags'           => 'shape, shopping, cart, bag',
			'section'        => 'ecommerce-payment',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'cc_visa',
			'tags'           => 'shape, card, payment, visa ',
			'section'        => 'ecommerce-payment',
			'preset_setting' => array(
				'width'  => array(
					'value' => '45',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'cc_amex',
			'tags'           => 'shape, card, payment, american express',
			'section'        => 'ecommerce-payment',
			'preset_setting' => array(
				'width'  => array(
					'value' => '45',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'cc_discover',
			'tags'           => 'shape, card, payment, discover ',
			'section'        => 'ecommerce-payment',
			'preset_setting' => array(
				'width'  => array(
					'value' => '45',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'cc_mastercard',
			'tags'           => 'shape, card, payment, mastercard',
			'section'        => 'ecommerce-payment',
			'preset_setting' => array(
				'width'  => array(
					'value' => '45',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'cc_ppl',
			'tags'           => 'shape,card, payment',
			'section'        => 'ecommerce-payment',
			'preset_setting' => array(
				'width'  => array(
					'value' => '45',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'cc_stripe',
			'tags'           => 'shape,card, payment, stripe',
			'section'        => 'ecommerce-payment',
			'preset_setting' => array(
				'width'  => array(
					'value' => '45',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'user',
			'tags'           => 'shape, user, name, person',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '25',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'user_o',
			'tags'           => 'shape, user, name, person',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '26',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'address_card',
			'tags'           => 'shape, address, address card, person, name',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '40',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'address_card_o',
			'tags'           => 'shape, address, address card, person, name',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '40',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'envelope',
			'tags'           => 'shape, envelope, email, social',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '39',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'envelope_o',
			'tags'           => 'shape, envelope, email, social',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '39',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'phone',
			'tags'           => 'shape, phone, mobile, telephone',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'map_marker',
			'tags'           => 'shape, map, marker, location',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '20',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'thumb_tack',
			'tags'           => 'shape, pin, tack, location, stick',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '20',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'area_chart',
			'tags'           => 'shape, chart, graph, line graph',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '40',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'line_chart',
			'tags'           => 'shape, chart, graph, line graph',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '40',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'pie_chart',
			'tags'           => 'shape, pie chart, chart, graph',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '31',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'asterisk',
			'tags'           => 'shape, asterisk, star',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '28',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'bolt',
			'tags'           => 'shape, bolt',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '15',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'camera',
			'tags'           => 'shape, camera, photo, video, image, pic',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '34',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'crosshairs',
			'tags'           => 'shape, crosshair, aim, point, accuracy',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'dot_circle_o',
			'tags'           => 'shape, crosshair, aim, point, accuracy',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'tint',
			'tags'           => 'shape, tint, drop, location, marker, map',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '20',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'clock_o',
			'tags'           => 'shape, clock, time, circle',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'hourglass_2',
			'tags'           => 'shape, hourglass, time, sand clock, clock',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '26',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'hourglass_3',
			'tags'           => 'shape, hourglass, time, sand clock, clock',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '26',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'cog',
			'tags'           => 'shape, cog, settings, advanced',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'exclamation_circle',
			'tags'           => 'shape, exclamation, warning',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'question',
			'tags'           => 'shape, question',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '22',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'smile_o',
			'tags'           => 'shape, smile, smiley',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'frown_o',
			'tags'           => 'shape, smile, smiley, frown',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'meh_o',
			'tags'           => 'shape, smile, smiley, meh',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'gift',
			'tags'           => 'shape, gift, surprise, fun',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '34',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'lock',
			'tags'           => 'shape, lock, surprise, paid',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '25',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'unlock_alt',
			'tags'           => 'shape, lock, unlock',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '22',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'home',
			'tags'           => 'shape, home, house, main, back',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '38',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'pencil',
			'tags'           => 'shape, pencil, write, content, note',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'play',
			'tags'           => 'shape, play, video, music',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '27',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'quote_left',
			'tags'           => 'shape, quote',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'quote_right',
			'tags'           => 'shape, quote',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'search',
			'tags'           => 'shape, search, zoom',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'check',
			'tags'           => 'shape, check, tick',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '39',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'check_square',
			'tags'           => 'shape, check, tick',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'close',
			'tags'           => 'shape, close, exit',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'times_circle',
			'tags'           => 'shape, close, circle',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'times_circle_o',
			'tags'           => 'shape, close, circle',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '30',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'times_rectangle',
			'tags'           => 'shape, close, rectangle',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'times_rectangle_o',
			'tags'           => 'shape, close, rectangle',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'half_triangle',
			'tags'           => 'half,triangle',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'ebook',
			'tags'           => 'ebook',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'smartphone',
			'tags'           => 'smartphone,phone,mobile',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
		array(
			'name'           => 'ipad',
			'tags'           => 'ipad',
			'section'        => 'others',
			'preset_setting' => array(
				'width'  => array(
					'value' => '35',
				),
				'height' => array(
					'value' => '30',
				),
			),
		),
	);

	$presets = array_merge( $shape_presets, $presets );

		return $presets;
}

add_filter( 'cp_shape_presets', 'add_cp_shape_presets', 9, 1 );
