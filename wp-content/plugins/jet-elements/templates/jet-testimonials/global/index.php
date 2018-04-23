<?php
/**
 * Testimonials template
 */

$classes_list[] = 'jet-testimonials';
$classes = implode( ' ', $classes_list );
?>

<div class="<?php echo $classes; ?>">
	<?php $this->__get_global_looped_template( 'testimonials', 'item_list' ); ?>
</div>
