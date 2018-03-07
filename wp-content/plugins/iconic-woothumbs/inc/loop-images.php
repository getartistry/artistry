<?php
/**
 * Loop main slider images
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$product_settings = (array) get_post_meta($post->ID, '_iconic_woothumbs', true);
$video_url = isset( $product_settings['video_url'] ) && $product_settings['video_url'] != "" ? $product_settings['video_url'] : false;

?>

<?php if(!empty($images)) { ?>

    <?php do_action( 'iconic_woothumbs_before_images_wrap' ); ?>

    <div class="iconic-woothumbs-images-wrap">

        <?php do_action( 'iconic_woothumbs_before_images' ); ?>

    	<div class="iconic-woothumbs-images <?php if( $this->settings['fullscreen_general_click_anywhere'] && $this->settings['fullscreen_general_enable'] ) echo "iconic-woothumbs-images--click-anywhere"; ?>">

        	<?php $i = 0; foreach($images as $image): ?>

        		<div class="iconic-woothumbs-images__slide <?php if($i == 0) echo "iconic-woothumbs-images__slide--active"; ?>" data-index="<?php echo $i; ?>">

        		    <?php do_action( 'iconic_woothumbs_before_image', $image ); ?>

                    <img <?php echo Iconic_WooThumbs::array_to_html_atts( Iconic_WooThumbs::get_image_loop_data( $image, $i ) ); ?>>

        			<?php do_action( 'iconic_woothumbs_after_image', $image ); ?>

        		</div>

        	<?php $i++; endforeach; ?>

    	</div>

    	<?php if( $this->settings['fullscreen_general_enable'] ) { ?>
    	    <a href="javascript: void(0);" class="iconic-woothumbs-fullscreen" data-iconic-woothumbs-tooltip="<?php _e('Fullscreen', 'iconic-woothumbs'); ?>"><i class="iconic-woothumbs-icon iconic-woothumbs-icon-fullscreen"></i></a>
    	<?php } ?>

    	<?php if( $video_url ) { ?>
    	    <a href="javascript: void(0);" class="iconic-woothumbs-play" data-iconic-woothumbs-tooltip="<?php _e('Play Video', 'iconic-woothumbs'); ?>"><i class="iconic-woothumbs-icon iconic-woothumbs-icon-play"></i></a>
    	<?php } ?>

    	<div class="iconic-woothumbs-loading-overlay">
            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                <path fill="<?php echo $this->settings['display_general_icon_colours']; ?>" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                    <animateTransform attributeType="xml"
                    attributeName="transform"
                    type="rotate"
                    from="0 25 25"
                    to="360 25 25"
                    dur="0.6s"
                    repeatCount="indefinite"/>
                </path>
            </svg>
        </div>

    	<?php do_action( 'iconic_woothumbs_after_images' ); ?>

    </div>

    <?php if( $video_url ) { ?>

        <?php $video_url = add_query_arg(array(
            'iconic-woothumbs' => 1
        ), $video_url); ?>

        <div id="iconic-woothumbs-video-template" style="display: none;">

            <div class="iconic-woothumbs-video-wrapper">
                <div class="iconic-woothumbs-responsive-video">
                    <?php echo wp_oembed_get( $video_url ); ?>
                </div>
            </div>

        </div>

    <?php } ?>

    <?php do_action( 'iconic_woothumbs_after_images_wrap' ); ?>

<?php } ?>