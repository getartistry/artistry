<?php
    $data = c27()->merge_options([
            'facet' => '',
            'facetID' => uniqid() . '__facet',
            'listing_type' => '',
            'options' => [
            	'type' => true,
            	'prefix' => '',
            	'suffix' => '',
            ],
            'facet_data' => [
                'min' => 0,
                'max' => 0,
            ],
            'is_vue_template' => true,
        ], $data);

    if (!$data['facet']) return;

    foreach((array) $data['facet']['options'] as $option) {
    	if ($option['name'] == 'type') $data['options']['type'] = $option['value'];
    	if ($option['name'] == 'prefix') $data['options']['prefix'] = $option['value'];
    	if ($option['name'] == 'suffix') $data['options']['suffix'] = $option['value'];
    	if ($option['name'] == 'default') $data['options']['default'] = $option['value'];
    }

    // Get Min Slider Value.
    $min_value_post = query_posts([
        'post_type' => 'job_listing',
        'order'     => 'ASC',
        'posts_per_page' => 1,

        'meta_type' => 'numeric',
        'meta_key'  => "_{$data['facet']['show_field']}",
        'orderby'   => 'meta_value_num',
        'order' => 'ASC',
        ]);

    if ($min_value_post) {
        $data['facet_data']['min'] = (int) get_post_meta($min_value_post[0]->ID, "_{$data['facet']['show_field']}", true);
    }

    // Get Max Slider Value.
    $max_value_post = query_posts([
        'post_type' => 'job_listing',
        'order'     => 'ASC',
        'posts_per_page' => 1,

        'meta_type' => 'numeric',
        'meta_key'  => "_{$data['facet']['show_field']}",
        'orderby'   => 'meta_value_num',
        'order' => 'DESC',
        ]);

    if ($max_value_post) {
        $data['facet_data']['max'] = (int) get_post_meta($max_value_post[0]->ID, "_{$data['facet']['show_field']}", true);
    }

    // Value from GET params.
    $range = isset($_GET[$data['facet']['show_field']]) ? explode('::', (string) $_GET[$data['facet']['show_field']]) : [];

    $value = '';

    if (isset($range[0]) && is_numeric($range[0])) {
        $value .= $range[0];
    }

    if (isset($range[1]) && is_numeric($range[1])) {
        $value .= "::{$range[1]}";
    }

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']][$data['facet']['show_field']] = $value;

    if ($data['options']['type'] == 'range') {
        $GLOBALS['c27-facets-vue-object'][$data['listing_type']]["{$data['facet']['show_field']}_default"] = "{$data['facet_data']['min']}::{$data['facet_data']['max']}";
    }

    if ($data['options']['type'] == 'simple') {
        $GLOBALS['c27-facets-vue-object'][$data['listing_type']]["{$data['facet']['show_field']}_default"] = $data['facet_data']['max'];
    }
?>

<div class="form-group radius radius1 range-slider explore-filter range-filter">
	<label><?php echo esc_html( $data['facet']['label'] ) ?></label>
    <?php if ($data['is_vue_template']): ?>
    <range-slider v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}']" ) ?>"
                  type="<?php echo esc_attr( $data['options']['type'] ) ?>"
                  prefix="<?php echo esc_attr( $data['options']['prefix'] ) ?>"
                  suffix="<?php echo esc_attr( $data['options']['suffix'] ) ?>"
                  min="<?php echo esc_attr( $data['facet_data']['min'] ) ?>"
                  max="<?php echo esc_attr( $data['facet_data']['max'] ) ?>"
                  start="<?php echo isset($range[0]) && is_numeric($range[0]) ? esc_attr( $range[0] ) : false ?>"
                  end="<?php echo isset($range[1]) && is_numeric($range[1]) ? esc_attr( $range[1] ) : false ?>"
                  @input="getListings"
                  ></range-slider>
    <?php else: ?>
       <input type="text" class="amount" readonly id="<?php echo esc_attr( $data['facetID'] . '__display' ) ?>">
       <input type="hidden" name="<?php echo esc_attr( $data['facet']['show_field'] ) ?>" id="<?php echo esc_attr( $data['facetID'] ) ?>">
       <div class="slider-range basic-form-slider-range"
            data-type="<?php echo esc_attr( $data['options']['type'] ) ?>"
            data-prefix="<?php echo esc_attr( $data['options']['prefix'] ) ?>"
            data-suffix="<?php echo esc_attr( $data['options']['suffix'] ) ?>"
            data-min="<?php echo esc_attr( $data['facet_data']['min'] ) ?>"
            data-max="<?php echo esc_attr( $data['facet_data']['max'] ) ?>"
            data-start="<?php echo isset($range[0]) && is_numeric($range[0]) ? esc_attr( $range[0] ) : false ?>"
            data-end="<?php echo isset($range[1]) && is_numeric($range[1]) ? esc_attr( $range[1] ) : false ?>"
            data-input-id="<?php echo esc_attr( $data['facetID'] ) ?>"
            ></div>
    <?php endif ?>
</div>
