<?php
namespace ElementPack\Modules\SocialShare;

use ElementPack\Base\Element_Pack_Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists('is_plugin_active')){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }

class Module extends Element_Pack_Module_Base {


	private static $medias = [
		'facebook' => [
			'title' => 'Facebook',
			'has_counter' => true,
		],
		'googleplus' => [
			'title' => 'Google+',
		],
		'twitter' => [
			'title' => 'Twitter',
		],
		'pinterest' => [
			'title' => 'Pinterest',
			'has_counter' => true,
		],
		'linkedin' => [
			'title' => 'Linkedin',
			'has_counter' => true,
		],
		'vkontakte' => [
			'title' => 'Vkontakte',
			'has_counter' => true,
		],
		'odnoklassniki' => [
			'title' => 'OK',
			'has_counter' => true,
		],
		'moimir' => [
			'title' => 'Mail.Ru',
			'has_counter' => true,
		],
		'livejournal' => [
			'title' => 'LiveJournal',
		],
		'tumblr' => [
			'title' => 'Tumblr',
			'has_counter' => true,
		],
		'blogger' => [
			'title' => 'Blogger',
		],
		'digg' => [
			'title' => 'Digg',
		],
		'evernote' => [
			'title' => 'Evernote',
		],
		'reddit' => [
			'title' => 'Reddit',
			'has_counter' => true,
		],
		'delicious' => [
			'title' => 'Delicious',
			'has_counter' => true,
		],
		'stumbleupon' => [
			'title' => 'StumbleUpon',
			'has_counter' => true,
		],
		'pocket' => [
			'title' => 'Pocket',
			'has_counter' => true,
		],
		'surfingbird' => [
			'title' => 'Surfingbird',
			'has_counter' => true,
		],
		'liveinternet' => [
			'title' => 'LiveInternet',
		],
		'buffer' => [
			'title' => 'Buffer',
			'has_counter' => true,
		],
		'instapaper' => [
			'title' => 'Instapaper',
		],
		'xing' => [
			'title' => 'Xing',
			'has_counter' => true,
		],
		'wordpress' => [
			'title' => 'Wordpress',
		],
		'baidu' => [
			'title' => 'Baidu',
		],
		'renren' => [
			'title' => 'Renren',
		],
		'weibo' => [
			'title' => 'Weibo',
		],
		// Mobile Device Sharing
		'skype' => [
			'title' => 'Skype',
		],
		'telegram' => [
			'title' => 'Telegram',
		],
		'viber' => [
			'title' => 'Viber',
		],
		'whatsapp' => [
			'title' => 'WhatsApp',
		],
		'line' => [
			'title' => 'LINE',
		],
	];

	public static function get_social_media( $media_name = null ) {
		if ( $media_name ) {
			return isset( self::$medias[ $media_name ] ) ? self::$medias[ $media_name ] : null;
		}

		return self::$medias;
	}

	public function get_name() {
		return 'social';
	}

	public function get_widgets() {

		$widgets = [
			'Social_Share',
		];

		return $widgets;
	}
}
