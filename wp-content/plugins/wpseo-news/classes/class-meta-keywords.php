<?php

class WPSEO_News_Meta_Keywords {

	/**
	 * The holder for the keywords
	 *
	 * @var array
	 */
	public $keywords = array();

	/**
	 * The holder for current item_id
	 *
	 * @var integer
	 */
	private $item_id;

	/**
	 * Getting the keywords for given $item_id
	 *
	 * @param integer $item_id
	 *
	 * @return mixed
	 */
	public function __construct( $item_id ) {

		$this->item_id = $item_id;

		// Get the keywords for current item_id
		$this->item_keywords();

		// Get the tags for current item_id
		$this->get_the_terms();

		// Get the default keywords for options
		$this->get_default_keywords();

		// Sanitize the list of keywords
		$this->sanitize_keywords();
	}

	/**
	 * Because of the result of this object will be printed as a string, we need a method that converts the object to a string
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->keywords_as_string();;
	}

	/**
	 * Getting the keywords for current item
	 */
	private function item_keywords() {
		$this->add_keywords( WPSEO_Meta::get_value( 'newssitemap-keywords', $this->item_id ) );
	}

	/**
	 * Getting the terms for this->item_id
	 *
	 * Each term will be added to this->keywords
	 *
	 */
	private function get_the_terms() {
		$tags = get_the_terms( $this->item_id, 'post_tag' );
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				$this->add_keyword( $tag->name );
			}
		}
	}

	/**
	 * If there are default keywords, use these also in keyword string
	 */
	private function get_default_keywords() {
		$options = WPSEO_News::get_options();

		// TODO: add suggested keywords to each post based on category, next to the entire
		if ( isset( $options['default_keywords'] ) && $options['default_keywords'] != '' ) {
			$this->add_keywords( $options['default_keywords'] );
		}
	}

	/**
	 * Adding the keywords to this->keywords
	 *
	 * If $keywords is not an array explode the comma
	 *
	 * @param mixed $keywords
	 */
	private function add_keywords( $keywords ) {
		if ( ! is_array( $keywords ) ) {
			$keywords = explode( ',', trim( $keywords ) );
		}

		$this->keywords = array_merge( $this->keywords, $keywords );
	}

	/**
	 * Adding a singe keyword to this->keywords
	 *
	 * @param string $keyword
	 */
	private function add_keyword( $keyword ) {
		array_push( $this->keywords, $keyword );
	}

	/**
	 * Sanitize this->keywords, first make the array unique and then sanitize each keyword of it
	 */
	private function sanitize_keywords() {
		// Sanitize each keyword
		$this->keywords = array_map( array( $this, 'sanitize_keyword' ), $this->keywords );

		// Make the list of keywords unique
		$this->keywords = array_unique( $this->keywords );
	}

	/**
	 * This method will lowercase the whole keyword and trim spaces before and after it.
	 *
	 * @param string $keyword
	 *
	 * @return string
	 */
	private function sanitize_keyword( $keyword ) {
		$keyword = strtolower( trim( $keyword ) );

		return $keyword;
	}

	/**
	 * Convert this->keywords to a string
	 *
	 * @return string
	 */
	private function keywords_as_string() {
		return trim( implode( ', ', $this->keywords ), ', ' );
	}
}
