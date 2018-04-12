<?php
/**
 * Adds custom functionality to the Admin panel.
 */

class CASE27_Reports {

    protected static $_instance = null;

    public static function instance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


	public function __construct()
	{
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }

        add_filter( 'manage_case27_report_posts_columns', [ $this, 'admin_columns' ] );
        add_action( 'manage_case27_report_posts_custom_column', [ $this, 'admin_columns_content' ], 10, 2 );

        add_action( 'wp_ajax_report_listing', [ $this, 'report_listing' ] );
        add_action( 'wp_ajax_nopriv_report_listing', [ $this, 'report_listing' ] );
	}


    public function admin_columns( $columns )
    {
        unset( $columns['title'] );

        $columns = [
            'cb' => $columns['cb'],
            'reported_listing' => __( 'Listing', 'my-listing' ),
            'report_reason' => __( 'Reason', 'my-listing' ),
            'reported_by' => __( 'Reported By', 'my-listing' ),
            'date' => $columns['date'],
            'report_actions' => __( 'Actions', 'my-listing' ),
        ];

        return $columns;
    }


    public function admin_columns_content( $column, $post_id )
    {
        switch ( $column ) {
            case 'reported_listing':
                $listingID = get_post_meta( $post_id, '_report_listing_id', true );
                echo $listingID ? esc_html( get_the_title( $listingID ) ) : ( '<em>' . __( 'This listing does not exist.', 'my-listing' ) . '</em>' );
                break;

            case 'report_reason':
                echo c27()->the_text_excerpt( get_post_meta( $post_id, '_report_content', true ), 200 );
                break;

            case 'reported_by':
                $userID = get_post_meta( $post_id, '_report_user_id', true );
                $user = $userID ? get_user_by( 'id', $userID ) : false;

                echo $user ? $user->data->display_name : ( '<em>' . __( 'This account does not exist.', 'my-listing' ) . '</em>' );
            break;

            case 'report_actions':
                $listingID = get_post_meta( $post_id, '_report_listing_id', true );
                $review_link = $listingID ? get_permalink( $listingID ) : false;

                if ( $review_link ) {
                    printf( '<a href="%1$s" class="button button-primary button-large" title="%2$s" target="_blank"><i class="fa fa-eye"></i></a> ', $review_link, __( 'Review Listing', 'my-listing' ) );
                }

                printf( '<a href="%1$s" class="button button-large" title="%2$s"><i class="icon-pencil-2"></i></a> ',  get_edit_post_link( $post_id ), __( 'View Report', 'my-listing' ) );
                printf( '<a href="%1$s" class="button button-large" title="%2$s"><i class="fa fa-check"></i></a>',  get_delete_post_link( $post_id ), __( 'Close Report', 'my-listing' ) );

                //
                break;
        }
    }


	public function init_metabox()
	{
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
	}

	/**
     * Adds the meta box.
     */
    public function add_metabox() {
        add_meta_box(
            'case27-report',
            __( 'Report Details', 'my-listing' ),
            [ $this, 'render_metabox' ],
            'case27_report',
            'advanced',
            'high'
        );
    }

    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
        // dump(get_post_meta($post->ID));

        // Add nonce for security and authentication.
        wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );

        require_once CASE27_INTEGRATIONS_DIR . '/27collective/reports/views/metabox.php';
    }

    /**
     * Handles saving the meta box.
     */
    public function save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['custom_nonce'] ) ? $_POST['custom_nonce'] : '';
        $nonce_action = 'custom_nonce_action';

        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }

        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        // update_post_meta($post_id, 'case27_listing_type_search_page', serialize($search_forms));
    }

    public function report_listing()
    {
        // Security nonce.
        check_ajax_referer( 'c27_ajax_nonce', 'security' );

        $listing_id = isset( $_POST['listing_id'] ) && $_POST['listing_id'] ? (int) $_POST['listing_id'] : false;
        $report_content = isset( $_POST['content'] ) && $_POST['content'] ? sanitize_textarea_field( $_POST['content'] ) : false;
        $user_id = get_current_user_id();

        if ( ! $listing_id || ! $report_content ) {
            return c27('Ajax')->json(['status' => 'validation_error', 'message' => __( 'Please fill in all the necessary data.', 'my-listing' )]);
        }

        // Check if current user is authorized to perform this action.
        if ( ! is_user_logged_in() || ! $user_id || ! $listing_id ) {
            return c27('Ajax')->json(['status' => 'unauthorized', 'message' => __( 'You need to be logged in to perform this action.', 'my-listing' )]);
        }

        $report_exists = get_posts([
            'post_type' => 'case27_report',
            'post_status' => 'publish',
            'meta_query' => [
                [ 'key' => '_report_listing_id', 'value' => $listing_id ],
                [ 'key' => '_report_user_id', 'value' => $user_id ],
            ],
            ]);

        if ( $report_exists ) {
            return c27('Ajax')->json(['status' => 'invalid_request', 'message' => __( 'You\'ve already reported this listing. It is currently being reviewed.', 'my-listing' )]);
        }

        // Insert report.
        $report_id = wp_insert_post([
            'post_type' => 'case27_report',
            'post_author' => $user_id,
            'post_title' => __( 'New user report submitted.', 'my-listing' ),
            'post_status' => 'publish',
            'meta_input' => [
                '_report_listing_id' => $listing_id,
                '_report_user_id' => $user_id,
                '_report_content' => $report_content,
            ],
            ]);

        if ( $report_id ) {
            return c27('Ajax')->json(['status' => 'success', 'message' => __( 'Your report was submitted successfully. It will be reviewed by our team.', 'my-listing' )]);
        }

        return c27('Ajax')->json(['status' => 'invalid_request', 'message' => __( 'There was an error with processing your request.', 'my-listing' )]);
    }
}

CASE27_Reports::instance();