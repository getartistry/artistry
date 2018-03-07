<style>

    /* Default Styles */

    .iconic-woothumbs-all-images-wrap {
    	float: <?php echo $this->settings['display_general_position']; ?>;
    	width: <?php echo $this->settings['display_general_width']; ?>%;
    }

    /* Icon Styles */

    .iconic-woothumbs-icon {
        color: <?php echo $this->settings['display_general_icon_colours']; ?>;
    }

    /* Bullet Styles */

    .iconic-woothumbs-all-images-wrap .slick-dots button,
    .iconic-woothumbs-zoom-bullets .slick-dots button {
        border-color: <?php echo $this->settings['display_general_icon_colours']; ?> !important;
    }

    .iconic-woothumbs-all-images-wrap .slick-dots .slick-active button,
    .iconic-woothumbs-zoom-bullets .slick-dots .slick-active button {
        background-color: <?php echo $this->settings['display_general_icon_colours']; ?> !important;
    }

    /* Thumbnails */

    <?php if( $this->settings['navigation_thumbnails_enable'] ) { ?>

        .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails-wrap,
        .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails-wrap {
            width: <?php echo $this->settings['navigation_thumbnails_width']; ?>%;
        }

        .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-images-wrap,
        .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-images-wrap {
            width: <?php echo 100-$this->settings['navigation_thumbnails_width']; ?>%;
        }

    <?php } else { ?>

        .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-images-wrap,
        .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-images-wrap {
            width: 100%;
        }

    <?php } ?>

    .iconic-woothumbs-thumbnails__control {
        color: <?php echo $this->settings['display_general_icon_colours']; ?>;
    }

    .iconic-woothumbs-all-images-wrap--thumbnails-above .iconic-woothumbs-thumbnails__control {
        bottom: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    .iconic-woothumbs-all-images-wrap--thumbnails-below .iconic-woothumbs-thumbnails__control {
        top: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails__control {
        right: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails__control {
        left: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    <?php $thumbnail_width = 100/(int)$this->settings['navigation_thumbnails_count']; ?>

    /* Stacked Thumbnails - Left & Right */

    .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails-wrap--stacked,
    .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails-wrap--stacked {
        margin: 0;
    }

        .iconic-woothumbs-thumbnails-wrap--stacked .iconic-woothumbs-thumbnails__slide {
            width: <?php echo $thumbnail_width; ?>%;
        }

        /* Stacked Thumbnails - Left */

        .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails-wrap--stacked .iconic-woothumbs-thumbnails__slide {
            padding: 0 <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px 0;
        }

        /* Stacked Thumbnails - Right */

        .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails-wrap--stacked .iconic-woothumbs-thumbnails__slide {
            padding: 0 0 <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
        }

    /* Stacked Thumbnails - Above & Below */

    <?php
    $thumbnail_gutter_left = floor($this->settings['navigation_thumbnails_spacing']/2);
    $thumbnail_gutter_right = ceil($this->settings['navigation_thumbnails_spacing']/2);
    ?>

    .iconic-woothumbs-all-images-wrap--thumbnails-above .iconic-woothumbs-thumbnails-wrap--stacked,
    .iconic-woothumbs-all-images-wrap--thumbnails-below .iconic-woothumbs-thumbnails-wrap--stacked {
        margin: 0 -<?php echo $thumbnail_gutter_left; ?>px 0 -<?php echo $thumbnail_gutter_right; ?>px;
    }

        /* Stacked Thumbnails - Above */

        .iconic-woothumbs-all-images-wrap--thumbnails-above .iconic-woothumbs-thumbnails-wrap--stacked .iconic-woothumbs-thumbnails__slide {
            padding: 0 <?php echo $thumbnail_gutter_left; ?>px <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px <?php echo $thumbnail_gutter_right; ?>px;
        }

        /* Stacked Thumbnails - Below */

        .iconic-woothumbs-all-images-wrap--thumbnails-below .iconic-woothumbs-thumbnails-wrap--stacked .iconic-woothumbs-thumbnails__slide {
            padding: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px <?php echo $thumbnail_gutter_left; ?>px 0 <?php echo $thumbnail_gutter_right; ?>px;
        }

    /* Sliding Thumbnails - Left & Right, Above & Below */

    .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails-wrap--sliding,
    .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails-wrap--sliding {
        margin: 0;
    }

    /* Sliding Thumbnails - Left & Right */

    .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails-wrap--sliding .slick-list,
    .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails-wrap--sliding .slick-list {
        margin-bottom: -<?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails-wrap--sliding img,
    .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails-wrap--sliding img {
        padding-bottom: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    /* Sliding Thumbnails - Left */

    .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails-wrap--sliding {
        padding-right: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    /* Sliding Thumbnails - Right */

    .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails-wrap--sliding {
        padding-left: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    /* Sliding Thumbnails - Above & Below */

    .iconic-woothumbs-thumbnails-wrap--horizontal.iconic-woothumbs-thumbnails-wrap--sliding .iconic-woothumbs-thumbnails__slide {
        width: <?php echo $thumbnail_width; ?>%;
    }

    .iconic-woothumbs-all-images-wrap--thumbnails-above .iconic-woothumbs-thumbnails-wrap--sliding .slick-list,
    .iconic-woothumbs-all-images-wrap--thumbnails-below .iconic-woothumbs-thumbnails-wrap--sliding .slick-list {
        margin-right: -<?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    .iconic-woothumbs-all-images-wrap--thumbnails-above .iconic-woothumbs-thumbnails-wrap--sliding img,
    .iconic-woothumbs-all-images-wrap--thumbnails-below .iconic-woothumbs-thumbnails-wrap--sliding img {
        padding-right: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    /* Sliding Thumbnails - Above */

    .iconic-woothumbs-all-images-wrap--thumbnails-above .iconic-woothumbs-thumbnails-wrap--sliding {
        padding-bottom: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    /* Sliding Thumbnails - Below */

    .iconic-woothumbs-all-images-wrap--thumbnails-below .iconic-woothumbs-thumbnails-wrap--sliding {
        padding-top: <?php echo $this->settings['navigation_thumbnails_spacing']; ?>px;
    }

    /* Zoom Styles */

    <?php if($this->settings['zoom_general_zoom_type'] == 'follow'):
    $borderRadius = ($this->settings['zoom_outside_follow_zoom_lens_width'] > $this->settings['zoom_outside_follow_zoom_lens_height']) ? $this->settings['zoom_outside_follow_zoom_lens_width'] : $this->settings['zoom_outside_follow_zoom_lens_height']; ?>
    .zm-viewer.shapecircular {
    	-webkit-border-radius: <?php echo $borderRadius; ?>px;
    	-moz-border-radius: <?php echo $borderRadius; ?>px;
    	border-radius: <?php echo $borderRadius; ?>px;
    }
    <?php endif; ?>

    .zm-handlerarea {
    	background: <?php echo $this->settings['zoom_outside_zoom_lens_colour']; ?>;
    	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $this->settings['zoom_outside_zoom_lens_opacity']*100; ?>)" !important;
    	filter: alpha(opacity=<?php echo $this->settings['zoom_outside_zoom_lens_opacity']*100; ?>) !important;
    	-moz-opacity: <?php echo $this->settings['zoom_outside_zoom_lens_opacity']; ?> !important;
    	-khtml-opacity: <?php echo $this->settings['zoom_outside_zoom_lens_opacity']; ?> !important;
    	opacity: <?php echo $this->settings['zoom_outside_zoom_lens_opacity']; ?> !important;
    }

    /* Media Queries */

    <?php if( $this->settings['responsive_general_breakpoint_enable'] ): ?>

        <?php $thumbnail_width = 100/(int)$this->settings['responsive_general_thumbnails_count']; ?>

        @media screen and (max-width: <?php echo $this->settings['responsive_general_breakpoint']; ?>px) {

        	.iconic-woothumbs-all-images-wrap {
        		float: <?php echo $this->settings['responsive_general_position']; ?>;
        		width: <?php echo $this->settings['responsive_general_width']; ?>%;
        	}

        	.iconic-woothumbs-hover-icons .iconic-woothumbs-icon {
                opacity: 1;
            }

                <?php if($this->settings['responsive_general_thumbnails_below']): ?>

                    .iconic-woothumbs-all-images-wrap--thumbnails-above .iconic-woothumbs-images-wrap,
                    .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-images-wrap,
                    .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-images-wrap {
                	    width: 100%;
            	    }

            	    .iconic-woothumbs-all-images-wrap--thumbnails-left .iconic-woothumbs-thumbnails-wrap,
            	    .iconic-woothumbs-all-images-wrap--thumbnails-right .iconic-woothumbs-thumbnails-wrap {
                	    width: 100%;
            	    }

        	    <?php endif; ?>

            .iconic-woothumbs-thumbnails-wrap--horizontal .iconic-woothumbs-thumbnails__slide {
                width: <?php echo $thumbnail_width; ?>%;
            }

        }

    <?php endif; ?>

</style>