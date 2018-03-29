<?php

	$icons = array(
		'air-conditioning',
		'alarm',
		'bar',
		'bathrobe',
		'bathroom',
		'breakfast',
		'car-rental',
		'concierge',
		'disabled',
		'elevator',
		'fitness-center',
		'free-toiletries',
		'hairdryer',
		'heating',
		'iron',
		'laundry',
		'linens',
		'lounge',
		'minibar',
		'newspapers',
		'no-smoking',
		'parking',
		'pets',
		'pool',
		'radio',
		'refrigerator',
		'restaurant',
		'safe',
		'satellite-channels',
		'shower',
		'shuttle-service',
		'slippers',
		'smoking',
		'soundproof',
		'spa',
		'sun-deck',
		'toilet',
		'towels',
		'tv',
		'wifi',
	);

	function get_elements_plus_icons( $icons ) {
		$ep_icons = [];

		foreach ( $icons as $icon ) {
			$ep_icons[] .= 'ep-icon-module ep-icon-module-' . $icon;
		}

		return $ep_icons;
	}

	function get_elements_plus_icons_options( $icons ) {
		$ep_icons_options = [];

		foreach ( $icons as $icon ) {
			$name = ucwords( str_replace( '-', ' ', $icon ) );
			$ep_icons_options[ 'ep-icon-module ep-icon-module-' . $icon ] = $name;
		}

		return $ep_icons_options;
	}
