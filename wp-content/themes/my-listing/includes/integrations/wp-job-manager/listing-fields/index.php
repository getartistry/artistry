<?php
/**
 * Listing.
 */

class CASE27_Listings {

	private $listing_type = false, $listing = null;

	public function __construct()
	{
		add_action( 'case27_add_listing_form_template_start', [$this, 'setup_listing_fields'] );
		add_action( 'wp', [$this, 'setup_listing_fields'], 1 );
		add_action( 'save_post', [ $this, 'admin_save_fields' ], 10, 2 );
		add_action( 'job_manager_job_listing_data_start', [ $this, 'listing_meta_data_start' ] );
		add_action( 'job_manager_job_listing_data_end', [ $this, 'listing_meta_data_end' ] );
		add_filter( 'job_manager_job_listing_data_fields', [$this, 'admin_fields'] );
		add_filter( 'job_manager_job_listing_data_fields', [$this, 'admin_add_expire_field'], 20 );
		add_action( 'job_manager_save_job_listing', [ $this, 'save_listing_data' ], 30, 2 );
		add_filter( 'submit_job_form_fields_get_job_data', [ $this, 'populate_listing_type_field' ], 30, 2 );
		add_action( 'job_manager_update_job_data', [ $this, 'frontend_update_listing_data' ], 50, 2 );

		// Delete attachment on delete post. 'delete_post' hook is too late.
		add_action( 'before_delete_post', function( $post_id ) {
			if ( 'job_listing' !== get_post_type( $post_id ) ) {
				return;
			}

			// Get all attachments IDs. Maybe need settings to enable this.
			$att_ids = get_posts( array(
				'numberposts' => -1,
				'post_type'   => 'attachment',
				'fields'      => 'ids',
				'post_status' => 'any',
				'post_parent' => $post_id,
			) );

			// Delete each attachments.
			if ( $att_ids && is_array( $att_ids ) ) {
				foreach( $att_ids as $id ) {
					wp_delete_attachment( $id, true );
				}
			}
		} );

		add_action( 'init', function() {
			// Unset preview listing id stored in cookies.
			// @todo: Keep the cookie functionality, but only if the newly added listing
			// belongs to the selected listing type.
			if ( isset( $_GET['new'] ) && ! empty( $_GET['listing_type'] ) ) {
			    unset( $_COOKIE['wp-job-manager-submitting-job-id'] );
			    setcookie( 'wp-job-manager-submitting-job-id', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN, false );
			}
		});

		$this->show_custom_fields_in_admin();
	}

	public function show_custom_fields_in_admin() {
		$types = [
			'location', 'date', 'email', 'hidden', 'links',
			'number', 'related-listing', 'select-products', 'select-product', 'term-multiselect',
			'url', 'work-hours', 'listing-type-select', 'wp-editor', 'texteditor',
		];

		foreach ($types as $fieldType) {
			if ( file_exists( trailingslashit( CASE27_INTEGRATIONS_DIR ) . "wp-job-manager/templates/form-fields/admin/{$fieldType}.php" ) ) {
				add_action( "job_manager_input_{$fieldType}", function( $key, $field ) use ( $fieldType ) {
					require trailingslashit( CASE27_INTEGRATIONS_DIR ) . "wp-job-manager/templates/form-fields/admin/{$fieldType}.php";
				}, 10, 2);
			}
		}
	}


	/*
     * Setup filters for displaying fields on
     * listing submit form, edit form, and admin edit form.
	 */
	public function setup_listing_fields($listing_type)
	{
		if (isset($_POST['case27_listing_type']) && !empty($_POST['case27_listing_type'])) {
			$listing_type = $_POST['case27_listing_type'];
		}

		// If it's the edit form.
		if ( !empty($_GET['job_id']) ) {
			$listing_type = get_post_meta(absint( $_GET[ 'job_id' ] ), '_case27_listing_type', true);
		}

		if (!is_string($listing_type) || !$listing_type) {
			return;
		}

		$listing_type = get_posts([
			'name' => $listing_type,
			'post_type' => 'case27_listing_type',
			'post_status' => 'publish',
			]);

		if ($listing_type) {
			$this->listing_type = $listing_type[0];

			add_filter( 'submit_job_form_fields', array($this, 'submit_fields') );
		}
	}

	/*
     * Fields on Submit Listing Form.
	 */
	public function submit_fields($fields)
	{
		unset($fields['company']);

		// If it's the edit form.
		$listing_id = ! empty( $_GET['job_id'] ) ? absint( $_GET[ 'job_id' ] ) : 0;
		$listing = null;

		if ( $listing_id && ( $listing_obj = get_post( $listing_id ) ) ) {
			$listing = $listing_obj;
			// dump($listing);
		}

		// dump($_REQUEST['job_id']);
		// dump($_POST['job_package']);

		$new_fields = $this->get_submit_form_fields( $listing );

		foreach ($fields['job'] as $key => $field) {
			if (!isset($new_fields[$key])) {
				continue;
			}

			$new_fields[$key] = array_merge($field, $new_fields[$key]);
		}

		$new_fields['case27_listing_type'] = [
			'label' => __( 'Listing Type', 'my-listing' ),
			'type' => 'hidden',
			'required' => false,
			'slug' => 'case27_listing_type',
			'value' => $this->listing_type->post_name,
			'priority' => 1,
		];

		return ['job' => $new_fields, 'company' => []];
	}

	/*
     * Fields on Admin Edit Listing Form.
	 */
	public function admin_fields($fields) {
		global $post;

		$listing_type_field = [
			'label' => __( 'Listing Type', 'my-listing' ),
			'type' => 'listing-type-select',
			'required' => false,
			'slug' => 'case27_listing_type',
			'value' => '',
			'priority' => 1,
			'options' => array_merge( [ '' => '&mdash; Select Type &mdash;' ], c27()->get_posts_dropdown_array([
				'post_type' => 'case27_listing_type',
				'posts_per_page' => -1,
			], 'post_name') ),
		];

		if (!$post->_case27_listing_type) {
			$fields['_case27_listing_type'] = $listing_type_field;
			return $fields;
		}

		$listing_type = get_posts([
			'name' => $post->_case27_listing_type,
			'post_type' => 'case27_listing_type',
			'post_status' => 'publish',
			]);

		if (!$listing_type) {
			$fields['_case27_listing_type'] = $listing_type_field;
			return $fields;
		}

		$this->listing_type = $listing_type[0];

		$new_fields = $this->get_admin_form_fields( $post );

		foreach ($new_fields as $key => $field) {
			if (substr($key, 0, 1) !== '_') {
				$new_fields["_{$key}"] = $field;
				unset($new_fields[$key]);
			}
		}

		foreach ($fields as $key => $field) {
			if (!isset($new_fields[$key])) {
				continue;
			}

			$new_fields[$key] = array_merge($field, $new_fields[$key]);
		}

		$new_fields['_case27_listing_type'] = $listing_type_field;
		$new_fields['_case27_listing_type']['value'] = $this->listing_type->post_name;

		// dd($new_fields);

		if ( isset( $new_fields['_job_title'] ) ) {
			unset( $new_fields['job_title'] );
		}

		$new_fields = [
			'_case27_listing_type' => isset( $new_fields['_case27_listing_type'] ) ? $new_fields['_case27_listing_type'] : null,
			'_job_description' => isset( $new_fields['_job_description'] ) ? $new_fields['_job_description'] : null,
		] + $new_fields;

		$new_fields['_job_description']['priority'] = 0.2;
		$new_fields['_case27_listing_type']['priority'] = 0.3;

		// dump($new_fields);

		return array_filter( $new_fields );
	}

	public function admin_add_expire_field( $fields ) {
		$fields['_job_expires'] = [
			'slug' => '_job_expires',
			'label' => __( 'Listing Expiry Date', 'my-listing' ),
			'type' => 'text',
			'required' => false,
			'placeholder' => '',
			'priority' => 250,
			'description' => '',
		];

		return $fields;
	}


	/*
     * Get Fields list for current listing.
	 */
	public function get_fields()
	{
		return (array) unserialize(get_post_meta($this->listing_type->ID, 'case27_listing_type_fields', true));
	}


	/*
     * Get Submit Form fields for current listing.
	 */
	public function get_submit_form_fields( $listing = null )
	{
		$fields = array_filter( $this->get_fields(), function( $field ) {
			return isset( $field['show_in_submit_form'] ) && $field['show_in_submit_form'] == true;
		});

		return apply_filters( 'case27\listings\fields', $fields, $listing );
	}

	/*
     * Get Admin Form fields for current listing.
	 */
	public function get_admin_form_fields( $listing = null )
	{
		$fields = array_filter($this->get_fields(), function($field) {
			return isset($field['show_in_admin']) && $field['show_in_admin'] == true;
		});

		return apply_filters( 'case27\listings\fields\admin', $fields, $listing );
	}

	public function admin_save_fields( $post_id, $post ) {
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

        if ( ! empty( $_POST['_links'] ) ) {
        	update_post_meta( $post_id, '_links', $_POST['_links'] );
        }

        if ( ! empty( $_POST['_work_hours'] ) ) {
        	update_post_meta( $post_id, '_work_hours', $_POST['_work_hours'] );
        }
	}

	public function listing_meta_data_start( $postid ) {
		global $post;

		if ( empty( $post->_case27_listing_type ) ) {
			echo '<div><em>' . __( 'Select a listing type and update the listing for additional fields to show.' ) . '</em></div>';
			echo '<div class="no-listing-type">';
		}
	}

	public function listing_meta_data_end( $postid ) {
		global $post;

		if ( empty( $post->_case27_listing_type ) ) {
			echo '</div>';
		}
	}

	public function save_listing_data( $post_id, $post ) {
		foreach ( WP_Job_Manager_Writepanels::instance()->job_listing_fields() as $key => $field ) {
			$type = ! empty( $field['type'] ) ? $field['type'] : '';

			if ( $type == 'wp-editor' ) {
				// dd($field, $key, $_POST[$key], $_POST);
				update_post_meta( $post_id, $key, wp_kses_post( $_POST[ $key ] ) );
			}

			if ( $type == 'texteditor' ) {
				$editor_type = ! empty( $field['editor-type'] ) ? $field['editor-type'] : 'wp-editor';

				if ( $editor_type == 'wp-editor' ) {
					update_post_meta( $post_id, $key, wp_kses_post( $_POST[ $key ] ) );
				}

				if ( $editor_type == 'textarea' ) {
					update_post_meta( $post_id, $key, wp_kses_post( stripslashes( $_POST[ $key ] ) ) );
				}
			}

			if ( $key == '_job_location' ) {
				if ( ! empty( $_POST['_job_location'] ) && ! empty( $_POST['_job_location__latitude'] ) && ! empty( $_POST['_job_location__longitude'] ) ) {
					$lockpin   =  ! empty( $_POST['_job_location__lock_pin'] ) && $_POST['_job_location__lock_pin'] == 'yes';
					$latitude  = (float) $_POST['_job_location__latitude'];
					$longitude = (float) $_POST['_job_location__longitude'];

					if ( $latitude && $longitude && ( $latitude <= 90 ) && ( $latitude >= -90 ) && ( $longitude <= 180 ) && ( $longitude >= -180 ) ) {
						update_post_meta( $post_id, 'geolocation_lat', $latitude );
						update_post_meta( $post_id, 'geolocation_long', $longitude );
					}

					update_post_meta ( $post_id, 'job_location__lock_pin', $lockpin ? 'yes' : false );
				}
			}
		}

		// Set job_title field
		update_post_meta( $post_id, '_job_title', $post->post_title );

		// Avoid infinite loop.
		remove_action( 'job_manager_save_job_listing', [ \WP_Job_Manager_Writepanels::instance(), 'save_job_listing_data' ], 20, 2 );
		remove_action( 'job_manager_save_job_listing', [ $this, 'save_listing_data' ], 30, 2 );
		// Update post description to have the same value as 'job_description'
		wp_update_post( [
			'ID' => $post_id,
			'post_content' => get_post_meta( $post_id, '_job_description', true ),
		] );
		add_action( 'job_manager_save_job_listing', [ \WP_Job_Manager_Writepanels::instance(), 'save_job_listing_data' ], 20, 2 );
		add_action( 'job_manager_save_job_listing', [ $this, 'save_listing_data' ], 30, 2 );
	}

	public function populate_listing_type_field( $fields, $listing ) {
		// Make sure it's the "Add Listing" form.
		if ( ( 'preview' === $listing->post_status || 'pending_payment' === $listing->post_status ) && get_post_meta( $listing->ID, '_submitting_key', true ) === $_COOKIE['wp-job-manager-submitting-job-key'] ) {
			if ( ! empty( $_POST['case27_listing_type'] ) ) {
				$listing_type = $_POST['case27_listing_type'];
			} elseif ( ! empty( $_GET['listing_type'] ) ) {
				$listing_type = $_GET['listing_type'];
			} elseif ( ! empty( $_GET['type'] ) ) {
				$listing_type = $_GET['type'];
			} else {
				$listing_type = false;
			}

			if ( $listing_type && ! empty( $fields['job'] ) && ! empty( $fields['job']['case27_listing_type'] ) ) {
				$fields['job']['case27_listing_type']['value'] = $listing_type;
			}
		}

		if ( $description = get_post_meta( $listing->ID, '_job_description', true ) ) {
			$fields['job']['job_description']['value'] = $description;
		}

		return $fields;
	}

	public function frontend_update_listing_data( $listingID, $values ) {
		if ( isset( $_POST['job_description'] ) ) {
			update_post_meta( $listingID, '_job_description', wp_kses_post( $_POST['job_description'] ) );
		}
	}
}

$GLOBALS['case27_listings'] = new CASE27_Listings;