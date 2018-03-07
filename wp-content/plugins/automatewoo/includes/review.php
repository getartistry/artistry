<?php

namespace AutomateWoo;

/**
 * @class Review
 * @since 3.0.0
 */
class Review {

	/** @var int */
	private $comment_id = 0;

	/** @var int  */
	private $user_id = 0;

	/** @var string */
	private $email;

	/** @var int  */
	private $product_id = 0;

	/** @var \WP_Comment */
	private $comment;


	/**
	 * @param \WP_Comment|int $comment
	 */
	function __construct( $comment ) {

		if ( is_numeric( $comment ) ) {
			$comment = get_comment( $comment );
		}

		if ( $comment ) {
			$this->comment = $comment;
			$this->comment_id = (int) $comment->comment_ID;
			$this->user_id = (int) $comment->user_id;
			$this->product_id = (int) $comment->comment_post_ID;
			$this->email = Clean::email( $comment->comment_author_email );
		}
	}


	/**
	 * @return int
	 */
	function get_id() {
		return $this->comment_id;
	}


	/**
	 * @return int
	 */
	function get_product_id() {
		return $this->product_id;
	}


	/**
	 * @return int
	 */
	function get_user_id() {
		return $this->user_id;
	}


	/**
	 * @return int
	 */
	function get_email() {
		return $this->email;
	}


	/**
	 * @return string
	 */
	function get_content() {
		return Clean::textarea( $this->comment->comment_content );
	}


	/**
	 * @return int
	 */
	function get_rating() {
		return (int) get_comment_meta( $this->get_id(), 'rating', true );
	}


}
