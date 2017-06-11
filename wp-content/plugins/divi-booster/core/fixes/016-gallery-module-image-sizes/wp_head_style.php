<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>
<?php 
$margin = @floor((1080-$option['imagewidth']*$option['imagescount'])/($option['imagescount']-1)); 
?>

/* Set the image widths */
.et_pb_gallery_grid .et_pb_gallery_item,
.et_pb_gallery_grid .column_width,
.et_pb_gallery_grid .et_pb_gallery_image,
.et_pb_gallery_grid .et_pb_gallery_image.portrait img
{
    width: <?php echo htmlentities(@$option['imagewidth']); ?>px !important;
}
.et_pb_gallery_grid .et_pb_gallery_image img
{
    min-width: <?php echo htmlentities(@$option['imagewidth']); ?>px;
}

/* Set the image heights */
.et_pb_gallery_grid .et_pb_gallery_image,
.et_pb_gallery_grid .et_pb_gallery_image.landscape img
{
    height: <?php echo htmlentities(@$option['imageheight']); ?>px !important;
}
.et_pb_gallery_grid .et_pb_gallery_image img
{
    min-height: <?php echo htmlentities(@$option['imageheight']); ?>px;
}

/* Set the spacing between images */
.et_pb_gallery_grid .gutter_width { width: <?php echo intval($margin); ?>px !important; }
.et_pb_gallery_grid .et_pb_gallery_item { margin-bottom:<?php echo intval($margin); ?>px !important; }

<?php if (is_divi24()) { ?>
.et_pb_gallery_grid .et_pb_gallery_item { clear:none !important; }
.et_pb_gallery_grid .et_pb_gallery_item:nth-child(<?php echo htmlentities(intval(@$option['imagescount'])); ?>n) { margin-right:0 !important; }
.et_pb_gallery_grid .et_pb_gallery_item:nth-child(<?php echo htmlentities(intval(@$option['imagescount'])); ?>n+1) { clear:both !important; }
.et_pb_gallery_grid .et_pb_gallery_item { margin-right:<?php echo intval($margin)-1; ?>px !important; }
.et_pb_gallery_grid .et_pb_gallery_image img { min-height: 0 !important; }
.et_pb_gallery_grid .et_pb_gallery_image,
.et_pb_gallery_grid .et_pb_gallery_image.landscape img
{
    height: auto !important;
}
<?php } ?>