<?php

namespace LivemeshAddons\Modules;

use Elementor\Group_Control_Image_Size;

class LAE_Module {

    protected $post;

    protected $post_ID;

    protected $settings;

    function __construct($post, $settings) {

        $this->post = $post;

        $this->post_ID = $post->ID;

        $this->settings = $settings;
    }

    function get_module_classes() {

        return 'lae-module';

    }

    function get_thumbnail($size = 'default') {

        $output = '';

        if ($thumbnail_exists = has_post_thumbnail($this->post_ID)):

            $output .= '<div class="lae-module-thumb">';

            $output .= $this->get_media($size);

            $output .= $this->get_lightbox();

            $output .= '</div><!-- .lae-module-thumb -->';

        endif;

        return $output;
    }

    function get_media($size = 'default') {

        $output = '';

        if ($size !== 'default') {

            $thumbnail_html = get_the_post_thumbnail($this->post_ID, $size);
        }
        else {

            $image_setting = ['id' => get_post_thumbnail_id($this->post_ID)];

            $thumbnail_html = lae_get_image_html($image_setting, 'thumbnail_size', $this->settings);
        }

        if ($this->settings['image_linkable']):

            $target = $this->settings['post_link_new_window'] ? ' target="_blank"' : '';

            $output .= '<a class="lae-post-link" href="' . get_the_permalink($this->post_ID) . '"' . $target . '>' . $thumbnail_html . '</a>';

        else:

            $output .= $thumbnail_html;

        endif;

        return $output;
    }

    function get_lightbox() {

        $output = '';

        if ($this->settings['enable_lightbox']) :

            $featured_image_id = get_post_thumbnail_id($this->post_ID);

            $featured_image_data = wp_get_attachment_image_src($featured_image_id, 'full');

            if ($featured_image_data) {

                $featured_image_src = $featured_image_data[0];

                $output .= '<a class="lae-lightbox-item" data-elementor-open-lightbox="no" data-fancybox="' . esc_attr($this->settings['block_class']) . '" data-post-link="' . esc_url(get_the_permalink($this->post_ID)) . '" data-post-excerpt="' . esc_html($this->get_excerpt_for_lightbox()) . '" href="' . $featured_image_src . '" title="' . get_the_title($this->post_ID) . '"><i class="lae-icon-full-screen"></i></a>';

            }

        endif;

        return $output;
    }

    function get_media_title() {

        $output = '';

        if ($this->settings['display_title_on_thumbnail']) :

            $target = $this->settings['post_link_new_window'] ? ' target="_blank"' : '';

            $output = '<' . $this->settings['title_tag'] . ' class="lae-post-title">';

            $output .= '<a href="' . get_permalink($this->post_ID) . '" title="' . get_the_title($this->post_ID) . '" rel="bookmark"' . $target . '>' . get_the_title($this->post_ID) . '</a>';

            $output .= '</' . $this->settings['title_tag'] . '>';

        endif;

        return $output;

    }

    function get_media_taxonomy() {

        $output = '';

        if ($this->settings['display_taxonomy_on_thumbnail']) :

            if (empty($taxonomies))
                $taxonomies = $this->settings['taxonomies'];

            foreach ($taxonomies as $taxonomy) {

                $output .= $this->get_taxonomy_info($taxonomy);

            }

        endif;

        return $output;

    }

    function get_media_overlay() {

        $output = '<div class="lae-module-image-overlay"></div>';

        return $output;

    }

    function get_title() {

        $output = '';

        if ($this->settings['display_title']) :

            $target = $this->settings['post_link_new_window'] ? ' target="_blank"' : '';

            $output = '<' . $this->settings['entry_title_tag'] . ' class="entry-title">';

            $output .= '<a href="' . get_permalink($this->post_ID) . '" title="' . get_the_title($this->post_ID) . '" rel="bookmark"' . $target . '>' . get_the_title($this->post_ID) . '</a>';

            $output .= '</' . $this->settings['entry_title_tag'] . '>';

        endif;

        return $output;

    }

    function get_excerpt() {

        $output = '';

        if ($this->settings['display_summary']) :

            $excerpt_count = $this->settings['excerpt_length'];

            $output = '<div class="entry-summary">';

            if (empty($this->post->post_excerpt))
                $excerpt = $this->post->post_content;
            else
                $excerpt = $this->post->post_excerpt;

            $output .= do_shortcode(force_balance_tags(html_entity_decode(wp_trim_words(htmlentities($excerpt), $excerpt_count, '…'))));

            $output .= '</div><!-- .entry-summary -->';

        endif;

        return $output;

    }

    function get_excerpt_for_lightbox() {

        $output = '';

        if ($this->settings['display_excerpt_lightbox']) :

            // Trim the excerpt only if you are displaying content since lightbox has lots of room for displaying excerpt
            if (empty($this->post->post_excerpt)) {

                $excerpt_count = $this->settings['excerpt_length'];

                $excerpt = $this->post->post_content;

                $excerpt = force_balance_tags(html_entity_decode(wp_trim_words(htmlentities($excerpt), $excerpt_count, '…')));
            }
            else {
                $excerpt = $this->post->post_excerpt;
            }

            $output .= do_shortcode($excerpt);

        endif;

        return $output;

    }

    function get_read_more_link() {

        $output = '';

        if ($this->settings['display_read_more']) {

            $output .= '<div class="lae-read-more">';

            $output .= '<a href="' . get_the_permalink($this->post_ID) . '">' . esc_html__('Read more', 'lae-bb-addons') . '</a>';

            $output .= '</div>';

        }

        return $output;

    }

    function get_taxonomy_info($taxonomy) {

        $output = '';

        $terms = get_the_terms($this->post_ID, $taxonomy);

        if (!empty($terms) && !is_wp_error($terms)) {

            $output .= '<span class="lae-terms">';

            $term_count = 0;

            foreach ($terms as $term) {

                if ($term_count != 0)
                    $output .= ', ';

                $output .= '<a href="' . get_term_link($term->slug, $taxonomy) . '">' . $term->name . '</a>';

                $term_count = $term_count + 1;
            }
            $output .= '</span>';
        }
        return $output;
    }

    function get_taxonomies_info($taxonomies = null) {

        $output = '';

        if ($this->settings['display_taxonomy']) :

            if (empty($taxonomies))
                $taxonomies = $this->settings['taxonomies'];

            foreach ($taxonomies as $taxonomy) {

                $output .= $this->get_taxonomy_info($taxonomy);

            }

        endif;

        return $output;
    }

    function get_author() {

        $output = '';

        if ($this->settings['display_author']) :

            $output .= '<span class="author vcard">' . esc_html__('By ', 'livemesh-el-addons') . '<a class="url fn n" href="' . get_author_posts_url($this->post->post_author) . '" title="' . esc_attr(get_the_author_meta('display_name', $this->post->post_author)) . '">' . esc_html(get_the_author_meta('display_name', $this->post->post_author)) . '</a></span>';

        endif;

        return $output;
    }

    function get_date($format = null) {

        $output = '';

        if ($this->settings['display_post_date']) :

            if (empty($format))
                $format = get_option('date_format');

            $output .= '<span class="published"><abbr title="' . get_the_time(esc_html__('l, F, Y, g:i a', 'livemesh-el-addons'), $this->post_ID) . '">' . get_the_time($format, $this->post_ID) . '</abbr></span>';

        endif;

        return $output;
    }

    function get_comments() {

        $output = '';

        if ($this->settings['display_comments']) :

            $output .= $this->entry_comments_link($this->post_ID);

        endif;

        return $output;

    }

    function entry_comments_link($id, $args = array()) {

        $comments_link = '';
        $num_of_comments = doubleval(get_comments_number($id));

        $defaults = array('zero' => __('No Comments', 'livemesh-el-addons'), 'one' => __('%1$s Comment', 'livemesh-el-addons'), 'more' => __('%1$s Comments', 'livemesh-el-addons'), 'css_class' => 'lae-comments', 'none' => '', 'before' => '', 'after' => '');

        /* Merge the input arguments and the defaults. */
        $args = wp_parse_args($args, $defaults);

        $comments_link .= '<span class="' . esc_attr($args['css_class']) . '">';

        if (0 == $num_of_comments && !comments_open($id) && !pings_open($id)) {
            if ($args['none'])
                $comments_link .= sprintf($args['none'], number_format_i18n($num_of_comments));
        }
        elseif (0 == $num_of_comments)
            $comments_link .= '<a href="' . get_permalink($id) . '#respond" title="' . sprintf(esc_attr__('Comment on %1$s', 'livemesh-el-addons'), the_title_attribute(array('echo' => false, 'post' => $id))) . '">' . sprintf($args['zero'], number_format_i18n($num_of_comments)) . '</a>';
        elseif (1 == $num_of_comments)
            $comments_link .= '<a href="' . get_comments_link($id) . '" title="' . sprintf(esc_attr__('Comment on %1$s', 'livemesh-el-addons'), the_title_attribute(array('echo' => false, 'post' => $id))) . '">' . sprintf($args['one'], number_format_i18n($num_of_comments)) . '</a>';
        elseif (1 < $num_of_comments)
            $comments_link .= '<a href="' . get_comments_link($id) . '" title="' . sprintf(esc_attr__('Comment on %1$s', 'livemesh-el-addons'), the_title_attribute(array('echo' => false, 'post' => $id))) . '">' . sprintf($args['more'], number_format_i18n($num_of_comments)) . '</a>';

        $comments_link .= '</span>';

        $comments_link = $args['before'] . $comments_link . $args['after'];

        return $comments_link;
    }

    function entry_comments_number($id, $args = array()) {
        $comments_text = '';
        $number = get_comments_number($id);
        $defaults = array('zero' => __('No Comments', 'livemesh-el-addons'), 'one' => __('%1$s Comment', 'livemesh-el-addons'), 'more' => __('%1$s Comments', 'livemesh-el-addons'), 'css_class' => 'lae-comments', 'none' => '', 'before' => '', 'after' => '');

        /* Merge the input arguments and the defaults. */
        $args = wp_parse_args($args, $defaults);

        $comments_text .= '<span class="' . esc_attr($args['css_class']) . '">';

        if (0 == $number && !comments_open($id) && !pings_open($id)) {
            if ($args['none'])
                $comments_text .= sprintf($args['none'], number_format_i18n($number));
        }
        elseif ($number == 0)
            $comments_text .= sprintf($args['zero'], number_format_i18n($number));
        elseif ($number == 1)
            $comments_text .= sprintf($args['one'], number_format_i18n($number));
        elseif ($number > 1)
            $comments_text .= sprintf($args['more'], number_format_i18n($number));

        $comments_text .= '</span>';

        if ($comments_text)
            $comments_text = $args['before'] . $comments_text . $args['after'];

        return $comments_text;
    }

}