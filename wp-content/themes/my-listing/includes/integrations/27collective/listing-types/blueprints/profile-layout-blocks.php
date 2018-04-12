<?php

return apply_filters('case27/listingtypes/profile_layout_blocks', [
	[
		'type' => 'text',
		'icon' => 'view_headline',
		'title' => 'Textarea',
		'show_field' => '',
		'allowed_fields' => ['text', 'texteditor', 'wp-editor', 'checkbox', 'select', 'multiselect', 'textarea', 'email', 'url', 'number', 'location'],
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'gallery',
		'icon' => 'insert_photo',
		'title' => 'Gallery',
		'show_field' => 'job_gallery',
		'allowed_fields' => ['file'],
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [[
			'label' => 'Gallery Type',
			'name' => 'gallery_type',
			'type' => 'select',
			'choices' => ['carousel' => 'Carousel', 'carousel-with-preview' => 'Carousel with image preview'],
			'value' => 'carousel',
		]],
	],

	[
		'type' => 'categories',
		'icon' => 'view_module',
		'title' => 'Categories',
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'tags',
		'icon' => 'view_module',
		'title' => 'Tags',
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'terms',
		'icon' => 'view_module',
		'title' => 'Terms',
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [
			[
				'label'   => __( 'Taxonomy', 'my-listing' ),
				'name'    => 'taxonomy',
				'type'    => 'select',
				'choices' => array_column( array_map( function( $tax ) {
								return [ 'name' => $tax->label, 'slug' => $tax->name ];
							 }, \CASE27\Integrations\ListingTypes\Designer::$store['taxonomies'] ), 'name', 'slug' ),
				'value'   => 'job_listing_category',
			],
			[
				'label'   => __( 'Style', 'my-listing' ),
				'name'    => 'style',
				'type'    => 'select',
				'choices' => [
					'listing-categories-block' => __( 'Colored Icons', 'my-listing' ),
					'list-block' => __( 'Outlined Icons', 'my-listing' ),
				],
				'value'   => 'listing-categories-block',
			]
		],
	],

	[
		'type' => 'location',
		'icon' => 'map',
		'title' => 'Location',
		'show_field' => 'job_location',
		'allowed_fields' => ['text', 'location'],
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [[
			'label' => 'Map Skin',
			'name' => 'map_skin',
			'type' => 'select',
			'value' => 'skin1',
			'choices' => c27()->get_map_skins(),
		]],
	],

	[
		'type' => 'contact_form',
		'icon' => 'email',
		'title' => 'Contact Form',
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [
			['label' => 'Contact Form ID', 'name' => 'contact_form_id', 'type' => 'number', 'value' => false],
			['label' => 'Send email to', 'name' => 'email_to', 'type' => 'multiselect', 'choices' => ['email'], 'value' => ['job_email']],
		],
	],

	[
		'type' => 'related_listing',
		'icon' => 'layers',
		'title' => 'Related Listing',
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'countdown',
		'icon' => 'av_timer',
		'title' => 'Countdown',
		'show_field' => 'job_countdown',
		'allowed_fields' => ['text', 'date'],
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'table',
		'icon' => 'view_module',
		'title' => 'Table',
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [[
			'label' => 'Table Rows',
			'name' => 'rows',
			'type' => 'repeater',
			'fields' => ['label', 'show_field', 'content'],
			'value' => [],
		]],
	],

	[
		'type' => 'details',
		'icon' => 'view_module',
		'title' => 'Details',
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [[
			'label' => 'Rows',
			'name' => 'rows',
			'type' => 'repeater',
			'fields' => ['icon', 'show_field', 'content'],
			'value' => [],
		]],
	],

	[
		'type' => 'file',
		'icon' => 'attach_file',
		'title' => 'Files',
		'show_field' => '',
		'allowed_fields' => ['file'],
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'social_networks',
		'icon' => 'view_module',
		'title' => 'Social Networks',
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'accordion',
		'icon' => 'view_module',
		'title' => 'Accordion',
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [[
			'label' => 'Rows',
			'name' => 'rows',
			'type' => 'repeater',
			'fields' => ['label', 'show_field', 'content'],
			'value' => [],
		]],
	],

	[
		'type' => 'tabs',
		'icon' => 'view_module',
		'title' => 'Tabs',
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [[
			'label' => 'Rows',
			'name' => 'rows',
			'type' => 'repeater',
			'fields' => ['label', 'show_field', 'content'],
			'value' => [],
		]],
	],

	[
		'type' => 'work_hours',
		'icon' => 'alarm',
		'title' => 'Work Hours',
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'video',
		'icon' => 'videocam',
		'title' => 'Video',
		'title_l10n' => ['locale' => 'en_US'],
		'show_field' => 'job_video_url',
		'allowed_fields' => ['url'],
	],

	[
		'type' => 'author',
		'icon' => 'account_circle',
		'title' => 'Author',
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'code',
		'icon' => 'view_headline',
		'title' => 'Shortcode',
		'content' => '',
		'allowed_fields' => ['text', 'texteditor', 'wp-editor', 'checkbox', 'select', 'multiselect', 'textarea', 'email', 'url', 'number', 'location', 'file'],
		'title_l10n' => ['locale' => 'en_US'],
	],

	[
		'type' => 'raw',
		'icon' => 'view_module',
		'title' => 'Static Code',
		'title_l10n' => ['locale' => 'en_US'],
		'options' => [[
			'label' => 'Enter any shortcode here. This block isn\'t specific to the active listing, so it can be used for ads and similar stuff added through a shortcode or embed code.',
			'name' => 'content',
			'type' => 'textarea',
			'value' => '',
		]],
	],
]);
