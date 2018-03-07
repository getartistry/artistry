<?php
/**
 * Bulk edit page pagination
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if($variations->max_num_pages > 1) { ?>

    <form method="get">

        <?php $iconic_woothumbs_class->output_bulk_parameters( array('paged') ); ?>

        <div class="tablenav" style="margin: 20px 0;">
            <div class="tablenav-pages" style="float: none;">
                <span class="pagination-links">
                    <a class="first-page" title="Go to the first page" href="<?php echo $iconic_woothumbs_class->get_pagination_link( 1 ); ?>">«</a>

                    <?php if ($paged > 1) { ?>
                        <a class="prev-page" title="Go to the previous page" href="<?php echo $iconic_woothumbs_class->get_pagination_link( $paged-1 ); ?>">‹</a>
                    <?php } ?>

                    <span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Select Page</label><input class="current-page" id="current-page-selector" title="Current page" type="text" name="paged" value="<?php echo $paged; ?>" size="1"> of <span class="total-pages"><?php echo $variations->max_num_pages; ?></span></span>

                    <?php if($paged < $variations->max_num_pages) { ?>
                        <a class="next-page" title="Go to the next page" href="<?php echo $iconic_woothumbs_class->get_pagination_link( $paged+1 ); ?>">›</a>
                    <?php } ?>

                    <a class="last-page" title="Go to the last page" href="<?php echo $iconic_woothumbs_class->get_pagination_link( $variations->max_num_pages ); ?>>">»</a>
                </span>
            </div>
        </div>
    </form>

<?php } ?>