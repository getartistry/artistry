<?php
/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 8/8/2017
 * Time: 4:39 PM
 */
function cs_count_words($string)
{
    $string = trim(preg_replace("/\s+/", " ", $string));
    $word_array = explode(" ", $string);
    $num = count($word_array);
    return $num;
}

function cs_limit_words($string, $word_limit)
{
    $words = explode(" ", $string);
    return implode(" ", array_splice($words, 0, $word_limit));
}
function cs_limit_words_with_dots($string, $word_limit)
{
    $theRet = '';
    $lenContent = cs_count_words($string);
    if ($lenContent > $word_limit) {
        $words = explode(" ", $string);
        $theRet = implode(" ", array_splice($words, 0, $word_limit));
        if ($lenContent > $word_limit)
            $theRet .= "...";
        return $theRet;
    } else {
        return $string;
    }
}
/**
 * this function takes a url and returns a domain name from that url
 *
 * @param string $domain_name
 *
 * @return string $url - the domain name without http or https
 */
function ybi_cu_getDomainName($domainb)
{
    $bits = explode('/', $domainb);
    if ($bits[0] == 'http:' || $bits[0] == 'https:') {
        $domainb = $bits[2];
    } else {
        $domainb = $bits[0];
    }
    unset($bits);
    $bits = explode('.', $domainb);
    $idz = count($bits);
    $idz -= 3;
    if (strlen($bits[($idz + 2)]) == 2) {
        $url = $bits[$idz] . '.' . $bits[($idz + 1)] . '.' . $bits[($idz + 2)];
    } else if (strlen($bits[($idz + 2)]) == 0) {
        $url = $bits[($idz)] . '.' . $bits[($idz + 1)];
    } else {
        $url = $bits[($idz + 1)] . '.' . $bits[($idz + 2)];
    }
    return $url;
}

function cs_startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function cs_endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return false;
    }

    return (substr($haystack, -$length) === $needle);
}
/**
 * this function is a shared function for the LE sort values. Requires font awesome which should be present in all our pages.
 *
 * @param bool $unicode return font awesome unicode values
 *
 * @return array key => value array with social sort and display elements
 */
function cs_le_get_sort_values($unicode = true)
{
    // removed in api
    //'diggs' => 'Diggs',
    //'reddit_shares' => 'Reddit Shares',
    //'stumbleupon_shares' => 'StumbleUpon',
    if ($unicode) {
        return array(
            'total_shares' => '&#xf1e1; Total Shares',
            'share_gravity' => '&#xf012; Trending',
            'most_recent' => '&#xf160; Most Recent',
            'facebook_total' => '&#xf230; Facebook',
            'facebook_shares' => '&#xf230; FB Shares',
            'facebook_comments' => '&#xf230; FB Comments',
            'facebook_likes' => '&#xf230; FB Likes',
            'linkedin_shares' => '&#xf0e1; LinkedIn',
            'googleplus_shares' => '&#xf0d5; Google+',
            'pinterest_shares' => '&#xf0d2; Pinterest',
        );
    } else {
        return array(
            'total_shares' => '<i class="fa fa-share-alt-square total_share"></i> Total Shares',
            'share_gravity' => '<i class="fa fa-signal share_gravity"></i> Trending',
            'most_recent' => '<i class="fa fa-sort-amount-asc most_recent"></i> Most Recent',
            'facebook_total' => '<i class="fa fa-facebook facebook"></i> Facebook',
            'linkedin_shares' => '<i class="fa fa-linkedin linkedin"></i> LinkedIn',
            'googleplus_shares' => '<i class="fa fa-google-plus googleplus"></i> Google+',
            'pinterest_shares' => '<i class="fa fa-pinterest pinterest"></i> Pinterest',
        );
    }
}