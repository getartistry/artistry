<?php
/**
 * Collector main template
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/templates/popup
 */
?>
<div id="collect_container">

	<?php include($template);?>

	   <div class="fields-wrapper">

	        <?php Wsi_Collector::printSubjectField();?>


	        <?php Wsi_Collector::printMessageField();?>

	   </div>


	<button type="submit" id="submit-button"><?php _e('Send', 'wsi');?></button>
</div><!--collect_container-->
