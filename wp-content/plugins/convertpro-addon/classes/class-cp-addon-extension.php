<?php
/**
 * Convert Pro Addon Extension Class
 *
 * @package Convert Pro Addon
 */

/**
 * Provide Extension related data.
 *
 * @since 1.0
 */
final class CP_Addon_Extension {

	/**
	 * Provide Extension array().
	 *
	 * @return array()
	 * @since 1.0
	 */
	static public function get_extension() {

		$extensions = array(
			'connects'         => array(
				'title'       => __( 'Connects', 'convertpro-addon' ),
				'icon'        => CP_ADDON_URL . 'assets/img/connect.png',
				'description' => 'Connects addon allows you to integrate ' . CPRO_BRANDING_NAME . ' with your favorite email marketing service provider / mailer.',
				'default'     => false,
			),
			'ab-test'          => array(
				'title'       => __( 'A/B Test', 'convertpro-addon' ),
				'icon'        => CP_ADDON_URL . 'assets/img/ab-test.png',
				'description' => 'A/B Test addon allows you to compare and test any call-to-actions created using ' . CPRO_BRANDING_NAME . '.',
				'default'     => false,
			),
			'advanced-scripts' => array(
				'title'       => __( 'Advanced Scripts', 'convertpro-addon' ),
				'icon'        => CP_ADDON_URL . 'assets/img/script.png',
				'description' => 'The ' . CPRO_BRANDING_NAME . ' Advanced script addon allows you to add JavaScript at a particular event like form submission, close popup and more.',
				'default'     => false,
			),
			'grid'             => array(
				'title'       => __( 'Grid', 'convertpro-addon' ),
				'icon'        => CP_ADDON_URL . 'assets/img/grid.png',
				'description' => 'The ' . CPRO_BRANDING_NAME . ' Grid addon will let you add a grid within the editor so that you can manage the placement and alignment of text and elements on the panel.',
				'default'     => false,
			),
			'analytics'        => array(
				'title'       => __( 'Analytics', 'convertpro-addon' ),
				'icon'        => CP_ADDON_URL . 'assets/img/analytics.png',
				'description' => 'The Analytics addon makes it possible to integrate ' . CPRO_BRANDING_NAME . ' with Google Analytics and facilitates the transfer of data to and fro.',
				'default'     => false,
			),
			'import-export'    => array(
				'title'       => __( 'Import/Export', 'convertpro-addon' ),
				'icon'        => CP_ADDON_URL . 'assets/img/import-export.png',
				'description' => 'Import/Export addon allows you to export desired call to actions and export it to any other website.',
				'default'     => false,
			),
		);

		return apply_filters( 'cp_addon_extensions', $extensions );
	}

	/**
	 * Provide Enable Extension array().
	 *
	 * @return array()
	 * @since 1.0
	 */
	static public function get_enabled_extension() {

		$enabled_data = array();

		$extensions         = self::get_extension();
		$enabled_extensions = CP_Addon_Admin_Helper::get_admin_settings_option( '_cp_addon_enabled_extensions' );

		if ( empty( $enabled_extensions ) ) {

			foreach ( $extensions as $slug => $data ) {
				$enabled_data[ $slug ] = ( isset( $data['default'] ) ) ? $data['default'] : false;
			}
		} else {
			$enabled_data = $enabled_extensions;

			// add new key.
			foreach ( $extensions as $slug => $data ) {
				if ( ! array_key_exists( $slug, $enabled_extensions ) ) {
					$enabled_data[ $slug ] = ( isset( $data['default'] ) ) ? $data['default'] : false;
				}
			}
		}

		return apply_filters( 'cpro_ext_enabled_extensions', $enabled_data );
	}

	/**
	 * Check extension status
	 *
	 * @param string  $key      Key to find in Extensions Array.
	 * @param boolean $default  Default if Key not exist in Extensions Array.
	 * @return boolean
	 * @since 1.0
	 */
	static public function is_active( $key, $default = false ) {
		$extensions = self::get_enabled_extension();

		if ( array_key_exists( $key, $extensions ) && $extensions[ $key ] ) {
			return true;
		} else {
			return $default;
		}
	}
}
