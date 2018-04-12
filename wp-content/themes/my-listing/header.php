<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php esc_attr( bloginfo( 'charset' ) ) ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="pingback" href="<?php esc_attr( bloginfo( 'pingback_url' ) ) ?>">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="c27-site-wrapper">

<?php c27()->get_partial( 'loading-screens/' . c27()->get_setting( 'general_loading_overlay', 'none' ) ) ?>

<?php

$GLOBALS['case27_custom_styles'] = '';
$pageTop = apply_filters('case27_pagetop_args', [
	'header' => [
		'show' => true,
		'args' => [],
	],

	'title-bar' => [
		'show' => c27()->get_setting('header_show_title_bar', false),
		'args' => [
			'title' => get_the_archive_title(),
			'ref' => 'default-title-bar',
		],
	]
]);

if ($pageTop['header']['show']) {
	c27()->get_section('header', $pageTop['header']['args']);

	if ($pageTop['title-bar']['show']) {
		c27()->get_section('title-bar', $pageTop['title-bar']['args']);
	}
}
