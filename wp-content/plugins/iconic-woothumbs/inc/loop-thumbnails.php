<?php
/**
 * Loop thumbnail slider images
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$mode = ($this->settings['navigation_thumbnails_position'] == "above" || $this->settings['navigation_thumbnails_position'] == "below") ? "horizontal" : "vertical";

?>

<?php if(!empty($images)) { ?>

    <?php do_action( 'iconic_woothumbs_before_thumbnails_wrap' ); ?>

    <div class="<?php echo $this->slug; ?>-thumbnails-wrap <?php echo $this->slug; ?>-thumbnails-wrap--<?php echo $this->settings['navigation_thumbnails_type']; ?> <?php echo $this->slug; ?>-thumbnails-wrap--<?php echo $mode; ?> iconic-woothumbs-thumbnails-wrap--hidden" style="height: 0;">

        <?php do_action( 'iconic_woothumbs_before_thumbnails' ); ?>

    	<div class="<?php echo $this->slug; ?>-thumbnails">

    	    <?php $image_count = count($images); ?>

    	    <?php if( $image_count > 1 ) { ?>

        	    <?php $i = 0; foreach($images as $image): ?>

        	        <?php $srcset = isset( $image['thumb']['retina'][0] ) ? sprintf('data-srcset="%s, %s 2x"', $image['thumb'][0], $image['thumb']['retina'][0]) : ""; ?>

            		<div class="<?php echo $this->slug; ?>-thumbnails__slide <?php if($i == 0) { ?><?php echo $this->slug; ?>-thumbnails__slide--active<?php } ?>" data-index="<?php echo $i; ?>">

            		    <?php do_action( 'iconic_woothumbs_before_thumbnail', $image ); ?>

            			<img class="<?php echo $this->slug; ?>-thumbnails__image" src="<?php echo $image['thumb'][0]; ?>" <?php echo $srcset; ?> title="<?php echo esc_attr( $image['title'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" width="<?php echo $image['thumb'][1]; ?>" height="<?php echo $image['thumb'][2]; ?>">

            			<?php do_action( 'iconic_woothumbs_after_thumbnail', $image ); ?>

            			<?php // echo $image['caption']; ?>

            		</div>

            	<?php $i++; endforeach; ?>

            	<?php

                // pad out thumbnails if there are less than the number
                // which are meant to be shown.

                if( $image_count < $this->settings['navigation_thumbnails_count'] ) {

                	$empty_count = $this->settings['navigation_thumbnails_count'] - $image_count;
                	$i = 0;

                	while( $i < $empty_count ) {

                    	echo "<div></div>";
                    	$i++;

                	}

            	}

            	?>

        	<?php } ?>

    	</div>

        <?php if( $this->settings['navigation_thumbnails_type'] == "sliding" && $this->settings['navigation_general_controls'] ) { ?>

    	    <a href="javascript: void(0);" class="<?php echo $this->slug; ?>-thumbnails__control <?php echo $this->slug; ?>-thumbnails__control--<?php echo ( $mode == "horizontal" ) ? "left" : "up"; ?>" data-direction="<?php echo ( is_rtl() && $mode == "horizontal" ) ? "next" : "prev"; ?>"><i class="iconic-woothumbs-icon iconic-woothumbs-icon-<?php echo ( $mode == "horizontal" ) ? "left" : "up"; ?>-open-mini"></i></a>
            <a href="javascript: void(0);" class="<?php echo $this->slug; ?>-thumbnails__control <?php echo $this->slug; ?>-thumbnails__control--<?php echo ( $mode == "horizontal" ) ? "right" : "down"; ?>" data-direction="<?php echo ( is_rtl() && $mode == "horizontal" ) ? "prev" : "next"; ?>"><i class="iconic-woothumbs-icon iconic-woothumbs-icon-<?php echo ( $mode == "horizontal" ) ? "right" : "down"; ?>-open-mini"></i></a>

        <?php } ?>

        <?php do_action( 'iconic_woothumbs_after_thumbnails' ); ?>

    </div>

    <?php do_action( 'iconic_woothumbs_after_thumbnails_wrap' ); ?>

<?php } ?>