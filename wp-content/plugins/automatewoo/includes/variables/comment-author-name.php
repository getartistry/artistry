<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Variable_Comment_Author_Name
 */
class AW_Variable_Comment_Author_Name extends AutomateWoo\Variable {


	function load_admin_details() {
		$this->description = __( "Displays the name of the comment author.", 'automatewoo');
	}


	/**
	 * @param $comment WP_Comment
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $comment, $parameters ) {
		return $comment->comment_author;
	}
}

return new AW_Variable_Comment_Author_Name();
