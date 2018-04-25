<?php
namespace ElementPack\Modules\Search\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Search extends Widget_Base {

	public function get_name() {
		return 'bdt-search';
	}

	public function get_title() {
		return esc_html__( 'Search', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_search_layout',
			[
				'label' => esc_html__( 'Search Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'skin',
			[
				'label'   => esc_html__( 'Skin', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => esc_html__( 'Default', 'bdthemes-element-pack' ),
					'dropbar'  => esc_html__( 'Dropbar', 'bdthemes-element-pack' ),
					'dropdown' => esc_html__( 'Dropdown', 'bdthemes-element-pack' ),
					'modal'    => esc_html__( 'Modal', 'bdthemes-element-pack' ),
				],
				'prefix_class'       => 'elementor-search-form-skin-',
				'render_type'        => 'template',
				//'frontend_available' => true,
			]
		);


		$this->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'default' => esc_html__( 'Search', 'bdthemes-element-pack' ) . '...',
			]
		);


		$this->add_control(
			'search_icon',
			[
				'label'   => esc_html__( 'Search Icon', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'search_icon_flip',
			[
				'label'   => esc_html__( 'Icon Flip', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'condition' => ['search_icon' => 'yes'],
			]
		);

		

		$this->add_responsive_control(
			'search_align',
			[
				'label'   => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor-align%s-',
			]
		);


		$this->add_control(
			'dropbar_position',
			[
				'label'   => esc_html__( 'Dropbar Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'bottom-left'    => esc_html('Bottom Left', 'bdthemes-element-pack'),
					'bottom-center'  => esc_html('Bottom Center', 'bdthemes-element-pack'),
					'bottom-right'   => esc_html('Bottom Right', 'bdthemes-element-pack'),
					'bottom-justify' => esc_html('Bottom Justify', 'bdthemes-element-pack'),
					'top-left'       => esc_html('Top Left', 'bdthemes-element-pack'),
					'top-center'     => esc_html('Top Center', 'bdthemes-element-pack'),
					'top-right'      => esc_html('Top Right', 'bdthemes-element-pack'),
					'top-justify'    => esc_html('Top Justify', 'bdthemes-element-pack'),
					'left-top'       => esc_html('Left Top', 'bdthemes-element-pack'),
					'left-center'    => esc_html('Left Center', 'bdthemes-element-pack'),
					'left-bottom'    => esc_html('Left Bottom', 'bdthemes-element-pack'),
					'right-top'      => esc_html('Right Top', 'bdthemes-element-pack'),
					'right-center'   => esc_html('Right Center', 'bdthemes-element-pack'),
					'right-bottom'   => esc_html('Right Bottom', 'bdthemes-element-pack'),
				],
				'condition' => [
					'skin' => ['dropbar', 'dropdown']
				],
			]
		);

		$this->add_control(
			'dropbar_offset',
			[
				'label' => esc_html__( 'Dropbar Offset', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 0,
				],
				'condition' => [
					'skin' => ['dropbar', 'dropdown']
				],
			]
		);

		$this->add_responsive_control(
			'search_width',
			[
				'label' => esc_html__( 'Search Width', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-search-container .bdt-search-default, 
					 {{WRAPPER}} .bdt-search-container .bdt-navbar-dropdown, 
					 {{WRAPPER}} .bdt-search-container .bdt-drop' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin!' => ['modal']
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_search_layout_style',
			[
				'label' => esc_html__( 'Search Container', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'search_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search-container .bdt-search-toggle' => 'color: {{VALUE}};',
				],
				'condition' => [
					'skin!' => 'default',
				],
			]
		);

		$this->add_control(
			'search_container_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search-container .bdt-search:not(.bdt-search-navbar), 
					 {{WRAPPER}} .bdt-search-container .bdt-navbar-dropdown,
					 {{WRAPPER}} .bdt-search-container .bdt-drop' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-search-container .bdt-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_container_radius',
			[
				'label'      => esc_html__( 'Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-search-container .bdt-search:not(.bdt-search-navbar), 
					 {{WRAPPER}} .bdt-search-container .bdt-navbar-dropdown,
					 {{WRAPPER}} .bdt-search-container .bdt-drop' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_container_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-search-container .bdt-search:not(.bdt-search-navbar), 
							   {{WRAPPER}} .bdt-search-container .bdt-navbar-dropdown,
					           {{WRAPPER}} .bdt-search-container .bdt-drop',
			]
		);

		$this->end_controls_section();



		$this->start_controls_section(
			'section_search_style',
			[
				'label' => esc_html__( 'Input', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);



		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} .bdt-search .bdt-search-input',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-search .bdt-search-input,
					 {{WRAPPER}} .bdt-search-container .bdt-drop .bdt-icon,
					 {{WRAPPER}} .bdt-search-container .bdt-search-default .bdt-icon,
					 {{WRAPPER}} .bdt-search-container .bdt-navbar-dropdown .bdt-icon' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'input_background_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search-container .bdt-search .bdt-search-input' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'skin!' => 'modal',
				],
			]
		);
		
		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search .bdt-search-input::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search .bdt-search-input' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-search .bdt-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-search .bdt-search-input',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
				'condition' => [
					'skin!' => 'modal',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => esc_html__( 'Focus', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'input_text_color_focus',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search .bdt-search-input:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color_focus',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search .bdt-search-input:focus' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'skin!' => 'modal',
				],
			]
		);

		$this->add_control(
			'input_border_color_focus',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search .bdt-search-input:focus' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow_focus',
				'selector' => '{{WRAPPER}} .bdt-search .bdt-search-input:focus',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
				'condition' => [
					'skin!' => 'modal',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Size', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .bdt-search .bdt-search-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-search .bdt-search-input' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);



		$this->end_controls_section();
	}

	public function render() {
		$settings    = $this->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );
		$id          = $this->get_id();


		$toggle            = ['class' => 'bdt-search-icon bdt-padding-remove-horizontal'];
		


		?>
		<div class="bdt-search-container">
			<?php $this->search_form(); ?>
		</div>

		<?php
		
	}


	public function search_form() {
		$settings    = $this->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );
		$id          = $this->get_id();



		$style = $settings['skin'];

		$attrs['class']    = array_merge(['bdt-search'], isset($attrs['class']) ? (array) $attrs['class'] : []);
		$search            = [];
		$search['class']   = [];
		$search['class'][] = 'bdt-search-input';


		$this->add_render_attribute(
			'input', [
				'placeholder' => $settings['placeholder'],
				'class' => 'bdt-search-input',
				'type' => 'search',
				'name' => 's',
				'title' => esc_html__( 'Search', 'bdthemes-element-pack' ),
				'value' => get_search_query(),
			]
		);


		?>
		
		<?php if ('default' === $settings['skin']) : ?>
			<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" class="bdt-search bdt-search-default">
				<div class="bdt-position-relative">
					<?php $this->search_icon(); ?>

					<input <?php echo $this->get_render_attribute_string('input'); ?>>
				</div>
			</form>

		<?php elseif ('dropbar' === $settings['skin']) : 
			
			$drop['bdt-drop'] = json_encode(array_filter([
			    'mode'           => 'click',
			    'boundary'       => false,
			    'pos'            => ($settings['dropbar_position']) ? $settings['dropbar_position'] : 'left-center',
			    'flip'           => 'x',
			    'offset'         => $settings['dropbar_offset']['size'],
			]));

			?>

			<a class="bdt-search-toggle" bdt-search-icon href="#"></a>
	        <div class="bdt-drop" <?php echo \element_pack_helper::attrs($drop) ?>>
	            <form class="bdt-search bdt-search-navbar bdt-width-1-1" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
	            	<div class="bdt-position-relative">
		                <?php $this->search_icon(); ?>
		                <input <?php echo $this->get_render_attribute_string('input'); ?> autofocus>
		            </div>
	            </form>
	        </div>

	    <?php elseif ('dropdown' === $settings['skin']) : 

	    	$dropdown['bdt-drop'] = json_encode(array_filter([
	    	    'mode'           => 'click',
	    	    'boundary'       => false,
	    	    'pos'            => ($settings['dropbar_position']) ? $settings['dropbar_position'] : 'bottom-right',
	    	    'flip'           => 'x',
	    	    'offset'         => $settings['dropbar_offset']['size'],
	    	]));

	    	?>

			<a class="bdt-search-toggle" href="#" bdt-search-icon></a>
            <div class="bdt-navbar-dropdown" <?php echo \element_pack_helper::attrs($dropdown) ?>>

                <div class="bdt-grid-small bdt-flex-middle" bdt-grid>
                    <div class="bdt-width-expand">
                        <form class="bdt-search bdt-search-navbar bdt-width-1-1" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
                        	<div class="bdt-position-relative">
	                        	<?php $this->search_icon(); ?>
	                            <input <?php echo $this->get_render_attribute_string('input'); ?> autofocus>
	                        </div>
                        </form>
                    </div>
                    <div class="bdt-width-auto">
                        <a class="bdt-navbar-dropdown-close" href="#" bdt-close></a>
                    </div>
                </div>

            </div>

        <?php elseif ('modal' === $settings['skin']) : ?>
			
			<a class="bdt-search-toggle" href="#modal-search-<?php echo esc_attr($id); ?>" bdt-search-icon bdt-toggle></a>

			<div id="modal-search-<?php echo esc_attr($id); ?>" class="bdt-modal-full bdt-modal" bdt-modal>
			    <div class="bdt-modal-dialog bdt-flex bdt-flex-center bdt-flex-middle" bdt-height-viewport>
			        <button class="bdt-modal-close-full" type="button" bdt-close></button>
			        <form class="bdt-search bdt-search-large" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
						<div class="bdt-position-relative">	
							<?php $this->add_render_attribute('input', ['class' => 'bdt-text-center']); ?>
			            	<?php $this->search_icon(); ?>
			                <input <?php echo $this->get_render_attribute_string('input'); ?> autofocus>
			            </div>
			        </form>
			    </div>
			</div>


		<?php endif; ?>


		<?php
	}


	private function search_icon() {
		$settings    = $this->get_settings();
		$icon_class = ('yes' === $settings['search_icon_flip']) ? 'bdt-search-icon-flip' : '';

		if ( 'yes' === $settings['search_icon'] ) : ?>
			<span class="<?php echo esc_attr($icon_class); ?>" bdt-search-icon></span>
		<?php endif;
	}

}