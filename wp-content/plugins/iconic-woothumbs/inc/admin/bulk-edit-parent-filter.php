<?php
/**
 * Bulk edit page filter by parent ID form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<form method="get" accept-charset="utf-8" style="margin-bottom: 20px;">

    <?php $iconic_woothumbs_class->output_bulk_parameters( array('parent', 'paged') ); ?>

    <label for=""><?php _e('Filter by parent product ID:', 'jckwt'); ?></label>

    <input type="number" value="<?php echo isset( $_GET['parent'] ) ? $_GET['parent'] : ""; ?>" name="parent" class="small-text">

    <input type="submit" value="<?php _e('Filter', 'jckwt'); ?>" class="button button-secondary">

</form>