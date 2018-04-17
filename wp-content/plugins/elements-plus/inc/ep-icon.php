<?php

	$icons = array(
		'add-bag',
		'air-conditioning',
		'alarm',
		'american-express',
		'bag',
		'bag-return',
		'bar',
		'baseball',
		'basket-ball',
		'bathrobe',
		'bathroom',
		'bowling-ball',
		'boxing-glove',
		'breakfast',
		'calculator',
		'cart',
		'car-rental',
		'concierge',
		'coupon',
		'credit-card',
		'cricket',
		'delta-credit-card',		
		'disabled',
		'discover-credit-card',
		'diving',
		'dollar-currency',
		'dumbbell',
		'elevator',
		'euro-currency',
		'fitness-center',
		'free-toiletries',
		'gavel',
		'gift',
		'globe',
		'golf-ball',
		'hairdryer',
		'heating',
		'iron',
		'jcb-credit-card',
		'laundry',
		'linens',
		'lounge',
		'maestro-credit-card',
		'mail',
		'mastercard-card',
		'medal',
		'minibar',
		'mobile-app',
		'newspapers',
		'no-smoking',
		'parking',
		'paypal',
		'pets',
		'piggy-bank',
		'ping-pong',
		'pool',
		'pool-ball',
		'pound-currency',		
		'radio',
		'refrigerator',
		'restaurant',
		'rollers',
		'rugby-ball',
		'ruler',
		'rupee-currency',
		'safe',
		'satellite-channels',
		'search',
		'secure',
		'shower',
		'shuttle-service',
		'shuttlecock',
		'skating',
		'ski',
		'slippers',
		'smoking',
		'soccer-ball',
		'solo-credit-card',
		'soundproof',
		'spa',
		'star',
		'sun-deck',
		'support',
		'tag',
		'telephone',
		'tennis-ball',
		'toilet',
		'towels',
		'transport',
		'trophy',
		'tv',
		'visa-credit-card',
		'visa-electron',
		'volley-ball',
		'yen-currency',
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
