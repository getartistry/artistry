<?php
// Listing field types.

namespace CASE27\Integrations\ListingTypes\Fields;

return apply_filters('case27\listingtypes\fields\presets', [
	'job_title' => new TextField([
		'slug' => 'job_title',
		'label' => __( 'Title', 'my-listing' ),
		'required' => true,
		'priority' => 1,
		'is_custom' => false,
	]),

	'job_tagline' => new TextField([
		'slug' => 'job_tagline',
		'label' => __( 'Tagline', 'my-listing' ),
		'required' => false,
		'priority' => 2,
		'is_custom' => false,
	]),

	'job_location' => new LocationField([
		'slug' => 'job_location',
		'label' => __( 'Location', 'my-listing' ),
		'placeholder' => __( 'e.g. "London"', 'my-listing' ),
		'required' => false,
		'priority' => 3,
		'is_custom' => false,
	]),

	'job_category' => new TermSelectField([
		'slug' => 'job_category',
		'label' => __( 'Category', 'my-listing' ),
		'required' => false,
		'priority' => 4,
		'taxonomy' => 'job_listing_category',
		'is_custom' => false,
		'terms-template' => 'multiselect',
	]),

	'region' => new TermSelectField([
		'slug' => 'region',
		'label' => __( 'Region', 'my-listing' ),
		'required' => false,
		'priority' => 5,
		'taxonomy' => 'region',
		'is_custom' => false,
		'terms-template' => 'multiselect',
	]),

	'job_tags' => new TermSelectField([
		'slug' => 'job_tags',
		'label' => __( 'Tags', 'my-listing' ),
		'required' => false,
		'priority' => 6,
		'taxonomy' => 'case27_job_listing_tags',
		'is_custom' => false,
		'terms-template' => 'multiselect',
	]),

	'job_description' => new TextEditorField([
		'slug' => 'job_description',
		'label' => __( 'Description', 'my-listing' ),
		'required' => true,
		'priority' => 7,
		'is_custom' => false,
	]),

	'job_email' => new EmailField([
		'slug' => 'job_email',
		'label' => __( 'Contact Email', 'my-listing' ),
		'required' => false,
		'priority' => 8,
		'is_custom' => false,
	]),

	'job_logo' => new FileField([
		'slug' => 'job_logo',
		'label' => __( 'Logo', 'my-listing' ),
		'required' => true,
		'priority' => 9,
		'ajax' => true,
		'multiple' => false,
		'is_custom' => false,
		'allowed_mime_types' => [
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'gif' => 'image/gif',
			'png' => 'image/png',
		],
	]),

	'job_cover' => new FileField([
		'slug' => 'job_cover',
		'label' => __( 'Cover Image', 'my-listing' ),
		'required' => false,
		'priority' => 10,
		'ajax' => true,
		'multiple' => false,
		'is_custom' => false,
		'allowed_mime_types' => [
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'gif' => 'image/gif',
			'png' => 'image/png',
		],
	]),

	'job_gallery' => new FileField([
		'slug' => 'job_gallery',
		'label' => __( 'Gallery Images', 'my-listing' ),
		'required' => false,
		'priority' => 11,
		'ajax' => true,
		'multiple' => true,
		'is_custom' => false,
		'allowed_mime_types' => [
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'gif' => 'image/gif',
			'png' => 'image/png',
		],
	]),

	'job_website' => new URLField([
		'slug' => 'job_website',
		'label' => __( 'Website', 'my-listing' ),
		'required' => false,
		'priority' => 12,
		'is_custom' => false,
	]),

	'job_phone' => new TextField([
		'slug' => 'job_phone',
		'label' => __( 'Phone Number', 'my-listing' ),
		'required' => false,
		'priority' => 13,
		'is_custom' => false,
	]),

	'job_video_url' => new URLField([
		'slug' => 'job_video_url',
		'label' => __( 'Video URL', 'my-listing' ),
		'required' => false,
		'priority' => 14,
		'is_custom' => false,
	]),

	'job_date' => new DateField([
		'slug' => 'job_date',
		'label' => __( 'Date', 'my-listing' ),
		'required' => false,
		'priority' => 15,
		'format' => 'date',
		'is_custom' => false,
	]),

	'related_listing' => new RelatedListingField([
		'slug' => 'related_listing',
		'label' => __( 'Related Listing', 'my-listing' ),
		'required' => false,
		'priority' => 16,
		'listing_type' => '',
		'is_custom' => false,
	]),

	'work_hours' => new WorkHoursField([
		'slug' => 'work_hours',
		'label' => __( 'Work Hours', 'my-listing' ),
		'required' => false,
		'priority' => 17,
		'is_custom' => false,
	]),

	'select_products' => new SelectProductsField([
		'slug' => 'select_products',
		'label' => __( 'Products', 'my-listing' ),
		'required' => false,
		'priority' => 18,
		'is_custom' => false,
	]),

	'links' => new LinksField([
		'slug' => 'links',
		'label' => __( 'Social Networks', 'my-listing' ),
		'required' => false,
		'priority' => 19,
		'is_custom' => false,
	]),

	'price_range' => new SelectField([
		'slug' => 'price_range',
		'label' => __( 'Price Range', 'my-listing' ),
		'required' => false,
		'priority' => 20,
		'options' => ['$' => '$', '$$' => '$$', '$$$' => '$$$'],
		'is_custom' => false,
	]),

	'form_heading' => new FormHeadingField([
		'slug' => 'form_heading',
		'label' => __( 'Form Heading', 'my-listing' ),
		'required' => false,
		'priority' => 21,
		'icon' => 'icon-pencil-2',
		'is_custom' => false,
	]),
]);
