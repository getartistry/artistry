<?php
require_once YBI_BASE_PATH . 'vendor/autoload.php';
/*ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);*/

?>
<style>
    .bad {color: #ff0a16;}
    .good {color: #048005;}
    .notice {display: none;}
    #recommend_toc { clear: both; margin: 0 0 10px 0;}
    #right_recommend_action { width: 100px;}
    #left_recommend_title {width: 30%;}
</style>
<div class="wrap">
    <div class="products_header" style="background: url(<?php echo plugins_url('youbrandinc_products/i/wordpress-logo-32-blue.png');?>); background-repeat: no-repeat; background-position: 0px 7px;">
        <h3><?php echo _e('WordPress Recommended Plugins & Products'); ?></h3>
        <div style="clear: both; overflow:auto; margin: 0 auto;"></div>
    </div>
    <?php
        $Table = new ybi\html\Table(
            'platform_overview_table',
            array('wp-list-table', 'widefat','fixed posts'),
            array('alternate'=>true,'tbody_class'=>'overview_body') );

        $Row = new ybi\html\Row('',array());
        $Column = new ybi\html\Column('left_recommend_title');
        $Column->setContent('Plugin/Tool');
        $Row->addColumn($Column);
        $Column = new ybi\html\Column('right_platform_setting');
        $Column->setContent('Description');
        $Row->addColumn($Column);
        $Column = new ybi\html\Column('right_recommend_action');
        $Column->setContent('Learn More');
        $Row->addColumn($Column);
        $Table->setTitleRow($Row);
//http://localhost/plugin_dev/wp-admin/plugin-install.php?s=wordfence&tab=search&type=term
    $admin_url = admin_url();
    $product_arr = array(
        array(
            'type' => 'header',
            'name' => '<i class="fa fa-wordpress" aria-hidden="true"></i> Should Be Required',
            'desc' => 'Every one of these plugins should be installed for fully functional and secure WordPress sites',
            'image' => '',
            'link' => 'required_plugins'
        ),
        array(
            'type' => 'item',
            'name' => 'Site Security & Protection',
            'desc' => 'Massively Reduces the Risk of Getting your WP Site Hacked! (No more website hacking)',
            'image' => '',
            'link' => 'https://goo.gl/tn7obs'),
        array(
            'type' => 'item',
            'name' => 'WordFence',
            'desc' => 'Secure your website with the most comprehensive WordPress security plugin. Firewall, malware scan, blocking, live traffic, login security & more.',
            'image' => '',
            'action' => 'install',
            'link' => $admin_url . 'plugin-install.php?s=wordfence&tab=search&type=term'),
        array(
            'type' => 'item',
            'name' => 'Find Hidden Themes & Plugins',
            'desc' => 'A powerful browser extension for Google Chrome and Mozilla Firefox. Your copy of this internet marketing software is compatible with your PC and Mac.',
            'image' => '',
            'link' => 'https://goo.gl/OYo5ta'),
        array(
            'type' => 'item',
            'name' => 'Easily Manage Multiple WordPress Sites in One Place',
            'desc' => 'The most simple way to manage multiple WordPress sites. From keeping WordPress versions, themes, and plugins updated. Plus many other helpful features.',
            'image' => '',
            'link' => 'https://goo.gl/VtiQSp'),
        array(
            'type' => 'header',
            'name' => '<i class="fa fa-filter" aria-hidden="true"></i> Conversion Tools',
            'desc' => 'Tools & plugins to help you with conversion.',
            'image' => '',
            'link' => 'conversion_tools'
        ),
        array(
            'type' => 'item',
            'name' => 'Lead Generation Plugin',
            'desc' => 'Easily create popups, sidebar lead gen, and other conversions.',
            'image' => '',
            'link' => 'https://goo.gl/USM4ox'),
        array(
            'type' => 'item',
            'name' => 'Headline Testing',
            'desc' => 'Testing headlines is simple and easy with this A/B headline testing tool.',
            'image' => '',
            'link' => 'https://goo.gl/O1BOLW'),
        array(
            'type' => 'item',
            'name' => 'Top Messenger Like Conversion Plugin',
            'desc' => 'Create a high converting messenger like popup to convert visitors on your site.',
            'image' => '',
            'link' => 'https://goo.gl/u9NTNu'),
        array(
            'type' => 'item',
            'name' => 'Fresh Popups',
            'desc' => 'Solves a big problem in the marketplace, by allowing people to actually give an enjoyable pop-up experience to their visitors.',
            'image' => '',
            'link' => 'https://goo.gl/HR22Bl'),
        array(
            'type' => 'item',
            'name' => 'Sharing Lock Plugin',
            'desc' => 'A WP plugin that locks your most valuable site content behind a set of social buttons until the visitor likes, shares, +1s or tweets your page. It helps to improve social performance of your website, get more likes/shares, build quality followers and attract more traffic from social networks.',
            'image' => '',
            'link' => 'https://goo.gl/TdVKDk'),
        array(
            'type' => 'item',
            'name' => 'Affiliate Link Management',
            'desc' => 'A simple to use affiliate link management tool that goes well beyond just masking your links.',
            'image' => '',
            'link' => 'https://goo.gl/gjOxUi'),
        array(
            'type' => 'header',
            'name' => '<i class="fa fa-sitemap" aria-hidden="true"></i> Site Enhancements',
            'desc' => 'Make your site or section of your site look better.',
            'image' => '',
            'link' => 'conversion_tools'
        ),
        array(
            'type' => 'item',
            'name' => 'Author Box',
            'desc' => 'If you’re a blogger or your business relies on content marketing you should install this author box plugin. Improve your blog’s functionality, make connections & enable reader engagement, highlight your articles in search engine result pages, increase conversions and click-through rates, and more...',
            'image' => '',
            'link' => 'https://goo.gl/P7RK4D'),
        array(
            'type' => 'item',
            'name' => 'Dynamic Menu',
            'desc' => 'user-friendly, highly customizable, responsive Mega Menu WordPress plugin. It works out of the box with the WordPress 3 Menu System, making it simple to get started but powerful enough to create highly customized and creative mega menu configurations.',
            'image' => '',
            'link' => 'https://goo.gl/AHSoFe'),
        array(
            'type' => 'item',
            'name' => 'Social Metrics',
            'desc' => 'Get the social media share data on all your content. Plus easily reshare your top posts and content.',
            'image' => '',
            'link' => 'https://goo.gl/YyAT9T'),
    );
        $toc_html = '';
        $toc_number = 0;
        foreach ($product_arr as $product) {

            if($product['type'] == 'header') {
                if($toc_number>0) {
                    $toc_html .= ' | ';
                }
                $Row = new ybi\html\Row('',array());
                $toc_html .= '<a href="#'.$product['link'].'">'.$product['name'].'</a>';
                $Row->addColumnContent('<strong>'.$product['name'].'<strong><a href="#" id="'. $product['link'] .'"></a> - ' . $product['desc'],'',array(),array('colspan'=>3));
                $toc_number++;
            } else {
                $Row = new ybi\html\Row('',array());
                $Link = new ybi\html\Link('',array(),array(),array(),$product['link'],$product['name'],true);
                $Row->addColumnContent($Link->getLink());
                $Row->addColumnContent($product['desc']);
                if(array_key_exists('action',$product)) {
                    if($product['action'] == 'install') {
                        $Link = new ybi\html\Link('',array(),array(),array(),$product['link'],'Install',true);
                        $Row->addColumnContent($Link->getLink());
                    } else {
                        $Link = new ybi\html\Link('',array(),array(),array(),$product['link'],'Learn More...',true);
                        $Row->addColumnContent($Link->getLink());
                    }
                } else {
                    $Link = new ybi\html\Link('',array(),array(),array(),$product['link'],'Learn More...',true);
                    $Row->addColumnContent($Link->getLink());
                }
            }
            $Table->addRow($Row);
        }
        echo '<div id="recommend_toc">'. $toc_html . '</div>';
        echo $Table->getFullTableHTML();
        ?>
    </div><!--inner-->
</div><!--wrap-->