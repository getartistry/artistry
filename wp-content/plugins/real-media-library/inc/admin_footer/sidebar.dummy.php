<?php
/**
 * This file creates a dummy for the sidebar
 * shown in the media library. Javascript handles
 * it, to append it to the components.
 * 
 * @author MatthiasWeb
 * @package real-media-library
 * @since 1.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$folders = RML_Structure::getInstance();
$folderActive = isset($_REQUEST['rml_folder']) ? $_REQUEST['rml_folder'] : "";
$folderTree = $folders->treeHTML($folderActive);
?>

<div class="rml-container rml-dummy"
    data-lang-delete-failed="<?php _e('In this folder are sub directories, please delete them first!', RML_TD); ?>"
    data-lang-delete-root="<?php _e('Do not delete root. :(', RML_TD); ?>"
    data-lang-delete-confirm="<?php _e('Would you like to delete this folder? Note: All files in this folder will be deleted.', RML_TD); ?>"
    data-lang-rename-root="<?php _e('Do not rename root. :(', RML_TD); ?>"
    data-lang-rename-prompt="<?php _e('Say me the new name: ', RML_TD); ?>">
    <div class="wrap ready-mode">
        <h1><?php _e('Folders', RML_TD); ?> <a class="page-title-action" id="rml-add-new-folder"><?php _e('Add New'); ?></a></h1>
        <div class="wp-filter">
            <div class="rml-info">
                <span><?php echo RML_Structure::getInstance()->getCntAttachments(); ?></span> <?php _e('Files', RML_TD); ?><br />
                <span><?php echo count(RML_Structure::getInstance()->getParsed()); ?></span> <?php _e('Folders', RML_TD); ?>
            </div>
        	<div class="filter-items">
        		<div class="view-switch">
                    <a href="javascript:window.location.reload();" class="view-switch-refresh"><i class="fa fa-refresh"></i></a>
                    <a href="#" class="view-switch-rename" id="rml-folder-rename"><i class="fa fa-pencil"></i></a>
                    <a href="#" class="view-switch-delete" id="rml-folder-delete"><i class="fa fa-trash-o"></i></a>
                    <a href="#" class="view-switch-sort"><i class="fa fa-sort"></i></a>
        		</div>
        	</div>
        	<div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div class="sort-notice"><?php _e('Change the hierarchical order.'); ?></div>
        <button style="display:none;" class="abort-sort button-secondary"><?php _e('Cancel'); ?></button>
        <button style="display:none;" class="save-sort button-primary"><?php _e('Save'); ?></button>
        <div class="clear" style="margin-top:5px;"></div>
        
        <div class="rml-uploading"></div>
        
        <div class="list">
            <a id="rml-list-li-all-files" href="<?php echo RML_Structure::getInstance()->treeHref("", ""); ?>"
                <?php echo RML_Structure::getInstance()->treeActive($folderActive, ""); ?>
                data-id="">
                    <i class="fa fa-files-o"></i> <?php _e('All Files', RML_TD); ?>
                    <span><?php echo RML_Structure::getInstance()->getCntAttachments(); ?></span>
            </a>
            
            <hr />
            
            <div class="list rml-root-list">
                <?php echo $folderTree; ?>
            </div>
        </div>
    </div>
</div>