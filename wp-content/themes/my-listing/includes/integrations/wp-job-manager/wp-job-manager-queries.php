<?php
/**
 * WP Job Manager Queries.
 */

class CASE27_WP_Job_Manager_Queries extends CASE27_Ajax {

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
		$this->register_action('get_listing_quick_view');
		$this->register_action('get_listing_type_options_by_id');

		parent::__construct();
	}

	public function get_listing_type_options_by_id()
	{
		$postid = isset($_GET['postid']) ? $_GET['postid'] : false;

		if ( ! $postid || ! current_user_can( 'edit_post', $postid ) ) {
			return false;
		}

		return $this->json([
			'fields' => [
				'available' => $GLOBALS['case27_listing_fields']['job'],
				'used' => unserialize(get_post_meta($postid, 'case27_listing_type_fields', true)),
				],
			'single' => unserialize(get_post_meta($postid, 'case27_listing_type_single_page_options', true)),
			'result' => unserialize(get_post_meta($postid, 'case27_listing_type_result_template', true)),
			'search' => unserialize(get_post_meta($postid, 'case27_listing_type_search_page', true)),
			'settings' => unserialize(get_post_meta($postid, 'case27_listing_type_settings_page', true)),
		]);
	}

	public function get_listing_quick_view() {
		if (!isset($_REQUEST['listing_id']) || !$_REQUEST['listing_id']) return;

		$listing = get_post(absint((int) $_REQUEST['listing_id']));

		if (!$listing || $listing->post_type !== 'job_listing') return;

		ob_start();

		c27()->get_partial('listing-quick-view', [
			'listing' => $listing,
			]);

		return $this->json([
			'html' => ob_get_clean(),
		]);
	}

	public function promoted_first_clause() {
		return [
			'relation' => 'OR',
			[
				'key' => '_case27_listing_promotion_end_date',
				'compare' => 'NOT EXISTS',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '<',
				'type' => 'DATETIME',
			],
			[
				'relation' => 'AND',
				[
					'key' => '_case27_listing_promotion_start_date',
					'value' => date('Y-m-d H:i:s'),
					'compare' => '<=',
					'type' => 'DATETIME',
				],
				'c27_promoted_clause_end_date' => [
					'key' => '_case27_listing_promotion_end_date',
					'value' => date('Y-m-d H:i:s'),
					'compare' => '>=',
					'type' => 'DATETIME',
				],
			],
		];
	}

	public function promoted_only_clause() {
		return [
			'relation' => 'AND',
			[
				'key' => '_case27_listing_promotion_start_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '<=',
				'type' => 'DATETIME',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '>=',
				'type' => 'DATETIME',
			],
		];
	}

	public function hide_promoted_clause() {
		return [
			'relation' => 'OR',
			[
				'key' => '_case27_listing_promotion_start_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '>',
				'type' => 'DATETIME',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'value' => date('Y-m-d H:i:s'),
				'compare' => '<',
				'type' => 'DATETIME',
			],
			[
				'key' => '_case27_listing_promotion_end_date',
				'compare' => 'NOT EXISTS',
			],
		];
	}
}

new CASE27_WP_Job_Manager_Queries;