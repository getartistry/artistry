<?php

/**
 * Includes the database query functionality for the Map view of the UsersInsights page.
 */
class USIN_Coordinates_Query extends USIN_Query{

	/**
	 * Loads the coordinates for the users with a geolocation detected.
	 * @return array the results of the database query
	 */
	public function get_coordinates(){
		global $wpdb;
		
		$this->build_query();

		$results =  $wpdb->get_results( $this->query );

		return $results;

	}

	/**
	 * Builds the database query by including custom parts to load the coordinates
	 * and by using existing methods from the parent query class.
	 * @return [type] [description]
	 */
	public function build_query(){
		global $wpdb;

		$args = $this->args;
		$filters = $this->filters;

		$this->set_query_select(array('coordinates', 'username'));

		$this->set_filters();

		$this->query .= $this->query_select;
		$this->query .= $this->get_query_joins();


		$this->query_where .= ' AND user_data.coordinates IS NOT NULL';

		$this->set_conditions();

	}
}