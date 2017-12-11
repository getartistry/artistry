<?php
namespace UserMeta;

/**
 * Download file by browser.
 *
 * @author Khaled Hossain
 * @since 1.2
 *       
 * @param string $fileName
 *            Downloaded filename
 * @param callable $callback_echo
 *            Callback function that should echo something
 */
function download($fileName, callable $callback_echo)
{
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename=' . $fileName);
    header('Content-Type: text/plain; charset=' . get_option('blog_charset'), true);
    
    call_user_func($callback_echo);
}

/**
 * Building bootstrap panel.
 *
 * @author Khaled Hossain
 * @since 1.2
 *       
 * @param string $title
 *            Panel title
 * @param string $body
 *            panel body
 * @param array $args
 *            Supported keys: panel_id, panel_class, collapsed, removable
 *            
 * @return string Html
 */
function panel($title, $body, array $args = [])
{
    extract($args);
    
    $panel_id = ! empty($panel_id) ? "id=\"$panel_id\"" : '';
    $panel_class = ! empty($panel_class) ? $panel_class : 'panel-info';
    $collapse_class = ! empty($collapsed) ? '' : ' in';
    
    if (! empty($removable)) {
        $title .= '<span class="um_trash" title="Remove this field"><i style="margin-left:10px" class="fa fa-times"></i></span>';
    }
    $title .= '<span title="Click to toggle"><i class="fa fa-caret-down"></i></span>';
    
    return '<div ' . $panel_id . '" class="panel ' . $panel_class . '">
        <div class="panel-heading">
            <h3 class="panel-title">
                ' . $title . '
            </h3>
        </div>
        <div class="panel-collapse collapse' . $collapse_class . '">
            <div class="panel-body">
            ' . $body . '
            </div>
        </div>
    </div>';
}

/**
 * Check if current theme supports wp_footer action
 * Related function: umPreloadController::checkWpFooterEnable() in shutdown action hook.
 *
 * @author Khaled Hossain
 * @since 1.2
 */
function isWpFooterEnabled()
{
    return get_site_transient('user_meta_is_wp_footer_enabled');
}

/**
 * Add javascript code to footer
 *
 * @author Khaled Hossain
 * @since 1.2
 *       
 * @param string $code            
 */
function addFooterJs($code)
{
    global $userMetaCache;
    if (empty($userMetaCache->footer_javascripts))
        $userMetaCache->footer_javascripts = null;
    $userMetaCache->footer_javascripts .= $code;
}

/**
 * Add code to footer
 *
 * @author Khaled Hossain
 * @since 1.2.1
 *       
 * @param string $code            
 */
function addFooterCode($code)
{
    global $userMetaCache;
    if (empty($userMetaCache->footer_codes))
        $userMetaCache->footer_codes = null;
    $userMetaCache->footer_codes .= $code;
}

/**
 * Print collected JavaScript code in jQuery ready block
 *
 * @author Khaled Hossain
 * @since 1.2
 */
function printFooterJs()
{
    global $userMetaCache;
    if (empty($userMetaCache->footer_javascripts))
        return;
    echo '<script type="text/javascript">jQuery(document).ready(function(){' . $userMetaCache->footer_javascripts . '});</script>';
    unset($userMetaCache->footer_javascripts);
}

/**
 * Print collected codes
 *
 * @author Khaled Hossain
 * @since 1.2.1
 */
function printFooterCodes()
{
    global $userMetaCache;
    if (empty($userMetaCache->footer_codes))
        return;
    echo $userMetaCache->footer_codes;
    unset($userMetaCache->footer_codes);
}

/**
 * print JavaScript code to footer
 *
 * @author Khaled Hossain
 * @since 1.2
 */
function footerJs()
{
    if (isWpFooterEnabled()) {
        add_action('wp_footer', '\UserMeta\printFooterJs', 1000);
        add_action('wp_footer', '\UserMeta\printFooterCodes', 1000);
    } else {
        printFooterJs();
        printFooterCodes();
    }
}

/**
 * Show notice message in admin screen
 *
 * @author Khaled Hossain
 * @since 1.2
 *       
 * @param string $message            
 * @param string $type
 *            error | success
 */
function adminNotice($message, $type = 'error')
{
    return "<div class=\"notice notice-$type is-dismissible\"><p>$message</p></div>";
}

/**
 * apply do_action and collect html printed by the hook
 *
 * @author Khaled Hossain
 * @since 1.2
 *       
 * @param string $hookName            
 * @return string
 */
function getHookHtml($hookName)
{
    global $userMeta;
    if ($userMeta->isHookEnable($hookName)) {
        ob_start();
        do_action($hookName);
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }
}

/**
 * Dumping data.
 *
 * @author Khaled Hossain
 * @since 1.2
 *       
 * @param mixed $data
 *            Data to dump
 * @param bool $dump
 *            true for using var_dump
 */
function dump($data, $dump = false)
{
    echo '<pre>';
    if (is_array($data) or is_object($data)) {
        if ($dump) {
            var_dump($data);
        } else {
            print_r($data);
        }
    } else {
        var_dump($data);
    }
    echo '</pre>';
}
