<?php

// SPBAS Licensing
// -----------------------------------------------------------------------------------------------------------------------------------------------------
//error_reporting(E_ERROR);
function CurrentPageURL() 
{
	$pageURL = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
	$pageURL .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	return $pageURL;
}

/**
* The pages that should be validated
* 
* @param array $licensed_pages 
* @param boolean $is_theme 
* @return boolean
*/

function youbrandinc_do_validate_license($licensed_pages, $is_theme=false)
	{
//echo 'ran youbrandinc_do_validate_license<br>';
	$this_page='';
	if (isset($_GET['page']))
	{
		$this_page=$_GET['page'];
	}
	elseif (isset($_POST['page']))
	{
		$this_page=$_POST['page'];
	}

	$theCurrentURL = CurrentPageURL();
	
	//echo $this_page;
	//var_dump($licensed_pages);
	
	if (strpos($theCurrentURL,'curate-this.php') !== false) 
	{
		$this_page = 'curate-this';
	}
	if (strpos($theCurrentURL,'curate-action.php') !== false) 
	{
		$this_page = 'curate-action';
	}
	if (strpos($theCurrentURL,'parse-page-worker.php') !== false) 
	{
		$this_page = 'parse-page-worker';
	}
	if (strpos($theCurrentURL,'post-new.php') !== false) 
	{
		$this_page = 'post-pages';
	}
	if (strpos($theCurrentURL,'post.php') !== false) 
	{
		$this_page = 'post-pages';
	}

	//$do_validate_license=in_array($this_page, $licensed_pages);
	if ($is_theme) { return $do_validate_license; }

	$do_validate_license = true;
	$plugin_active=in_array(plugin_basename(__FILE__), get_option('active_plugins'));

	return ($do_validate_license&&$plugin_active);
	}
/**
* Validate the license
* 
* @param boolean $raw 
* @return boolean
*/
	function do_validate_license($inProduct, $raw=false, $clear_cache=false)
	{
		$spbas=new spbas_youbrandinc;
		$spbas->local_key_storage='database';  
		$spbas->read_query=array('local_key' => get_option($inProduct->prefix.'_license_local_key'));
		$spbas->update_query=array('function' => 'update_option', 'key' => $inProduct->prefix.'_license_local_key');
		$spbas->local_key_grace_period='1,2,3,4,5,6,7,8,9,10';

		if ($clear_cache)
		{
			$spbas->clear_cache_local_key(true);
			return false;
		}

		$spbas->license_key=get_option($inProduct->prefix.'_license_key');
		$spbas->secret_key= $inProduct->secret_key;
		$spbas->api_server='https://www.youbrandinc.com/licensing/api/index.php';
		$spbas->local_key_name= $inProduct->prefix.'_product.txt';
		$spbas->validate();
	
		if ($raw) { return $spbas; }
		// no longer checking for pages
		//$licensed_pages=array('ucta_plugin','curate-this', 'curation_traffic_plugin', 'social_quote_traffic', 'curation_suite_display_settings', 'curate-action','parse-page-worker','post-pages'); 	
		if ($spbas->errors)
		{
			return $spbas->errors;
		}
		return true;
	}
	
// this is a legacy function from before we rewrote the plugin
// we'll take this out once we release it
function ct_plugin_validate_license()
{
	$CurationTrafficPluginProduct = new ybi_product('Curation Traffic Plugin');
	return 	do_validate_license($CurationTrafficPluginProduct);
}
function sq_traffic_validate_license()
{
	$SocialQuoteTrafficProduct = new ybi_product('Social Quote Traffic');	
	return 	do_validate_license($SocialQuoteTrafficProduct);
}
function ucta_validate_license()
{
	$UCTAProduct = new ybi_product('Ultimate Call to Action');	
	return 	do_validate_license($UCTAProduct);
}

  function youbrandinc_license_activation_page()
  {
		  // make sure we have the needed function to verify the nonce.
		  if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	  yb_products_cs_js();
		// this is an array of all product objects, we use down below and we use on the activate-license.php  
		$AllProductsArr = array();
	
		/**
		* 	Below is each product in a very simple object, we set the settings then add it to the array.
		* 	The prefix is used to prepend keys and options
		* 	The name is the friendly name and the active name has to match the exact plugin name as we use it in the isYBIPluginActive function.
		*/
		$CurationSuiteProduct = new ybi_product('Curation Suite');
		$AllProductsArr[] = $CurationSuiteProduct;
		
		$SocialQuoteTrafficProduct = new ybi_product('Social Quote Traffic');
		$AllProductsArr[] = $SocialQuoteTrafficProduct;
	
		$CurationTrafficPluginProduct = new ybi_product('Curation Traffic Plugin');
		$AllProductsArr[] = $CurationTrafficPluginProduct;
		
		//$WPRoundUpProduct = new ybi_product('WP RoundUp');
		//$AllProductsArr[] = $WPRoundUpProduct;
	
		$UCTAProduct = new ybi_product('Ultimate Call to Action');
		$AllProductsArr[] = $UCTAProduct;

		$SSEProduct = new ybi_product('Super Social Engagement');
		$AllProductsArr[] = $SSEProduct;
	  
	  // verify nonce
		  $license_updated=(isset($_POST['youbrandinc_license'])&&wp_verify_nonce($_POST['youbrandinc_license'], 'youbrandinc_license'));
	
		// we have a submit
		if ($license_updated)
		{
			// go thru all products and update the option with the key set by user
			foreach($AllProductsArr as $product)
			{
				// clear the local key cache
				do_validate_license($product, null, true);

				if (isset($_POST[$product->prefix.'_license_key'])) {
					update_option($product->prefix.'_license_key', '');
					update_option($product->prefix.'_license_key', $_POST[$product->prefix.'_license_key']);
				}

			}
		}
	  
		/**
		* 	Now we verify the license.
		*	The function do_validate_license returns a SPBAS object we use in the activate-license.php admin page.
		*/
		$CurationSuiteProduct->spbas_obj = do_validate_license($CurationSuiteProduct, true);
		$SocialQuoteTrafficProduct->spbas_obj = do_validate_license($SocialQuoteTrafficProduct, true);
		$CurationTrafficPluginProduct->spbas_obj = do_validate_license($CurationTrafficPluginProduct, true);
//		$WPRoundUpProduct->spbas_obj = do_validate_license($WPRoundUpProduct, true);
		$UCTAProduct->spbas_obj = do_validate_license($UCTAProduct, true);
	  	$SSEProduct->spbas_obj = do_validate_license($SSEProduct, true);


		include dirname(__FILE__).'/activate-license.php';
		return true;
	}
/**
* YBI Product Class
*
* @package		SPBAS_License_Method
* @author		Super Scott <scott@youbrandinc.com>
*/
class ybi_product
	{
		var $name, $active_name, $prefix, $secret_key, $info_url, $spbas_obj;
		function __construct($inName)
		{
			$this->name = $inName;
			switch($this->name)
			{
				case 'Curation Suite':
					$this->active_name = 'Curation Suite';
					$this->prefix = 'curation_suite';
					$this->secret_key = '851a40cc3ef116d3f742c6cb213419b3';
					$this->info_url = 'https://CurationSuite.com/';
				break;
				case 'Social Quote Traffic':
					$this->active_name = 'Social Quote Traffic';
					$this->prefix = 'sqt';
					$this->secret_key = '147e2ff7a71924f4469c39fe674d144c';
					$this->info_url = 'http://SocialQuoteTraffic.com/';
				break;
				case 'Curation Traffic Plugin':
					$this->active_name = 'Curation Traffic Plugin';
					$this->prefix = 'curation_traffic_plugin';
					$this->secret_key = 'a5d572187af9e7a134f64fa4859cbc37';
					$this->info_url = 'http://CurationTraffic.com/';
				break;
				case 'WP RoundUp':
					$this->active_name = 'WP RoundUp';
					$this->prefix = 'wp_roundup';
					$this->secret_key = 'b13194756deb5010aee852707791da08';
					$this->info_url = 'http://CurationTraffic.com/products/wp-round-plugin/';
				break;
				case 'Ultimate Call to Action':
					$this->name = 'Ultimate Call to Action';
					$this->active_name = 'Ultimate Call to Action';
					$this->prefix = 'ucta';
					$this->secret_key = '3ea7660a8fe4323e81d4f4245a65080b';
					$this->info_url = 'http://CuationTraffic.com/';
				break;
				case 'Super Social Engagement':
					$this->name = 'Super Social Engagement';
					$this->active_name = 'Super Social Engagement';
					$this->prefix = 'sse';
					$this->secret_key = '3a8cdfda8f53199f6bd155074d4f9a3b';
					$this->info_url = 'https://CurationSuite.com/super-social-engagement/';
				break;
			}
		}
        public function ybi_product()
        {
            // PHP4-style constructor.
            // This will NOT be invoked, unless a sub-class that extends `foo` calls it.
            // In that case, call the new-style constructor to keep compatibility.
            self::__construct();
        }
	}
  

/**
* SPBAS License Validation
*
* @license 		Commercial / Proprietary
* @copyright	SolidPHP, Inc.
* @package		SPBAS_License_Method
* @author		Andy Rockwell <support@solidphp.com>
*/

class spbas_youbrandinc
	{
	var $errors;
	var $license_key;
	var $api_server;
	var $remote_port;
	var $remote_timeout;
	var $local_key_storage;
	var $read_query;
	var $update_query;
	var $local_key_path;
	var $local_key_name;
	var $local_key_transport_order;
	var $local_key_grace_period;
	var $local_key_last;
	var $validate_download_access;
	var $release_date;
	var $key_data;
	var $status_messages;
	var $valid_for_product_tiers;
	var $secret_key;

	function __construct() //spbas_youbrandinc()
		{

		$this->errors=false;
		$this->remote_port=80;
		$this->remote_timeout=10;
		$this->valid_local_key_types=array('spbas');
		$this->local_key_type='spbas';
		$this->local_key_storage='filesystem';
		$this->local_key_grace_period=0;
		$this->local_key_last=0;
		$this->read_query=false;
		$this->update_query=false;
		$this->local_key_path='./';
		//$this->local_key_name='license.txt';
		$this->local_key_transport_order='scf';
		$this->validate_download_access=false;
		$this->release_date=false;
		$this->valid_for_product_tiers=false;

		$this->key_data=array(
						'custom_fields' => array(), 
						'download_access_expires' => 0, 
						'license_expires' => 0, 
						'local_key_expires' => 0, 
						'status' => 'Invalid', 
						);

		$this->status_messages=array(
						'active' => 'This license is active.', 
						'suspended' => 'Error: This license has been suspended.', 
						'expired' => 'Error: This license has expired.', 
						'pending' => 'Error: This license is pending review.', 
						'download_access_expired' => 'Error: This version of the software was released '.
													'after your download access expired. Please '.
													'downgrade or contact support for more information.', 
						'missing_license_key' => 'Error: Please enter your license key to continue.',
						'unknown_local_key_type' => 'Error: An unknown type of local key validation was requested.',
						'could_not_obtain_local_key' => 'Error: I could not obtain a new local license key.', 
						'maximum_grace_period_expired' => 'Error: The maximum local license key grace period has expired.',
						'local_key_tampering' => 'Error: The local license key has been tampered with or is invalid.',
						'local_key_invalid_for_location' => 'Error: The local license key is invalid for this site. Please contact support.',
						'missing_license_file' => "Error: Please create the following file (and directories if they don't exist already):<br />\r\n<br />\r\n",
						'license_file_not_writable' => 'Error: Please make the following path writable:<br />',
						'invalid_local_key_storage' => 'Error: I could not determine the local key storage on clear.',
						'could_not_save_local_key' => 'Error: I could not save the local license key.',
						'license_key_string_mismatch' => 'Error: The local key is invalid for this license.',
						);

		// replace plain text messages with tags, make the tags keys for this localization array on the server side.
		// move all plain text messages to tags & localizations
		$this->localization=array(
						'active' => 'This license is active.', 
						'suspended' => 'Error: This license has been suspended.', 
						'expired' => 'Error: This license has expired.', 
						'pending' => 'Error: This license is pending review.', 
						'download_access_expired' => 'Error: This version of the software was released '.
													'after your download access expired. Please '.
													'downgrade or contact support for more information.', 
						);
		}

	/**
	* Validate the license
	* 
	* @return string
	*/
	function validate()
		{
		// Make sure we have a license key.
		//		echo 'validate();';
				//var_dump($this);
		if (!$this->license_key) 
			{ 
			return $this->errors=$this->status_messages['missing_license_key']; 
			}

		// Make sure we have a valid local key type.
		if (!in_array(strtolower($this->local_key_type), $this->valid_local_key_types)) 
			{ 
			return $this->errors=$this->status_messages['unknown_local_key_type'];
			}

		// Read in the local key.
		$this->trigger_grace_period=$this->status_messages['could_not_obtain_local_key'];
		switch($this->local_key_storage)
			{
			case 'database':
				$local_key=$this->db_read_local_key();
				break;

			case 'filesystem':
				$local_key=$this->read_local_key();
				break;

			default:
				return $this->errors=$this->status_messages['missing_license_key'];
			}

		// The local key has expired, we can't go remote and we have grace periods defined.
		if ($this->errors==$this->trigger_grace_period&&$this->local_key_grace_period)
			{
			// Process the grace period request
			$grace=$this->process_grace_period($this->local_key_last); 
			if ($grace['write'])
				{
				// We've consumed one of the allowed grace periods.
				if ($this->local_key_storage=='database')
					{
					$this->db_write_local_key($grace['local_key']);
					}
				elseif ($this->local_key_storage=='filesystem')
					{
					$this->write_local_key($grace['local_key'], "{$this->local_key_path}{$this->local_key_name}");
					}
				}

			// We've consumed all the allowed grace periods.
			if ($grace['errors']) { return $this->errors=$grace['errors']; }

			// We are in a valid grace period, let it slide!
			$this->errors=false;
			return $this;
			}

		// Did reading in the local key go ok?
		if ($this->errors) 
			{ 
			return $this->errors; 
			}

		// Validate the local key.
		return $this->validate_local_key($local_key);
		}

	/**
	* Calculate the maximum grace period in unix timestamp.
	* 
	* @param integer $local_key_expires 
	* @param integer $grace 
	* @return integer
	*/
	function calc_max_grace($local_key_expires, $grace)
		{
		return ((integer)$local_key_expires+((integer)$grace*86400));
		}

	/**
	* Process the grace period for the local key.
	* 
	* @param string $local_key 
	* @return string
	*/
	function process_grace_period($local_key)
		{
		// Get the local key expire date
		$local_key_src=$this->decode_key($local_key); 
		$parts=$this->split_key($local_key_src);
		$key_data=unserialize($parts[0]);
		$local_key_expires=(integer)$key_data['local_key_expires'];
		unset($parts, $key_data);

		// Build the grace period rules
		$write_new_key=false;
		$parts=explode("\n\n", $local_key); $local_key=$parts[0];
		foreach ($local_key_grace_period=explode(',', $this->local_key_grace_period) as $key => $grace)
			{
			// add the separator
			if (!$key) { $local_key.="\n"; }

			// we only want to log days past
			if ($this->calc_max_grace($local_key_expires, $grace)>time()) { continue; }

			// log the new attempt, we'll try again next time
			$local_key.="\n{$grace}";

			$write_new_key=true;
			}

		// Are we at the maximum limit? 
		if (time()>$this->calc_max_grace($local_key_expires, array_pop($local_key_grace_period)))
			{
			return array('write' => false, 'local_key' => '', 'errors' => $this->status_messages['maximum_grace_period_expired']);
			}

		return array('write' => $write_new_key, 'local_key' => $local_key, 'errors' => false);
		}

	/**
	* Are we still in a grace period?
	* 
	* @param string $local_key 
	* @param integer $local_key_expires 
	* @return integer
	*/
	function in_grace_period($local_key, $local_key_expires)
		{
		$grace=$this->split_key($local_key, "\n\n"); 
		if (!isset($grace[1])) { return -1; }

		return (integer)($this->calc_max_grace($local_key_expires, array_pop(explode("\n", $grace[1])))-time());
		}

	/**
	* Validate the local license key.
	* 
	* @param string $local_key 
	* @return string
	*/
	function decode_key($local_key)
		{
		return base64_decode(str_replace("\n", '', urldecode($local_key)));
		}

	/**
	* Validate the local license key.
	* 
	* @param string $local_key 
	* @param string $token		{spbas} or \n\n 
	* @return string
	*/
	function split_key($local_key, $token='{spbas}')
		{
		return explode($token, $local_key);
		}

	/**
	* Does the key match anything valid?
	* 
	* @param string $key
	* @param array $valid_accesses
	* @return array
	*/ 
	function validate_access($key, $valid_accesses)
		{
		return in_array($key, (array)$valid_accesses);
		}

	/**
	* Create an array of wildcard IP addresses
	* 
	* @param string $key
	* @param array $valid_accesses
	* @return array
	*/ 
	function wildcard_ip($key)
		{
		$octets=explode('.', $key);

		array_pop($octets);
		$ip_range[]=implode('.', $octets).'.*';

		array_pop($octets);
		$ip_range[]=implode('.', $octets).'.*';

		array_pop($octets);
		$ip_range[]=implode('.', $octets).'.*';

		return $ip_range;
		}

	/**
	* Create an array of wildcard IP addresses
	* 
	* @param string $key
	* @param array $valid_accesses
	* @return array
	*/ 
	function wildcard_domain($key)
		{
		return '*.'.str_replace('www.', '', $key);
		}

	/**
	* Create a wildcard server hostname
	* 
	* @param string $key
	* @param array $valid_accesses
	* @return array
	*/ 
	function wildcard_server_hostname($key)
		{
		$hostname=explode('.', $key);
		unset($hostname[0]);

		$hostname=(!isset($hostname[1]))?array($key):$hostname;

		return '*.'.implode('.', $hostname);
		}

	/**
	* Extract a specific set of access details from the instance
	* 
	* @param array $instances
	* @param string $enforce
	* @return array
	*/ 
	function extract_access_set($instances, $enforce)
		{
		foreach ($instances as $key => $instance)
			{
			if ($key!=$enforce) { continue; }
			return $instance;
			}

		return array();
		}

	/**
	* Validate the local license key.
	* 
	* @param string $local_key 
	* @return string
	*/
	function validate_local_key($local_key)
		{
		// Convert the license into a usable form.
		$local_key_src=$this->decode_key($local_key); 
		
		// Break the key into parts.
		$parts=$this->split_key($local_key_src);

		// If we don't have all the required parts then we can't validate the key.
		if (!isset($parts[1]))
			{
				return $this->errors=$this->status_messages['local_key_tampering'];
			}

		// Make sure the data wasn't forged.
		if (md5($this->secret_key.$parts[0])!=$parts[1])
			{
				return $this->errors=$this->status_messages['local_key_tampering'];
			}
		unset($this->secret_key);

		// The local key data in usable form.
		$key_data=unserialize($parts[0]);
		$instance=$key_data['instance']; unset($key_data['instance']);
		$enforce=$key_data['enforce']; unset($key_data['enforce']);
		$this->key_data=$key_data;
		//var_dump($key_data);
		// Make sure this local key is valid for the license key string
		if ((string)$key_data['license_key_string']!=(string)$this->license_key)
			{
				return $this->errors=$this->status_messages['license_key_string_mismatch'];
			}

		// Make sure we are dealing with an active license.
		if ((string)$key_data['status']!='active')
			{
			return $this->errors=$this->status_messages[$key_data['status']];
			}

		// License string expiration check
		if ((string)$key_data['license_expires']!='never'&&(integer)$key_data['license_expires']<time())
			{
			return $this->errors=$this->status_messages['expired'];
			}

		// Local key expiration check
		if ((string)$key_data['local_key_expires']!='never'&&(integer)$key_data['local_key_expires']<time())
			{
			if ($this->in_grace_period($local_key, $key_data['local_key_expires'])<0)
				{
				// It's absolutely expired, go remote for a new key!
				$this->clear_cache_local_key(true);
				return $this->validate();
				}
			}

		// Download access check
		if ($this->validate_download_access&&(integer)$key_data['download_access_expires']<strtotime($this->release_date))
			{
			return $this->errors=$this->status_messages['download_access_expired'];
			}

		// Is this key valid for this location?
		$conflicts=array(); 
		$access_details=$this->access_details();
		foreach ((array)$enforce as $key)
			{
			$valid_accesses=$this->extract_access_set($instance, $key);
			if (!$this->validate_access($access_details[$key], $valid_accesses))
				{
				$conflicts[$key]=true; 

				// check for wildcards
				if (in_array($key, array('ip', 'server_ip')))

					{
					foreach ($this->wildcard_ip($access_details[$key]) as $ip) 
						{
						if ($this->validate_access($ip, $valid_accesses))
							{
							unset($conflicts[$key]);
							break;
							}
						}
					}
				elseif (in_array($key, array('domain')))
					{
					if ($this->validate_access($this->wildcard_domain($access_details[$key]) , $valid_accesses))
						{
						unset($conflicts[$key]);
						}
					}
				elseif (in_array($key, array('server_hostname')))
					{
					if ($this->validate_access($this->wildcard_server_hostname($access_details[$key]) , $valid_accesses))
						{
						unset($conflicts[$key]);
						}
					}
				}
			}

		// Is the local key valid for this location?
		if (!empty($conflicts))
			{
				$this->clear_cache_local_key(true);
				return $this->errors=$this->status_messages['local_key_invalid_for_location'];
			}
		}

	/**
	* Read in a new local key from the database.
	* 
	* @return string
	 * $spbas->read_query=array('local_key' => get_option($inProduct->prefix.'_license_local_key'));
	 *
	*/
	function db_read_local_key()
		{
		$result=array();
		if (is_array($this->read_query)) { $result=$this->read_query; }
		else
			{
			$query=@mysql_query($this->read_query);
			if ($mysql_error=mysql_error()) { return $this -> errors="Error: {$mysql_error}"; }

			$result=@mysql_fetch_assoc($query);
			if ($mysql_error=mysql_error()) { return $this -> errors="Error: {$mysql_error}"; }
			}

		// is the local key empty?
		if (!$result['local_key'])
			{ 
			// Yes, fetch a new local key.
			$result['local_key']=$this->fetch_new_local_key();

			// did fetching the new key go ok?
			if ($this->errors) { return $this->errors; }

			// Write the new local key.
			$this->db_write_local_key($result['local_key']);
			}

		// return the local key
		return $this->local_key_last=$result['local_key'];
		}

	/**
	* Write the local key to the database.
	* 
	* @return string|boolean string on error; boolean true on success
	*/
	function db_write_local_key($local_key)
		{
		if (is_array($this->update_query))
			{
			$run=$this->update_query['function'];
			return $run($this->update_query['key'], $local_key);
			}

		@mysql_query(str_replace('{local_key}', $local_key, $this->update_query));
		if ($mysql_error=mysql_error()) { return $this -> errors="Error: {$mysql_error}"; }

		return true;
		}

	/**
	* Read in the local license key.
	* 
	* @return string
	*/
	function read_local_key()
		{ 
		if (!file_exists($path="{$this->local_key_path}{$this->local_key_name}"))
			{
			return $this -> errors=$this->status_messages['missing_license_file'].$path;
			}

		if (!is_writable($path))
			{
			return $this -> errors=$this->status_messages['license_file_not_writable'].$path;
			}

		// is the local key empty?
		if (!$local_key=@file_get_contents($path))
			{
			// Yes, fetch a new local key.
			$local_key=$this->fetch_new_local_key();

			// did fetching the new key go ok?
			if ($this->errors) { return $this->errors; }

			// Write the new local key.
			$this->write_local_key(urldecode($local_key), $path);
			}

		// return the local key
		return $this->local_key_last=$local_key;
		}

	/**
	* Clear the local key file cache by passing in ?clear_local_key_cache=y
	* 
	* @param boolean $clear 
	* @return string on error
	*/
	function clear_cache_local_key($clear=false)
		{
		switch(strtolower($this->local_key_storage))
			{
			case 'database':
				$this->db_write_local_key('');
				break;

			case 'filesystem':
				$this->write_local_key('', "{$this->local_key_path}{$this->local_key_name}");
				break;

			default:
				return $this -> errors=$this->status_messages['invalid_local_key_storage'];
			}
		}

	/**
	* Write the local key to a file for caching.
	* 
	* @param string $local_key 
	* @param string $path 
	* @return string|boolean string on error; boolean true on success
	*/
	function write_local_key($local_key, $path)
		{
		$fp=@fopen($path, 'w');
		if (!$fp) { return $this -> errors=$this->status_messages['could_not_save_local_key']; }
		@fwrite($fp, $local_key);
		@fclose($fp);

		return true;
		}

	/**
	* Query the API for a new local key
	*  
	* @return string|false string local key on success; boolean false on failure.
	*/
	function fetch_new_local_key()
		{
		// build a querystring
		$querystring="mod=license&task=SPBAS_validate_license&license_key={$this->license_key}&";
		$querystring.=$this->build_querystring($this->access_details());

		// was there an error building the access details?
		if ($this->errors) { return false; }

		$priority=$this->local_key_transport_order;
		while (strlen($priority)) 
			{
			$use=substr($priority, 0, 1);

			// try fsockopen()
			if ($use=='s') 
				{ 
				if ($result=$this->use_fsockopen($this->api_server, $querystring))
					{
					break;
					}
				}

			// try curl()
			if ($use=='c') 
				{
				if ($result=$this->use_curl($this->api_server, $querystring))
					{
					break;
					}
				}

			// try fopen()
			if ($use=='f') 
				{ 
				if ($result=$this->use_fopen($this->api_server, $querystring))
					{
					break;
					}
				}

			$priority=substr($priority, 1);
			}

		if (!$result) 
			{ 
			$this->errors=$this->status_messages['could_not_obtain_local_key']; 
			return false;
			}

		if (substr($result, 0, 7)=='Invalid') 
			{ 
			$this->errors=str_replace('Invalid', 'Error', $result); 
			return false;
			}

		if (substr($result, 0, 5)=='Error') 
			{ 
			$this->errors=$result; 
			return false;
			}

		return $result;
		}

	/**
	* Convert an array to querystring key/value pairs
	* 
	* @param array $array 
	* @return string
	*/
	function build_querystring($array)
		{
		$buffer='';
		foreach ((array)$array as $key => $value)
			{
			if ($buffer) { $buffer.='&'; }
			$buffer.="{$key}={$value}";
			}

		return $buffer;
		}

	/**
	* Build an array of access details
	* 
	* @return array
	*/
	function access_details()
		{
		$access_details=array();

		// Try phpinfo()
		if (function_exists('phpinfo'))
			{
			ob_start();
			phpinfo(INFO_GENERAL);
			phpinfo(INFO_ENVIRONMENT);
			$phpinfo=ob_get_contents();
			ob_end_clean();

			$list=strip_tags($phpinfo);
			$access_details['domain']=$this->scrape_phpinfo($list, 'HTTP_HOST');
			$access_details['ip']=$this->scrape_phpinfo($list, 'SERVER_ADDR');
			$access_details['directory']=$this->scrape_phpinfo($list, 'SCRIPT_FILENAME');
			$access_details['server_hostname']=$this->scrape_phpinfo($list, 'System');
			$access_details['server_ip']=@gethostbyname($access_details['server_hostname']);
			}

		// Try legacy.
		$access_details['domain']=($access_details['domain'])?$access_details['domain']:$_SERVER['HTTP_HOST'];
		$access_details['ip']=($access_details['ip'])?$access_details['ip']:$this->server_addr();
		$access_details['directory']=($access_details['directory'])?$access_details['directory']:$this->path_translated();
		$access_details['server_hostname']=($access_details['server_hostname'])?$access_details['server_hostname']:@gethostbyaddr($access_details['ip']);
		$access_details['server_hostname']=($access_details['server_hostname'])?$access_details['server_hostname']:'Unknown';
		$access_details['server_ip']=($access_details['server_ip'])?$access_details['server_ip']:@gethostbyaddr($access_details['ip']);
		$access_details['server_ip']=($access_details['server_ip'])?$access_details['server_ip']:'Unknown';

		// Last resort, send something in...
		foreach ($access_details as $key => $value)
			{
			$access_details[$key]=($access_details[$key])?$access_details[$key]:'Unknown';
			}

		// enforce product IDs
		if ($this->valid_for_product_tiers)
			{
			$access_details['valid_for_product_tiers']=$this->valid_for_product_tiers;
			}

		return $access_details;
		}

	/**
	* Get the directory path
	* 
	* @return string|boolean string on success; boolean on failure
	*/
	function path_translated()
		{
		$option=array('PATH_TRANSLATED', 
					'||IG_PATH_TRANSLATED', 
					'SCRIPT_FILENAME', 
					'DOCUMENT_ROOT',
					'APPL_PHYSICAL_PATH');

		foreach ($option as $key)
			{
			if (!isset($_SERVER[$key])||strlen(trim($_SERVER[$key]))<=0) { continue; }

			if ($this->is_windows()&&strpos($_SERVER[$key], '\\'))
				{
				return  @substr($_SERVER[$key], 0, @strrpos($_SERVER[$key], '\\'));
				}
			
			return  @substr($_SERVER[$key], 0, @strrpos($_SERVER[$key], '/'));
			}

		return false;
		}

	/**
	* Get the server IP address
	* 
	* @return string|boolean string on success; boolean on failure
	*/
	function server_addr()
		{
		$options=array('SERVER_ADDR', 'LOCAL_ADDR');
		foreach ($options as $key)
			{
			if (isset($_SERVER[$key])) { return $_SERVER[$key]; }
			}

		return false;
		}

	/**
	* Get access details from phpinfo()
	* 
	* @param array $all 
	* @param string $target
	* @return string|boolean string on success; boolean on failure
	*/
	function scrape_phpinfo($all, $target)
		{
		$all=explode($target, $all);
		if (count($all)<2) { return false; }
		$all=explode("\n", $all[1]);
		$all=trim($all[0]);

		if ($target=='System')
			{
			$all=explode(" ", $all);
			$all=trim($all[(strtolower($all[0])=='windows'&&strtolower($all[1])=='nt')?2:1]);
			}

		if ($target=='SCRIPT_FILENAME')
			{
			$slash=($this->is_windows()?'\\':'/');

			$all=explode($slash, $all);
			array_pop($all);
			$all=implode($slash, $all);
			}

		if (substr($all, 1, 1)==']') { return false; }

		return $all;
		}

	/**
	* Pass the access details in using fsockopen
	* 
	* @param string $url 
	* @param string $querystring
	* @return string|boolean string on success; boolean on failure
	*/
	function use_fsockopen($url, $querystring)
		{
		if (!function_exists('fsockopen')) { return false; }

		$url=parse_url($url);

		$fp=@fsockopen($url['host'], $this->remote_port, $errno, $errstr, $this->remote_timeout);
		if (!$fp) { return false; }

		$header="POST {$url['path']} HTTP/1.0\r\n";
		$header.="Host: {$url['host']}\r\n";
		$header.="Content-type: application/x-www-form-urlencoded\r\n";
		$header.="User-Agent: SPBAS (http://www.spbas.com)\r\n";
		$header.="Content-length: ".@strlen($querystring)."\r\n";
		$header.="Connection: close\r\n\r\n";
		$header.=$querystring;

		$result=false;
		fputs($fp, $header);
		while (!feof($fp)) { $result.=fgets($fp, 1024); }
		fclose ($fp);

		if (strpos($result, '200')===false) { return false; }

		$result=explode("\r\n\r\n", $result, 2);

		if (!$result[1]) { return false; }

		return $result[1];
		}

	/**
	* Pass the access details in using cURL
	* 
	* @param string $url 
	* @param string $querystring
	* @return string|boolean string on success; boolean on failure
	*/
	function use_curl($url, $querystring)
		{ 
		if (!function_exists('curl_init')) { return false; }

		$curl = curl_init();
		
		$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
		$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: ";
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'SPBAS (http://www.spbas.com)');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $querystring);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->remote_timeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->remote_timeout); // 60

		$result= curl_exec($curl);
		$info=curl_getinfo($curl);
		curl_close($curl);

		if ((integer)$info['http_code']!=200) { return false; }

		return $result;
		}

	/**
	* Pass the access details in using the fopen wrapper file_get_contents()
	* 
	* @param string $url 
	* @param string $querystring
	* @return string|boolean string on success; boolean on failure
	*/
	function use_fopen($url, $querystring)
		{ 
		if (!function_exists('file_get_contents')) { return false; }

		return @file_get_contents("{$url}?{$querystring}");
		}

	/**
	* Determine if we are running windows or not.
	* 
	* @return boolean
	*/
	function is_windows()
		{
		return (strtolower(substr(php_uname(), 0, 7))=='windows'); 
		}

	/**
	* Debug - prints a formatted array
	* 
	* @param array $stack The array to display
	* @param boolean $stop_execution
	* @return string 
	*/
	function pr($stack, $stop_execution=true)
		{
		$formatted='<pre>'.var_export((array)$stack, 1).'</pre>';

		if ($stop_execution) { die($formatted); }

		return $formatted;
		}
	}
// -----------------------------------------------------------------------------------------------------------------------------------------------------
// SPBAS Licensing