<?php
    $data = c27()->merge_options([
            'facet' => '',
            'facetID' => uniqid() . '__facet',
            'listing_type' => '',
            'options' => [
            	'type' => 'range',
                'format' => 'ymd',
            ],
            'is_vue_template' => true,
        ], $data);

    if (!$data['facet']) return;

    foreach((array) $data['facet']['options'] as $option) {
        if ($option['name'] == 'type') $data['options']['type'] = $option['value'];
    	if ($option['name'] == 'format') $data['options']['format'] = $option['value'];
    }

    $exact_date = isset($_GET[ $data['facet']['show_field'] ]) ? $_GET[$data['facet']['show_field']] : '';
    $from_date = isset($_GET[ "{$data['facet']['show_field']}_from" ]) ? $_GET["{$data['facet']['show_field']}_from"] : '';
    $to_date = isset($_GET[ "{$data['facet']['show_field']}_to" ]) ? $_GET["{$data['facet']['show_field']}_to"] : '';

?>

<?php if ($data['options']['format'] == 'ymd'): ?>
    <?php if ($data['is_vue_template']): ?>
        <?php if ($data['options']['type'] == 'range'): ?>
            <?php $GLOBALS['c27-facets-vue-object'][$data['listing_type']]["{$data['facet']['show_field']}_from"] = $from_date; ?>
            <?php $GLOBALS['c27-facets-vue-object'][$data['listing_type']]["{$data['facet']['show_field']}_to"] = $to_date; ?>
            <div class="form-group explore-filter double-input datepicker-form-group">
                <label for="<?php echo esc_attr( $data['facetID'] ) ?>"><?php echo esc_html( $data['facet']['label'] ) ?></label>
                <datepicker v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}_from']" ) ?>"
                            placeholder="<?php esc_attr_e( 'From...', 'my-listing' ) ?>" @input="getListings"></datepicker>
                <datepicker v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}_to']" ) ?>"
                            placeholder="<?php esc_attr_e( 'To...', 'my-listing' ) ?>" @input="getListings"></datepicker>
        	</div>
        <?php endif ?>

        <?php if ($data['options']['type'] == 'exact'): ?>
            <?php $GLOBALS['c27-facets-vue-object'][$data['listing_type']][$data['facet']['show_field']] = $exact_date; ?>
        	<div class="form-group explore-filter datepicker-form-group">
        		<label><?php echo esc_html( $data['facet']['label'] ) ?></label>
                <datepicker v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}']" ) ?>" placeholder="<?php esc_attr_e( 'Pick a date...', 'my-listing' ) ?>" @input="getListings"></datepicker>
        	</div>
        <?php endif ?>

    <?php else: ?>
        <?php if ($data['options']['type'] == 'range'):
            $fromID = 'from__' . uniqid();
            $toID = 'to__' . uniqid();
            ?>
            <div class="form-group explore-filter double-input date-input">
                <label><?php echo esc_html( $data['facet']['label'] ) ?></label>
                <input type="hidden" name="<?php echo esc_attr( "{$data['facet']['show_field']}_from" ) ?>" id="<?php echo esc_attr( $fromID ) ?>">
                <input type="hidden" name="<?php echo esc_attr( "{$data['facet']['show_field']}_to" ) ?>" id="<?php echo esc_attr( $toID ) ?>">
                <input type="text" data-input-id="<?php echo esc_attr( $fromID ) ?>"
                       placeholder="<?php esc_attr_e( 'From...', 'my-listing' ) ?>" class="basic-form-datepicker">
                <input type="text" data-input-id="<?php echo esc_attr( $toID ) ?>"
                       placeholder="<?php esc_attr_e( 'To...', 'my-listing' ) ?>" class="basic-form-datepicker">
            </div>
        <?php endif ?>

        <?php if ($data['options']['type'] == 'exact'):
            $inputID = 'input__' . uniqid();
            ?>
            <div class="form-group explore-filter date-input">
                <label><?php echo esc_html( $data['facet']['label'] ) ?></label>
                <input type="hidden" name="<?php echo esc_attr( $data['facet']['show_field'] ) ?>" id="<?php echo esc_attr( $inputID ) ?>">
                <input type="text" data-input-id="<?php echo esc_attr( $inputID ) ?>"
                       placeholder="<?php esc_attr_e( 'Pick a date...', 'my-listing' ) ?>" class="basic-form-datepicker">
            </div>
        <?php endif ?>
    <?php endif ?>
<?php endif ?>

<?php if ($data['options']['format'] == 'year'): ?>
    <?php

    if (!function_exists('query_group_by_filter_2')) {
        function query_group_by_filter_2($groupby) { global $wpdb;
            return 'c27_year ';
        }
    }

    if (!function_exists('query_fields_filter_2')) {
        function query_fields_filter_2($fields) { global $wpdb;
            return "{$fields}, year({$wpdb->postmeta}.meta_value) as c27_year ";
        }
    }

    add_filter('posts_fields', 'query_fields_filter_2');
    add_filter('posts_groupby', 'query_group_by_filter_2');

    $listing_years = query_posts([
        'post_type' => 'job_listing',
        'posts_per_page' => -1,
        'meta_key'  => "_{$data['facet']['show_field']}",
        'orderby'   => 'meta_value',
        'order' => 'DESC',
        ]);

    $choices = [];

    foreach ($listing_years as $year) {
        if ($year->c27_year) {
            $choices[] = [
            'value' => $year->c27_year,
            'label' => $year->c27_year,
            ];
        }
    }

    remove_filter('posts_fields', 'query_fields_filter_2');
    remove_filter('posts_groupby', 'query_group_by_filter_2');
    ?>

    <?php if ($data['is_vue_template']): ?>
        <?php if ($data['options']['type'] == 'range'): ?>
            <?php $GLOBALS['c27-facets-vue-object'][$data['listing_type']]["{$data['facet']['show_field']}_from"] = $from_date; ?>
            <?php $GLOBALS['c27-facets-vue-object'][$data['listing_type']]["{$data['facet']['show_field']}_to"] = $to_date; ?>
            <div class="form-group explore-filter double-input">
                <label><?php echo esc_attr( $data['facet']['label'] ) ?></label>

                <select2 v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}_from']" ) ?>" placeholder="<?php esc_attr_e( 'From...', 'my-listing' ) ?>"
                         :choices="<?php echo htmlspecialchars(json_encode($choices), ENT_QUOTES, 'UTF-8'); ?>" @input="getListings"></select2>

               <select2 v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}_to']" ) ?>" placeholder="<?php esc_attr_e( 'To...', 'my-listing' ) ?>"
                        :choices="<?php echo htmlspecialchars(json_encode($choices), ENT_QUOTES, 'UTF-8'); ?>" @input="getListings"></select2>
            </div>
        <?php endif ?>

        <?php if ($data['options']['type'] == 'exact'): ?>
            <?php $GLOBALS['c27-facets-vue-object'][$data['listing_type']][$data['facet']['show_field']] = $exact_date; ?>
            <div class="form-group explore-filter">
                <label for="<?php echo esc_attr( $data['facetID'] ) ?>"><?php echo esc_html( $data['facet']['label'] ) ?></label>

                <select2 v-model="<?php echo esc_attr( "facets['{$data['listing_type']}']['{$data['facet']['show_field']}']" ) ?>" placeholder="<?php _e( 'Choose year...', 'my-listing' ) ?>"
                         :choices="<?php echo htmlspecialchars(json_encode($choices), ENT_QUOTES, 'UTF-8'); ?>" @input="getListings"></select2>
            </div>
        <?php endif ?>
    <?php else: ?>
        <?php if ($data['options']['type'] == 'range'): ?>
            <div class="form-group explore-filter double-input">
                <label><?php echo esc_attr( $data['facet']['label'] ) ?></label>
                <select name="<?php echo esc_attr( "{$data['facet']['show_field']}_from" ) ?>" class="custom-select">
                    <option value=""><?php _e( 'From...', 'my-listing' ) ?></option>
                    <?php foreach ($choices as $choice): ?>
                        <option value="<?php echo esc_attr( $choice['value'] ) ?>"><?php echo esc_html( $choice['label'] ) ?></option>
                    <?php endforeach ?>
                </select>

                <select name="<?php echo esc_attr( "{$data['facet']['show_field']}_to" ) ?>" class="custom-select">
                    <option value=""><?php _e( 'To...', 'my-listing' ) ?></option>
                    <?php foreach ($choices as $choice): ?>
                        <option value="<?php echo esc_attr( $choice['value'] ) ?>"><?php echo esc_html( $choice['label'] ) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        <?php endif ?>

        <?php if ($data['options']['type'] == 'exact'): ?>
            <?php $GLOBALS['c27-facets-vue-object'][$data['listing_type']][$data['facet']['show_field']] = $exact_date; ?>
            <div class="form-group explore-filter">
                <label for="<?php echo esc_attr( $data['facetID'] ) ?>"><?php echo esc_html( $data['facet']['label'] ) ?></label>
                <select name="<?php echo esc_attr( $data['facet']['show_field'] ) ?>" class="custom-select">
                    <option value=""><?php _e( 'Choose year...', 'my-listing' ) ?></option>
                    <?php foreach ($choices as $choice): ?>
                        <option value="<?php echo esc_attr( $choice['value'] ) ?>"><?php echo esc_html( $choice['label'] ) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        <?php endif ?>
    <?php endif ?>
<?php endif ?>
