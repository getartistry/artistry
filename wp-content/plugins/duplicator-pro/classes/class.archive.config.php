<?php
/**
 * @copyright 2016 Snap Creek LLC
 */
class DUP_PRO_Archive_Config
{        
    public $created;
    public $version_dup;
    public $version_wp;
    public $version_db;
    public $version_php;
    public $version_os;
    public $secure_on;
    public $secure_pass;
    public $skipscan;
    public $wp_tableprefix;
    public $blogname;
    public $dbhost;
    public $dbname;
    public $dbuser;
    public $dbpass;
    public $cpnl_dbname;
    public $cpnl_host;
    public $cpnl_user;
    public $cpnl_pass;
    public $cpnl_enable;
    public $cpnl_connect;
    public $cpnl_dbaction;
    public $cpnl_dbhost;
    public $cpnl_dbuser;
    public $ssl_admin;
    public $ssl_login;
    public $cache_wp;
    public $cache_path;
    public $wproot;
    public $url_old;
    public $url_new;
    public $mu_mode;
    public $subsites;
    public $opts_delete;
    public $license_limit;
    public $relative_content_dir;

    function __construct()
    {
        $this->subsites = array();
    }

}