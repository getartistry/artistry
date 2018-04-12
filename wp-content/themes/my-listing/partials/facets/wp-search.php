<?php
    $data = c27()->merge_options([
            'facet' => '',
            'facetID' => uniqid() . '__facet',
        ], $data);

    if (!$data['facet']) return;

    $value = isset($_GET['search_keywords']) ? $_GET['search_keywords' ] : '';

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['search_keywords'] = $value;

    $placeholder = ! empty( $data['facet']['placeholder'] ) ? $data['facet']['placeholder'] : false;
?>

<div class="form-group explore-filter <?php echo esc_attr( ! $placeholder ? 'md-group' : '' ) ?> wp-search-filter <?php echo esc_attr( trim( $value ) ? 'md-active' : '' ) ?>">
    <input type="text" v-model="facets['<?php echo esc_attr( $data['listing_type'] ) ?>']['search_keywords']" id="<?php echo esc_attr( $data['facetID'] ) ?>" name="search_keywords"
           placeholder="<?php echo esc_attr( $placeholder ) ?>"
           @keyup="getListings">
    <label for="<?php echo esc_attr( $data['facetID'] ) ?>"><?php echo esc_html( $data['facet']['label'] ) ?></label>
    <div class="md-border-line"></div>
</div>