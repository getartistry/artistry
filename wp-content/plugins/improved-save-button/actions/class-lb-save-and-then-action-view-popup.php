<?php

/**
 * Copyright 2017 Label Blanc (http://www.labelblanc.ca/)
 *
 * This file is part of the "Improved Save Button"
 * Wordpress plugin.
 *
 * The "Improved Save Button" Wordpress plugin
 * is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 'Save and view (popup)' action: after saving the post, the same post
 * editing page is shown, but a popup is opened with the post's frontend
 * page.
 */
class LB_Save_And_Then_Action_View_Popup extends LB_Save_And_Then_Action {

	/**
	 * HTTP param added to the URL when we re-show the post editing
	 * page (after saving the post with this action) that triggers
	 * the JavaScrip that reloads the popup containing the post's
	 * frontend page.
	 */
	const HTTP_PARAM_RELOAD_POPUP = 'lb-sat-reload-popup';
	const HTML_ICON = '<span class="dashicons dashicons-external"></span>';

	/**
	 * Constructor. Adds some hooks.
	 */
	function __construct() {
		parent::__construct();
		add_action( 'post_submitbox_start', array( $this, 'post_submitbox_start' ) );
		add_filter( 'removable_query_args', array( get_called_class(), 'removable_query_args' ), 99 );
	}

	/**
	 * Adds the URL param HTTP_PARAM_RELOAD_POPUP to the list of
	 * URL params that can be removed after being used once.
	 * Called by the removable_query_args filter in the constructor.
	 * 
	 * @param  array $removable_query_args An array of parameters to remove from the URL.
	 * @return array                       The array with the added param.
	 */
	static function removable_query_args( $removable_query_args ) {
		$removable_query_args[] = self::HTTP_PARAM_RELOAD_POPUP;
		return $removable_query_args;
	}

	/**
	 * Adds 2 JavaScript to the page:
	 * - One that opens a new window when the user uses this action.
	 *   This window contains a text asking the user to wait for the
	 *   reload.
	 * - One that is called after the post edit page reloads (after
	 *   this action was used to save the post) and that reloads the
	 *   popup with the post's frontend page.
	 */
	function post_submitbox_start() {
		ob_start();
		$js_window_name = 'lb-save-and-then-post-preview';
		
		?>
		<script>
		<?php
		/*
		 * Script included when the post editing page reloads after this
		 * action was used (checks the HTTP_PARAM_RELOAD_POPUP parameter).
		 * The JS script reloads the popup (that was opened) with the post's
		 * frontend page url.
		 */
		if( isset( $_GET[ self::HTTP_PARAM_RELOAD_POPUP ] ) && $_GET[ self::HTTP_PARAM_RELOAD_POPUP ] === '1' ) :
		?>
			var url = "<?php echo get_permalink(); ?>",
				popupWindow = window.open( url, '<?php echo $js_window_name; ?>' );
		<?php endif;

		/*
		 * Script that listens to the post submit and opens a popup
		 * (asking the user to wait) if this action was used.
		 */
		?>
		jQuery(document).on('lb-save-and-then:submit', '#post', function( event, postEditForm ) {
			var action = postEditForm.getAction(),
				popupWindow;

			if( ! action || action.id !== "<?php echo $this->get_id(); ?>" ) {
				return;
			}

			popupWindow = window.open( '', '<?php echo $js_window_name; ?>' );
			popupWindow.document.open();
			popupWindow.document.write("<?php _ex('Please wait while the post is being saved. This window will refresh automatically.', 'Message shown in the new window when "Save and view (new window)" is used.', 'improved-save-button'); ?>");
			popupWindow.document.close();
		});
		</script>
		<?php

		$html = ob_get_contents();
		ob_end_clean();

		print $html;
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */		
	function get_name() {
		return sprintf( _x('Save and View %s (new window)', 'Action name (used in settings page). %s = new window icon', 'improved-save-button'), self::HTML_ICON );
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */	
	function get_id() {
		return 'labelblanc.viewPopup';
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */	
	function get_description() {
		return _x('Shows the <strong>post itself in a new window</strong> after save.', 'Action description (used in settings page)', 'improved-save-button');
	}

	/**
	 * @see LB_Save_And_Then_Action
	 */	
	function get_button_label_pattern( $post ) {
		// The first %s must be escaped, because it is not replaced by this sprintf
		return sprintf( _x('%%s and View %s', 'Button label (used in post edit page). %%s = "Publish" or "Update"; %s = new window icon', 'improved-save-button'), self::HTML_ICON );
	}

	/**
	 * Returns a title attribute that simply informs the
	 * user the post will open in a new window.
	 * 
	 * @see LB_Save_And_Then_Action
	 * @param WP_Post $post
	 */	
	function get_button_title( $post ) {
		return _x('The post will be shown in a new window.', 'Button title attribute (used in post edit page)', 'improved-save-button');
	}

	/**
	 * Returns the current redirect url, but adds the parameter to
	 * trigger the JavaScript popup reload.
	 *
	 * @see LB_Save_And_Then_Action
	 * @param  string $current_url
	 * @param  WP_Post $post
	 * @return string
	 */
	function get_redirect_url( $current_url, $post ) {
		return add_query_arg( self::HTTP_PARAM_RELOAD_POPUP, '1', $current_url );
	}
}