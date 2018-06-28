<?php

/**
 * The CMB code
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
/**
 * Provide CMB
 */
class Glossary_CMB
{
    /**
     * Initialize the class
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $plugin = Glossary::get_instance();
        $this->cpts = $plugin->get_cpts();
        $this->settings = gl_get_settings();
        require_once plugin_dir_path( __FILE__ ) . '/CMB2/init.php';
        require_once plugin_dir_path( __FILE__ ) . '/cmb2-extra.php';
        require_once plugin_dir_path( __FILE__ ) . '/cmb2-post-search-field.php';
        add_filter( 'multicheck_posttype_posttypes', array( $this, 'hide_glossary' ) );
        // Add metabox
        add_action( 'cmb2_init', array( $this, 'glossary_post_types' ) );
        add_action( 'cmb2_init', array( $this, 'glossary' ) );
        add_action(
            'cmb2_save_options-page_fields',
            array( $this, 'permalink_alert' ),
            4,
            9999
        );
    }
    
    /**
     * Hide glossary post type from settings
     *
     * @param array $cpts The cpts.
     *
     * @return array
     */
    public function hide_glossary( $cpts )
    {
        unset( $cpts['attachment'] );
        return $cpts;
    }
    
    /**
     * Metabox for post types
     *
     * @return void
     */
    public function glossary_post_types()
    {
        if ( empty($this->settings['posttypes']) ) {
            $this->settings['posttypes'] = array( 'post' );
        }
        $cmb_post = new_cmb2_box( array(
            'id'           => 'glossary_post_metabox',
            'title'        => __( 'Glossary Post Override', GT_TEXTDOMAIN ),
            'object_types' => $this->settings['posttypes'],
            'context'      => 'normal',
            'priority'     => 'high',
            'show_names'   => true,
        ) );
        $cmb_post->add_field( array(
            'name' => __( 'Disable Glossary for this post', GT_TEXTDOMAIN ),
            'id'   => GT_SETTINGS . '_disable',
            'type' => 'checkbox',
        ) );
    }
    
    /**
     * Metabox for glossary post type
     *
     * @return void
     */
    public function glossary()
    {
        $cmb = new_cmb2_box( array(
            'id'           => 'glossary_metabox',
            'title'        => __( 'Glossary Auto-Link settings', GT_TEXTDOMAIN ),
            'object_types' => $this->cpts,
            'context'      => 'normal',
            'priority'     => 'high',
            'show_names'   => true,
        ) );
        $cmb->add_field( array(
            'name' => __( 'Additional key terms for this definition', GT_TEXTDOMAIN ),
            'desc' => __( 'Case-insensitive. To add more than one, separate them with commas', GT_TEXTDOMAIN ),
            'id'   => GT_SETTINGS . '_tag',
            'type' => 'text',
        ) );
        $cmb->add_field( array(
            'name'    => __( 'What type of link?', GT_TEXTDOMAIN ),
            'id'      => GT_SETTINGS . '_link_type',
            'type'    => 'radio',
            'default' => 'external',
            'options' => array(
            'external' => 'External URL',
            'internal' => 'Internal URL',
        ),
        ) );
        $cmb->add_field( array(
            'name'      => __( 'Link to external URL', GT_TEXTDOMAIN ),
            'desc'      => __( 'If this is left blank, the previous options defaults back and key term is linked to internal definition page', GT_TEXTDOMAIN ),
            'id'        => GT_SETTINGS . '_url',
            'type'      => 'text_url',
            'protocols' => array( 'http', 'https' ),
        ) );
        $cmb->add_field( array(
            'name'        => __( 'Internal', GT_TEXTDOMAIN ),
            'desc'        => __( 'Select a post type of your site', GT_TEXTDOMAIN ),
            'id'          => GT_SETTINGS . '_cpt',
            'type'        => 'post_search_text',
            'select_type' => 'radio',
            'onlyone'     => true,
        ) );
        $cmb->add_field( array(
            'name' => __( 'Open external link in a new window', GT_TEXTDOMAIN ),
            'id'   => GT_SETTINGS . '_target',
            'type' => 'checkbox',
        ) );
        $cmb->add_field( array(
            'name' => __( 'Mark this link as "No Follow"', GT_TEXTDOMAIN ),
            'desc' => __( 'To learn more about No-Follow links, check <a href="https://support.google.com/webmasters/answer/96569?hl=en">this article</a>', GT_TEXTDOMAIN ),
            'id'   => GT_SETTINGS . '_nofollow',
            'type' => 'checkbox',
        ) );
    }
    
    /**
     * Prompt a reminder to flush the pernalink
     *
     * @param string $object_id CMB Object ID.
     * @param string $cmb_id    CMB ID.
     * @param string $updated   Status.
     * @param array  $object    The CMB object.
     *
     * @return void
     */
    public function permalink_alert(
        $object_id,
        $cmb_id,
        $updated,
        $object
    )
    {
        
        if ( $cmb_id === GT_SETTINGS . '_options' ) {
            $notice = new WP_Admin_Notice( __( 'You must flush the permalink if you changed the slug, go on Settings->Permalink and press Save changes!', GT_TEXTDOMAIN ), 'updated' );
            $notice->output();
        }
    
    }

}
new Glossary_CMB();