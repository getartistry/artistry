<?php

/**
 * Listing type search tab structure.
 *
 * @since 1.5.1
 */
return [
    'advanced' => [
    	'facets' => [],
    ],

    'basic' => [
    	'facets' => [],
    ],

    'order' => [

    	/**
		 * List of options by which listing can be ordered.
		 * Each option can contain one or more ordering clauses.
         * string options[][label]
         * array options[][clauses]
    	 */
    	'options' => [],
    	'default' => 'date',
    ],
];