<?php
global $post;

c27()->get_partial('listing-preview', [
	'listing' => $post,
	'wrap_in' => isset($post->c27_options__wrap_in) && $post->c27_options__wrap_in ? $post->c27_options__wrap_in : '',
	]);
