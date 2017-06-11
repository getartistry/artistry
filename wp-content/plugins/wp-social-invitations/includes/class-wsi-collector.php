<?php

/**
 * Class that handle popup collector
 *
 * @since      2.5.0
 * @package    Wsi
 * @subpackage Wsi/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Wsi_Collector {

	/**
	 * Plugin Options
	 * @var array
	 */
	public $opts;

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


		$this->wsi          = $wsi;
		$this->version      = $version;
		// too early to call get_opts in here
	}

	/**
	 * Fires the collector process
	 */
	public function run(){

		global $wsi_plugin;
		$this->opts         = $wsi_plugin->get_opts();

		// if we are not trying to show collector exit
		if( !isset( $_GET['action'] ) || 'wsi_collector' != $_GET['action'] || empty( $_REQUEST["provider"] ) )
			return;
		// prevent caching
		if ( ! defined( 'DONOTCACHEPAGE' ) )
			define( 'DONOTCACHEPAGE', true);

		if ( ! defined( 'DONOTCACHCEOBJECT' ) )
			define( 'DONOTCACHCEOBJECT', true );

		if ( ! defined( 'DONOTMINIFY' ) )
			define( 'DONOTMINIFY', true );

		// let display a loading message. should be better than a white screen
		if( isset( $_REQUEST["provider"] ) && ! isset( $_REQUEST["redirect_to_provider"] ) ) {
			$this->show_loading_page();
		}
		$_SESSION['wsi_data'] = '';//start with an empty session
		$provider_class = 'Wsi_' . ucfirst( $_REQUEST["provider"] );
		$provider = new $provider_class;

		$sdata      = wsi_get_data('sdata');
		$user_info  = wsi_get_data('user_info');
		// Save some data to use later
		$_SESSION['wsi_data'] = array(
			'provider'      => $provider->getName(),
			'current_url'   => wsi_current_url(),
			'obj_id'        => esc_attr($_GET['wsi_obj_id']),
			'sdata'         => $sdata,
			'user_info'     => $user_info
		);
		nocache_headers();
		include_once( WSI_PLUGIN_DIR . '/public/partials/collector-header.php');

		$provider->collector();

		include_once( WSI_PLUGIN_DIR . '/public/partials/collector-footer.php');

		wsi_get_template('popup/sending.php', array( 'provider' => $provider->getName() ) );

		die(); // we die everything after
	}

	/**
	 * Shows loading page before collecting form
	 */
	public function show_loading_page() {

		global $wsi_plugin;
		$this->opts         = $wsi_plugin->get_opts();

		// selected provider
		$provider = @ trim( strip_tags( esc_attr( $_REQUEST["provider"] ) ) );
		wsi_get_template('popup/loading.php',
			array(
				'options' => $this->opts,
				'provider' => $provider
			)
		);
		die(); // we die everything after
	}

	/**
	 * Print subject field on popup collector
	 * @return mixed Echoes the subject field
	 */
	public static function printSubjectField() {
		global $wsi_plugin;
		$opts = $wsi_plugin->get_opts();

		$provider   = wsi_get_data('provider');
		$type       = 'text';
		$value      = $provider == 'linkedin' ? $opts['text_subject'] : $opts['subject'];

		if( 'text' == $type ) {
			?><label for="subject"><?php _e('Subject', 'wsi');?></label><?php
		}
		?>
		<input type="<?php echo $type;?>" name="subject" value="<?php echo self::getFieldValue( apply_filters('wsi/messages/'.$provider.'_subject', $value ) );?>" class="form-control"/>
		<?php
	}

	/**
	 * Print Message field on popup collector
	 * @return mixed Echoes the Message field
	 */
	public static function printMessageField() {
		global $wsi_plugin;
		$opts = $wsi_plugin->get_opts();

		$provider   = wsi_get_data('provider');
		$type           = 'visible'; // by default will be hidden unless specified
		$rich_editor    = false;

		if( $provider == 'twitter') {
			$value = $opts['tw_message'];

		} elseif ( $provider == 'facebook') {
			$value = $opts['fb_message'];

		} elseif ( $provider == 'linkedin') {
			$value = $opts['message'] ;

		} else {
			$rich_editor = true;
			$value = $opts['html_message'];
		}

		if( 'visible' == $type ) {
			?><label for="message"><?php _e('Message', 'wsi');?></label><?php
		}
		//no te olvides de añadir filtros strip tags cuando correspoda

		if( $rich_editor && 'visible' == $type ) {
			?>
			<div class="box-wrapper">
				<?php wp_editor( self::getFieldValue( apply_filters( 'wsi/messages/' . $provider . '_message', $value ) ),'message' , array('media_buttons' => false,'quicktags' => false,'textarea_rows' => 15));?>
			</div>
			<?php
		} else {
			?>
			<textarea class="form-control" name="message" id="msg_<?php echo $provider; ?>" <?php echo $type == 'hidden' ? 'style="display:none;"' : ''; ?>><?php echo self::getFieldValue( apply_filters( 'wsi/messages/' . $provider . '_message', $value ) ); ?></textarea>
			<?php
		}
	}

	/**
	 * Print the value but first replace shortcodes
	 * and then print utf8 values (some internation websites shows errors without these)
	 * @param $value
	 * @return string
	 */
	public static function getFieldValue( $value ){

		if( function_exists('mb_convert_encoding'))
		{
			 return mb_convert_encoding( self::replaceShortcodes( do_shortcode($value) ), "HTML-ENTITIES", "UTF-8");
		}
		else
		{
			return utf8_decode( self::replaceShortcodes( do_shortcode($value) ) );
		}

	}

	static function replaceShortcodes($content){
		global $wsi_plugin, $wpdb;
		$opts = $wsi_plugin->get_opts();
		/*
		%%INVITERNAME%%: Display name of the inviter
		%%SITENAME%%: Name of your website - Test site
		%%ACCEPTURL%%: Link that invited users can click to accept the invitation and register
		%%INVITERURL%%: If Buddypress is enabled, URL to the profile of the inviter
		%%CUSTOMURL%%: A custom URL that you can edit with a simple filter
		%%CURRENTURL%%: Prints the url where the widget was clicked
		%%CURRENTTITLE%%: Title of the post / page where the widget was clicked
		*/

		$que = array(
			'%%INVITERNAME%%',
			'%%SITENAME%%',
			'%%CURRENTURL%%',
			'%%CUSTOMURL%%',
			'%%INVITERURL%%',
			'%%CURRENTTITLE%%',
		);


		$inviter_url = $post_data = '';

		$invitername = wsi_get_display_name();

		// one more check in case we are rendering a fb invitation.
		if( empty($invitername) && !empty( $_REQUEST['wsi_invitation']) ) {
			$invitername = $wpdb->get_var( $wpdb->prepare("SELECT display_name FROM {$wpdb->prefix}wsi_stats WHERE queue_id = %d", array((int)base64_decode($_REQUEST['wsi_invitation']))));
		}
		// ok no friend name
		if( empty($invitername) )
			$invitername = __('A friend of yours', 'wsi');


		// If bp exist show inviter url
		if( function_exists('bp_get_root_domain') ) {
			$inviter_url 	= bp_core_get_user_domain(get_current_user_id());
		}
		// Or usepro is enabled
		global $userpro;
		if( method_exists( $userpro, 'permalink') ) {
			$inviter_url    = $userpro->permalink( get_current_user_id() );
		}

		if( $pid = wsi_get_data('obj_id') ) {
			$post_data = get_post( $pid );
		}

		$por = array(
			apply_filters('wsi/placeholders/invitername'	, $invitername ),
			apply_filters('wsi/placeholders/sitename'	    , get_bloginfo('name') ),
			apply_filters('wsi/placeholders/current_url'    , isset( $post_data->ID ) ? get_permalink($post_data->ID) : ''),
			apply_filters('wsi/placeholders/custom_url'     , $opts['custom_url'] ),
			apply_filters('wsi/placeholders/inviter_url'    , $inviter_url ),
			apply_filters('wsi/placeholders/current_title'  , isset( $post_data->post_title ) ? $post_data->post_title : '')

		);
		return str_replace($que, $por, $content);
	}
}