<?php

namespace Aepro;

use Elementor;
use Elementor\Plugin;
use Elementor\Post_CSS_File;
use WP_Query;

class Frontend{

    private static $_instance = null;

    private $_hook_templates = array();

    private $_page_type = null;

    private $_page_info = array();

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {

        add_shortcode('INSERT_ELEMENTOR',[ $this, 'render_shortcode' ]);
        add_shortcode('AE_SEARCH_KEY', [ $this, 'ae_search_key' ]);
        add_shortcode('AE_SEARCH_COUNT', [ $this, 'ae_search_count' ]);
        add_action('init',[$this, 'init']);

        add_filter('aepro_single_data', [ $this, 'apply_ae_single_post_template'],10,1);
        add_action('aepro_archive_data', [ $this, 'apply_ae_archive_template'],10,1);
        add_action('aepro_404', [$this, 'apply_404_template']);
        add_action('ae_pro_search', [$this, 'apply_search_template']);

        // remove theme hooks and action for single page/post
        add_action('template_redirect', [ $this, 'ae_template_hook']);

        add_action('template_redirect', [ $this, 'set_page_type']);
        add_action('template_redirect', [ $this, 'bind_template_for_hooks']);
    }

    public function ae_search_key(){
        if(is_search()){
            return esc_html( get_search_query( false ) );
        }else{
            return 'Search Key';
        }
    }
    public function ae_search_count(){
        if(is_search()){
            global $wp_query;
            return $wp_query->found_posts;
        }else{
            return '0';
        }
    }
    public function set_page_type(){

        if ( is_front_page() && is_home() ) {
            // Default homepage
        } elseif ( is_front_page() ) {
            // static homepage
        } elseif ( is_home() ) {
            // blog page
        } else {
            //everything else
        }


        if(is_singular()){
            $this->_page_type = 'single';
            return;
        }

        if(is_archive() || is_category() || is_tag()){
            $this->_page_type = 'archive';
            $this->_page_info = get_queried_object();
            return;
        }

        if(is_search()){
            $this->_page_type = 'search';
            return;
        }

        if(is_404()){
            $this->_page_type = '404';
            return;
        }

        if(is_author()){
            $this->_page_type = 'author';
        }

        if(is_home()){
            $this->_page_type = 'home';
        }
    }
    public function init(){
        //$this->bind_template_for_hooks();
    }

    public function render_shortcode($atts){
        if(!isset($atts['id']) || empty($atts['id'])){
            return '';
        }

        $template_id = $atts['id'];

        return $this->render_insert_elementor($template_id);

    }

    public function run_elementor_builder($template_id){
        if(!isset($template_id) || empty($template_id)){
            return '';
        }
        $post_id = $template_id;
        ob_start();
        if(Plugin::$instance->db->is_built_with_elementor( $post_id )) {
            ?>
            <div class="ae_data elementor elementor-<?php echo $post_id; ?>" data-aetid="<?php echo $post_id; ?>">
                <?php echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id ); ?>
            </div>
            <?php
        }else{
            echo __('Not a valid elementor page','ae-pro');
        }
        $response = ob_get_contents();
        ob_end_clean();
        return $response;
    }

    public function render_insert_elementor($template_id,$with_css = false){
        if(!isset($template_id) || empty($template_id)){
            return '';
        }

        $post_id = $template_id;

        // check if page is elementor page

        $edit_mode = get_post_meta($post_id,'_elementor_edit_mode','');

	    $post_id = apply_filters( 'wpml_object_id', $post_id, 'ae_global_templates' );

        ob_start();
        if(Plugin::$instance->db->is_built_with_elementor( $post_id )) {
            ?>
            <div class="ae_data elementor elementor-<?php echo $post_id; ?>" data-aetid="<?php echo $post_id; ?>">
                <?php echo Elementor\Plugin::instance()->frontend->get_builder_content( $post_id,$with_css ); ?>
            </div>
            <?php
        }else{
            echo __('Not a valid elementor page','ae-pro');
        }
        $response = ob_get_contents();
        ob_end_clean();
        return $response;
    }

    private function print_ae_data($sections){
        foreach ( $sections as $section_data ) {
            $section = new Elementor\Element_Section( $section_data );

            $section->print_element();
        }
    }

    public function bind_template_for_hooks(){
        $curr_post = $GLOBALS['post'];
        if(is_admin()){ return; }
        $hook_positions = Aepro::instance()->get_hook_positions();
        $hook_positions = apply_filters('ae_pro_filter_hook_positions',$hook_positions);

        $meta_query = array('relation' => 'OR');
        foreach($hook_positions as $key => $hook_position){
            if(empty($hook_position)){
                continue;
            }
            $meta_query[] = array(
                'key' => 'ae_usage',
                'value'   => $key,
                'compare' => '='
            );
        }

        $args = array(
            'post_type'  =>  'ae_global_templates',
            'posts_per_page'  => -1,
            'meta_query' => $meta_query
        );
        $templates = new WP_Query($args);

        if($templates->found_posts){
            while($templates->have_posts()){

                $templates->the_post();
                $tid = get_the_ID();

                $render_mode = get_post_meta($tid,'ae_render_mode',true);
                if($render_mode != 'normal'){
                    continue;
                }

                if(!$this->validate_hook($tid,$curr_post)){
                    continue;
                }

                $hook_position = get_post_meta($tid,'ae_usage',true);
                if($hook_position == 'custom'){
                    $hook_position = get_post_meta($tid,'ae_custom_usage_area',true);
                }
                if(!empty($hook_position)){
                    $this->_hook_templates[$hook_position] = $tid;

                    add_action($hook_position,function(){
                        $current_filter = current_filter();
                        echo $this->render_insert_elementor($this->_hook_templates[$current_filter]);
                    },10,1);
                }
            }
            wp_reset_postdata();
        }

    }

    /**
     * Checks whether hook is valid for these page as per
     * rules settings in AE Template
     * @param $tid
     * @param null $curr_post
     * @return bool
     */
    protected function validate_hook($tid,$curr_post = null){
        $ae_apply_global = get_post_meta($tid,'ae_apply_global',true);
        if($ae_apply_global === 'true'){
            // applied globally without any restriction
            return true;
        }

        $ae_hook_apply_on = get_post_meta($tid,'ae_hook_apply_on');

        switch($this->_page_type){
            case 'single'   :   // check if AE Template is allowed on current page type,
                                if(!isset($ae_hook_apply_on[0]) || !in_array($this->_page_type,$ae_hook_apply_on[0])){
                                    return false;
                                }

                                // check if post type allowed
                                $ae_hook_post_types = get_post_meta($tid,'ae_hook_post_types');

                                if(!isset($ae_hook_post_types[0]) || !in_array($curr_post->post_type,$ae_hook_post_types[0])){
                                    return false;
                                }

                                $ae_hook_posts_selected = get_post_meta($tid,'ae_hook_posts_selected',true);
                                $ae_hook_posts_excluded = get_post_meta($tid,'ae_hook_posts_excluded',true);

                                if(!empty($ae_hook_posts_selected)){
                                    $ae_hps = explode(',',$ae_hook_posts_selected);
                                    if(!in_array($curr_post->ID,$ae_hps)){
                                        return false;
                                    }
                                }elseif(!empty($ae_hook_posts_excluded)){
                                    $ae_hpe = explode(',',$ae_hook_posts_excluded);
                                    if(in_array($curr_post->ID,$ae_hpe)){
                                        return false;
                                    }
                                }
                                break;

            case 'archive'  :   // check if AE Template is allowed on current page type,
                                if(!isset($ae_hook_apply_on[0]) || !in_array($this->_page_type,$ae_hook_apply_on[0])){
                                    return false;
                                }

                                // check if taxonomy is allowed
                                $ae_hook_taxonomies = get_post_meta($tid,'ae_hook_taxonomies');
                                if(!isset($ae_hook_taxonomies[0]) || !in_array($this->_page_info->taxonomy,$ae_hook_taxonomies[0])){
                                    return false;
                                }

                                $ae_hook_terms_selected = get_post_meta($tid,'ae_hook_terms_selected',true);
                                $ae_hook_terms_excluded = get_post_meta($tid,'ae_hook_terms_excluded',true);

                                if(!empty($ae_hook_terms_selected)){
                                    $ae_hts = explode(',',$ae_hook_terms_selected);
                                    if(!in_array($this->_page_info->term_id,$ae_hts)){
                                        return false;
                                    }
                                }
                                if(!empty($ae_hook_terms_excluded)){
                                    $ae_hte = explode(',',$ae_hook_terms_excluded);
                                    if(in_array($this->_page_info->term_id,$ae_hte)){
                                        return false;
                                    }
                                }
                                break;

            default       :     if(!isset($ae_hook_apply_on[0]) || !in_array($this->_page_type,$ae_hook_apply_on[0])){
                                    return false;
                                }
        }


        return true;
    }


    public function apply_ae_single_post_template($content){
        $helper = new Helper();
        if(!is_single() && !is_page()){
            return $content;
        }
        $post_id = $GLOBALS['post']->ID;

        if(class_exists('WooCommerce') && $GLOBALS['post']->post_type == 'product'){
            return $content;
        }
        // check ae_post_template
        $ae_post_template = $helper->get_ae_active_post_template($post_id,$GLOBALS['post']->post_type);
        if(isset($ae_post_template) && is_numeric($ae_post_template)){
            $template_content = $this->render_insert_elementor($ae_post_template);
            echo $template_content;
        }

        return $content;
    }

    public function apply_ae_archive_template($content){
        $helper = new Helper();

        $ae_archive_template = $helper->get_ae_active_archive_template();

        if($ae_archive_template){
            $template_content = $this->render_insert_elementor($ae_archive_template);
            echo $template_content;
        }

        return $content;
    }

    /**
     * Remove hooks for post single page
     */
    public function ae_template_hook(){
        $helper = new Helper();
        $is_blog = $helper->is_blog();

        if(is_single() || is_page()){
            $post = get_post();
            $tid_post = $helper->get_ae_active_post_template($post->ID,$post->post_type);
            $post_is_canvas_enabled = $helper->is_canvas_enabled($tid_post);
            if(!$tid_post){
                return false;
            }

            if(class_exists('WooCommerce') && $post->post_type == 'product'){
                remove_action( 'woocommerce_before_main_content','hestia_woocommerce_before_main_content');
                add_filter('body_class',function($classes){
                    $classes[] = 'aep-product';
                    return $classes;
                });
                return false;
            }

            // Todo:: Move remove actions to separate file. Run only after theme detect.
            if(class_exists('Aepro\Ae_Theme')){
                $theme_obj = new Ae_Theme();

                if($post_is_canvas_enabled){
                    $theme_obj->setUseCanvas(true);
                }
                $theme_obj->manage_actions();
            }

            // handle canvas template
            add_filter('template_include', [$this, 'handle_canvas_template']);



        }elseif(is_archive()){
            $tid = $helper->get_ae_active_archive_template();
            if(!$tid && !$is_blog){
                return false;
            }

            if(class_exists('Aepro\Ae_Theme')){
                $theme_obj = new Ae_Theme();
                // check if canvas enabled
                if($helper->is_full_override($tid)){
                    $theme_obj->setOverride('full');
                }
                if($helper->is_canvas_enabled($tid)){
                    $theme_obj->setUseCanvas(true);
                }
                $theme_obj->manage_actions();
            }
        }elseif($is_blog){
            $template_id = $helper->get_ae_active_archive_template();
            if(!$template_id){
                return false;
            }
            // is blog - force load archive template
            if(class_exists('Aepro\Ae_Theme')){
                // get override mode

                $theme_obj = new Ae_Theme();
                $theme_obj->setPageType('blog');


                if($helper->is_full_override($template_id)){
                    $theme_obj->setOverride('full');

                    if($helper->is_canvas_enabled($template_id)){
                        $theme_obj->setUseCanvas('full');
                    }
                }
                $theme_obj->manage_actions();
            }
        }elseif(is_404()){
            $tid_404 = $helper->has_404_template();
            if($tid_404){
                if(class_exists('Aepro\Ae_Theme')){
                    $theme_obj = new Ae_Theme();
                    if($helper->is_canvas_enabled($tid_404)){
                        $theme_obj->setUseCanvas(true);
                    }
                    $theme_obj->manage_actions();
                }
            }
        }elseif(is_search()){
            $tid_search = $helper->has_search_template();
            if($tid_search){
                if(class_exists('Aepro\Ae_Theme')){
                    $theme_obj = new Ae_Theme();
                    if($helper->is_canvas_enabled($tid_search)){
                        $theme_obj->setUseCanvas(true);
                    }
                    $theme_obj->manage_actions();
                }
            }
        }

        // load_template i
        do_action('ae_remove_theme_single_page_actions');
        return true;
    }

    function handle_canvas_template($template_include){
        if(is_single() && strpos($template_include,'canvas.php')){
            $template_include = AE_PRO_PATH . 'includes/themes/canvas.php';
        }
        return $template_include;
    }

    public function apply_ae_wc_single_template(){
        global $product;
        $helper = new Helper();
        $ae_product_template = $helper->get_ae_active_post_template($product->get_id(),'product');

        if($ae_product_template != '' && is_numeric($ae_product_template)){
            $template_content = $this->render_insert_elementor($ae_product_template);
            $wc_sd = new \WC_Structured_Data();
            $wc_sd->generate_product_data();
            echo $template_content;
        }
    }

    public function apply_ae_wc_archive_template(){
        $helper = new Helper();
        $ae_product_template = $helper->get_woo_archive_template();

        if($ae_product_template != '' && is_numeric($ae_product_template)){
            $template_content = $this->render_insert_elementor($ae_product_template);
            echo $template_content;
        }
    }

    function apply_404_template(){
        $helper = new Helper();
        $tid = $helper->has_404_template();
        if($tid){
            echo $this->render_insert_elementor($tid);
        }
    }

    function apply_search_template(){
        $helper = new Helper();
        $tid = $helper->has_search_template();
        if($tid){
            echo $this->render_insert_elementor($tid);
        }
    }

}

Frontend::instance();