<?php
/**
 * CP_V2_Tab_Menu.
 *
 * @package ConvertPro
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Class bsf menu.
 */
class CP_V2_Tab_Menu extends Bsf_Menu {

	/**
	 * Action
	 *
	 * @var action
	 */
	public $action;

	/**
	 * Constructor
	 *
	 * @param string $action string parameter.
	 */
	function __construct( $action = '' ) {

		$position = esc_attr( get_option( 'bsf_menu_position' ) );

		if ( $position ) {
			parent::$default_menu_position = $position;
		}

		parent::$is_top_level_page = in_array( parent::$default_menu_position, array( 'top', 'middle', 'bottom' ), true );

		if ( '' !== $action ) {
			self::cp_render_tab_menu( $action );
		}

	}

	/**
	 * Function Name: cp_render_tab_menu.
	 * Function Description: Render tab menu.
	 *
	 * @param string $action string parameter.
	 */
	static public function cp_render_tab_menu( $action = '' ) {

		self::render( $action );
	}

	/**
	 * Function Name: render.
	 * Function Description: Prints HTML content for tabs.
	 *
	 * @param string $action string parameter.
	 */
	static public function render( $action ) {

		?>
		<div class="nav-tab-wrapper">
			<?php
			$view_actions = apply_filters( 'bsf_menu_options', parent::$view_actions );

			foreach ( $view_actions as $slug => $menu ) {

				$name = $menu['name'];
				$url  = self::get_page_url( $slug, $menu );

				if ( $slug == parent::$parent_page_slug ) {
					update_option( 'cp_parent_page_url', $url );
				}

				$active = ( $slug == $action ) ? 'nav-tab-active' : '';

				echo "<a class='nav-tab " . $active . "' href='" . $url . "'>" . $name . '</a>';
			}

			?>

		</div>
		<?php

		if ( isset( $_REQUEST['message'] ) && 'saved' == $_REQUEST['message'] ) {

			$message = __( 'Settings saved successfully!', 'convertpro' );

			echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', $message );
		}

	}

	/**
	 * Function Name: get_page_url.
	 * Function Description: get page url.
	 *
	 * @param string $menu_slug string parameter.
	 * @param bool   $menu bool parameter.
	 */
	public static function get_page_url( $menu_slug, $menu = false ) {

		$plugin_slug = parent::$plugin_slug;

		// Menu position.
		$position              = esc_attr( get_option( 'bsf_menu_position' ) );
		$menu_position         = ! $position ? 'middle' : $position;
		$chk_is_top_level_page = in_array( $menu_position, array( 'top', 'middle', 'bottom' ), true );

		if ( $chk_is_top_level_page ) {

			if ( $menu_slug == parent::$parent_page_slug ) {
				$url = admin_url( 'admin.php?page=' . $plugin_slug );
			} else {
				$url = admin_url( 'admin.php?page=' . $plugin_slug . '-' . $menu_slug );
			}

			if ( false !== $menu && false !== $menu['link'] ) {
				$url = $menu['link'];
			}
		} else {

			$parent_page = parent::$default_menu_position;

			if ( strpos( $parent_page, '?' ) !== false ) {
				$query_var = '&page=' . $plugin_slug;
			} else {
				$query_var = '?page=' . $plugin_slug;
			}
					$parent_page_url = admin_url( $parent_page . $query_var );

					$url = $parent_page_url . '&action=' . $menu_slug;

		}

		return $url;

	}
}
