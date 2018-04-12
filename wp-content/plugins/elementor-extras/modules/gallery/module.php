<?php
namespace ElementorExtras\Modules\Gallery;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'gallery';
	}

	public function get_widgets() {
		return [
			'Gallery',
			'Gallery_Slider',
		];
	}

	public static function get_link_url( $attachment, $instance ) {
		if ( 'none' === $instance['link_to'] ) {
			return false;
		}

		if ( 'custom' === $instance['link_to'] ) {
			if ( empty( $instance['link']['url'] ) ) {
				return false;
			}
			return $instance['link'];
		}

		return [
			'url' => wp_get_attachment_url( $attachment['id'] ),
		];
	}

	public static function get_image_caption( $attachment, $type ) {
		$caption_type = $type;

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $attachment['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}

	public static function get_image_info( $image_id, $image_url, $image_size ) {

		if ( ! $image_id && ! $image_url )
			return false;

		$info = [];

		if ( ! empty( $image_id ) ) { // Existing attachment

			$attachment = get_post( $image_id );

			$info['id']			= $image_id;
			$info['url']		= $image_url;
			$info['image'] 		= wp_get_attachment_image( $attachment->ID, $image_size, true );
			$info['caption'] 	= $attachment->post_excerpt;

		} else { // Placeholder image, most likely

			if ( empty( $image_url ) )
				return;

			$info['id']			= false;
			$info['url']		= $image_url;
			$info['image'] 		= '<img src="' . $image_url . '" />';
			$info['caption'] 	= '';
		}

		return $info;

	}
}
