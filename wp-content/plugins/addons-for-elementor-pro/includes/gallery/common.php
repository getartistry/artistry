<?php

namespace LivemeshAddons\Gallery;

/**
 * Gallery class.
 *
 */
class LAE_Gallery_Common {

    /**
     * Holds the class object.
     */
    public static $instance;

    /**
     * Primary class constructor.
     * 
     */
    public function __construct() {

        add_filter('attachment_fields_to_edit', array($this, 'attachment_field_grid_width'), 10, 2);
        add_filter('attachment_fields_to_save', array($this, 'attachment_field_grid_width_save'), 10, 2);

        // Ajax calls
        add_action('wp_ajax_lae_load_gallery_items', array( $this, 'load_gallery_items_callback'));
        add_action('wp_ajax_nopriv_lae_load_gallery_items', array( $this, 'load_gallery_items_callback'));

    }

    public function attachment_field_grid_width( $form_fields, $post ) {
        $form_fields['lae_grid_width'] = array(
            'label' => esc_html__( 'Masonry Width', 'livemesh-el-addons' ),
            'input' => 'html',
            'html' => '
<select name="attachments[' . $post->ID . '][lae_grid_width]" id="attachments-' . $post->ID . '-lae_grid_width">
  <option ' . selected(get_post_meta( $post->ID, 'lae_grid_width', true ), "lae-default", false) .' value="lae-default">' . esc_html__('Default', 'livemesh-el-addons') .'</option>
  <option ' . selected(get_post_meta( $post->ID, 'lae_grid_width', true ), "lae-wide", false) .' value="lae-wide">' . esc_html__('Wide', 'livemesh-el-addons') .'</option>
</select>',
            'value' => get_post_meta( $post->ID, 'lae_grid_width', true ),
            'helps' => esc_html__('Width of the image in masonry gallery grid', 'livemesh-el-addons')
        );

        return $form_fields;
    }

    public function attachment_field_grid_width_save( $post, $attachment ) {
        if( isset( $attachment['lae_grid_width'] ) )
            update_post_meta( $post['ID'], 'lae_grid_width', $attachment['lae_grid_width'] );

        return $post;
    }


    function load_gallery_items_callback() {
        $items = $this->parse_items($_POST['items']);
        $settings = $this->parse_gallery_settings($_POST['settings']);
        $paged = intval($_POST['paged']);

        $this->display_gallery($items, $settings, $paged);

        wp_die();

    }

    function parse_items($items) {

        $parsed_items = array();

        foreach ($items as $item):

            // Remove encoded quotes or other characters
            $item['item_name'] = stripslashes($item['item_name']);

            $item['item_description'] = stripslashes($item['item_description']);

            $item['item_link'] = isset($item['item_link']) ? filter_var($item['item_link'], FILTER_DEFAULT) : '';

            $item['video_link'] = isset($item['video_link']) ? filter_var($item['video_link'], FILTER_DEFAULT) : '';

            $item['mp4_video_link'] = isset($item['mp4_video_link']) ? filter_var($item['mp4_video_link'], FILTER_DEFAULT) : '';

            $item['webm_video_link'] = isset($item['webm_video_link']) ? filter_var($item['webm_video_link'], FILTER_DEFAULT) : '';

            $item['display_video_inline'] = isset($item['display_video_inline']) ? filter_var($item['display_video_inline'], FILTER_VALIDATE_BOOLEAN) : false;

            $parsed_items[] = $item;

        endforeach;

        return $parsed_items;
    }

    function parse_gallery_settings($settings) {

        $s = $settings;

        $s['gallery_class'] = filter_var($s['gallery_class'], FILTER_DEFAULT);
        $s['gallery_id'] = filter_var($s['gallery_id'], FILTER_DEFAULT);
        $s['filterable'] = filter_var($s['filterable'], FILTER_VALIDATE_BOOLEAN);
        $s['per_line'] = filter_var($s['per_line'], FILTER_VALIDATE_INT);
        $s['items_per_page'] = filter_var($s['items_per_page'], FILTER_VALIDATE_INT);

        return $s;
    }

    function display_gallery($items, $settings, $paged = 1) {

        $gallery_video = LAE_Gallery_Video::get_instance();

        $num_of_columns = intval($settings['per_line']);
        $items_per_page = intval($settings['items_per_page']); ?>

        <?php $column_style = lae_get_column_class($num_of_columns); ?>

        <?php
        // If pagination option is chosen, filter the items for the current page
        if ($settings['pagination'] != 'none')
            $items = $this->get_items_to_display($items, $paged, $items_per_page);
        ?>

        <?php foreach ($items as $item): ?>

            <?php

            // No need to populate anything if no image is provided for video or for the image
            if (empty($item['item_image']))
                continue;

            $style = '';
            if (!empty($item['item_tags'])) {
                $terms = array_map('trim', explode(',', $item['item_tags']));

                foreach ($terms as $term) {
                    // Get rid of spaces before adding the term
                    $style .= ' term-' . preg_replace('/\s+/', '-', $term);
                }
            }
            ?>

            <?php

            $item_type = $item['item_type'];
            $item_class = 'lae-' . $item_type . '-type';

            $custom_class = get_post_meta($item['item_image']['id'], 'lae_grid_width', true);

            if ($custom_class !== '')
                $item_class .= ' ' . $custom_class;

            ?>

            <div class="lae-gallery-item <?php echo $style; ?> <?php echo $column_style; ?> <?php echo $item_class; ?>">

                <?php if ($gallery_video->is_inline_video($item, $settings)): ?>

                    <?php $gallery_video->display_inline_video($item, $settings); ?>

                <?php else: ?>

                    <div class="lae-project-image">

                        <?php if ($gallery_video->is_gallery_video($item, $settings)): ?>

                            <?php $image_html = ''; ?>

                            <?php if (isset($item['item_image']) && !empty($item['item_image']['id'])): ?>

                                <?php $image_html = lae_get_image_html($item['item_image'], 'thumbnail_size', $settings); ?>

                            <?php elseif ($item_type == 'youtube' || $item_type == 'vimeo') : ?>

                                <?php $thumbnail_url = $gallery_video->get_video_thumbnail_url($item['video_link'], $settings); ?>

                                <?php if (!empty($thumbnail_url)): ?>

                                    <?php $image_html = sprintf('<img src="%s" title="%s" alt="%s" class="lae-image"/>', esc_attr($thumbnail_url), esc_html($item['item_name']), esc_html($item['item_name'])); ?>

                                <?php endif; ?>

                            <?php endif; ?>

                            <?php echo $image_html; ?>

                        <?php else: ?>

                            <?php $image_html = lae_get_image_html($item['item_image'], 'thumbnail_size', $settings); ?>

                            <?php if ($item_type == 'image' && !empty($item['item_link']['url'])): ?>

                                <a href="<?php echo esc_url($item['item_link']['url']); ?>"
                                   title="<?php echo esc_html($item['item_name']); ?>"><?php echo $image_html; ?> </a>

                            <?php else: ?>

                                <?php echo $image_html; ?>

                            <?php endif; ?>

                        <?php endif; ?>

                        <div class="lae-image-info">

                            <div class="lae-entry-info">

                                <?php if ($settings['display_item_title'] == 'yes'): ?>

                                <<?php echo $settings['item_title_tag']; ?> class="lae-entry-title">

                                <?php if ($item_type == 'image' && !empty($item['item_link']['url'])): ?>

                                    <?php $target = $item['item_link']['is_external'] ? 'target="_blank"' : ''; ?>

                                    <a href="<?php echo esc_url($item['item_link']['url']); ?>"
                                       title="<?php echo esc_html($item['item_name']); ?>"
                                        <?php echo $target; ?>><?php echo esc_html($item['item_name']); ?></a>

                                <?php else: ?>

                                    <?php echo esc_html($item['item_name']); ?>

                                <?php endif; ?>

                            </<?php echo $settings['item_title_tag']; ?>>

                            <?php endif; ?>

                            <?php if ($gallery_video->is_gallery_video($item, $settings)): ?>

                                <?php $gallery_video->display_video_lightbox_link($item, $settings); ?>

                            <?php endif; ?>

                            <?php if ($settings['display_item_tags'] == 'yes'): ?>

                                <span class="lae-terms"><?php echo esc_html($item['item_tags']); ?></span>

                            <?php endif; ?>

                        </div>

                        <?php if ($item_type == 'image' && !empty($item['item_image']) && $settings['enable_lightbox']) : ?>

                            <?php $this->display_image_lightbox_link($item, $settings); ?>

                        <?php endif; ?>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

            <?php

        endforeach;

    }

    function display_image_lightbox_link($item, $settings) {

        $anchor_type = (empty($item['item_link']['url']) ? 'lae-click-anywhere' : 'lae-click-icon');

        if ($settings['lightbox_library'] == 'elementor'): ?>

            <a class="lae-lightbox-item <?php echo $anchor_type; ?> elementor-clickable"
               href="<?php echo $item['item_image']['url']; ?>"
               data-elementor-open-lightbox="yes"
               data-elementor-lightbox-slideshow="<?php echo esc_attr($settings['gallery_id']); ?>"
               title="<?php echo esc_html($item['item_name']); ?>"><i
                        class="lae-icon-full-screen"></i></a>

        <?php else: ?>

            <a class="lae-lightbox-item <?php echo $anchor_type; ?>"
               data-fancybox="<?php echo $settings['gallery_class']; ?>"
               href="<?php echo $item['item_image']['url']; ?>"
               data-elementor-open-lightbox="no"
               title="<?php echo esc_html($item['item_name']); ?>"
               data-description="<?php echo wp_kses_post($item['item_description']); ?>"><i
                        class="lae-icon-full-screen"></i></a>

        <?php endif;
    }

    function get_gallery_terms($items) {

        $tags = $terms = array();

        foreach ($items as $item) {
            $tags = array_merge($tags, explode(',', $item['item_tags']));
        }

        // trim whitespaces before applying array_unique
        $tags = array_map('trim', $tags);

        $terms = array_values(array_unique($tags));

        return $terms;

    }

    function get_items_to_display($items, $paged, $items_per_page) {

        $offset = $items_per_page * ($paged - 1);

        $items = array_slice($items, $offset, $items_per_page);

        return $items;
    }

    function paginate_gallery($items, $settings) {

        $pagination_type = $settings['pagination'];

        // no pagination required if option is not chosen by user or if all posts are already displayed
        if ($pagination_type == 'none' || count($items) <= $settings['items_per_page'])
            return;

        $max_num_pages = ceil(count($items) / $settings['items_per_page']);

        $output = '<div class="lae-pagination">';

        if ($pagination_type == 'load_more') {
            $output .= '<a href="#" class="lae-load-more lae-button">';
            $output .= esc_html__('Load More', 'livemesh-el-addons');
            if ($settings['show_remaining'])
                $output .= ' - ' . '<span>' . (count($items) - $settings['items_per_page']) . '</span>';
            $output .= '</a>';
        }
        else {
            $page_links = array();

            for ($n = 1; $n <= $max_num_pages; $n++) :
                $page_links[] = '<a class="lae-page-nav' . ($n == 1 ? ' lae-current-page' : '') . '" href="#" data-page="' . $n . '">' . number_format_i18n($n) . '</a>';
            endfor;

            $r = join("\n", $page_links);

            if (!empty($page_links)) {
                $prev_link = '<a class="lae-page-nav lae-disabled" href="#" data-page="prev"><i class="lae-icon-arrow-left3"></i></a>';
                $next_link = '<a class="lae-page-nav" href="#" data-page="next"><i class="lae-icon-arrow-right3"></i></a>';

                $output .= $prev_link . "\n" . $r . "\n" . $next_link;
            }
        }

        $output .= '<span class="lae-loading"></span>';

        $output .= '</div>';

        return $output;

    }

    /** Isotope filtering support for Gallery * */

    function get_gallery_terms_filter($terms) {

        $output = '';

        if (!empty($terms)) {

            $output .= '<div class="lae-taxonomy-filter">';

            $output .= '<div class="lae-filter-item segment-0 lae-active"><a data-value="*" href="#">' . esc_html__('All', 'livemesh-el-addons') . '</a></div>';

            $segment_count = 1;
            foreach ($terms as $term) {

                $output .= '<div class="lae-filter-item segment-' . intval($segment_count) . '"><a href="#" data-value=".term-' . preg_replace('/\s+/', '-', $term) . '" title="' . esc_html__('View all items filed under ', 'livemesh-el-addons') . esc_attr($term) . '">' . esc_html($term) . '</a></div>';

                $segment_count++;
            }

            $output .= '</div>';

        }

        return $output;
    }

    /**
     * Returns the singleton instance of the class.
     * 
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof LAE_Gallery_Common ) ) {
            self::$instance = new LAE_Gallery_Common();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$lae_gallery_common = LAE_Gallery_Common::get_instance();


