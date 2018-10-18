<?php 
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
* premium_Templat_Tags class defines all the query of options of select box
* 
* Setting up the helper assets of the premium widgets
*
* @since 1.0
*/

class premium_Template_Tags {

	/**
	* Instance of this class 
	* @since 1.0
	*/

	protected static $instance;
	

	/**
	* $options is option field of select
	*
	* @access protected 
	*
	* @since 1.0
	*/
	protected $options;

	/**
	* set instance of this class
	*/

	public static function getInstance() {
		if( !static::$instance ) {
			static::$instance = new self;
		}
		return static::$instance;
	}


	public function get_all_post() {

		$post_types = get_post_types();
		$post_type_not__in = array('attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'elementor_library', 'post');

		foreach ( $post_type_not__in as $post_type_not ) {
			unset( $post_types[$post_type_not] );
		}
		$post_type = array_values( $post_types );
        

		$all_posts = get_posts( array(
                'posts_per_page'    => -1,
				'post_type'         => 'page',
			)
		);
		if( !empty( $all_posts ) && !is_wp_error( $all_posts ) ) {
			foreach ( $all_posts as $post ) {
				$this->options[ $post->ID ] = strlen( $post->post_title ) > 20 ? substr( $post->post_title, 0, 20 ).'...' : $post->post_title;
			}
		}
		return $this->options;
	}

	public function get_elementor_page_list(){
		$pagelist = get_posts(array(
			'post_type' => 'elementor_library',
			'showposts' => 999,
		));
        
		if ( ! empty( $pagelist ) && ! is_wp_error( $pagelist ) ){
			foreach ( $pagelist as $post ) {
				$options[ $post->ID ] = __( $post->post_title, 'premium-addons-for-elementor' );
			}
        update_option( 'temp_count', $options );
        return $options;
		}
	}
}