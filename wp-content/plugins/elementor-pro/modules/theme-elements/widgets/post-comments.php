<?php
namespace ElementorPro\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use ElementorPro\Modules\QueryControl\Controls\Query;
use ElementorPro\Modules\ThemeElements\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Comments extends Base {

	public function get_name() {
		return 'post-comments';
	}

	public function get_title() {
		return __( 'Post Comments', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Comments', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'_skin',
			[
				'type' => Controls_Manager::HIDDEN,
			]
		);

		$this->add_control(
			'skin_temp',
			[
				'label' => __( 'Skin', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Theme Comments', 'elementor-pro' ),
				],
                'description' => __( 'The Theme Comments skin uses the currently active theme comments design and layout to display the comment form and comments.', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => __( 'Source', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::SOURCE_TYPE_CURRENT_POST => __( 'Current Post', 'elementor-pro' ),
					Module::SOURCE_TYPE_CUSTOM => __( 'Custom', 'elementor-pro' ),
				],
				'default' => Module::SOURCE_TYPE_CURRENT_POST,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'source_custom',
			[
				'label' => __( 'Search & Select', 'elementor-pro' ),
				'type' => Query::CONTROL_ID,
				'label_block' => true,
				'multiple' => false,
				'filter_type' => 'by_id',
				'condition' => [
					'source_type' => Module::SOURCE_TYPE_CUSTOM,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		if ( Module::SOURCE_TYPE_CURRENT_POST === $settings['source_type'] ) {
			$post_id = get_the_ID();
		} else {
			$post_id = (int) $settings['source_custom'];
		}

		Plugin::$instance->db->switch_to_post( $post_id );

		if ( ! comments_open() && ( Plugin::$instance->preview->is_preview_mode() || Plugin::$instance->editor->is_edit_mode() ) ) :
			?>
			<div class="elementor-alert elementor-alert-danger" role="alert">
				<span class="elementor-alert-title">
					<?php esc_html_e( 'Comments Are Closed!', 'elementor-pro' ); ?>
				</span>
                <span class="elementor-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'elementor-pro' ); ?>
				</span>
			</div>
		<?php
		else :
			comments_template();
		endif;

		Plugin::$instance->db->restore_current_post();
	}
}
