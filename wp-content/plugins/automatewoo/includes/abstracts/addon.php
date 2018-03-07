<?php

namespace AutomateWoo;

/**
 * @class Addon
 */
abstract class Addon {

	/** @var Addon - must declare in child */
	protected static $_instance;

	/** @var string */
	public $id;

	/** @var string */
	public $name;

	/** @var string */
	public $version;

	/** @var string */
	public $plugin_basename;

	/** @var string */
	public $plugin_path;

	/** @var string */
	public $file;

	/** @var string */
	public $min_php_version;

	/** @var string */
	public $min_automatewoo_version;

	/** @var string */
	public $min_woocommerce_version;

	/** @var array */
	public $db_updates = [];



	/**
	 * Method to init the add on
	 */
	abstract function init();

	/**
	 * Required method to return options class
	 * @return Options_API
	 */
	abstract function options();

	/**
	 * Optional installer method
	 */
	function install() {}


	/**
	 * Constructor for add-on
	 * @param Plugin_Data|object $plugin_data
	 */
	function __construct( $plugin_data ) {

		$this->id = $plugin_data->id;
		$this->name = $plugin_data->name;
		$this->version = $plugin_data->version;
		$this->file = $plugin_data->file;
		$this->min_php_version = $plugin_data->min_php_version;
		$this->min_automatewoo_version = $plugin_data->min_automatewoo_version;
		$this->min_woocommerce_version = $plugin_data->min_woocommerce_version;

		$this->plugin_basename = plugin_basename( $plugin_data->file );
		$this->plugin_path = dirname( $plugin_data->file );

		add_action( 'automatewoo_init_addons', [ $this, 'maybe_init' ] );
	}


	/**
	 * @param string $end
	 * @return string
	 */
	function url( $end = '' ) {
		return untrailingslashit( plugin_dir_url( $this->plugin_basename ) ) . $end;
	}


	/**
	 * @param string $end
	 * @return string
	 */
	function path( $end = '' ) {
		return untrailingslashit( $this->plugin_path ) . $end;
	}


	/**
	 * Check the version stored in the database and determine if an upgrade needs to occur
	 */
	function check_version() {

		if ( version_compare( $this->version, $this->options()->version, '=' ) )
			return;

		$this->install();

		if ( $this->is_database_upgrade_available() ) {
			add_action( 'admin_notices', [ $this, 'data_upgrade_prompt' ] );
		}
		else {
			$this->update_database_version();
		}
	}


	/**
	 * @return bool
	 */
	function is_database_upgrade_available() {

		if ( version_compare( $this->version, $this->options()->version, '=' ) || empty( $this->db_updates ) ) {
			return false;
		}

		return $this->options()->version && version_compare( $this->options()->version, max( $this->db_updates ), '<' );
	}


	/**
	 * Handle updates
	 */
	function do_database_update() {

		@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );

		foreach ( $this->db_updates as $update ) {
			if ( version_compare( $this->options()->version, $update, '<' ) ) {
				include( $this->path( "/includes/updates/$update.php" ) );
			}
		}

		$this->update_database_version();
	}


	/**
	 * Update version to current
	 */
	function update_database_version() {
		update_option( $this->options()->prefix . 'version', $this->version, true );
		do_action( 'automatewoo_addon_updated' );
	}


	/**
	 * Renders prompt notice for user to update
	 */
	function data_upgrade_prompt() {
		AW()->admin->get_view( 'data-upgrade-prompt', [
			'plugin_name' => $this->name,
			'plugin_slug' => $this->id
		]);
	}


	/**
	 *
	 */
	function maybe_init() {

		Addons::register( $this );

		if ( Licenses::is_active( $this->id ) ) {
			$this->init();
		}
	}


	/**
	 * Runs when the license for the add-on is activated
	 */
	function activate() {
		flush_rewrite_rules();
	}


	/**
	 * @return string
	 */
	function get_getting_started_url() {
		return '';
	}


	/**
	 * @param Plugin_Data|mixed $data
	 * @return Addon|mixed
	 */
	static function instance( $data ) {
		if ( is_null( static::$_instance ) ) {
			static::$_instance = new static( $data );
		}
		return static::$_instance;
	}

}


/**
 * @class Plugin_Data
 */
class Plugin_Data {
	public $id;
	public $name;
	public $version;
	public $file;
	public $min_php_version;
	public $min_automatewoo_version;
	public $min_woocommerce_version;
}