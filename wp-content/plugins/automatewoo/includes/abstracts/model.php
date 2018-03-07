<?php

namespace AutomateWoo;

/**
 * @class Model
 *
 * @property $id
 */
abstract class Model {

	/** @var string : required for every model and must have a corresponding AW_Database_Table */
	public $table_id;

	/** @var string|null : set if model supports meta data */
	public $meta_table_id;

	/** @var bool */
	public $exists = false;

	/** @var array */
	public $data = [];

	/** @var array - data as it last existed in the database */
	public $original_data = [];

	/** @var array */
	public $changed_fields = [];

	/** @var array */
	public $meta_cache = [];

	/** @var string */
	public $object_type;


	/**
	 * @return int
	 */
	function get_id() {
		return $this->id ? (int) $this->id : 0;
	}


	/**
	 * @param int $id
	 */
	function set_id( $id ) {
		$this->id = $id;
	}


	/**
	 * Fill model with data
	 *
	 * @param array $row
	 */
	function fill( $row ) {

		if ( ! is_array( $row ) ) {
			return;
		}

		// remove meta columns
		if ( $meta_table = $this->get_meta_table() ) {
			unset( $row[ 'meta_key' ] );
			unset( $row[ 'meta_value' ] );
			unset( $row[ 'meta_id' ] );
			unset( $row[ $meta_table->object_id_column ] );
		}

		$this->data = $row;
		$this->original_data = $row;
		$this->exists = true;

		do_action( 'automatewoo/object/load', $this );
	}


	/**
	 * @param $value string|int
	 * @param $field string
	 */
	function get_by( $field, $value ) {

		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare( "
				SELECT * FROM {$this->get_table_name()}
		 		WHERE $field = %s
			", $value
			), ARRAY_A
		);

		if ( ! $row )
			return;

		$this->fill( $row );
	}


	/**
	 * Magic method for accessing db fields
	 *
	 * @param string $key
	 * @return mixed
	 */
	function __get( $key ) {
		return $this->get_prop( $key );
	}


	/**
	 * Magic method for setting db fields
	 *
	 * @param $key
	 * @param $value
	 */
	function __set( $key, $value ) {
		$this->set_prop( $key, $value );
	}


	/**
	 * @param $key
	 * @param $value
	 */
	function set_prop( $key, $value ) {

		if ( is_array( $value ) && ! $value ) {
			$value = ''; // convert empty arrays to blank
		}

		$this->data[$key] = $value;
		$this->changed_fields[] = $key;
	}


	/**
	 * @param $key
	 * @return bool
	 */
	function has_prop( $key ) {
		return isset( $this->data[$key] );
	}


	/**
	 * @param $key
	 * @return mixed
	 */
	function get_prop( $key ) {
		if ( ! isset( $this->data[$key] ) ) {
			return false;
		}

		$value = $this->data[$key];
		$value = maybe_unserialize( $value );

		return $value;
	}


	/**
	 * @return Database_Table
	 */
	function get_table() {

		if ( ! isset( $this->table_id ) ) {
			trigger_error( sprintf( 'AutomateWoo - %s is an incompatible subclass of %s. You may need need to update your AutomateWoo add-ons.', get_called_class(), get_class()), E_USER_ERROR );
		}

		return AW()->database_tables()->get_table( $this->table_id );
	}


	/**
	 * @return string
	 */
	function get_table_name() {
		return $this->get_table()->name;
	}


	/**
	 * @return Database_Table|false
	 */
	function get_meta_table() {
		if ( ! $this->meta_table_id ) return false;
		return AW()->database_tables()->get_table( $this->meta_table_id );
	}


	/**
	 * @return string
	 */
	function get_meta_table_name() {
		if ( ! $this->meta_table_id ) return false;
		return $this->get_meta_table()->name;
	}


	/**
	 * @return string
	 */
	function get_meta_object_id_column() {
		if ( ! $this->meta_table_id ) return false;
		return $this->get_meta_table()->object_id_column;
	}


	/**
	 * Inserts or updates the model
	 * Only updates modified fields
	 *
	 * @return void
	 */
	function save() {

		global $wpdb;

		if ( $this->exists ) {
			// update changed fields
			$changed_data = array_intersect_key( $this->data, array_flip( $this->changed_fields ) );

			// serialize
			$changed_data = array_map( 'maybe_serialize', $changed_data );

			if ( empty( $changed_data ) )
				return;

			$wpdb->update(
				$this->get_table_name(),
				$changed_data,
				[ 'id' => $this->get_id() ],
				null,
				[ '%d' ]
			);

			do_action( 'automatewoo/object/update', $this ); // cleans object cache
		}
		else {
			$this->data = array_map( 'maybe_serialize', $this->data );

			// insert row
			$wpdb->insert(
				$this->get_table_name(),
				$this->data
			);

			$this->exists = true;
			$this->id = $wpdb->insert_id;

			do_action( 'automatewoo/object/create', $this ); // cleans object cache
		}

		// reset changed data
		// important reset after cache hooks
		$this->changed_fields = [];
		$this->original_data = $this->data;
	}


	/**
	 * @return void
	 */
	function delete() {

		global $wpdb;

		do_action( 'automatewoo/object/delete', $this ); // cleans object cache

		if ( ! $this->exists ) return;

		if ( $this->get_meta_table_name() ) {
			$wpdb->query($wpdb->prepare( "
                DELETE FROM {$this->get_meta_table_name()}
		 		WHERE {$this->get_meta_object_id_column()} = %d
			", $this->get_id()
			));
		}

		$wpdb->query( $wpdb->prepare( "
                DELETE FROM {$this->get_table_name()}
		 		WHERE id = %d
			", $this->get_id()
		));

		$this->exists = false;
	}


	/**
	 * Return false if field does not exist return empty string if field is empty
	 *
	 * @param $key
	 * @return mixed
	 */
	function get_meta( $key ) {

		if ( ! $this->get_meta_table_name() )
			return false;

		// check meta cache
		if ( isset( $this->meta_cache[$key] ) )
			return $this->meta_cache[$key];

		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare( "
                SELECT meta_value FROM {$this->get_meta_table_name()}
		 		WHERE {$this->get_meta_object_id_column()} = %d AND meta_key = %s
			", $this->get_id(), $key
			), ARRAY_A
		);

		$value = $row ? maybe_unserialize( $row[ 'meta_value' ] ) : false;

		$this->meta_cache[$key] = $value;
		return $value;
	}


	/**
	 * Updates meta if it already exists other wise inserts new meta row.
	 *
	 * Note: Objects they must be saved before adding meta
	 *
	 * @param $key
	 * @param $value
	 */
	function update_meta( $key, $value ) {

		if ( ! $this->can_add_meta() )
			return;

		global $wpdb;

		$data = $this->generate_meta_array( $key, $value );
		$data_format = [ '%d', '%s', '%s' ];

		if ( $existing_meta_id = $this->has_meta( $key ) ) {
			$wpdb->update(
				$this->get_meta_table_name(),
				$data,
				[ 'meta_id' => $existing_meta_id ],
				$data_format
			);
		}
		else {
			$wpdb->insert(
				$this->get_meta_table_name(),
				$data,
				$data_format
			);
		}

		// update cache
		$this->meta_cache[$key] = $value;
	}


	/**
	 * Does the object have a certain meta key
	 * @param string $key
	 * @return bool|int returns false or meta_id if exists
	 */
	function has_meta( $key ) {

		if ( ! $this->can_add_meta() || ! $key ) {
			return false;
		}

		global $wpdb;

		$row = $wpdb->get_row( $wpdb->prepare(
			"SELECT meta_id FROM {$this->get_meta_table_name()} WHERE {$this->get_meta_object_id_column()} = %d AND meta_key = %s",
			$this->get_id(), $key
		));

		return $row ? absint( $row->meta_id ) : false;
	}


	/**
	 * Adds meta without checking if it already exists - use with caution
	 *
	 * Note: Objects they must be saved before adding meta
	 *
	 * @param $key
	 * @param $value
	 */
	function add_meta( $key, $value ) {

		if ( ! $this->can_add_meta() ) return;
		if ( ! $key ) return;

		global $wpdb;

		$data = $this->generate_meta_array( $key, $value );
		$data_format = [ '%d', '%s', '%s' ];

		$wpdb->insert(
			$this->get_meta_table_name(),
			$data,
			$data_format
		);

		// update cache
		$this->meta_cache[$key] = $value;
	}


	/**
	 * @param $key
	 * @param $value
	 *
	 * @return array
	 */
	private function generate_meta_array( $key, $value ) {
		return [
			$this->get_meta_object_id_column()=> $this->get_id(),
			'meta_key' => $key,
			'meta_value' => maybe_serialize( $value )
		];
	}


	/**
	 * Check if the modal supports meta and is ready to save meta.
	 *
	 * @return bool
	 */
	private function can_add_meta() {

		if ( ! $this->exists ) {
			_doing_it_wrong( __FUNCTION__, __( 'Object must be saved before adding meta.', 'automatewoo' ), '2.1.0' );
			return false;
		}

		if ( ! $this->get_meta_table_name() || ! $this->get_meta_object_id_column() )
			return false;

		return true;
	}


	/**
	 * @param $column
	 * @return bool|\DateTime
	 */
	protected function get_date_column( $column ) {
		if ( $column && $prop = $this->get_prop( $column ) ) {
			return new \DateTime( $prop );
		}

		return false;
	}


	/**
	 * @param string $column
	 * @param \DateTime|string $value - string must be mysql formatted
	 */
	protected function set_date_column( $column, $value ) {
		if ( $value instanceof \DateTime ) {
			$this->set_prop( $column, $value->format( Format::MYSQL ) );
		}
		elseif ( $value ) {
			$this->set_prop( $column, Clean::string( $value ) );
		}
	}
}
