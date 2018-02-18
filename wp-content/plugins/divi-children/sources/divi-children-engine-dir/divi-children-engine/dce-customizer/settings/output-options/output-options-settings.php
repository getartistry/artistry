<?php

/**
 * Customizer controls: Output Options section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


/* Customize or Production Mode */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_css_output_source',
	'label'			=> __( 'Customize or Production Mode', 'divi-children-engine' ),
	'section'		=> 'dce_output_options',
	'description'	=> __( 'Default "Development Mode" option lets you see your changes as you use the Customizer, while the faster "Production Mode" outputs only changes you have already saved.', 'divi-children-engine' ),
	'help'			=> __( '"Production Mode" CSS output makes your site much faster than "Development Mode", so it is your best option for finished sites. "Development Mode", on the other hand, lets you see your modifications going live as you change the different child theme settings, so it should be your choice while you are still customizing your site.', 'divi-children-engine' ),	
	'default'		=> 'customize',
	'priority'		=> 10,
	'choices'		=> array(
		'customize'		=>	__( 'Development Mode', 'divi-children-engine' ),
		'production'	=>	__( 'Production Mode', 'divi-children-engine' ),
	),
) );


/* Save Output for Production Mode */
Kirki::add_field( 'dce', array(
	'type'		=>	'checkbox',
	'settings'	=>	'dce_css_output_save',
	'label'		=>	__( 'Save Production Mode settings', 'divi-children-engine' ),
	'description'		=> __( 'Check this box when you are ready to save all your customization work for the Production output mode. Your customized CSS output will be saved when you click on the Customizer "Save & Publish" button.', 'divi-children-engine' ),
	'section'	=>	'dce_output_options',
	'default'	=>	false,
	'priority'	=>	20,
	'required'	=> array(
		array(
			'setting'	=> 'dce_css_output_source',
			'operator'	=> '==',
			'value'		=> 'production',
			),
	),
) );

