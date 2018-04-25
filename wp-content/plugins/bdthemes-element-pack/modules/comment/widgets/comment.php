<?php
namespace ElementPack\Modules\Comment\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Comment extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-comment';
	}

	public function get_title() {
		return esc_html__( 'Comment', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);


		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Comment Type', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => 
					[

						''         => esc_html__( 'Select', 'bdthemes-element-pack' ),
						'disqus'   => esc_html__( 'Disqus', 'bdthemes-element-pack' ),
						'facebook' => esc_html__( 'Facebook', 'bdthemes-element-pack' ),
					],
			]
		);


		$this->add_control(
			'comments_number',
			[
				'label'       => __( 'Comment Count', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 5,
				'max'         => 100,
				'default'     => '10',
				'description' => __( 'Minimum number of comments: 5', 'bdthemes-element-pack' ),
				'condition' => [
					'layout' => 'facebook',
				]
			]
		);

		$this->add_control(
			'order_by',
			[
				'label'   => __( 'Order By', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'social',
				'options' => [
					'social'       => __( 'Social', 'bdthemes-element-pack' ),
					'reverse_time' => __( 'Reverse Time', 'bdthemes-element-pack' ),
					'time'         => __( 'Time', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'layout' => 'facebook',
				]
			]
		);

		$this->end_controls_section();
	}


	public function render() {
		$settings  = $this->get_settings();
		$id        = $this->get_id();
		$permalink = get_the_permalink();
		$options   = get_option( 'element_pack_api_settings' );
		$user_name = (!empty($options['disqus_user_name'])) ? $options['disqus_user_name'] : 'bdthemes';
		$app_id    = (!empty($options['facebook_app_id'])) ? $options['facebook_app_id'] : '461738690569028';
		
		?>
		
		<div class="bdt-comment-container">
			<?php if ('disqus' === $settings['layout']) : ?>
				<div id="disqus_thread" style="min-height: 1px;"></div>
				<script>

					jQuery(document).ready(function($) {
					    'use strict';
					
						var disqus_config = function () {
						this.page.url = '<?php echo esc_attr( $permalink ); ?>';  // Replace PAGE_URL with your page's canonical URL variable
						this.page.identifier = '<?php echo esc_attr( $id ); ?>'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
						};
						
						(function() { // DON'T EDIT BELOW THIS LINE
						var d = document, s = d.createElement('script');
						s.src = '//<?php echo esc_attr( $user_name ); ?>.disqus.com/embed.js';
						s.setAttribute('data-timestamp', +new Date());
						(d.head || d.body).appendChild(s);
						})();
					});
				</script>
				<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

			<?php elseif('facebook' === $settings['layout']) : ?>
				<?php 
					$attributes = [
						'class'         => 'elementor-facebook-widget fb-comments',
						'data-href'     => $permalink,
						'data-numposts' => $settings['comments_number'],
						'data-order-by' => $settings['order_by'],
						'style'         => 'min-height: 1px',
					];

					$this->add_render_attribute( 'fb_comment', $attributes );
				?>
				<div class="fb-comments" <?php echo $this->get_render_attribute_string( 'fb_comment' ); ?>></div>

				<div id="fb-root"></div>
				<script>
					jQuery(document).ready(function($) {
					    'use strict';
						(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=<?php echo esc_attr( $app_id ); ?>&autoLogAppEvents=1';
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					});
				</script>
			<?php endif; ?>
		</div>
		<?php
	}

}
