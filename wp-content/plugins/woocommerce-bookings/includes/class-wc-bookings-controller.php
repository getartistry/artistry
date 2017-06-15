<?php
/**
 * Gets bookings
 */
class WC_Bookings_Controller {

	/**
	 * Return all bookings for a product in a given range
	 * @param integer $start_date
	 * @param integer $end_date
	 * @param int  $product_or_resource_id
	 * @param bool $check_in_cart
	 *
	 * @return array
	 */
	public static function get_bookings_in_date_range( $start_date, $end_date, $product_or_resource_id = 0, $check_in_cart = true ) {
		$transient_name = 'book_dr_' . md5( http_build_query( array( $start_date, $end_date, $product_or_resource_id, $check_in_cart, WC_Cache_Helper::get_transient_version( 'bookings' ) ) ) );

		if ( false === ( $booking_ids = get_transient( $transient_name ) ) ) {
			$booking_ids = self::get_bookings_in_date_range_query( $start_date, $end_date, $product_or_resource_id, $check_in_cart );
			set_transient( $transient_name, $booking_ids, DAY_IN_SECONDS * 30 );
		}

		return array_map( 'get_wc_booking', wp_parse_id_list( $booking_ids ) );
	}

	/**
	 * Return an array of un-bookable buffer days
	 * @since 1.9.13
	 *
	 * @param  WC_Product_Booking|int $bookable_product
	 * @return array Days that are buffer days and therefor should be un-bookable
	 */
	public static function find_buffer_day_blocks( $bookable_product ) {
		if ( is_int( $bookable_product ) ) {
			$bookable_product = wc_get_product( $bookable_product );
		}
		if ( ! is_a( $bookable_product, 'WC_Product_Booking' ) ) {
			return array();
		}
		$booked            = WC_Bookings_Controller::find_booked_day_blocks( $bookable_product );
		$fully_booked_days = $booked['fully_booked_days'];
		$buffer_period     = $bookable_product->get_buffer_period();
		$buffer_days       = array();

		foreach ( $fully_booked_days as $date => $data ) {
			$next_day = strtotime( '+1 day', strtotime( $date ) );

			if ( array_key_exists( date( 'Y-n-j', $next_day ), $fully_booked_days ) ) {
				continue;
			}

			// x days after
			for ( $i = 1; $i < $buffer_period + 1; $i++ ) {
				$buffer_day = date( 'Y-n-j', strtotime( "+{$i} day", strtotime( $date ) ) );
				$buffer_days[ $buffer_day ] = $buffer_day;
			}
		}

		if ( $bookable_product->get_apply_adjacent_buffer() ) {
			foreach ( $fully_booked_days as $date => $data ) {
				$previous_day = strtotime( '-1 day', strtotime( $date ) );

				if ( array_key_exists( date( 'Y-n-j', $previous_day ), $fully_booked_days ) ) {
					continue;
				}

				// x days before
				for ( $i = 1; $i < $buffer_period + 1; $i++ ) {
					$buffer_day = date( 'Y-n-j', strtotime( "-{$i} day", strtotime( $date ) ) );
					$buffer_days[ $buffer_day ] = $buffer_day;
				}
			}
		}
		return $buffer_days;
	}

	/**
	 * Finds days which are partially booked & fully booked already.
	 *
	 * @param  WC_Product_Booking|int $bookable_product
	 * @return array( 'partially_booked_days', 'fully_booked_days' )
	 */
	public static function find_booked_day_blocks( $bookable_product ) {
		if ( is_int( $bookable_product ) ) {
			$bookable_product = wc_get_product( $bookable_product );
		}
		if ( ! is_a( $bookable_product, 'WC_Product_Booking' ) ) {
			return array(
				'partially_booked_days' => array(),
				'fully_booked_days'     => array(),
			);
		}

		$fully_booked_days     = array();
		$partially_booked_days = array();
		$find_bookings_for     = array( $bookable_product->get_id() );
		$resource_count        = 0;
		$available_qty         = 0;
		if ( $bookable_product->has_resources() ) {
			foreach ( $bookable_product->get_resources() as $resource ) {
				$find_bookings_for[] = $resource->get_id();
				$available_qty += $resource->get_qty();
				$resource_count ++;
			}
		}

		// Determine a min and max date
		$min_date = $bookable_product->get_min_date();
		$min_date = empty( $min_date ) ? array( 'unit' => 'minute', 'value' => 1 ) : $min_date ;
		$min_date = strtotime( "midnight +{$min_date['value']} {$min_date['unit']}", current_time( 'timestamp' ) );

		$max_date = $bookable_product->get_max_date();
		$max_date = empty( $max_date ) ? array( 'unit' => 'month', 'value' => 12 ) : $max_date;
		$max_date = strtotime( "+{$max_date['value']} {$max_date['unit']}", current_time( 'timestamp' ) );

		// Get existing bookings and go through them to set partial/fully booked days
		$existing_bookings = WC_Bookings_Controller::get_bookings_for_objects( $find_bookings_for, get_wc_booking_statuses( 'fully_booked' ), $min_date, $max_date );
		foreach ( $existing_bookings as $existing_booking ) {
			$check_date = $existing_booking->get_start();
			$end_date 	= $existing_booking->is_all_day() ? strtotime( 'tomorrow midnight', $existing_booking->end ) : $existing_booking->end;
			// Loop over all booked days in this booking
			while ( $check_date < $end_date ) {
				$js_date = date( 'Y-n-j', $check_date );

				// if the check date is in the past, unless we are looking at daily bookings, skip to the next one
				if ( $check_date < current_time( 'timestamp' ) && 'day' !== $bookable_product->get_duration_unit() ) {
					$check_date = strtotime( '+1 day', $check_date );
					continue;
				}

				// set the resource ID, main product always has resource of 0
				$resource_id = 0;
				$available_qty_persons = $bookable_product->get_max_persons() ? $bookable_product->get_max_persons() : INF;

				if ( $bookable_product->has_resources() ) {
					$resource_id = $existing_booking->get_resource_id();
					$resource    = $existing_booking->get_resource();

					if ( is_a( $resource, 'WC_Product_Booking_Resource' ) ) {
						// limit max persons by resource quantity
						$available_qty_persons = min( $available_qty_persons, intval( $resource->get_qty() ) );
					}
				}

				// Skip if we've already found this resource is unavailable
				if ( ! empty( $fully_booked_days[ $js_date ][ $resource_id ] ) ) {
					$check_date = strtotime( '+1 day', $check_date );
					continue;
				}

				$midnight                 = strtotime( 'midnight', $check_date ); // Midnight on the date being checked is 00:00 start of day.
				$before_midnight_tomorrow = strtotime( '23:59', $check_date );    // End of the date being checked, not the following morning.

				// Regardless of duration unit, we need to pass all blocks of bookings so that the availability rules are properly calculated against.
				$booking_start_and_end    = self::get_bookings_star_and_end_times( $existing_bookings );
				$blocks_in_range          = $bookable_product->get_blocks_in_range( $midnight, $before_midnight_tomorrow, array(), $resource_id, $booking_start_and_end );

				$available_blocks         = $bookable_product->get_available_blocks( $blocks_in_range, array(), $resource_id );

				// Check if date being checked has bookings. This compares the dates and determines which dates are fully booked on the calendar.
				$bookings_on_check_date    = self::filter_bookings_on_date( $existing_bookings, $check_date );
				$check_date_beginning      = strtotime( 'midnight', $check_date );
				if ( 'day' === $bookable_product->get_duration_unit()  ) {
					$slots_times_on_check_date = $bookable_product->get_blocks_in_range( $check_date_beginning, strtotime( '23:59', $check_date_beginning ), array(), $resource_id );
				} else {
					$slots_times_on_check_date = $bookable_product->get_blocks_in_range( $check_date_beginning, strtotime( '+1 day', $check_date_beginning ), array(), $resource_id );
				}
				$available_slots_on_check_date = count( $slots_times_on_check_date ) * $bookable_product->get_available_quantity();

				if ( $bookable_product->has_persons() ) {
					$persons_booked = 0;
					foreach ( $bookings_on_check_date as $booking ) {
						$persons_booked = (int) $persons_booked + $booking->get_persons_total();
					}
				}

				// Check fo fully booked non-person related
				if ( ! $bookable_product->has_resources() && ! $available_blocks
				     && count( $bookings_on_check_date ) >= $available_slots_on_check_date ) {
					$fully_booked_days[ $js_date ][ $resource_id ] = true;

					// If previous loop rev. assigned this as partial remove it as this is fully booked now
					if ( isset( $partially_booked_days[ $js_date ][ $resource_id ] ) ) {
						unset( $partially_booked_days[ $js_date ][ $resource_id ] );
						// Remove the date if there are no more entries
						if( empty( $partially_booked_days[ $js_date ] ) ) {
							unset( $partially_booked_days[ $js_date ] );
						}
					}
					// resource affects product in the next check so product also set
					if ( 1 === $resource_count || sizeof( $fully_booked_days[ $js_date ] ) === $resource_count ) {
						$fully_booked_days[ $js_date ][0] = true;
					}
				}
				//
				// Else if persons as bookings fully booked / only if a person counts as a booking
				//
				elseif ( isset( $persons_booked ) && $bookable_product->get_has_person_qty_multiplier()
						&&  $persons_booked >= $bookable_product->get_available_quantity() ) {

					if ( 'hour' !== $bookable_product->get_duration_unit() && 'minute' !== $bookable_product->get_duration_unit() ) {
						$fully_booked_days[ $js_date ][0] = true;
					} else if ( ! is_infinite( $available_qty_persons ) ) {
						//
						// If hour or minute blocks, we need to set a variable to subtract from for each $check_date
						// With each iteration we subtract the amount of persons in the booking from the total for the day
						// If we get to 0, the day is fully booked, else it is partially booked
						//
						if ( ! isset( $person_count[ $midnight ][ $resource_id ] ) ) {
							$person_count[ $midnight ][ $resource_id ] = intval( min( $available_qty_persons, INF ) * min( count( $blocks_in_range ), INF ) );
						}

						$person_count[ $midnight ][ $resource_id ]-= $existing_booking->get_persons_total();

						if ( 0 >= $person_count[ $midnight ][ $resource_id ] ) {
							$fully_booked_days[ $js_date ][ $resource_id ] = true;
							if ( isset( $partially_booked_days[ $js_date ][ $resource_id ] ) ) {
								unset( $partially_booked_days[ $js_date ][ $resource_id ] );
							}
						} else {
							$partially_booked_days[ $js_date ][ $resource_id ] = true;
						}
					}
				}
				//
				// Else if partially booked days cases
				//
				elseif ( sizeof( $available_blocks ) < sizeof( $blocks_in_range )
				         || count( $bookings_on_check_date ) < $bookable_product->get_available_quantity() ) {
					$partially_booked_days[ $js_date ][ $resource_id ] = true;
					// resource affects product in the next check so product also set
					if ( 1 === $resource_count || sizeof( $partially_booked_days[ $js_date ] ) === $resource_count ) {
						$partially_booked_days[ $js_date ][0] = true;
					}
				}
				//
				// Else if resources booked when customer selected resource assignment setting is turned on.
				//
				elseif ( 'bookable_resource' === get_post_type( $resource_id ) && $bookable_product->has_resources() ) {
					$resource              = new WC_Product_Booking_Resource( $resource_id );
					$automattic_assignment = 'automatic' === $bookable_product->get_resources_assignment();

					// in case this is a shared resource between bookings, we will want to merge available times and booked times
					// so to get a complete count of availability
					$booked_on_check_date = array_map( function( $booking ) {
						return $booking->get_start();
					}, $bookings_on_check_date );

					$slots_times_on_check_date = array_unique( array_merge( $booked_on_check_date, $slots_times_on_check_date ) );

					if ( count( $bookings_on_check_date ) < $resource->get_qty() || count( $bookings_on_check_date ) < $available_qty * count( $slots_times_on_check_date ) ) {
						$partially_booked_days[ $js_date ][ $resource_id ] = true;
						if ( $automattic_assignment ) {
							$partially_booked_days[ $js_date ][0] = true;
						}
					} elseif ( count( $bookings_on_check_date ) >= $available_qty * count( $slots_times_on_check_date ) ) {
						$fully_booked_days[ $js_date ][ $resource_id ] = true;
						if ( $automattic_assignment ) {
							$fully_booked_days[ $js_date ][0] = true;
						}
					}
				}
				//
				// Else if the blocks in range are actually available blocks, but we do have other bookings on the date as well.
				//
				elseif ( $available_blocks === $blocks_in_range && count( $bookings_on_check_date ) > 0 ) {
					// If there are no available blocks at all, it's fully booked
					if ( empty( $available_blocks ) ) {
						$fully_booked_days[ $js_date ][ $resource_id ] = true;
					} else {
						$partially_booked_days[ $js_date ][ $resource_id ] = true;
					}
				}
				foreach ( $booking_start_and_end as $index => $booking ) {
					$booking_s = date( 'Y-n-j', $booking[0] );
					$booking_e = date( 'Y-n-j', $booking[1] );

					// if the booking end date spans to the next day, mark the next day as partially booked if it is not already fully booked
					$is_day_fully_booked = ! empty( $fully_booked_days[ $booking_e ][ $resource_id ] );
					if ( $booking_s !== $booking_e && ! $is_day_fully_booked ) {
						$partially_booked_days[ $booking_e ][ $resource_id ] = true;
					}
				}

				$check_date = strtotime( '+1 day', $check_date );
			}
		}

		$booked_day_blocks = array(
			'partially_booked_days' => $partially_booked_days,
			'fully_booked_days'     => $fully_booked_days,
		);

		/**
		 * Filter the booked day blocks calculated per project.
		 * @since 1.9.13
		 *
		 * @param array $booked_day_blocks {
		 *  @type array $partially_booked_days
		 *  @type array $fully_booked_days
		 * }
		 * @param WC_Product $bookable_product
		 */
		return apply_filters( 'woocommerce_bookings_booked_day_blocks', $booked_day_blocks, $bookable_product );
	}

	/**
	 * Loop through given bookings to find those that are on or over lap the given date.
	 *
	 * @since 1.9.14
	 * @param  array $bookings
	 * @param  string $date
	 *
	 * @return array of booking ids
	 */
	public static function filter_bookings_on_date( $bookings, $date ) {
		$bookings_on_date = array();
		$date_start       = strtotime( 'midnight', $date ); // Midnight today.
		$date_end         = strtotime( 'tomorrow', $date ); // Midnight next day.

		foreach ( $bookings as $booking ) {
			// does the date we want to check fall on one of the days in the booking?
			if ( $booking->get_start() < $date_end && $booking->get_end() > $date_start ) {
				$bookings_on_date[] = $booking;
			}
		}
		return $bookings_on_date;
	}

	/**
	 * Gets bookings for product ids and resource ids
	 * @param  array  $ids
	 * @param  array  $status
	 * @param  integer  $date_from
	 * @param  integer  $date_to
	 * @return array of WC_Booking objects
	 */
	public static function get_bookings_for_objects( $ids = array(), $status = array(), $date_from = 0, $date_to = 0 ) {
		$transient_name = 'book_fo_' . md5( http_build_query( array( $ids, $status, WC_Cache_Helper::get_transient_version( 'bookings' ) ) ) );
		$status = ( ! empty( $status ) ) ? $status : get_wc_booking_statuses( 'fully_booked' );
		$date_from 	= ! empty( $date_from ) ? $date_from : strtotime( 'midnight', current_time( 'timestamp' ) );
		$date_to 	= ! empty( $date_to ) ? $date_to : strtotime( '+12 month', current_time( 'timestamp' ) );

		if ( false === ( $booking_ids = get_transient( $transient_name ) ) ) {
			$booking_ids = self::get_bookings_for_objects_query( $ids, $status, $date_from, $date_to );
			set_transient( $transient_name, $booking_ids, DAY_IN_SECONDS * 30 );
		}

		if ( ! empty( $booking_ids ) ) {
			return array_map( 'get_wc_booking', wp_parse_id_list( $booking_ids ) );
		}
		return array();
	}

	/**
	 * Gets bookings for product ids and resource ids
	 * @param  array  $ids
	 * @param  array  $status
	 * @param  integer  $date_from
	 * @param  integer  $date_to
	 * @return array of WC_Booking objects
	 */
	public static function get_bookings_for_objects_query( $ids, $status, $date_from = 0, $date_to = 0 ) {
		$status     = ( ! empty( $status ) ) ? $status   : get_wc_booking_statuses( 'fully_booked' );
		$date_from 	= ! empty( $date_from ) ? $date_from : strtotime( 'midnight', current_time( 'timestamp' ) );
		$date_to 	= ! empty( $date_to ) ? $date_to : strtotime( '+12 month', current_time( 'timestamp' ) );

		$booking_ids = WC_Booking_Data_Store::get_booking_ids_by( array(
			'status'       => $status,
			'object_id'    => $ids,
			'object_type'  => 'product_or_resource',
			'date_between' => array(
				'start' => $date_from,
				'end'   => $date_to,
			),
		) );
		return $booking_ids;
	}

	/**
	 * Gets bookings for a resource.
	 *
	 * @param  int $resource_id ID
	 * @param  array  $status
	 * @return array of WC_Booking objects
	 */
	public static function get_bookings_for_resource( $resource_id, $status = array( 'confirmed', 'paid' ) ) {
		$booking_ids = WC_Booking_Data_Store::get_booking_ids_by( array(
			'object_id'   => $resource_id,
			'object_type' => 'resource',
			'status'      => $status,
		) );
		return array_map( 'get_wc_booking', $booking_ids );
	}

	/**
	 * Gets bookings for a product by ID
	 *
	 * @param int $product_id The id of the product that we want bookings for
	 * @return array of WC_Booking objects
	 */
	public static function get_bookings_for_product( $product_id, $status = array( 'confirmed', 'paid' ) ) {
		$booking_ids = WC_Booking_Data_Store::get_booking_ids_by( array(
			'object_id'   => $product_id,
			'object_type' => 'product',
			'status'      => $status,
		) );
		return array_map( 'get_wc_booking', $booking_ids );
	}

	/**
	 * Return all bookings for a product in a given range - the query part (no cache)
	 * @param  integer $start_date
	 * @param  integer$end_date
	 * @param  int $product_or_resource_id
	 * @param  bool $check_in_cart
	 * @return array of booking ids
	 */
	private static function get_bookings_in_date_range_query( $start_date, $end_date, $product_or_resource_id = 0, $check_in_cart = true ) {
		$args = array(
			'status'       => get_wc_booking_statuses(),
			'object_id'    => $product_or_resource_id,
			'object_type'  => 'product_or_resource',
			'date_between' => array(
				'start' => $start_date,
				'end'   => $end_date,
			),
		);

		if ( ! $check_in_cart ) {
			$args['status'] = array_diff( $args['status'], array( 'in-cart' ) );
		}

		if ( $product_or_resource_id ) {
			if ( get_post_type( $product_or_resource_id ) === 'bookable_resource' ) {
				$args['resource_id'] = absint( $product_or_resource_id );
			} else {
				$args['product_id']  = absint( $product_or_resource_id );
			}
		}

		return apply_filters( 'woocommerce_bookings_in_date_range_query', WC_Booking_Data_Store::get_booking_ids_by( $args ) );
	}

	/**
	 * Get latest bookings
	 *
	 * @param int $number_of_items Number of objects returned (default to unlimited)
	 * @param int $offset The number of objects to skip (as a query offset)
	 * @return array of WC_Booking objects
	 */
	public static function get_latest_bookings( $number_of_items = 10, $offset = 0 ) {

		$booking_ids = get_posts( array(
			'numberposts' => $number_of_items,
			'offset'      => $offset,
			'orderby'     => 'post_date',
			'order'       => 'DESC',
			'post_type'   => 'wc_booking',
			'post_status' => get_wc_booking_statuses(),
			'fields'      => 'ids',
		) );

		return array_map( 'get_wc_booking', $booking_ids );
	}

	/**
	 * Gets bookings for a user by ID
	 *
	 * @param  int   $user_id    The id of the user that we want bookings for
	 * @param  array $query_args The query arguments used to get booking IDs
	 * @return array             Array of WC_Booking objects
	 */
	public static function get_bookings_for_user( $user_id, $query_args = null ) {
		$booking_ids = WC_Booking_Data_Store::get_booking_ids_by( array_merge( $query_args, array(
			'status'      => get_wc_booking_statuses( 'user' ),
			'object_id'   => $user_id,
			'object_type' => 'customer',
		) ) );
		return array_map( 'get_wc_booking', $booking_ids );
	}

	/*
	|--------------------------------------------------------------------------
	| Deprecated Methods
	|--------------------------------------------------------------------------
	*/
	/**
	 * Get the start and end times for and array of bookings
	 *
	 * @param WC_Booking[] $bookings_objects
	 * @deprecated  should be removed after other parts of the is optimised to use an array of bookings objects
	 * @since 1.10.0
	 *
	 * @return array
	 */
	public static function get_bookings_star_and_end_times( $bookings_objects ) {
		$bookings_start_and_end = array();
		foreach ( $bookings_objects as $booking ) {
			$bookings_start_and_end[] = array( $booking->get_start(), $booking->get_end() );
		}
		return $bookings_start_and_end;
	}

}
