<?php

namespace AutomateWoo;

/**
 * Handles plugin updates.
 *
 * @class Updater
 */
class Updater {


	static function init() {
		add_filter( 'plugins_api', [ __CLASS__, 'check_info' ], 10, 3 );
		add_filter( 'pre_set_site_transient_update_plugins', [ __CLASS__, 'inject_updates' ] );
		add_action( 'in_plugin_update_message-' . AW()->plugin_basename, [ __CLASS__, 'in_plugin_update_message' ] );
		add_action( 'load-update-core.php', [ __CLASS__, 'maybe_force_check' ], 5 );

		foreach ( Addons::get_all() as $addon ) {
			add_action( 'in_plugin_update_message-' . $addon->plugin_basename, [ __CLASS__, 'in_plugin_update_message' ] );
		}
	}


	/**
	 * @param $transient
	 * @return array
	 */
	static function inject_updates( $transient ) {

		if ( ! isset( $transient->response ) ) {
			return $transient;
		}

		$plugins = [
			AW()->plugin_slug => [
				'version' => AW()->version,
				'basename' => AW()->plugin_basename
			],
		];

		foreach ( Addons::get_all() as $addon ) {
			$plugins[ $addon->id ] = [
				'version' => $addon->version,
				'basename' => $addon->plugin_basename
			];
		}

		foreach( $plugins as $plugin_slug => $plugin_data ) {

			if ( empty( $transient->response[ $plugin_data['basename'] ] ) ) {

				$latest_version = self::get_latest_version( $plugin_slug );

				if ( $latest_version ) {
					if ( version_compare( $plugin_data['version'], $latest_version, '<' ) ) {
						$transient->response[ $plugin_data['basename'] ] = self::get_plugin_update_info( $plugin_slug, $latest_version );
					}
					$transient->checked[ $plugin_data['basename'] ] = $latest_version;
				}
				else {
					$transient->checked[ $plugin_data['basename'] ] = $plugin_data['version'];
				}
			}
		}

		return $transient;
	}


	/**
	 * @param $plugin_slug
	 * @param $latest_version
	 * @return \stdClass
	 */
	static function get_plugin_update_info( $plugin_slug, $latest_version ) {

		$obj = new \stdClass();
		$obj->slug = $plugin_slug;
		$obj->new_version = $latest_version;
		$obj->url = AW()->website_url;

		if ( Licenses::is_primary( $plugin_slug ) ) {

			$obj->plugin = AW()->plugin_basename;

			if ( Licenses::is_active( $plugin_slug ) && ! Licenses::is_expired( $plugin_slug ) ) {
				$license = Licenses::get_primary_license();
				$obj->package = self::get_package_url( $license['key'], $plugin_slug );
			}
			else {
				$obj->upgrade_notice = self::get_update_notice( Licenses::is_expired( $plugin_slug ) ? 'expired' : 'no-license', true );
			}

		}
		else {

			$addon = Addons::get( $plugin_slug );
			$obj->plugin = $addon->plugin_basename;

			if ( Licenses::is_active( $addon->id ) && ! Licenses::is_expired( $addon->id ) ) {

				$license = Licenses::get_license( $addon->id );
				$obj->package = self::get_package_url( $license['key'], $addon->id );
			}
			else {
				$obj->upgrade_notice = self::get_update_notice( Licenses::is_expired( $addon->id ) ? 'expired' : 'no-license', true );
			}

		}

		return $obj;
	}


	/**
	 * @param $plugin_slug
	 * @return bool|string
	 */
	static function get_latest_version( $plugin_slug ) {
		$versions = self::get_latest_versions();
		return $versions && isset( $versions[$plugin_slug] ) ? $versions[$plugin_slug] : false;
	}


	/**
	 * @return array
	 */
	static function get_latest_versions() {

		if ( $cache = Cache::get_transient( 'latest_versions' ) ) {
			return $cache;
		}

		$response = Licenses::remote_get( 'app_versions' );

		if ( ! $response || ! isset( $response->success ) ) {
			return [];
		}

		$versions = Clean::recursive( (array) $response->versions );

		Cache::set_transient('latest_versions', $versions, 3 );

		return $versions;
	}



	/**
	 * @param $plugin_data array
	 */
	static function in_plugin_update_message( $plugin_data ) {

		$product_id = $plugin_data['slug'];
		$message = false;

		if ( Licenses::is_expired( $product_id ) ) {
			$message = self::get_update_notice( 'expired' );
		}
		elseif ( ! Licenses::is_active( $product_id ) ) {
			$message = self::get_update_notice( 'no-license' );
		}

		if ( ! $message ) {
			return;
		}

		echo self::wrap_plugin_update_message( $message );
	}



	static function maybe_force_check() {
		if ( ! empty( $_GET['force-check'] ) ) {
			Cache::delete_transient( 'latest_versions' );
		}
	}


	/**
	 * @param $false
	 * @param $action
	 * @param $arg
	 * @return mixed
	 */
	static function check_info( $false, $action, $arg ) {

		if ( isset( $arg->slug ) && $arg->slug === AW()->plugin_slug ) {
			if ( $response = Licenses::remote_get( 'app_wp_plugin_info' ) ) {
				if ( $response->success ) {
					$info = $response->info;

					$info->sections = get_object_vars( $info->sections );
					return $info;
				}
			}
		}
		return false;
	}


	/**
	 * @param $license_key
	 * @param $plugin_slug
	 * @return string
	 */
	static function get_package_url( $license_key, $plugin_slug ) {
		return add_query_arg([
			'wc-api' => 'licences',
			'request' => 'app_download',
			'license_key' => $license_key,
			'domain' => urlencode( Licenses::get_domain() ),
			'app_id' => $plugin_slug
		], AW()->website_url );
	}


	/**
	 * @param $type
	 * @param bool $plain
	 * @return string
	 */
	static function get_update_notice( $type, $plain = false ) {
		switch ( $type ) {

			case 'expired':
				if ( $plain ) {
					return __( "To enable this update you must renew your expired license via the AutomateWoo account area.", 'automatewoo' );
				}
				else {
					return sprintf(
						__('<strong>Your license has expired.</strong> To enable this update please renew your license via the <%s>AutomateWoo account area<%s> then visit the <%s>licenses screen<%s> to refresh the status.', 'automatewoo'),
						'a href="' . Licenses::get_renewal_url( 'expired-license-update' ) . '"', '/a',
						'a href="' . Admin::page_url( 'licenses' ) . '"', '/a'
					);
				}
				break;

			case 'no-license':

				if ( $plain ) {
					return __( "To enable this update you must enter your license key by visiting the the AutomateWoo > Settings > License screen.", 'automatewoo' );
				}
				else {
					return sprintf(
						__('To enable this update please enter your license key by visiting the <%s>AutomateWoo Licenses<%s> screen.', 'automatewoo'),
						'a href="' . Admin::page_url( 'licenses' ) . '"', '/a'
					);
				}
				break;
		}
	}


	/**
	 * @param $message
	 * @return string
	 */
	static function wrap_plugin_update_message( $message ) {
		return  '<br /><span class="automatewoo-plugin-table-update-message">' . $message . '</span>';
	}

}
