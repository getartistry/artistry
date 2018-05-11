<?php
/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 8/6/2017
 * Time: 5:12 PM
 */
class CS_LE_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_options = array(
            'classname' => 'cs_le_widget',
            'description' => 'Listening Engine News Widget',
        );
        parent::__construct('cs_le_widget', 'Listening Engine News Widget', $widget_options);
    }
    public function widget( $args, $instance ) {
        $show_content = true;

        $options = get_option('curation_suite_data');
        if (is_array($options) && array_key_exists('curation_suite_listening_platform', $options)) {
            $loadCSListening = $options['curation_suite_listening_platform'];
        }
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $platform_id = $instance['cs_le_widget_platform_id'];
        $image_align = $instance['cs_le_widget_image_option'];
        $show_snippet = $instance['cs_le_widget_show_snippet'];
        $number_posts = $instance['cs_le_widget_number_posts'];

        $cu_date_sort = '';

        if($number_posts =='') {
            $number_posts = 3;
        }
        $api_url_arr = array('content','latest',$platform_id,$number_posts);
        $passed_data = array('sort'=>$cu_date_sort,'view_type'=>'widget');
        $data = ybi_curation_suite_api_call('display',$passed_data, $api_url_arr);
        $status= $data['status'];
        if(is_null($status) || $status != 'success') {
            $show_content = false;
        }
        echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];

        if($show_content) {
            $results = $data['results'];
            $html = '<ul>';

            if(!is_null($results) && $results) {
                foreach ($results as $ContentItem) {
                    $html .= '<div style="margin-bottom: 20px;"><li>';
                    $title_html_safe = html_entity_decode($ContentItem['title']);
                    $html .= '<a href="'.$ContentItem['url'].'" target="_blank" title="'.$title_html_safe.'" style="display: inline-block;">';
                    $snippet_html = '';
                    $size_html ='';
                    if($show_snippet==1) {
                        $snippet = cs_limit_words_with_dots($ContentItem['snippet'],30);
                        $snippet = strip_tags($snippet);
                        $snippet_html .= '<p style="font-size: .8em; line-height: 1.2em;">'.$snippet.'</p>';
                    }
                    if($image_align != 'none') {
                        if($image_align=='aligncenter') {
                            $html .= $ContentItem['title'].'<img src="'.$ContentItem['image_src'].'" class="'.$image_align.'" style="margin: 5px 0;width:100%;">'.$snippet_html;
                        } else {
                            // image align is either left or right
                            if($image_align=='alignleft') {
                                $size_html = 'style="max-width: 75px; margin: 6px 6px 6px 0;"';
                            } else {
                                $size_html = 'style="max-width: 75px; margin: 6px 0 6px 6px;"';
                            }

                                if($show_snippet==0) {
                                    $html .= '<img src="'.$ContentItem['image_src'].'" class="'.$image_align.'" '.$size_html.'>'.$ContentItem['title'].$snippet_html;
                                } else {
                                    $html .= $ContentItem['title'].'<img src="'.$ContentItem['image_src'].'" class="'.$image_align.'" '.$size_html.'>'.$snippet_html;
                                }
                        }
                    } else {
                        $html .= $ContentItem['title'] . $snippet_html;
                    }
                    $html .= '</a></li>';
                    $html .= '</div>';

                }
                echo $html;
            }

        }
        echo $args['after_widget'];
    }
    public function form( $instance ) {
        $show_form = true;
        $options = get_option('curation_suite_data');
        $load_le_engine = 0;
        if (is_array($options) && array_key_exists('curation_suite_listening_platform', $options)) {
            $load_le_engine = $options['curation_suite_listening_platform'];
        }

        $error_message = '';
        $platform_arr = array();
        $api_key = get_option('curation_suite_listening_api_key');
        if ($api_key && $load_le_engine) {
            $action_parms = array('get-organization-platforms');
            $data = ybi_curation_suite_api_call('', array(), $action_parms);
            //echo $data['url'];
            if ($data && array_key_exists('status', $data) && $data['status'] == 'success') {
                $Organization = $data['organization'];
                if($Organization['active'] == 1) {
                    $platforms = $data['results'];
                    foreach ($platforms as $platform) {
                        $id = $platform['id'];
                        $name = $platform['id'];
                        //$platform_arr[$id] = $platform['platform_name'];
                        $active_text = 'Active';
                        if($platform['active']!=1) {
                            $active_text = 'InActive';
                        }
                        $platform_arr[$id] = $platform['platform_name'] . ' (' . $active_text . ')';
                    }
                } else {
                    $show_form = false;
                    $error_message = 'Your Listening Engine is not active. To use the news widget you need an active Listening Engine platform.';
                }
            } else {
                $show_form = false;
                $error_message = 'Your Listening Engine is not active. To use the news widget you need an active Listening Engine platform.';
            }
        } else {
            $show_form = false;
            if($api_key == '') {
                $error_message = 'Your Listening Engine has not been activated yet. Use the API key to activate and setup here: <a href="' . admin_url('admin.php?page=youbrandinc-listening-platform') . '" target="_blank">Listening Engine Reading Page</a>';
            } else {
                if($api_key && !$load_le_engine) {
                    //$error_message = 'Listening Engine is not turned on. Turn on your Listening Engine with API Key: <a href="' . admin_url('admin.php?page=youbrandinc-listening-platform') . '" target="_blank">Listening Engine Reading Page</a>';
                    $error_message = 'Listening Engine is not turned on. Turn on your Listening Engine <a href="' . admin_url('admin.php?page=curation_suite_display_settings') . '" target="_blank">Curation Suite Admin</a>';
                } else {
                    $error_message = 'No active Listening Engines found. Turn on and activate your Listening Engine in the <a href="' . admin_url('admin.php?page=curation_suite_display_settings') . '" target="_blank">Curation Suite Admin</a>';
                }
            }


        }

        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $platform_id = ! empty( $instance['cs_le_widget_platform_id'] ) ? esc_attr($instance['cs_le_widget_platform_id']) : '';
        $image_option = ! empty( $instance['cs_le_widget_image_option'] ) ? esc_attr($instance['cs_le_widget_image_option']) : '';
        $show_snippet = ! empty( $instance['cs_le_widget_show_snippet'] ) ? esc_attr($instance['cs_le_widget_show_snippet']) : '';
        $number_posts = ! empty( $instance['cs_le_widget_number_posts'] ) ? esc_attr($instance['cs_le_widget_number_posts']) : 3;

        if($show_form) {
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br />
        <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'cs_le_widget_platform_id' ); ?>">Platform:</label>
        <select id="<?php echo $this->get_field_id( 'cs_le_widget_platform_id' ); ?>" name="<?php echo $this->get_field_name( 'cs_le_widget_platform_id' ); ?>">
            <?php
            foreach ($platform_arr as $key => $value) { ?>
                <option value="<?php echo $key; ?>" <?php selected($platform_id, intval($key), true); ?>><?php echo $value; ?></option>
            <?php } ?>
        </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'cs_le_widget_image_option' ); ?>">Image Options:</label>
            <select id="<?php echo $this->get_field_id( 'cs_le_widget_image_option' ); ?>" name="<?php echo $this->get_field_name( 'cs_le_widget_image_option' ); ?>">
                    <option value="none" <?php selected($image_option, 'none', true); ?>>No Images</option>
                    <option value="alignleft" <?php selected($image_option, 'alignleft', true); ?>>Left Align</option>
                    <option value="aligncenter" <?php selected($image_option, 'aligncenter', true); ?>>Center Below Headline</option>
                    <option value="alignright" <?php selected($image_option, 'alignright', true); ?>>Right Align</option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'cs_le_widget_show_snippet' ) ); ?>">
            <input id="<?php echo esc_attr( $this->get_field_id( 'cs_le_widget_show_snippet' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cs_le_widget_show_snippet' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_snippet ); ?> />
                <?php _e( 'Show Snippet', 'cs_le_widget' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'cs_le_widget_number_posts' ) ); ?>"><?php _e( 'Number of news items to show:', 'cs_le_widget' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'cs_le_widget_number_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cs_le_widget_number_posts' ) ); ?>" step="1" min="1" max="10" value="<?php echo esc_attr( $number_posts ); ?>" size="3" type="number">
        </p>

        <?php
        } else { // if($show_form)
            ?>
            <p><?php echo $error_message ?></p>
            <?php
        }

    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'cs_le_widget_platform_id' ] = intval(strip_tags( $new_instance[ 'cs_le_widget_platform_id' ] ));
        $instance[ 'cs_le_widget_image_option' ] = strip_tags( $new_instance[ 'cs_le_widget_image_option' ] );
        $instance[ 'cs_le_widget_show_snippet' ] = intval(strip_tags( $new_instance[ 'cs_le_widget_show_snippet' ] ));
        $instance[ 'cs_le_widget_number_posts' ] = intval(strip_tags( $new_instance[ 'cs_le_widget_number_posts' ] ));
        return $instance;
    }
}