<?php
$data = c27()->merge_options([
    'facet' => '',
    'facetID' => uniqid() . '__facet',
    'options' => [
        'count' => 8,
        'multiselect' => false,
        'hide_empty' => true,
        'order_by' => 'count',
        'order' => 'DESC',
    ],
    'facet_data' => [
        'choices' => [],
    ],
    'type' => null,
], $data);

$type = $data['type'];
$facet = $data['facet'];

if ( ! $type || ! $facet ) {
    return false;
}

if ( ! ( $field = $type->get_field( $facet[ 'show_field' ] ) ) ) {
    return false;
}

foreach( (array) $facet['options'] as $option ) {
    if ( isset( $data['options'][ $option['name'] ] ) ) {
        $data['options'][ $option['name'] ] = $option['value'];
    }
}


if ( ! empty( $field['taxonomy'] ) && taxonomy_exists( $field['taxonomy'] ) ) {
    // dump($field);
    $args = [
        'taxonomy' => $field['taxonomy'],
        'hide_empty' => $data['options']['hide_empty'],
        'orderby' => $data['options']['order_by'],
        'number' => $data['options']['count'],
        'order' => $data['options']['order'],
        'meta_query' => [
            'relation' => 'OR',
            [
                'key' => 'listing_type',
                'value' => '"' . $type->get_id() . '"',
                'compare' => 'LIKE',
            ],
            [
                'key' => 'listing_type',
                'value' => '',
            ],
            [
                'key' => 'listing_type',
                'compare' => 'NOT EXISTS',
            ]
        ],
    ];

    $cache_version = get_option( 'listings_tax_' . $field['taxonomy'] . '_version', 100 );
    // dump($cache_version);
    $categories_hash = 'c27_cats_' . md5( json_encode( $args ) ) . '_v' . $cache_version;
    $terms = get_transient( $categories_hash );

    if ( empty( $terms ) ) {
        $terms = get_terms( $args );
        set_transient( $categories_hash, $terms, HOUR_IN_SECONDS * 6 );
        // dump( 'Loaded via db query' );
    } else {
        // dump( 'Loaded from cache' );
    }

    if ( ! is_wp_error( $terms ) ) {
        if ( $data['options']['order_by'] == 'name' ) {

            CASE27\Classes\Term::iterate_recursively(
                function( $term, $depth ) use ( &$data ) {
                    $data['facet_data']['choices'][] = [
                        'value' => $term->slug,
                        'label' => str_repeat( '&mdash;', $depth - 1 ) . ' ' . $term->name,
                        'selected' => false,
                    ];
                },
                CASE27\Classes\Term::get_term_tree( $terms )
            );

        } else {
            foreach ((array) $terms as $term) {
                $term = new CASE27\Classes\Term( $term );
                $data['facet_data']['choices'][] = [
                    'value' => $term->get_slug(),
                    'label' => $term->get_full_name(),
                    'selected' => false,
                ];
            }
        }
    }
} else {

    if ( $data['options']['order_by'] == 'include' ) {
        if ( $data['options']['order'] == 'DESC' ) {
            $field['options'] = array_reverse( (array) $field['options'] );
        }

        if ( is_numeric( $data['options']['count'] ) && $data['options']['count'] >= 1 ) {
            $field['options'] = array_slice( (array) $field['options'], 0, $data['options']['count'] );
        }

        foreach ( (array) $field['options'] as $option ) {
            $data['facet_data']['choices'][] = [
                'value' => $option,
                'label' => $option,
                'selected' => false,
            ];
        }
    } else {
        // dump($facet, $field);
        if (!function_exists('c27_dropdown_facet_query_group_by_filter')) {
            function c27_dropdown_facet_query_group_by_filter( $groupby ) { global $wpdb;
                return $wpdb->postmeta . '.meta_value ';
            }
        }

        if (!function_exists('c27_dropdown_facet_query_fields_filter')) {
            function c27_dropdown_facet_query_fields_filter( $fields ) { global $wpdb;
                return $wpdb->postmeta . '.meta_value ';
            }
        }

        add_filter('posts_fields', 'c27_dropdown_facet_query_fields_filter');
        add_filter('posts_groupby', 'c27_dropdown_facet_query_group_by_filter');

        $posts = query_posts([
            'post_type' => 'job_listing',
            'posts_per_page' => $data['options']['count'],
            'meta_key'  => "_{$facet['show_field']}",
            'orderby' => $data['options']['order_by'],
            'order' => $data['options']['order'],
            ]);

        remove_filter('posts_fields', 'c27_dropdown_facet_query_fields_filter');
        remove_filter('posts_groupby', 'c27_dropdown_facet_query_group_by_filter');

        foreach ((array) $posts as $post) {
            if ( is_serialized( $post->meta_value ) ) {
                foreach ( array_filter( (array) unserialize( $post->meta_value ) ) as $value ) {
                    $data['facet_data']['choices'][] = [
                        'value' => $value,
                        'label' => $value,
                        'selected' => false,
                    ];
                }

                continue;
            }

            $data['facet_data']['choices'][] = [
                'value' => $post->meta_value,
                'label' => "{$post->meta_value}",
                'selected' => false,
            ];
        }

        $data['facet_data']['choices'] = array_map( 'unserialize', array_unique( array_map( 'serialize', $data['facet_data']['choices'] ) ) );
    }
}

$facet_show_field = $facet['show_field'];
if ( $facet_show_field == 'job_category' ) {
    $facet_show_field = 'category';
} elseif ( $facet_show_field == 'job_tags' ) {
    $facet_show_field = 'tag';
}

if ( ! empty( $_GET[$facet['show_field']] ) ) {
    $selected = (array) $_GET[$facet['show_field']];
} elseif ( ( $selected_val = get_query_var( sprintf( 'explore_%s', $facet_show_field ) ) ) ) {
    $selected = (array) $selected_val;
} else {
    $selected = [];
}

$choices_flat = (array) array_column( $data['facet_data']['choices'], 'value' );
$selected = array_filter( array_filter( $selected, function( $value ) use ( $choices_flat ) {
    return in_array( $value, $choices_flat );
} ) );

$GLOBALS['c27-facets-vue-object'][$data['listing_type']][$facet['show_field']] = $selected;
?>

<div class="form-group form-group-tags explore-filter checkboxes-filter">
	<label><?php echo esc_html( $data['facet']['label'] ) ?></label>
	<ul class="tags-nav">
		<?php foreach ((array) $data['facet_data']['choices'] as $choice): $choiceID = uniqid() . '__choiceid'; ?>
			<li>
				<div class="md-checkbox">
					<input id="<?php echo esc_attr( $choiceID ) ?>"
                           type="<?php echo $data['options']['multiselect'] ? 'checkbox' : 'radio' ?>"
                           value="<?php echo esc_attr( $choice['value'] ) ?>"
                           v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}']" ) ?>"
                           @change="getListings"
                           >
					<label for="<?php echo esc_attr( $choiceID ) ?>" class=""><?php echo esc_attr( $choice['label'] ) ?></label>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
</div>
