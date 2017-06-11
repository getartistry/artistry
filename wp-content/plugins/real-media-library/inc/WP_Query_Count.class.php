<?php
/**
 * fixed count bug when WPML in usage
 * 
 * @since 2.1.2
 */
class RML_WP_Query_Count extends WP_Query 
{       
    public function __construct( $args = array() )
    {
        add_filter( 'posts_request',    array( $this, 'posts_request'   ) );
        add_filter( 'posts_orderby',    array( $this, 'posts_orderby'   ) );
        add_filter( 'post_limits',      array( $this, 'post_limits'     ) );
        add_action( 'pre_get_posts',    array( $this, 'pre_get_posts'   ) );
    
        parent::__construct( $args );
    }
    
    public function count()
    {
        if( isset( $this->posts[0] ) )
            return $this->posts[0];
    
        return '';          
    }
    
    public function posts_request( $request )
    {
        remove_filter( current_filter(), array( $this, __FUNCTION__ ) );
        return sprintf( 'SELECT COUNT(*) FROM ( %s ) as t', $request );
    }
    
    public function pre_get_posts( $q )
    {
        $q->query_vars['fields'] = 'ids';
        remove_action( current_filter(), array( $this, __FUNCTION__ ) );
    }
    
    public function post_limits( $limits )
    {
        remove_filter( current_filter(), array( $this, __FUNCTION__ ) );
        return '';
    }
    
    public function posts_orderby( $orderby )
    {
        remove_filter( current_filter(), array( $this, __FUNCTION__ ) );
        return '';
    }
}