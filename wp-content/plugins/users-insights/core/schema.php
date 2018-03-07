<?php

class USIN_Schema{

	protected $version;
	protected $plugin_file;
	protected $option_key = 'usin_version_installed';
	protected $table_name;

	public function __construct($table_name, $plugin_file, $version){
		$this->table_name = $table_name;
		$this->plugin_file = $plugin_file;
		$this->version = $version;
	}

	public function init(){
		register_activation_hook( $this->plugin_file, array($this, 'set_db_table') );
		add_action( 'plugins_loaded', array($this, 'check_for_updates') );
	}

	public function set_db_table(){
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix.$this->table_name;

		$query = "CREATE TABLE $table_name (
			user_data_id bigint(20) unsigned NOT NULL auto_increment,
			user_id bigint(20) unsigned NOT NULL default '0',
			last_seen datetime,
			sessions bigint(20),
			country varchar(255),
			region varchar(255),
			city varchar(255),
			coordinates varchar(255),
			browser varchar(255),
			browser_version varchar(255),
			platform varchar(255),
			PRIMARY KEY  (user_data_id),
			UNIQUE KEY user_id (user_id),
			KEY last_seen (last_seen)
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $query );

		$this->update_version();
	}

	public function check_for_updates(){
		if($this->should_update()){
			$this->set_db_table();
		}
	}

	protected function update_version(){
		update_option($this->option_key, $this->version);
	}

	protected function should_update(){
		return get_option($this->option_key) !== $this->version;
	}


}