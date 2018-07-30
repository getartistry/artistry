<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Image_Separator_Widget extends Widget_Base
{
    protected $templateInstance;

    public function getTemplateInstance() {
        return $this->templateInstance = premium_Template_Tags::getInstance();
    }

    public function get_name() {
        return 'premium-addon-image-separator';
    }

    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Image Separator';
	}

    public function get_icon() {
        return 'pa-image-separator';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    // Adding the controls fields for the premium image separator
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        /* Start Content Section */
        $this->start_controls_section('premium_image_separator_general_settings',
                [
                    'label'         => esc_html__('Image Settings', 'premium-addons-for-elementor')
                    ]
                );
        
        /*Separator Image*/ 
        $this->add_control('premium_image_separator_image',
                [
                    'label'         => esc_html__('Image', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::MEDIA,
                    'default'       => [
                        'url'	=> Utils::get_placeholder_image_src(),
                        ],
                    'description'   => esc_html__('Choose the separator image', 'premium-addons-for-elementor' ),
                    'label_block'   => true
                    ]
                );

        /*Separator Image Size*/
        $this->add_responsive_control('premium_image_separator_image_size',
                [
                    'label'         => esc_html__('Image Size', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', "em"],
                    'default'       => [
                        'unit'  => 'px',
                        'size'  => 200,
                    ],
                    'range'         => [
                        'px'    => [
                            'min'   => 1, 
                            'max'   => 800,
                        ],
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-separator-container img' => 'width: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        /*Separator Image Gutter*/
        $this->add_control('premium_image_separator_image_gutter',
                [
                    'label'         => esc_html__('Image Gutter (%)', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => -50,
                    'description'   => esc_html__('-50% is default. Increase to push the image outside or decrease to pull the image inside.','premium-addons-for-elementor'),
                    'label_block'   => true,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-separator-container' => 'transform: translateY( {{VALUE}}% );'
                        
                        ]
                    ]
                );
        
        $this->add_control('premium_image_separator_image_align', 
            [
                'label'         => esc_html__('Image Alignment', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'  => [
                        'title'     => esc_html__('Left', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'  => [
                        'title'     => esc_html__('Center', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'  => [
                        'title'     => esc_html__('Right', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-separator-container'   => 'text-align: {{VALUE}};',
                ]
            ]
            );
        
        
        /*Add Link Switcher*/
        $this->add_control('premium_image_separator_link_switcher', 
                [
                    'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'description'   => esc_html__('Add a custom link or select an existing page link','premium-addons-for-elementor'),
                ]
                );
        
        $this->add_control('premium_image_separator_link_type', 
                [
                    'label'         => esc_html__('Link/URL', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'url'   => esc_html__('URL', 'premium-addons-for-elementor'),
                        'link'  => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'url',
                    'condition'     => [
                       'premium_image_separator_link_switcher'  => 'yes',
                    ],
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_image_separator_existing_page', 
                [
                    'label'         => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT2,
                    'options'       => $this->getTemplateInstance()->get_all_post(),
                    'condition'     => [
                       'premium_image_separator_link_switcher'  => 'yes',
                        'premium_image_separator_link_type'     => 'link',
                    ],
                    'multiple'      => false,
                    'label_block'   => true,
                ]
                );
        
         /*Link Below Image*/
        $this->add_control('premium_image_separator_image_link',
                [
                    'label'         => esc_html__('URL', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'condition'     => [
                        'premium_image_separator_link_switcher' => 'yes',
                        'premium_image_separator_link_type'     => 'url',
                    ],
                    'label_block'   => true
                ]
                );
        
        
        /*Link Below Image*/
        $this->add_control('premium_image_separator_image_link_text',
                [
                    'label'         => esc_html__('Image Hovering Title', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'condition'     => [
                        'premium_image_separator_link_switcher' => 'yes',
                    ],
                    'label_block'   => true
                ]
                );
        
        /*Link Target*/ 
        $this->add_control('premium_image_separator_link_target',
                [
                    'label'         => esc_html__('Link Target', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'description'   => esc_html__( ' Where would you like the link be opened?', 'premium-addons-for-elementor' ),
                    'options'       => [
                        'blank'  => esc_html('Blank'),
                        'parent' => esc_html('Parent'),
                        'self'   => esc_html('Self'),
                        'top'    => esc_html('Top'),
                        ],
                    'default'       => esc_html__('blank','premium-addons-for-elementor'),
                    'condition'     => [
                       'premium_image_separator_link_switcher'  => 'yes',
                    ],
                    'label_block'   => true,
                    ]
                );
       
        /*End Price Settings Section*/
        $this->end_controls_section();
       
    }

    protected function render($instance = [])
    {
        // get our input from the widget settings.
        $settings = $this->get_settings();
        
        $link_type = $settings['premium_image_separator_link_type'];
        
        if ($link_type == 'url') {
            $link_url = $settings['premium_image_separator_image_link'];
        } elseif ($link_type == 'link') {
            $link_url = get_permalink($settings['premium_image_separator_existing_page']);
        }
?>

<div class="premium-image-separator-container">
    
    <img alt="image separator" class="img-responsive" src="<?php echo $settings['premium_image_separator_image']['url']; ?>">
            <?php if (  $settings['premium_image_separator_link_switcher'] == 'yes' ) : ?>
                <a class="premium-image-separator-link" href="<?php echo $link_url; ?>" target="_<?php echo $settings['premium_image_separator_link_target']; ?>" title="<?php echo $settings['premium_image_separator_image_link_text']; ?>">
                </a>
            <?php endif;?>
</div>
    <?php
    }
}
Plugin::instance()->widgets_manager->register_widget_type(new Premium_Image_Separator_Widget());