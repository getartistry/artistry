<?php
/**
 * Replaces the link sitewide if the direct curated links feature is turned on. It also adds a parameter to the URL so that can be picked up JS to add a target element.
 *
 * @param $url String passed URL
 * @param $post post object
 * @param bool $leavename
 * @return string returns URL to be used if direct curated link feature is turned on
 */
function cs_use_direct_curated_link( $url='', $post=null, $leavename=false )
{

    $do_direct_curated_links = false;
    $options = get_option('curation_suite_data');
    if (is_array($options)) {
        if (array_key_exists('curation_suite_direct_curated_links', $options)) {
            if ($options['curation_suite_direct_curated_links'] == 1) {
                $do_direct_curated_links = true;
            }
        }
    }

    if($do_direct_curated_links) {
        if ( $post->post_type == 'post' ) {
            if(!is_singular( 'post' )) { // && in_the_loop()
                $curated_links = get_post_meta($post->ID, 'cu_curated_links', false);
                if($curated_links) {
                    $count = count($curated_links);
                    // If there is a curated link we only link if there is only one. Mainly because if it's a multi source curation we want to link to the post on the site.
                    if($count > 0 && $count < 2) {
                        $is_direct_override = get_post_meta($post->ID, 'cs_override_direct_link', true);
                        if($is_direct_override) {
                            if($is_direct_override==1) {
                                return $url;
                            }
                        }
                        $curated_url = $curated_links[0];
                        $curated_url = add_query_arg( 'cs_referral', 'yes', $curated_url );
                        // if it's curated video from youtube we display the post to keep user on site
                        if(!is_admin()) {
                            if(ybi_cu_getDomainName($curated_url) != 'youtube.com') {
                                $url = $curated_url;
                            }
                        }
                    }
                }
            }
        }
    }
    return $url;
}


function cs_direct_curated_link_post_display( $url='', $post=null) {
    global $post;
    $do_direct_curated_links = false;
    $options = get_option('curation_suite_data');
    if (is_array($options)) {
        if (array_key_exists('curation_suite_direct_curated_links', $options)) {
            if ($options['curation_suite_direct_curated_links'] == 1) {
                $do_direct_curated_links = true;
            }
        }
    }

    if($do_direct_curated_links) {
        if($post) {
            if ( $post->post_type == 'post' ) {
                if(!is_singular( 'post' )) { // && in_the_loop()
                    $curated_links = get_post_meta($post->ID, 'cu_curated_links', false);
                    if($curated_links) {
                        $count = count($curated_links);
                        // If there is a curated link we only link if there is only one. Mainly because if it's a multi source curation we want to link to the post on the site.
                        if($count > 0 && $count < 2) {
                            $curated_url = $curated_links[0];
                            $curated_url = add_query_arg( 'cs_referral', 'yes', $curated_url );
                            // if it's curated video from youtube we display the post to keep user on site
                            if(is_admin()) {
                                if(ybi_cu_getDomainName($curated_url) != 'youtube.com') {
                                    $is_direct_override = get_post_meta($post->ID, 'cs_override_direct_link', true);
                                    $direct_override_checked = '';
                                    if($is_direct_override) {
                                        if($is_direct_override==1) {
                                            $direct_override_checked = 'checked=\"checked\"';
                                        }
                                    }

                                    echo "<script type='text/javascript'>jQuery('#edit-slug-box').append('<div><strong>Direct Curated Link:</strong> <a href=\"".$curated_url."\" target=\"_blank\">".$curated_url."</a><br /><i> ' +
 '* You have Direct Curated Links turned on so the above link will be used throughout your site.</i>' +
 ' <label id=\"cs_overide_direct_lbl\"><input type=\"checkbox\" id=\"cs_override_direct_link\" value=\"1\" " . $direct_override_checked . " rel=\"cs_override_direct_link\" class=\"cs_meta_value_save\" /> Override direct link</label></div>');</script>";
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $url;
}

/**
 * This function puts javascript in the footer if the direct link feature is turned on. It looks to see if the URL has the parameter assigned.
 * If it does then it adds the link to be targeted to a new tab.
 *
 */
function ybi_cs_direct_link() {
    $do_direct_curated_links = false;
    $options = get_option('curation_suite_data');
    if (is_array($options)) {
        if (array_key_exists('curation_suite_direct_curated_links', $options)) {
            if ($options['curation_suite_direct_curated_links'] == 1) {
                $do_direct_curated_links = true;
            }
        }

        if($do_direct_curated_links) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {

                jQuery("a").each(function () {
                    if (this.href.indexOf('cs_referral=yes') != -1) {
                        jQuery(this).attr('target', '_blank');
                    }
                });

                function CS_CheckForDOMChange()
                {
                    // check for any new element being inserted here,
                    // or a particular node being modified
                    jQuery("a").each(function () {
                        if (this.href.indexOf('cs_referral=yes') != -1) {
                            jQuery(this).attr('target', '_blank');
                        }
                    });
                    // call the function again after 100 milliseconds
                    setTimeout( CS_CheckForDOMChange, 100 );
                }
                CS_CheckForDOMChange();
                }); // end of doc
            </script>
            <?php
        }
        if (array_key_exists('curation_suite_sub_headline_color', $options)) {
            if ($options['curation_suite_sub_headline_color'] != '') {
                ?>
                <style type="text/css">
                    .cs_sub_headline { color: <?php echo $options['curation_suite_sub_headline_color']; ?>; }
                </style>
                <?php
            }
        }

    }
}