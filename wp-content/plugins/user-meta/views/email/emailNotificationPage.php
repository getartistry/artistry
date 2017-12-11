<?php
global $userMeta;
// Expected: $data, $roles
?>

<div class="wrap">
	<h2><?php _e( 'E-mail Notification', $userMeta->name ); ?></h2>   
    <?php do_action( 'um_admin_notice' ); ?>
    <div id="dashboard-widgets-wrap">
		<div class="metabox-holder">
			<div id="um_admin_content">
                <?php echo $userMeta->proDemoImage( 'email-notification.png' ); ?>
            </div>

			<div id="um_admin_sidebar">                            
                <?php
                $variable = null;
                $variable .= "<strong>" . __('Site Placeholder', $userMeta->name) . "</strong><p>";
                $variable .= "%site_title%, ";
                $variable .= "%site_url%, ";
                $variable .= "%login_url%, ";
                $variable .= "%logout_url%, ";
                $variable .= "%activation_url%, ";
                $variable .= "%email_verification_url%";
                $variable .= "</p>";
                
                $variable .= "<strong>" . __('User Placeholder', $userMeta->name) . "</strong><p>";
                $variable .= "%ID%, ";
                $variable .= "%user_login%, ";
                $variable .= "%user_email%, ";
                $variable .= "%password%, ";
                $variable .= "%display_name%, ";
                $variable .= "%first_name%, ";
                $variable .= "%last_name%";
                $variable .= "</p>";
                
                $variable .= "<strong>" . __('Custom Field', $userMeta->name) . "</strong><p>";
                $variable .= "%your_custom_user_meta_key%</p>";
                
                $variable .= "<p><em>(" . __("Placeholder will be replaced with the relevant value when used in email subject or body.", $userMeta->name) . ")</em></p>";
                
                $panelArgs = [
                    'panel_class' => 'panel-default'
                ];
                
                if (empty($userMeta->isPro)) {
                    echo UserMeta\panel(__('Live Demo', $userMeta->name), $userMeta->boxLiveDemo(), $panelArgs);
                    echo UserMeta\panel(__('User Meta Pro', $userMeta->name), $userMeta->boxGetPro(), $panelArgs);
                }
                echo UserMeta\panel(__('Placeholder', $userMeta->name), $variable, $panelArgs);
                echo UserMeta\panel(__('Shortcodes', $userMeta->name), $userMeta->boxShortcodesDocs(), $panelArgs);
                ?>
            </div>
		</div>
	</div>
</div>
