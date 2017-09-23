<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

$steps = array(
	'login'    => array(
		'label' => _x( 'Login', 'Frontend: Step title', 'yith-woocommerce-multi-step-checkout' ),
		'icon'  => 'icon'
	),
	'billing'  => array(
		'label' => _x( 'Billing', 'Frontend: Step title', 'yith-woocommerce-multi-step-checkout' ),
		'icon'  => 'icon'
	),
	'shipping' => array(
		'label' => _x( 'Shipping', 'Frontend: Step title', 'yith-woocommerce-multi-step-checkout' ),
		'icon'  => 'icon'
	),
	'order'    => array(
		'label' => _x( 'Order info', 'Frontend: Step title', 'yith-woocommerce-multi-step-checkout' ),
		'icon'  => 'icon'
	),
	'payment'  => array(
		'label' => _x( 'Payment info', 'Frontend: Step title', 'yith-woocommerce-multi-step-checkout' ),
		'icon'  => 'icon'
	)
);

$icon = _x( 'Icon', 'Admin: part of label string, i.e. Login Icon, Billing Icon', 'yith-woocommerce-multi-step-checkout' );

$buttons = array(
	'next'       => _x( 'Next', 'Frontend: button label', 'yith-woocommerce-multi-step-checkout' ),
	'prev'       => _x( 'Previous', 'Frontend: button label', 'yith-woocommerce-multi-step-checkout' ),
	'skip_login' => _x( 'Skip Login', 'Frontend: button label', 'yith-woocommerce-multi-step-checkout' ),
);

$options = array(

	'timeline' => array(

		'timeline_template_options_start' => array(
			'type' => 'sectionstart',
		),

		'timeline_template_options_title' => array(
			'title' => _x( 'Timeline style', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
			'type'  => 'title',
		),

		'timeline_template_options_style' => array(
			'type'    => 'yith_timeline_template_style',
			'title'   => _x( 'Timeline style', 'Option: title', 'yith-woocommerce-multi-step-checkout' ),
			'desc'    => _x( 'Select style for the timeline', 'Option: description', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_template',
			'default' => 'text',
			'options' => array(
				'text'   => _x( 'Text', 'Option: Timeline Style', 'yith-woocommerce-multi-step-checkout' ),
				'style1' => _x( 'Style 1', 'Option: Timeline Style', 'yith-woocommerce-multi-step-checkout' ),
				'style2' => _x( 'Style 2', 'Option: Timeline Style', 'yith-woocommerce-multi-step-checkout' ),
				'style3' => _x( 'Style 3', 'Option: Timeline Style', 'yith-woocommerce-multi-step-checkout' ),
			),
			'css'     => 'width: 170px'
		),

		'timeline_style_options_type' => array(
			'type'    => 'select',
			'title'   => _x( 'Display', 'Option: Title', 'yith-woocommerce-multi-step-checkout' ),
			'desc'    => _x( 'Select timeline display type', 'Option: description', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_display',
			'options' => array(
				'horizontal' => _x( 'Horizontal', 'Option: timeline display', 'yith-woocommerce-multi-step-checkout' ),
				'vertical'   => _x( 'Vertical', 'Option: timeline display', 'yith-woocommerce-multi-step-checkout' ),
			),
			'default' => 'horizontal',
			'css'     => 'width: 170px'
		),

		'timeline_transition_options_type' => array(
			'type'    => 'select',
			'title'   => _x( 'FadeIn and FadeOut Transition duration', 'Option: Title. Please, do not translate FadeIn/FadeOut', 'yith-woocommerce-multi-step-checkout' ),
			'desc'    => _x( 'A number determining how long the animation will run.Durations are given in milliseconds; higher values indicate slower animations, not faster ones.', 'Option: description', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_fade_duration',
			'options' => array(
				'0'    => 0,
				'100'  => 100,
				'200'  => 200,
				'300'  => 300,
				'400'  => 400,
				'500'  => 500,
				'600'  => 600,
				'700'  => 700,
				'800'  => 800,
				'900'  => 900,
				'1000' => 1000,
			),
			'default' => '200',
			'css'     => 'width: 170px'
		),

		'timeline_template_options_end' => array(
			'type' => 'sectionend',
		),

		'timeline_style1_options_start' => array(
			'type' => 'sectionstart',
		),

		'timeline_style1_options_title' => array(
			'title'    => _x( 'Timeline style 1 Customization', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
			'type'     => 'yith_wcms_title',
			'id'       => 'yith_wcms_timeline_style1_options_title',
			'refer_to' => 'style1'
		),

		'timeline_style1_background_color' => array(
			'title'   => _x( 'Step color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#e2e2e2',
			'desc'    => _x( 'Select background color for timeline steps (Default: #e2e2e2)', 'Frontend option: Single step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style1_background_color'
		),

		'timeline_style1_step_background_color' => array(
			'title'   => _x( 'Box color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#b2b2b0',
			'desc'    => _x( 'Select color for number box of timeline steps (Default: #b2b2b0)', 'Frontend option: step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style1_step_background_color'
		),

		'timeline_style1_step_number_color' => array(
			'title'   => _x( 'Step number color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#ffffff',
			'desc'    => _x( 'Select color for the numbers identifying timeline steps (Default: #ffffff)', 'Frontend option: Bubble number color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style1_step_color'
		),

		'timeline_style1_label_color' => array(
			'title'   => _x( 'Step label color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#8b8b8b',
			'desc'    => _x( 'Select color for step label (Default: #8b8b8b)', 'Frontend option: Step label color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style1_step_label_color'
		),

		'timeline_style1_current_background_color' => array(
			'title'   => _x( 'Step color of current step', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#1e8cbe',
			'desc'    => _x( 'Select the background color of the step users are currently in (Default: #1e8cbe)', 'Frontend option: Single step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style1_active_background_color'
		),

		'timeline_style1_current_step_background_color' => array(
			'title'   => _x( 'Box color of current step ', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#005b84',
			'desc'    => _x( 'Select color for the box containing the number or icon of the step users are currently in (Default: #005b84)', 'Frontend option: Bubble step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style1_current_step_background_color'
		),

		'timeline_style1_current_step_number_color' => array(
			'title'   => _x( 'Color of current step number', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#ffffff',
			'desc'    => _x( 'Select the color for the number of the step users are currently in (Default: #ffffff)', 'Frontend option: Bubble number color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style1_current_step_color'
		),

		'timeline_style1_current_label_color' => array(
			'title'   => _x( 'Current step label color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#ffffff',
			'desc'    => _x( 'Select color for the label of the step users are currently in (Default: #ffffff)', 'Frontend option: Step label color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style1_current_step_label_color'
		),

		'timeline_style1_template_options_end' => array(
			'type' => 'sectionend',
		),

		'timeline_style2_options_start' => array(
			'type' => 'sectionstart',
		),

		'timeline_style2_options_title' => array(
			'title'    => _x( 'Customize timeline style 2', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
			'type'     => 'yith_wcms_title',
			'id'       => 'yith_wcms_timeline_style2_options_title',
			'refer_to' => 'style2'
		),

		'timeline_style2_border_color' => array(
			'title'   => _x( 'Border Color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#eeeeee',
			'desc'    => _x( 'Select border color for timeline steps (Default: #eeeeee)', 'Frontend option: Single step border color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_border_color'
		),

		'timeline_style2_background_color' => array(
			'title'   => _x( 'Background Color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => 'transparent',
			'desc'    => _x( 'Select background color for timeline steps (Default: transparent)', 'Frontend option: Single step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_background_color'
		),

		'timeline_style2_bubble_background_color' => array(
			'title'   => _x( 'Bubble Background Color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#b2b2b0',
			'desc'    => _x( 'Select background color for bubbles of timeline steps (Default: #b2b2b0)', 'Frontend option: Bubble step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_bubble_background_color'
		),

		'timeline_style2_bubble_number_color' => array(
			'title'   => _x( 'Number Color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#ffffff',
			'desc'    => _x( 'Select color for the number identifying timeline (bubble) steps (Default: #ffffff)', 'Frontend option: Bubble number color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_bubble_color'
		),

		'timeline_style2_label_color' => array(
			'title'   => _x( 'Step Label Color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#b2b2b0',
			'desc'    => _x( 'Select color for step labels (Default: #b2b2b0)', 'Frontend option: Step label color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_step_color'
		),

		'timeline_style2_current_background_color' => array(
			'title'   => _x( 'Background Color of Current Step ', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => 'transparent',
			'desc'    => _x( 'Select background color for the step users are currently in (Default: transparent)', 'Frontend option: Single step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_active_background_color'
		),

		'timeline_style2_current_bubble_background_color' => array(
			'title'   => _x( 'Bubble Background Color of current step', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#acc327',
			'desc'    => _x( 'Select background color for the bubble of the step users are currently in (Default: #acc327)', 'Frontend option: Bubble step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_current_bubble_background_color'
		),

		'timeline_style2_current_bubble_number_color' => array(
			'title'   => _x( 'Number Color of current step', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#ffffff',
			'desc'    => _x( 'Select color for the number in the bubble identifying the step users are currently in (Default: #ffffff)', 'Frontend option: Bubble number color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_current_bubble_color'
		),

		'timeline_style2_current_label_color' => array(
			'title'   => _x( 'Label Color of Current Step', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#303030',
			'desc'    => _x( 'Select color for label of the step users are currently in (Default: #303030)', 'Frontend option: Step label color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style2_current_step_color'
		),

		'timeline_style2_template_options_end' => array(
			'type' => 'sectionend',
		),

		'timeline_style3_options_start' => array(
			'type' => 'sectionstart',
		),

		'timeline_style3_options_title' => array(
			'title'    => _x( 'Customize timeline style 3', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
			'type'     => 'yith_wcms_title',
			'id'       => 'yith_wcms_timeline_style3_options_title',
			'refer_to' => 'style3'
		),

		'timeline_style3_border_color' => array(
			'title'   => _x( 'Border Color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#e2e2e2',
			'desc'    => _x( 'Select border color for timeline steps (Default: #e2e2e2)', 'Frontend option: Single step border color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style3_border_color'
		),

		'timeline_style3_background_color' => array(
			'title'   => _x( 'Background Color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => 'transparent',
			'desc'    => _x( 'Select background color for timeline steps (Default: transparent)', 'Frontend option: Single step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style3_background_color'
		),

		'timeline_style3_label_color' => array(
			'title'   => _x( 'Step Label Color', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#b2b2b0',
			'desc'    => _x( 'Select color for step labels (Default: #b2b2b0)', 'Frontend option: Step label color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style3_step_color'
		),

		'timeline_style3_current_background_color' => array(
			'title'   => _x( 'Background Color of Current Step', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#1e8cbe',
			'desc'    => _x( 'Select background color for timeline step users are currently in (Default: #1e8cbe)', 'Frontend option: Single step background color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style3_active_background_color'
		),

		'timeline_style3_current_label_color' => array(
			'title'   => _x( 'Label Color of Current Step', 'Option Title', 'yith-woocommerce-multi-step-checkout' ),
			'type'    => 'color',
			'default' => '#ffffff',
			'desc'    => _x( 'Select color for the label of the step users are currently in (Default: #ffffff)', 'Frontend option: Step label color', 'yith-woocommerce-multi-step-checkout' ),
			'id'      => 'yith_wcms_timeline_style3_current_step_color'
		),

		'timeline_style3_template_options_end' => array(
			'type' => 'sectionend',
		),

		'timeline_options_start' => array(
			'type' => 'sectionstart',
		),

		'timeline_options_title' => array(
			'title' => _x( 'Timeline Customization', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
			'type'  => 'title',
		),
	)
);

foreach ( $steps as $step => $option ) {
	$k                         = 'timeline_options_label_' . $step;
	$options['timeline'][ $k ] = array(
		'title'   => $option['label'],
		'type'    => 'text',
		'id'      => 'yith_wcms_timeline_options_' . $step,
		'default' => $option['label'],
		'desc'    => sprintf( '%s: "%s"', _x( 'Select a label for the step', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ), $option['label'] )
	);
}

$options['timeline']['timeline_step_count_type'] = array(
	'title'   => _x( 'Step Count Type', 'Admin: Option title', 'yith-woocommerce-multi-step-checkout' ),
	'type'    => 'select',
	'id'      => 'yith_wcms_timeline_step_count_type',
	'desc'    => _x( 'Select style for step count: you can either use a number or an icon', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ),
	'options' => array(
		'number' => _x( 'Number', 'Admin: Select value', 'yith-woocommerce-multi-step-checkout' ),
		'icon'   => _x( 'Icon', 'Admin: Select value', 'yith-woocommerce-multi-step-checkout' )
	),
	'default' => 'number',
	'css'     => 'width: 170px;',
	'class'   => ''
);

$options['timeline']['timeline_remove_shipping_step'] = array(
    'title'   => _x( 'Remove shipping step', 'Admin: Option title', 'yith-woocommerce-multi-step-checkout' ),
    'type'    => 'checkbox',
    'id'      => 'yith_wcms_timeline_remove_shipping_step',
    'desc'    => _x( 'Enable this option to remove the shipping step on checkout page.', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ),
    'default' => 'no',
);

foreach ( $steps as $step => $option ) {
	$k                         = 'timeline_options_label_icon_' . $step;
	$options['timeline'][ $k ] = array(
		'title'             => $option['label'] . ' ' . $icon,
		'type'              => 'yith_wcms_media_upload',
		'id'                => 'yith_wcms_timeline_options_icon_' . $step,
		'default'           => yith_wcms_checkout_timeline_default_icon( $step ),
		'desc'              => sprintf( '%s: "%s"', _x( 'Select an icon for the step', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ), $option['label'] ),
		'placeholder'       => _x( 'Select image for your icon', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ),
		'class'             => 'yith_wcms_upload',
		'custom_attributes' => array(
			'data-step' => $step
		)
	);
}

$options['timeline']['timeline_options_end'] = array(
	'type' => 'sectionend',
);


$options['timeline']['button_options_start'] = array(
	'type' => 'sectionstart',
);

$options['timeline']['button_options_title'] = array(
	'title' => _x( 'Prev/Next Button', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
	'type'  => 'title',
);

$options['timeline']['button_options_enable'] = array(
	'title'   => _x( 'Enable Prev/Next Button?', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
	'type'    => 'checkbox',
	'id'      => 'yith_wcms_nav_buttons_enabled',
	'desc'    => _x( 'Select whether you want to show navigation buttons or not', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ),
	'default' => 'yes'
);

$options['timeline']['button_disable_previous'] = array(
	'title'   => _x( 'Disable Prev Button in last step ?', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
	'type'    => 'checkbox',
	'id'      => 'yith_wcms_nav_disabled_prev_button',
	'desc'    => _x( 'Select whether you want to hide Previous button in the last step', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ),
	'default' => 'no'
);

foreach ( $buttons as $button => $label ) {
	$k                         = 'timeline_options_label_' . $button;
	$options['timeline'][ $k ] = array(
		'title'   => $label,
		'type'    => 'text',
		'id'      => 'yith_wcms_timeline_options_' . $button,
		'default' => $label,
	);
}

$options['timeline']['button_enable_back_to_cart'] = array(
	'title'   => _x( 'Enabled Back to cart Button ?', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
	'type'    => 'checkbox',
	'id'      => 'yith_wcms_nav_enable_bakc_to_cart_button',
	'desc'    => _x( 'Select whether you want to show the Back to cart button in checkout page', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ),
	'default' => 'no'
);

$options['timeline']['timeline_options_label_back_to_cart'] = array(
	'title'   => _x( 'Back to cart', 'Frontend: button label', 'yith-woocommerce-multi-step-checkout' ),
	'type'    => 'text',
	'id'      => 'yith_wcms_timeline_options_back_to_cart',
	'default' => _x( 'Back to cart', 'Frontend: button label', 'yith-woocommerce-multi-step-checkout' ),
);

$options['timeline']['button_options_end'] = array(
	'type' => 'sectionend',
);

//string added @version 1.4.3
$options['timeline']['scroll_top_start'] = array(
    'type' => 'sectionstart'
);

$options['timeline']['scroll_top_title'] = array(
    'title' => _x( 'Scroll to top', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
    'type'  => 'title'
);

$options['timeline']['scroll_top_enable'] = array(
    'title'   => _x( 'Enable scrollTop ?', 'Panel: section title', 'yith-woocommerce-multi-step-checkout' ),
    'type'    => 'checkbox',
    'id'      => 'yith_wcms_scroll_top_enabled',
    'desc'    => _x( 'Select scrollTop option after click on next/prev button', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ),
    'default' => 'no'
);

$options['timeline']['scroll_top_wrapper'] = array(
    'title'   => _x( 'Scroll top anchor', 'Frontend: button label', 'yith-woocommerce-multi-step-checkout' ),
    'type'    => 'text',
    'id'      => 'yith_wcms_scroll_top_anchor',
    'default' => '#checkout_timeline'
);

$options['timeline']['scroll_top_end'] = array(
    'type' => 'sectionend'
);

return $options;