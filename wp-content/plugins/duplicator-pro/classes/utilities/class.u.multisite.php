<?php

class DUP_PRO_MU
{

    public static function networkMenuPageUrl($menu_slug, $echo = true)
    {
        global $_parent_pages;

        if (isset($_parent_pages[$menu_slug])) {
            $parent_slug = $_parent_pages[$menu_slug];
            if ($parent_slug && !isset($_parent_pages[$parent_slug])) {
                $url = network_admin_url(add_query_arg('page', $menu_slug, $parent_slug));
            } else {
                $url = network_admin_url('admin.php?page='.$menu_slug);
            }
        } else {
            $url = '';
        }

        $url = esc_url($url);

        if ($echo) {
            echo $url;
        }

        return $url;
    }

    public static function isMultisite()
    {
        return self::getMode() > 0;
    }

    // 0 = single site; 1 = multisite subdomain; 2 = multisite subdirectory
    public static function getMode()
    {
        if (defined('MULTISITE') && MULTISITE) {
            if (defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }

    // Return an array of { id: {subsite id}, name {subsite name})
    public static function getSubsites()
    {
        $site_array = array();
        $mu_mode    = DUP_PRO_MU::getMode();

        if ($mu_mode !== 0) {
            if (function_exists('get_sites')) {
                $sites = get_sites();

                $home_url_path = parse_url(get_home_url(), PHP_URL_PATH);
                foreach ($sites as $site) {
                    if ($mu_mode == 1) {
                        // Subdomain
                        $name = $site->domain;
                    } else {
                        // Subdirectory
                        $name = $site->path;
                        if (DUP_PRO_STR::startsWith($name, $home_url_path)) {
                            $name = substr($name, strlen($home_url_path));
                        }
                    }

                    $site_info       = new stdClass();
                    $site_info->id   = $site->blog_id;
                    $site_info->name = $name;

                    array_push($site_array, $site_info);
                    DUP_PRO_LOG::trace("Multisite subsite detected. ID={$site_info->id} Name={$site_info->name}");
                }
            } else if (function_exists('wp_get_sites')) {
                $wp_sites = wp_get_sites();

                DUP_PRO_LOG::traceObject("####wp sites", $wp_sites);

                foreach ($wp_sites as $wp_site) {
                    if ($mu_mode == 1) {
                        // Subdomain
                        $wp_name = $wp_site['domain'];
                    } else {
                        // Subdirectory
                        $wp_name = $wp_site['path'];
                    }

                    $wp_site_info       = new stdClass();
                    $wp_site_info->id   = $wp_site['blog_id'];
                    $wp_site_info->name = $wp_name;

                    array_push($site_array, $wp_site_info);
                }
            } else {
                DUP_PRO_LOG::trace("#### ERROR. Neither get_sites() nor wp_get_sites exist even though in multisite mode...");
                return false;
            }
        }

        return $site_array;
    }
}