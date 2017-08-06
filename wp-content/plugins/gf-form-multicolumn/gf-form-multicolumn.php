<?php

/**
 * Plugin Name: Multiple Columns for Gravity Forms
 * Plugin URI: https://wordpress.org/plugins/gf-form-multicolumn/
 * Description: Add multiple columns (and multiple rows of multiple columns) to Gravity Forms.
 * Author: WebHolism
 * Author URI: http://www.webholism.com
 * Version: 2.1.1
 * Text Domain: gravityforms, gravity forms, multiple columns, multicolumn, multicolumns, multi column, multi columns, responsive, gravity forms multi column, multi row, multirow, multiple rows
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */

defined( 'ABSPATH' ) or die( 'Invalid operation!' );

function fieldContainer( $field_container, $field, $form, $css_class, $style, $field_content ) {
	if ( IS_ADMIN ) {
		return $field_container;
	} // only modify HTML on the front end

	// Variable to specify the width of the column
	$columnWidth = null;

	// Calculate width value based on the number of columns in the row, which has been coded into the cssClass
	$columnCountStartPos = strpos( $field['cssClass'], '-of-' );
	if ( $columnCountStartPos != false ) {
		$columnTotalForRow = substr( $field['cssClass'], $columnCountStartPos + 4 );
		$columnWidth       = ' width: ' . floor( 100 / $columnTotalForRow ) . '%;';
	}

	$columnSpecificCSSTextPos = strpos( $field['cssClass'], 'column-count-' );
	$columnSpecificCSSText    = null;
	if ( $columnSpecificCSSTextPos != false ) {
		$columnSpecificCSSText = substr( $field['cssClass'], $columnSpecificCSSTextPos );
	}

	// Break the existing cssClass definition to see if the previously set markers for the start and end columns have been set
	if ( $field['type'] == 'dividerStart' && strpos( $field['cssClass'], 'start-divider' ) !== false ) {
		echo( '<style>.' . $columnSpecificCSSText . ' { ' . $columnWidth . ' }</style>' );
		$field_container = '<li class="' . $columnSpecificCSSText . ' divider-list-item multicolumn-start"><div class="multicolumn-wrapper"><ul>';
	} elseif ( $field['type'] == 'dividerStart' ) {
		echo( '<style>.' . $columnSpecificCSSText . ' { ' . $columnWidth . ' }</style>' );
		$field_container = '<li class="' . $columnSpecificCSSText . ' divider-list-item"><div><ul>';
	}
	if ( $field['type'] == 'dividerEnd' && strpos( $field['cssClass'], 'end-divider' ) !== false ) {
		$field_container = '</ul></div></li>';
	} elseif ( $field['type'] == 'dividerEnd' ) {
		$field_container = '</ul>';
	}
	if ( $field['type'] == 'row-divider' ) {
		$field_container = '<div class="row-divider"></div>';
	}

	return ( $field_container );
}

add_filter( 'gform_field_container', 'fieldContainer', 10, 6 );

function preRender( $form ) {
	$dividerStartCounter = 0;
	$dividerEndCounter   = 0;

	$columnCount = 0;
	$rowCount    = 1;

	$rowColumnArray = array();

	// Set type of field & CSS if section settings have been dfined as required for column definition: section & split-start/split-end CSS
	foreach ( $form['fields'] as $field ) {
		if ( $field['type'] == 'section' && strpos( $field['cssClass'], 'split-start' ) !== false ) {
			// Set row and column details for later break up based on row and column position
			$field['calculationFormula'] = 'row-' . $rowCount . 'column-' . $columnCount ++;
			$rowColumnArray[ $rowCount ] = $columnCount;

			$field['type'] = 'dividerStart';
			$dividerStartCounter += 1;
			$field['cssClass'] .= ' dividerStart-' . $dividerStartCounter;
		} elseif ( $field['type'] == 'section' && strpos( $field['cssClass'], 'split-end' ) !== false ) {
			$field['type'] = 'dividerEnd';
			$dividerEndCounter += 1;
			$field['cssClass'] .= ' dividerEnd-' . $dividerEndCounter;
		} elseif ( $field['type'] == 'section' && strpos( $field['cssClass'], 'new-row' ) !== false ) {
			$field['type'] = 'row-divider';
			// Reset column counter
			$columnCount = 0;
			$rowCount    = $rowCount + 1;
			$field['cssClass'] .= ' row-divider';
		}
	}

	// Loop again through the field list to ensure that the first and last column are identified, based on the numbers defined in the previous loop
	foreach ( $form['fields'] as $field ) {
		if ( $field['type'] == 'dividerStart' && strpos( $field['cssClass'], 'dividerStart-1' ) ) {
			$field['cssClass'] .= ' start-divider';
		} elseif ( $field['type'] == 'dividerEnd' && strpos( $field['cssClass'], 'dividerEnd-' . $dividerEndCounter ) ) {
			$field['cssClass'] .= ' end-divider';
		}
		// Add column count to all dividers to ensure that this can be calculated as the split quantity later
		if ( $field['type'] == 'dividerStart' ) {
			// Set cssClass details to allow for break up of rows and columns based on
			// Variable to hold the value stored in the calculationFormula parameter that relates to the row and column count
			// Not that this will hold the value for the commencement of the column string, and this defines the end of the row count number
			$endOfRowCount   = strpos( $field['calculationFormula'], 'column-' );
			$rowNumberLength = $endOfRowCount - 4;
			$rowNumber       = substr( $field['calculationFormula'], 4, $rowNumberLength );
			$columnNumber    = substr( $field['calculationFormula'], $endOfRowCount + 7 ) + 1;
			$field['cssClass'] .= ' column-count-' . $columnNumber . '-of-' . $rowColumnArray[ $rowNumber ];
		}
	}

	return $form;

}

add_filter( 'gform_pre_render', 'preRender' );

function supporting_files() {
	wp_enqueue_style( 'cssStylesheets', plugins_url( '/css/gf-form-multicolumn.css', __FILE__ ), false, '2.1.1', 'all' );
}

add_action( 'wp_enqueue_scripts', "supporting_files" );

/*
 * Change Log:
 * v2.1.1
 * Removed code that output the blog id for testing multisite.
 *
 * v2.1.0
 * Added Plugin URI to header as this plugin was updating other plugins with similar naming conventions.
 * Updated supporting_files function to update version.
 * Changed Header code to remove Network: True so that plugin will work with multisite installations.
 * Added CSS style to remove any padding or margins to the left of the first column of each row: li[class*="column-count-1"] > div > ul.
 *
 * v2.0.1
 * Changed array definition on line 68 to use old syntax as users using PHP version < 5.4 were experiencing issues.
 *
 * v2.0.0
 * Updated mechanism for detecting multiple rows, for a different number of columns.
 *
 * v1.0.1
 * Altered superficial details such as name and wording in description.  Cosmetic only and no functionality alteration.
 *
 * v1.0.0
 * Initial release
 */