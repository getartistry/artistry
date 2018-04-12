<?php
    $data = c27()->merge_options([
            'facet' => [],
            'facetID' => uniqid() . '__facet',
        ], $data);

    if (!$data['facet']) return;

    $value = isset($_GET['search_location']) ? $_GET['search_location' ] : '';

    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['search_location'] = $value;
    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['search_location_lat'] = false;
    $GLOBALS['c27-facets-vue-object'][$data['listing_type']]['search_location_lng'] = false;

    $placeholder = ! empty( $data['facet']['placeholder'] ) ? $data['facet']['placeholder'] : false;
?>

<div class="form-group location-wrapper explore-filter <?php echo esc_attr( ! $placeholder ? 'md-group' : '' ) ?> location-filter <?php echo esc_attr( trim( $value ) ? 'md-active' : '' ) ?>">
    <input type="text"
    	   class="form-location-autocomplete"
    	   id="<?php echo esc_attr( $data['facetID'] ) ?>"
           name="search_location"
    	   placeholder="<?php echo esc_attr( $placeholder ) ?>"
           v-model="facets['<?php echo esc_attr( $data['listing_type'] ) ?>']['search_location']"
           @keyup="geocodeLocation"
    	   >
    <i class="material-icons geocode-location" @click="getUserLocation">my_location</i>
    <label for="<?php echo esc_attr( $data['facetID'] ) ?>"><?php echo esc_html( $data['facet']['label'] ) ?></label>
    <div class="md-border-line"></div>
</div>