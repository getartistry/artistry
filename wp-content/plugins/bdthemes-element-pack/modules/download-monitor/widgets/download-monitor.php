<?php
namespace ElementPack\Modules\DownloadMonitor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class DownloadMonitor extends Widget_Base {

	public function get_name() {
		return 'bdt-download-monitor';
	}

	public function get_title() {
		return esc_html__( 'Download Monitor', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-file-download';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function download_file_list() {
		$output       = '';
		$search_query = ( ! empty( $_POST['dlm_search'] ) ? $_POST['dlm_search'] : '' );
		$limit        = 10;
		$page         = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
		$filters      = array( 'post_status' => 'publish' );
        if ( ! empty( $search_query ) ) { $filters['s'] = $search_query; }
        $d_num_rows = download_monitor()->service( 'download_repository' )->num_rows( $filters );
        $downloads  = download_monitor()->service( 'download_repository' )->retrieve( $filters, $limit, ( ( $page - 1 ) * $limit ) );

        foreach ( $downloads as $download ) {
        	$output[absint( $download->get_id() )] = $download->get_title() .' ('. $download->get_version()->get_filename() . ')';
        }

        return $output;
    }

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_download_monitor',
			[
				'label' => esc_html__( 'Content', 'bdthemes-element-pack' ),
			]
		);


		$file_list = $this->download_file_list();

		$this->add_control(
			'file_id',
			[
				'label'     => esc_html__( 'Select File', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $file_list,
			]
		);


		$this->add_control(
			'file_type_show',
			[
				'label'     => esc_html__( 'Show File Type', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'file_id!' => '',
				],
			]
		);

		$this->add_control(
			'file_size_show',
			[
				'label'     => esc_html__( 'Show File Size', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'file_id!' => '',
				],
			]
		);

		$this->add_control(
			'download_count_show',
			[
				'label'     => esc_html__( 'Show Download Count', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'file_id!' => '',
				],
			]
		);



		$this->end_controls_section();


		$this->start_controls_section(
			'section_content_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'alt_title',
			[
				'label' => esc_html__( 'Alternative Title', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => [
					'url'         => '#',
					'is_external' => '',
				],
			]
		);

		$this->add_responsive_control(
			'align',
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
			'icon',
			[
				'label'       => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 8,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-download-monitor-button .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-download-monitor-button .bdt-button-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();




		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_footer_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.bdt-download-monitor-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background_color',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} a.bdt-download-monitor-button',
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} a.bdt-download-monitor-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} a.bdt-download-monitor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.bdt-download-monitor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.bdt-download-monitor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Title Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.bdt-download-monitor-button .bdt-dm-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_meta_typography',
				'label' => esc_html__( 'Meta Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.bdt-download-monitor-button .bdt-dm-meta > *',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.bdt-download-monitor-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background_hover_color',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} a.bdt-download-monitor-button:hover',
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} a.bdt-download-monitor-button:hover',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.bdt-download-monitor-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();




	}

	public function render() {
		$settings = $this->get_settings();
		//echo do_shortcode( $this->get_shortcode() );

		$external = ($settings['link']['is_external']) ? "_blank" : "_self";
		$link_url = empty( $settings['link']['url'] ) ? '#' : $settings['link']['url'];
		$animation = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';

		try {
			$download = download_monitor()->service( 'download_repository' )->retrieve_single( $settings['file_id'] );
		} catch ( \Exception $exception ) {
			$exception->getMessage();
			return;
		}


		//print_r($download);

		if (isset($download)) {
			?>
            <a class="bdt-download-monitor-button elementor-button elementor-size-sm<?php echo esc_html($animation); ?>" href="<?php echo $download->the_download_link(); ?>" target="<?php echo esc_attr($external); ?>">

				<div class="bdt-dm-description">
	            	<div class="bdt-dm-title">
						<?php if ($settings['alt_title']) {
							echo esc_html( $settings['alt_title'] );
						} else {
							echo esc_html($download->get_title());
						} ?>
	            	</div>

					<div class="bdt-dm-meta">
		            	<?php if ('yes' === $settings['file_type_show']) : ?>
		            	<div class="bdt-dm-file">
		            		<?php echo esc_html($download->get_version()->get_filetype()); ?>
		            		
		            	</div>
		            	<?php endif; ?>
		            	
		            	<?php if ('yes' === $settings['file_size_show']) : ?>
		            	<div class="bdt-dm-size">
		            		<?php echo esc_html($download->get_version()->get_filesize_formatted()); ?>
		            	</div>
		            	<?php endif; ?>

		            	<?php if ('yes' === $settings['download_count_show']) : ?>
		            	<div class="bdt-dm-count">
		            		<?php esc_html_e('Downloaded', 'bdthemes-element-pack'); ?> <?php echo esc_html($download->get_download_count()); ?>
		            	</div>
		            	<?php endif; ?>
					</div>
				</div>


            	
            	<?php if ($settings['icon']) : ?>
					<span class="bdt-button-icon-align-<?php echo esc_html($settings['icon_align']); ?>">
						<i class="<?php echo esc_html($settings['icon']); ?>"></i>
					</span>
				<?php endif; ?>

            </a>
			<?php
		}
	}

}