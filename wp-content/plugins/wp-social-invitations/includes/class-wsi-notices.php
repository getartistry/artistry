<?php

/**
 * Class that handle all admin notices
 *
 * @since      2.5.4
 * @package    Wsi
 * @subpackage Wsi/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Wsi_Notices {


	/**
	 * The ID of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $wsi    The ID of this plugin.
	 */
	private $wsi;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.5
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.5
	 * @var      string    $wsi       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wsi, $version ) {

		$this->wsi = $wsi;
		$this->version = $version;
		if( isset( $_GET['wsi_notice'])){
			update_option('wsi_'.esc_attr($_GET['wsi_notice']), true);
		}
	}


	public function rate_plugin(){
		?><div class="updated notice">
		<h3>WordPress Social Invitations!</h3>
			<p><?php echo sprintf(__( 'We noticed that you have been using our plugin for a while and we would like to ask you a little favour. If you are happy with it and can take a minute please <a href="%s" target="_blank">leave a nice review</a> on WordPress', 'wsi' ), 'https://wordpress.org/support/view/plugin-reviews/wp-social-invitations?filter=5' ); ?></p>
		<ul>
			<li><?php echo sprintf(__('<a href="%s" target="_blank">Leave a nice review</a>'),'https://wordpress.org/support/view/plugin-reviews/wp-social-invitations?filter=5');?></li>
			<li><?php echo sprintf(__('<a href="%s">No, thanks</a>'), '?wsi_notice=rate_plugin');?></li>
		</ul>
		</div><?php
	}
}