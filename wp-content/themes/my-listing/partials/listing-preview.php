<?php
$data = c27()->merge_options([
    'listing' => '',
    'options' => [],
    'wrap_in' => '',
], $data);

if ( ! class_exists( 'WP_Job_Manager' ) || ! $data['listing'] ) {
    return false;
}

$listing = new CASE27\Classes\Listing( $data['listing'] );

if ( ! $listing->type ) {
    return false;
}

// Get the preview template options for the listing type of the current listing.
$options = $listing->get_preview_options();

// Finally, in case custom options have been provided through the c27()->get_partial() method,
// then give those the highest priority, by overwriting the listing type options with those.
$options = c27()->merge_options( $options, (array) $data['options'] );

$classes = [
    'default' => '',
    'alternate' => 'lf-type-2'
];

// Categories.
$categories = $listing->get_field( 'job_category' );
// $categories = array_filter( (array) wp_get_object_terms($listing->ID, 'job_listing_category', ['orderby' => 'term_order', 'order' => 'ASC']) );

$first_category = $categories ? new CASE27\Classes\Term( $categories[0] ) : false;
$listing_thumbnail = $listing->get_logo( 'thumbnail' ) ?: c27()->image( 'marker.jpg' );
$latitude = false;
$longitude = false;

if ( is_numeric( $listing->get_data('geolocation_lat') ) ) {
    $latitude = $listing->get_data('geolocation_lat') + ( rand(0, 1000) / 10e6 );
}

if ( is_numeric( $listing->get_data('geolocation_long') ) ) {
    $longitude = $listing->get_data('geolocation_long') + ( rand( 0, 1000 ) / 10e6 );
}

$data['listing']->_c27_marker_data = [
    'lat' => $latitude,
    'lng' => $longitude,
    'thumbnail' => $listing_thumbnail,
    'category_icon' => $first_category ? $first_category->get_icon() : null,
    'category_color' => $first_category ? $first_category->get_color() : null,
    'category_text_color' => $first_category ? $first_category->get_text_color() : null,
];

// Get the number of details, so the height of the listing preview
// can be reduced if there are many details.
$detailsCount = 0;
foreach ((array) $options['footer']['sections'] as $section) {
    if ( $section['type'] == 'details' ) $detailsCount = count( $section['details'] );
}

if ( ! isset( $data['listing']->_c27_show_promoted_badge ) ) {
    $data['listing']->_c27_show_promoted_badge = true;
}

$isPromoted = false;
if (
     $data['listing']->_c27_show_promoted_badge &&
     $listing->get_data( '_case27_listing_promotion_start_date' ) &&
     strtotime( $listing->get_data( '_case27_listing_promotion_start_date' ) ) &&
     $listing->get_data( '_case27_listing_promotion_end_date' ) &&
     strtotime( $listing->get_data( '_case27_listing_promotion_end_date' ) )
 ) {
    try {
        $startDate = new DateTime( $listing->get_data( '_case27_listing_promotion_start_date' ) );
        $endDate = new DateTime( $listing->get_data( '_case27_listing_promotion_end_date' ) );
        $currentDate = new DateTime( date( 'Y-m-d H:i:s' ) );

        if ( $currentDate >= $startDate && $currentDate <= $endDate ) {
            $isPromoted = true;
        }
    } catch (Exception $e) {}
}

$wrapper_classes = get_job_listing_class( [
    'lf-item-container',
    'listing-preview',
    'type-' . $listing->type->get_slug(),
    $classes[ $options['template'] ],
], $listing->get_id() );

if ( $detailsCount > 2 ) {
    $wrapper_classes[] = 'lf-small-height';
}

if ( $listing->get_data( '_claimed' ) ) {
    $wrapper_classes[] = 'c27-verified';
}

?>

<!-- LISTING ITEM PREVIEW -->
<div class="<?php echo $data['wrap_in'] ? esc_attr( $data['wrap_in'] ) : '' ?>">
<div
    class="<?php echo esc_attr( join( ' ', $wrapper_classes ) ) ?>"
    data-id="listing-id-<?php echo esc_attr( $listing->get_id() ); ?>"
    data-latitude="<?php echo esc_attr( $latitude ); ?>"
    data-longitude="<?php echo esc_attr( $longitude ); ?>"
    data-category-icon="<?php echo esc_attr( $first_category ? $first_category->get_icon() : '' ) ?>"
    data-category-color="<?php echo esc_attr( $first_category ? $first_category->get_color() : '' ) ?>"
    data-category-text-color="<?php echo esc_attr( $first_category ? $first_category->get_text_color() : '' ) ?>"
    data-thumbnail="<?php echo esc_url( $listing_thumbnail ) ?>"
    >
    <div class="lf-item">
        <a href="<?php echo esc_url( $listing->get_link() ) ?>">
            <div class="overlay" style="
                background-color: <?php echo esc_attr( c27()->get_setting('listing_preview_overlay_color', '#242429') ); ?>;
                opacity: <?php echo esc_attr( c27()->get_setting('listing_preview_overlay_opacity', '0.5') ); ?>;
                "></div>

            <!-- BACKGROUND GALLERY -->
            <?php if ($options['background']['type'] == 'gallery' && ( $gallery = $listing->get_field( 'gallery' ) ) ): ?>
                <div class="owl-carousel lf-background-carousel">
                    <?php foreach (array_slice($gallery, 0, 3) as $gallery_image): ?>
                        <div class="item">
                            <div
                                class="lf-background"
                                style="background-image: url('<?php echo esc_url( job_manager_get_resized_image( $gallery_image, 'large' ) ) ?>');">
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php else: $options['background']['type'] = 'image'; endif; // Fallback to cover image if no gallery images are present ?>

            <!-- BACKGROUND IMAGE -->
            <?php if ($options['background']['type'] == 'image' && ( $cover = $listing->get_cover_image( 'large' ) ) ): ?>
                <div
                    class="lf-background"
                    style="background-image: url('<?php echo esc_url( $cover ) ?>');">
                </div>
            <?php endif ?>


            <!-- DEFAULT TITLE TEMPLATE -->
            <?php if ($options['template'] == 'default'): ?>
                <div class="lf-item-info">
                    <h4 class="case27-secondary-text listing-preview-title"><?php echo apply_filters( 'the_title', $listing->get_name(), $listing->get_id() ) ?></h4>

                    <?php if (isset($options['info_fields']) && $options['info_fields']): ?>
                        <ul>
                            <?php foreach ( (array) $options['info_fields'] as $info_field ):
                                if ( ! isset( $info_field['icon'] ) ) {
                                    $info_field['icon'] = '';
                                }

                                if ( ! ( $field_value = $listing->get_field( $info_field['show_field'] ) ) ) {
                                    continue;
                                }

                                $field_value = apply_filters( 'case27\listing\preview\info_field\\' . $info_field['show_field'], $field_value, $info_field, $listing );

                                if ( is_array( $field_value ) ) {
                                    $field_value = join( ', ', $field_value );
                                }

                                $GLOBALS['c27_active_shortcode_content'] = $field_value;
                                $field_content = str_replace( '[[field]]', $field_value, do_shortcode( $info_field['label'] ) );

                                ?>
                                <?php if (trim($field_value) && trim($field_content)): ?>
                                    <li>
                                        <i class="<?php echo esc_attr( $info_field['icon'] ) ?> sm-icon"></i>
                                        <?php echo esc_html( $field_content ) ?>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <!-- ALTERNATE TITLE TEMPLATE -->
            <?php if ($options['template'] == 'alternate'): ?>
                <div class="lf-item-info-2">
                    <?php if ( $logo = $listing->get_logo() ): ?>
                        <div
                            class="lf-avatar"
                            style="background-image: url('<?php echo esc_url( $logo ) ?>')">
                        </div>
                    <?php endif ?>

                    <h4 class="case27-secondary-text listing-preview-title"><?php echo apply_filters( 'the_title', $listing->get_name(), $listing->get_id() ) ?></h4>

                    <?php if ( $tagline = $listing->get_field('job_tagline') ): ?>
                        <h6><?php echo esc_html( $tagline ) ?></h6>
                    <?php elseif ( $description = $listing->get_field('job_description') ): ?>
                        <h6><?php echo c27()->the_text_excerpt( wp_kses( $description, [] ), 114 ) ?></h6>
                    <?php endif ?>

                    <?php if (isset($options['info_fields']) && $options['info_fields']): ?>
                    <ul class="lf-contact">
                        <?php foreach ((array) $options['info_fields'] as $info_field):
                            if ( ! isset( $info_field['icon'] ) ) {
                                $info_field['icon'] = '';
                            }

                            if ( ! ( $field_value = $listing->get_field( $info_field['show_field'] ) ) ) {
                                continue;
                            }

                            $field_value = apply_filters( 'case27\listing\preview\info_field\\' . $info_field['show_field'], $field_value, $info_field, $listing );

                            if ( is_array( $field_value ) ) {
                                $field_value = join( ', ', $field_value );
                            }

                            $GLOBALS['c27_active_shortcode_content'] = $field_value;
                            $field_content = str_replace( '[[field]]', $field_value, do_shortcode( $info_field['label'] ) );
                            ?>
                            <?php if (trim($field_value) && trim($field_content)): ?>
                                <li>
                                    <i class="<?php echo esc_attr( $info_field['icon'] ) ?> sm-icon"></i>
                                    <?php echo esc_html( $field_content ) ?>
                                </li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ul>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <!-- BUTTONS AT TOP LEFT CORNER -->
            <?php if ($options['buttons']): ?>
                <div class="lf-head">

                    <?php if ( $isPromoted ): ?>
                        <div class="lf-head-btn ad-badge">
                            <span>
                                <i class="icon-flash"></i><?php _e( 'Ad', 'my-listing' ) ?>
                            </span>
                        </div>
                    <?php endif ?>

                    <?php foreach ($options['buttons'] as $button): ?>

                        <?php if ( $button['show_field'] == '__listing_rating' ): ?>
                            <?php if ( $listing_rating = MyListing\Reviews::get_listing_rating_optimized($listing->get_id()) ): ?>
                                <div class="lf-head-btn listing-rating">
                                    <span class="value"><?php echo esc_html( $listing_rating ) ?></span>
                                    <sup class="out-of">/<?php echo MyListing\Reviews::max_rating( $listing->get_id() ); ?></sup>
                                </div>
                            <?php endif ?>
                        <?php elseif ( $button['show_field'] == 'work_hours' ): ?>
                            <?php if ( $listing->get_field('work_hours') && $listing->schedule->get_status() !== 'not-available' ): ?>
                                <?php $open_now = $listing->get_schedule()->get_open_now(); ?>
                                <div class="lf-head-btn open-status">
                                    <span><?php echo $open_now ? __( 'Open', 'my-listing' ) : __( 'Closed', 'my-listing' ) ?></span>
                                </div>
                            <?php endif ?>
                        <?php else:
                            if ( ! ( $button_val = $listing->get_field( $button['show_field'] ) ) ) {
                                continue;
                            }

                            $button_val = apply_filters( 'case27\listing\preview\button\\' . $button['show_field'], $button_val, $button, $listing );

                            if ( is_array( $button_val ) ) {
                                $button_val = join( ', ', $button_val );
                            }

                            $GLOBALS['c27_active_shortcode_content'] = $button_val;
                            $btn_content = str_replace( '[[field]]', $button_val, do_shortcode( $button['label'] ) );
                            ?>

                            <?php if ( trim( $btn_content ) ): ?>
                                <div class="lf-head-btn <?php echo has_shortcode($button['label'], '27-format') ? 'formatted' : '' ?>">
                                    <?php echo $btn_content ?>
                                </div>
                            <?php endif ?>
                        <?php endif ?>

                    <?php endforeach ?>

                </div>
            <?php endif ?>
        </a>

        <!-- BACKGROUND GALLERY NAVIGATION BUTTONS -->
        <?php if ($options['background']['type'] == 'gallery'): ?>
            <div class="gallery-nav">
                <ul>
                    <li>
                        <a href="#" class="lf-item-prev-btn">
                            <i class="material-icons">keyboard_arrow_left</i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="lf-item-next-btn">
                            <i class="material-icons">keyboard_arrow_right</i>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif ?>
    </div>

    <?php ob_start() ?>
        <li class="item-preview" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php esc_attr_e( 'Quick view', 'my-listing' ) ?>">
            <a href="#" type="button" class="c27-toggle-quick-view-modal" data-id="<?php echo esc_attr( $listing->get_id() ); ?>"><i class="material-icons">zoom_in</i></a>
        </li>
    <?php $quick_view_button = ob_get_clean() ?>

    <?php ob_start() ?>
        <li data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php esc_attr_e( 'Bookmark', 'my-listing' ) ?>">
            <a class="c27-bookmark-button <?php echo mylisting()->bookmarks()->is_bookmarked($listing->get_id(), get_current_user_id()) ? 'bookmarked' : '' ?>"
               data-listing-id="<?php echo esc_attr( $listing->get_id() ) ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('c27_bookmark_nonce') ) ?>">
               <i class="material-icons">favorite_border</i>
            </a>
        </li>
    <?php $bookmark_button = ob_get_clean() ?>

    <!-- FOOTER SECTIONS -->
    <?php $footer_section_count = 0; ?>
    <?php if ($options['footer']['sections']): ?>
        <?php foreach ((array) $options['footer']['sections'] as $section): ?>

            <!-- CATEGORIES SECTION -->
            <?php if ($section['type'] == 'categories'):
                // Keys = taxonomy name
                // Value = taxonomy field name
                $taxonomies = [
                    'job_listing_category' => 'job_category',
                    'case27_job_listing_tags' => 'job_tags',
                    'region' => 'region',
                ];

                $taxonomy = ! empty( $section['taxonomy'] ) ? $section['taxonomy'] : 'job_listing_category';

                if ( ! isset( $taxonomies[ $taxonomy ] ) ) {
                    continue;
                }

                if ( ! $terms = $listing->get_field( $taxonomies[ $taxonomy ] ) ) {
                    continue;
                }

                $footer_section_count++;
                ?>
                <div class="listing-details c27-footer-section">
                    <ul class="c27-listing-preview-category-list">

                        <?php if ( $terms ):
                            $category_count = count( $terms );
                            $first_category = array_shift( $terms );
                            $first_ctg = new CASE27\Classes\Term( $first_category );
                            $category_names = array_map(function($category) {
                                return $category->name;
                            }, $terms);
                            $categories_string = join('<br>', $category_names);
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $first_ctg->get_link() ) ?>">
                                    <span class="cat-icon" style="background-color: <?php echo esc_attr( $first_ctg->get_color() ) ?>;">
                                        <?php echo $first_ctg->get_icon([ 'background' => false ]) ?>
                                    </span>
                                    <span class="category-name"><?php echo esc_html( $first_ctg->get_name() ) ?></span>
                                </a>
                            </li>

                            <?php if (count($terms)): ?>
                                <li data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo esc_attr( $categories_string ) ?>" data-html="true">
                                    <div class="categories-dropdown dropdown c27-more-categories">
                                        <a href="#other-categories">
                                            <span class="cat-icon cat-more">+<?php echo $category_count - 1 ?></span>
                                        </a>
                                    </div>
                                </li>
                            <?php endif ?>
                        <?php endif ?>
                    </ul>

                    <div class="ld-info">
                        <ul>
                            <?php if (isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes'): ?>
                                <?php echo $quick_view_button ?>
                            <?php endif ?>
                            <?php if (isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes'): ?>
                                <?php echo $bookmark_button ?>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            <?php endif ?>

            <!-- RELATED LISTING (HOST) SECTION -->
            <?php if ( $section['type'] == 'host' && ( $hostID = $listing->get_field('related_listing') ) ): ?>
                <?php $host = \CASE27\Classes\Listing::get( $hostID ) ?>

                <?php if ( $host ): $footer_section_count++; ?>
                    <div class="event-host c27-footer-section">
                        <a href="<?php echo esc_url( $host->get_link() ) ?>">
                            <?php if ( $host_logo = $host->get_logo() ): ?>
                                <div class="avatar">
                                    <img src="<?php echo esc_url( $host_logo ) ?>" alt="<?php echo esc_attr( $host->get_name() ) ?>">
                                </div>
                            <?php endif ?>
                            <span class="host-name"><?php echo str_replace('[[listing_name]]', apply_filters( 'the_title', $host->get_name(), $host->get_id() ), $section['label']) ?></span>
                        </a>

                        <div class="ld-info">
                            <ul>
                                <?php if (isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes'): ?>
                                    <?php echo $quick_view_button ?>
                                <?php endif ?>
                                <?php if (isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes'): ?>
                                    <?php echo $bookmark_button ?>
                                <?php endif ?>
                            </ul>
                        </div>
                    </div>
                <?php endif ?>
            <?php endif ?>

            <!-- AUTHOR SECTION -->
            <?php if ( $section['type'] == 'author' && ( $listing->author instanceof \MyListing\User ) && $listing->author->exists() ):
                $footer_section_count++; ?>
                    <div class="event-host c27-footer-section">
                        <a href="<?php echo esc_url( $listing->author->get_link() ) ?>">
                            <?php if ( $avatar = $listing->author->get_avatar() ): ?>
                                <div class="avatar">
                                    <img src="<?php echo esc_url( $avatar ) ?>" alt="<?php echo esc_attr( $listing->author->get_name() ) ?>">
                                </div>
                            <?php endif ?>
                            <span class="host-name"><?php echo str_replace('[[author]]', esc_html( $listing->author->get_name() ), $section['label']) ?></span>
                        </a>

                        <div class="ld-info">
                            <ul>
                                <?php if (isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes'): ?>
                                    <?php echo $quick_view_button ?>
                                <?php endif ?>
                                <?php if (isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes'): ?>
                                    <?php echo $bookmark_button ?>
                                <?php endif ?>
                            </ul>
                        </div>
                    </div>
            <?php endif ?>

            <!-- DETAILS SECTION -->
            <?php if ($section['type'] == 'details' && $section['details']): $footer_section_count++; ?>
                <div class="listing-details-3 c27-footer-section">
                    <ul class="details-list">
                        <?php foreach ((array) $section['details'] as $detail):
                            if ( ! isset( $detail['icon'] ) ) {
                                $detail['icon'] = '';
                            }

                            if ( ! ( $detail_val = $listing->get_field( $detail['show_field'] ) ) ) {
                                continue;
                            }

                            $detail_val = apply_filters( 'case27\listing\preview\detail\\' . $detail['show_field'], $detail_val, $detail, $listing );

                            if ( is_array( $detail_val ) ) {
                                $detail_val = join( ', ', $detail_val );
                            }

                            $GLOBALS['c27_active_shortcode_content'] = $detail_val; ?>
                            <li>
                                <i class="<?php echo esc_attr( $detail['icon'] ) ?>"></i>
                                <span><?php echo str_replace( '[[field]]', $detail_val, do_shortcode( $detail['label'] ) ) ?></span>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <?php if ($section['type'] == 'actions' || $section['type'] == 'details'): ?>
                <?php if (
                    ( isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes' ) ||
                    ( isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes' )
                 ): $footer_section_count++; ?>
                    <div class="listing-details actions c27-footer-section">
                        <div class="ld-info">
                            <ul>
                                <?php if (isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes'): ?>
                                    <?php echo $quick_view_button ?>
                                <?php endif ?>
                                <?php if (isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes'): ?>
                                    <?php echo $bookmark_button ?>
                                <?php endif ?>
                            </ul>
                        </div>
                    </div>
                <?php endif ?>
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>

    <?php if ( $footer_section_count < 1 ): ?>
        <div class="c27-footer-empty"></div>
    <?php endif ?>
</div>
</div>