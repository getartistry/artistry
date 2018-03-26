<?php
/**
 * Sanitize functions
 */

/**
 * Sanitize post per page value so it can be a negative or positive value
 *
 * @since 1.0.0
 */
function op_portfolio_sanitize_intval( $value ) {
	return $value && is_numeric( $value ) ? intval( $value ) : '';
}

/**
 * Sanitize post per page value so it can be a negative or positive value
 *
 * @since 1.0.0
 */
function op_portfolio_sanitize_absint( $value ) {
	return $value && is_numeric( $value ) ? absint( $value ) : '';
}