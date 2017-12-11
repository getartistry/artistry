<?php
global $userMeta;
// Expected $csvCache, $maxSize
?>

<div class="wrap">
	<div id="icon-users" class="icon32 icon32-posts-page">
		<br />
	</div>
	<h2><?php _e( 'Export & Import', $userMeta->name ); ?></h2>   
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
		<div class="metabox-holder">
			<div id="um_admin_content">
                <?php echo $userMeta->proDemoImage( 'export-import.png' ); ?>                                          
            </div>

			<div id="um_admin_sidebar">                            
                <?php
                $panelArgs = [
                    'panel_class' => 'panel-default'
                ];
                
                if (empty($userMeta->isPro)) {
                    echo UserMeta\panel(__('Live Demo', $userMeta->name), $userMeta->boxLiveDemo(), $panelArgs);
                    echo UserMeta\panel(__('User Meta Pro', $userMeta->name), $userMeta->boxGetPro(), $panelArgs);
                }
                echo UserMeta\panel(__('Shortcodes', $userMeta->name), $userMeta->boxShortcodesDocs(), $panelArgs);
                ?>
            </div>
		</div>
	</div>
</div>
