<?php
use ElementPack\Element_Pack_Loader;

/**
 * all array css classes will output as proper space
 * @param array $classes shortcode css class as array
 * @return proper string
 */

if (!function_exists('element_pack_allow_tags')) {
    function element_pack_allow_tags($tag = null) {
        $tag_allowed = wp_kses_allowed_html('post');

        $tag_allowed['input'] = array(
            'class'   => [],
            'id'      => [],
            'name'    => [],
            'value'   => [],
            'checked' => [],
            'type'    => []
        );
        $tag_allowed['select'] = array(
            'class'    => [],
            'id'       => [],
            'name'     => [],
            'value'    => [],
            'multiple' => [],
            'type'     => []
        );
        $tag_allowed['option'] = array(
            'value'    => [],
            'selected' => []
        );

        if($tag == null){
            return $tag_allowed;
        }
        elseif(is_array($tag)){
            $new_tag_allow = [];
            foreach ($tag as $_tag){
                $new_tag_allow[$_tag] = $tag_allowed[$_tag];
            }

            return $new_tag_allow;
        }
        else{
            return isset($tag_allowed[$tag]) ? array($tag=>$tag_allowed[$tag]) : [];
        }
    }
}

/**
 * Check update for latest version
 */
if(!function_exists("bdthemes_update_info")){
    function bdthemes_update_info(){
        $url    = 'https://bdthemes.com/updates/element-pack';
        $prarm  = bdthemes_element_pack_site_info();
        $return = bdthemes_check_element_pack($url,$prarm);
    }
}

/**
 * Make sure elementor plugin installed or not
 * @return error message
 */
function bdthemes_elementor_not_found() {
    $class = 'notice notice-error';
    $message = __( 'Ops! Elementor Plugin Not Found! Make sure you installed and Activated correctly.', 'bdthemes-element-pack' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}

/**
 * site info function
 * this function handle basic site data for some element.
 * @return  some basic info of website
 */
if ( ! function_exists( 'bdthemes_element_pack_site_info' ) ) {
    function bdthemes_element_pack_site_info(){
        $data             = new stdClass();  
        $data->product    = esc_html__('Element Pack', 'bdthemes_element_pack');
        $data->version    = BDTEP_VER;
        $data->domain     = get_bloginfo( 'url' );
        $data->blogTitle  = get_bloginfo ( 'name' );              
        $data->adminEmail = get_bloginfo( 'admin_email' );
        return $data;
    }
}

register_activation_hook( BDTEP__FILE__, 'bdthemes_update_info');

function element_pack_get_menu() {
    $menus = wp_get_nav_menus();
    $items = ['0' => esc_html__( 'Select Menu', 'bdthemes-element-pack' ) ];
    foreach ( $menus as $menu ) {
        $items[ $menu->slug ] = $menu->name;
    }

    return $items;
}

/**
 * default get_option() default value check
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 * @return mixed
 */
function element_pack_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}

// Anywhere Template
function element_pack_ae_options() {    
    if (post_type_exists('ae_global_templates')) {
        $anywhere = get_posts(array(
            'fields'         => 'ids', // Only get post IDs
            'posts_per_page' => -1,
            'post_type'      => 'ae_global_templates',
        ));

        $anywhere_options = ['0' => esc_html__( 'Select Template', 'bdthemes-element-pack' ) ];

        foreach ($anywhere as $key => $value) {
            $anywhere_options[$value] = get_the_title($value);
        }        
    } else {
        $anywhere_options = ['0' => esc_html__( 'AE Plugin Not Installed', 'bdthemes-element-pack' ) ];
    }
    return $anywhere_options;
}

// Elementor Saved Template 
function element_pack_et_options() {
    $templates = Element_Pack_Loader::elementor()->templates_manager->get_source( 'local' )->get_items();
    $types     = [];

    if ( empty( $templates ) ) {
        $template_options = [ '0' => __( 'You Haven’t Saved Templates Yet.', 'bdthemes-element-pack' ) ];
    } else {
        $template_options = [ '0' => __( 'Select Template', 'bdthemes-element-pack' ) ];
        
        foreach ( $templates as $template ) {
            $template_options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            $types[ $template['template_id'] ] = $template['type'];
        }
    }

    return $template_options;
}

// Sidebar Widgets
function element_pack_sidebar_options() {
    global $wp_registered_sidebars;
    $sidebar_options = [];

    if ( ! $wp_registered_sidebars ) {
        $sidebar_options['0'] = esc_html__( 'No sidebars were found', 'bdthemes-element-pack' );
    } else {
        $sidebar_options['0'] = esc_html__( 'Select Sidebar', 'bdthemes-element-pack' );

        foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
            $sidebar_options[ $sidebar_id ] = $sidebar['name'];
        }
    }

    return $sidebar_options;
}

// BDT Transition
function element_pack_transition_options() {
    $transition_options = [
        ''                    => esc_html__('None', 'bdthemes-element-pack'),
        'fade'                => esc_html__('Fade', 'bdthemes-element-pack'),
        'scale-up'            => esc_html__('Scale Up', 'bdthemes-element-pack'),
        'scale-down'          => esc_html__('Scale Down', 'bdthemes-element-pack'),
        'slide-top'           => esc_html__('Slide Top', 'bdthemes-element-pack'),
        'slide-bottom'        => esc_html__('Slide Bottom', 'bdthemes-element-pack'),
        'slide-left'          => esc_html__('Slide Left', 'bdthemes-element-pack'),
        'slide-right'         => esc_html__('Slide Right', 'bdthemes-element-pack'),
        'slide-top-small'     => esc_html__('Slide Top Small', 'bdthemes-element-pack'),
        'slide-bottom-small'  => esc_html__('Slide Bottom Small', 'bdthemes-element-pack'),
        'slide-left-small'    => esc_html__('Slide Left Small', 'bdthemes-element-pack'),
        'slide-right-small'   => esc_html__('Slide Right Small', 'bdthemes-element-pack'),
        'slide-top-medium'    => esc_html__('Slide Top Medium', 'bdthemes-element-pack'),
        'slide-bottom-medium' => esc_html__('Slide Bottom Medium', 'bdthemes-element-pack'),
        'slide-left-medium'   => esc_html__('Slide Left Medium', 'bdthemes-element-pack'),
        'slide-right-medium'  => esc_html__('Slide Right Medium', 'bdthemes-element-pack'),
    ];

    return $transition_options;
}

// BDT Position
function element_pack_position_options() {
    $position_options = [
        ''              => esc_html__('None', 'bdthemes-element-pack'),
        'top-left'      => esc_html__('Top Left', 'bdthemes-element-pack') ,
        'top-center'    => esc_html__('Top Center', 'bdthemes-element-pack') ,
        'top-right'     => esc_html__('Top Right', 'bdthemes-element-pack') ,
        'center'        => esc_html__('Center', 'bdthemes-element-pack') ,
        'center-left'   => esc_html__('Center Left', 'bdthemes-element-pack') ,
        'center-right'  => esc_html__('Center Right', 'bdthemes-element-pack') ,
        'bottom-left'   => esc_html__('Bottom Left', 'bdthemes-element-pack') ,
        'bottom-center' => esc_html__('Bottom Center', 'bdthemes-element-pack') ,
        'bottom-right'  => esc_html__('Bottom Right', 'bdthemes-element-pack') ,
    ];

    return $position_options;
}

// BDT Drop Position
function element_pack_drop_position_options() {
    $drop_position_options = [
        'bottom-left'    => esc_html__('Bottom Left', 'bdthemes-element-pack'),
        'bottom-center'  => esc_html__('Bottom Center', 'bdthemes-element-pack'),
        'bottom-right'   => esc_html__('Bottom Right', 'bdthemes-element-pack'),
        'bottom-justify' => esc_html__('Bottom Justify', 'bdthemes-element-pack'),
        'top-left'       => esc_html__('Top Left', 'bdthemes-element-pack'),
        'top-center'     => esc_html__('Top Center', 'bdthemes-element-pack'),
        'top-right'      => esc_html__('Top Right', 'bdthemes-element-pack'),
        'top-justify'    => esc_html__('Top Justify', 'bdthemes-element-pack'),
        'left-top'       => esc_html__('Left Top', 'bdthemes-element-pack'),
        'left-center'    => esc_html__('Left Center', 'bdthemes-element-pack'),
        'left-bottom'    => esc_html__('Left Bottom', 'bdthemes-element-pack'),
        'right-top'      => esc_html__('Right Top', 'bdthemes-element-pack'),
        'right-center'   => esc_html__('Right Center', 'bdthemes-element-pack'),
        'right-bottom'   => esc_html__('Right Bottom', 'bdthemes-element-pack'),
    ];

    return $drop_position_options;
}

// Button Size
function element_pack_button_sizes() {
    $button_sizes = [
        'xs' => esc_html__( 'Extra Small', 'bdthemes-element-pack' ),
        'sm' => esc_html__( 'Small', 'bdthemes-element-pack' ),
        'md' => esc_html__( 'Medium', 'bdthemes-element-pack' ),
        'lg' => esc_html__( 'Large', 'bdthemes-element-pack' ),
        'xl' => esc_html__( 'Extra Large', 'bdthemes-element-pack' ),
    ];

    return $button_sizes;
}

// Title Tags
function element_pack_title_tags() {
    $title_tags = [
        'h1'   => esc_html__( 'H1', 'bdthemes-element-pack' ),
        'h2'   => esc_html__( 'H2', 'bdthemes-element-pack' ),
        'h3'   => esc_html__( 'H3', 'bdthemes-element-pack' ),
        'h4'   => esc_html__( 'H4', 'bdthemes-element-pack' ),
        'h5'   => esc_html__( 'H5', 'bdthemes-element-pack' ),
        'h6'   => esc_html__( 'H6', 'bdthemes-element-pack' ),
        'div'  => esc_html__( 'div', 'bdthemes-element-pack' ),
        'span' => esc_html__( 'span', 'bdthemes-element-pack' ),
        'p'    => esc_html__( 'p', 'bdthemes-element-pack' ),
    ];

    return $title_tags;
}

/**
 * Ninja form array creator for get all form as 
 * @return array [description]
 */
function element_pack_ninja_forms_options() {
    $form_options = [];
    if ( class_exists( 'Ninja_Forms' ) ) {
        $ninja_forms  = Ninja_Forms()->form()->get_forms();
        if ( ! empty( $ninja_forms ) && ! is_wp_error( $ninja_forms ) ) {
            $form_options = ['0' => esc_html__( 'Select Form', 'bdthemes-element-pack' )];
            foreach ( $ninja_forms as $form ) {   
                $form_options[ $form->get_id() ] = $form->get_setting( 'title' );
            }
        }
    } else {
        $form_options = ['0' => esc_html__( 'Form Not Found!', 'bdthemes-element-pack' ) ];
    }

    return $form_options;
}

function element_pack_caldera_forms_options() {
    if ( class_exists( 'Caldera_Forms' ) ) {
        $caldera_forms = Caldera_Forms_Forms::get_forms( true, true );
        $form_options  = ['0' => esc_html__( 'Select Form', 'bdthemes-element-pack' )];
        $form          = [];
        if ( ! empty( $caldera_forms ) && ! is_wp_error( $caldera_forms ) ) {
            foreach ( $caldera_forms as $form ) {
                if ( isset($form['ID']) and isset($form['name'])) {
                    $form_options[$form['ID']] = $form['name'];
                }   
            }
        }
    } else {
        $form_options = ['0' => esc_html__( 'Form Not Found!', 'bdthemes-element-pack' ) ];
    }

    return $form_options;
}

function element_pack_quform_options() {
    if ( class_exists( 'Quform' ) ) {
        $quform       = Quform::getService('repository');
        $quform       = $quform->formsToSelectArray();
        $form_options = ['0' => esc_html__( 'Select Form', 'bdthemes-element-pack' )];
        if ( ! empty( $quform ) && ! is_wp_error( $quform ) ) {
            foreach ( $quform as $id => $name ) {
                $form_options[esc_attr( $id )] = esc_html( $name );
            }
        }
    } else {
        $form_options = ['0' => esc_html__( 'Form Not Found!', 'bdthemes-element-pack' ) ];
    }

    return $form_options;
}


function element_pack_gravity_forms_options() {
    if ( class_exists( 'GFCommon' ) ) {
        $contact_forms = RGFormsModel::get_forms( null, 'title' );
        $form_options = ['0' => esc_html__( 'Select Form', 'bdthemes-element-pack' )];
        if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {
            foreach ( $contact_forms as $form ) {   
                $form_options[ $form->id ] = $form->title;
            }
        }
    } else {
        $form_options = ['0' => esc_html__( 'Form Not Found!', 'bdthemes-element-pack' ) ];
    }

    return $form_options;
}


function element_pack_rev_slider_options() {
    if( class_exists( 'RevSlider' ) ){
        $slider             = new RevSlider();
        $revolution_sliders = $slider->getArrSliders();
        $slider_options     = ['0' => esc_html__( 'Select Slider', 'bdthemes-element-pack' ) ];
        if ( ! empty( $revolution_sliders ) && ! is_wp_error( $revolution_sliders ) ) {
            foreach ( $revolution_sliders as $revolution_slider ) {
               $alias = $revolution_slider->getAlias();
               $title = $revolution_slider->getTitle();
               $slider_options[$alias] = $title;
            }
        }
    } else {
        $slider_options = ['0' => esc_html__( 'No Slider Found!', 'bdthemes-element-pack' ) ];
    }
    return $slider_options;
}


/**
 * Pagination function for help pagination type element
 */

if (!function_exists('element_pack_pagination')) {
    function element_pack_pagination() {
        if( is_singular() )
            return;

        global $wp_query;

        /** Stop execution if there's only 1 page */
        if( $wp_query->max_num_pages <= 1 )
            return;

        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
        $max   = intval( $wp_query->max_num_pages );

        /** Add current page to the array */
        if ( $paged >= 1 )
            $links[] = $paged;

        /** Add the pages around the current page to the array */
        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<div class="pagination-wrapper"><ul class="bdt-pagination bdt-flex-center">' . "\n";

        /** Previous Post Link */
        if ( get_previous_posts_link() )
            printf( '<li class="pagination-prev">%s</li>' . "\n", get_previous_posts_link('<span bdt-pagination-previous></span>') );

        /** Link to first page, plus ellipses if necessary */
        if ( ! in_array( 1, $links ) ) {
            $class = 1 == $paged ? ' class="current"' : '';

            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

            if ( ! in_array( 2, $links ) )
                echo '<li><span>...</span></li>';
        }

        /** Link to current page, plus 2 pages in either direction if necessary */
        sort( $links );
        foreach ( (array) $links as $link ) {
            $class = $paged == $link ? ' class="bdt-active"' : '';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
        }

        /** Link to last page, plus ellipses if necessary */
        if ( ! in_array( $max, $links ) ) {
            if ( ! in_array( $max - 1, $links ) )
                echo '<li><span>...</span></li>' . "\n";

            $class = $paged == $max ? ' class="bdt-active"' : '';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
        }

        /** Next Post Link */
        if ( get_next_posts_link() )
            printf( '<li class="pagination-next">%s</li>' . "\n", get_next_posts_link('<span bdt-pagination-next></span>') );

        echo '</ul></div>' . "\n";
        echo '<p class="bdt-hidden">'.posts_nav_link().'</p>';
    }
}

/**
 * compare with existing version with latest version
 * @return url element pack update server
 * @return string some basic info that need to compare
 */
if ( ! function_exists('bdthemes_check_element_pack')) {
    function bdthemes_check_element_pack($url,$param=[]){
        $arg = array( "body" => $param ); 
        $result = @wp_remote_post( $url,$arg );
    }
}



/**
 * helper functions class for helping some common usage things
 */
if (!class_exists('element_pack_helper')) {
    class element_pack_helper {

        static $selfClosing = ['input'];

        /**
         * Renders a tag.
         *
         * @param  string $name
         * @param  array  $attrs
         * @param  string $text
         * @return string
         */
        public static function tag($name, array $attrs = [], $text = null) {
            $attrs = self::attrs($attrs);
            return "<{$name}{ $attrs }" . (in_array($name, self::$selfClosing) ? '/>' : ">$text</{$name}>");
        }

        /**
         * Renders a form tag.
         *
         * @param  array $tags
         * @param  array $attrs
         * @return string
         */
        public static function form($tags, array $attrs = []) {
            $attrs = self::attrs($attrs);
            return "<form{$attrs}>\n" . implode("\n", array_map(function($tag) {
                $output = self::tag($tag['tag'], array_diff_key($tag, ['tag' => null]));
                return $output;
            }, $tags)) . "\n</form>";
        }

        /**
         * Renders an image tag.
         *
         * @param  array|string $url
         * @param  array        $attrs
         * @return string
         */
        public static function image($url, array $attrs = []) {
            $url = (array) $url;
            $path = array_shift($url);
            $params = $url ? '?'.http_build_query(array_map(function ($value) {
                return is_array($value) ? implode(',', $value) : $value;
            }, $url)) : '';

            if (!isset($attrs['alt']) || empty($attrs['alt'])) {
                $attrs['alt'] = true;
            }

            $output = self::attrs(['src' => $path.$params], $attrs);

            return "<img{$output}>";
        }
        
        /**
         * Renders tag attributes.
         * @param  array $attrs
         * @return string
         */
        public static function attrs(array $attrs) {
            $output = [];

            if (count($args = func_get_args()) > 1) {
                $attrs = call_user_func_array('array_merge_recursive', $args);
            }

            foreach ($attrs as $key => $value) {

                if (is_array($value)) { $value = implode(' ', array_filter($value)); }
                if (empty($value) && !is_numeric($value)) { continue; }

                if (is_numeric($key)) {
                   $output[] = $value;
                } elseif ($value === true) {
                   $output[] = $key;
                } elseif ($value !== '') {
                   $output[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_COMPAT, 'UTF-8', false));
                }
            }

            return $output ? ' '.implode(' ', $output) : '';
        }

        /**
         * social icon generator from link
         * @param  [type] $link [description]
         * @return [type]       [description]
         */
        public static function icon($link) {
           static $icons;
           $icons = self::social_icons();

           if (strpos($link, 'mailto:') === 0) {
               return 'mail';
           }

           $icon = parse_url($link, PHP_URL_HOST);
           $icon = preg_replace('/.*?(plus\.google|[^\.]+)\.[^\.]+$/i', '$1', $icon);
           $icon = str_replace('plus.google', 'google-plus', $icon);

           if (!in_array($icon, $icons)) {
               $icon = 'social';
           }

           return $icon;
        }

        // most used social icons array
        public static function social_icons() {
           $icons = [ "behance", "dribbble", "facebook", "github-alt", "github", "foursquare", "tumblr", "whatsapp", "soundcloud", "flickr", "google-plus", "google", "linkedin", "vimeo", "instagram", "joomla", "pagekit", "pinterest", "twitter", "uikit", "wordpress", "xing", "youtube" ];

           return $icons;
        }


        public static function remove_p( $content ) {
            $content = force_balance_tags( $content );
            $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
            $content = preg_replace( '~\s?<p>(\s| )+</p>\s?~', '', $content );
            return $content;
        }

        /**
         * Get timezone id from server
         * @return [type] [description]
         */
        public static function get_timezone_id() {    
            $timezone = get_option( 'timezone_string' );

            /* If site timezone string exists, return it */
            if ( $timezone ) {
                return $timezone;
            }

            $utc_offset = 3600 * get_option( 'gmt_offset', 0 );

            /* Get UTC offset, if it isn't set return UTC */
            if ( ! $utc_offset ) {
                return 'UTC';
            }

            /* Attempt to guess the timezone string from the UTC offset */
            $timezone = timezone_name_from_abbr( '', $utc_offset );

            /* Last try, guess timezone string manually */
            if ( $timezone === false ) {

                $is_dst = date( 'I' );

                foreach ( timezone_abbreviations_list() as $abbr ) {
                    foreach ( $abbr as $city ) {
                        if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset ) {
                            return $city['timezone_id'];
                        }
                    }
                }
            }

            /* If we still haven't figured out the timezone, fall back to UTC */
            return 'UTC';
        }

        /**
         * ACtual CSS Class extrator
         * @param  [type] $classes [description]
         * @return [type]          [description]
         */
        public static function acssc($classes) {
            if (is_array($classes)) {
                $classes     = implode($classes, ' ');
            }
            $abs_classes = trim(preg_replace('/\s\s+/', ' ', $classes));
            return $abs_classes;
        }

        /**
         * Custom Excerpt Length
         * @param  integer $limit [description]
         * @return [type]         [description]
         */
        public static function custom_excerpt($limit=50) {
            return strip_shortcodes(wp_trim_words(get_the_content(), $limit, '...'));
        }

    }
}