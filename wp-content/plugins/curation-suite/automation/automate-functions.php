<?php
/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 10/23/2017
 * Time: 6:47 PM
 */
function cs_auto_api_call($route, $data, $param_arr, $cs_call = false)
{
    // $site_url = get_site_url();
    $site_url = $_SERVER['SERVER_NAME'];
    $data['site_url'] = $site_url;
    $data['version'] = '1.2.4';
    $data['license'] = get_option('sse_license_key');
    // global $current_user;
    // $current_user = wp_get_current_user();
    // get_currentuserinfo();
    $data['user_full_name'] = 'auto';
    $data['user_email'] = 'team@curationsuite.com';
    // this will be the base route of api

    $api_key = get_option('curation_suite_listening_api_key');  // get the api key
    $data['api_key'] = $api_key;
    // this will be the base route of api
    if ($cs_call) {
        $api_base_url = CS_API_BASE_URL . $route . '/';
    } else {
        if ($route == '')
            $api_base_url = CS_API_BASE_URL . $api_key . '/';
        else {
            $api_base_url = CS_API_BASE_URL . $route . '/' . $api_key . '/';
        }
    }


    // $api_base_url = 'http://localhost/listening-platform/api/sse/' . $route . '/';
    $url = $api_base_url . implode('/', $param_arr);
    $is_error = true;
    // fallback CURL for issues with WP built in wp_remote_post
    if ($is_error) {
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $JSON = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($JSON, true);
    }
    $data['url'] = $url;
    return $data;
}

function wp_set_auth_cookie_for_cron( $user_id, $remember = false, $secure = '', $token = '' ) {
    if ( $remember ) {
        /**
         * Filters the duration of the authentication cookie expiration period.
         *
         * @since 2.8.0
         *
         * @param int  $length   Duration of the expiration period in seconds.
         * @param int  $user_id  User ID.
         * @param bool $remember Whether to remember the user login. Default false.
         */
        $expiration = time() + apply_filters( 'auth_cookie_expiration', 14 * DAY_IN_SECONDS, $user_id, $remember );

        /*
         * Ensure the browser will continue to send the cookie after the expiration time is reached.
         * Needed for the login grace period in wp_validate_auth_cookie().
         */
        $expire = $expiration + ( 12 * HOUR_IN_SECONDS );
    } else {
        /** This filter is documented in wp-includes/pluggable.php */
        $expiration = time() + apply_filters( 'auth_cookie_expiration', 2 * DAY_IN_SECONDS, $user_id, $remember );
        $expire = 0;
    }

    if ( '' === $secure ) {
        $secure = is_ssl();
    }

    // Front-end cookie is secure when the auth cookie is secure and the site's home URL is forced HTTPS.
    $secure_logged_in_cookie = $secure && 'https' === parse_url( get_option( 'home' ), PHP_URL_SCHEME );

    /**
     * Filters whether the connection is secure.
     *
     * @since 3.1.0
     *
     * @param bool $secure  Whether the connection is secure.
     * @param int  $user_id User ID.
     */
    $secure = apply_filters( 'secure_auth_cookie', $secure, $user_id );

    /**
     * Filters whether to use a secure cookie when logged-in.
     *
     * @since 3.1.0
     *
     * @param bool $secure_logged_in_cookie Whether to use a secure cookie when logged-in.
     * @param int  $user_id                 User ID.
     * @param bool $secure                  Whether the connection is secure.
     */
    $secure_logged_in_cookie = apply_filters( 'secure_logged_in_cookie', $secure_logged_in_cookie, $user_id, $secure );

    if ( $secure ) {
        $auth_cookie_name = SECURE_AUTH_COOKIE;
        $scheme = 'secure_auth';
    } else {
        $auth_cookie_name = AUTH_COOKIE;
        $scheme = 'auth';
    }

    if ( '' === $token ) {
        $manager = WP_Session_Tokens::get_instance( $user_id );
        $token   = $manager->create( $expiration );
    }

    $auth_cookie = wp_generate_auth_cookie( $user_id, $expiration, $scheme, $token );
    $logged_in_cookie = wp_generate_auth_cookie( $user_id, $expiration, 'logged_in', $token );

    /**
     * Fires immediately before the authentication cookie is set.
     *
     * @since 2.5.0
     *
     * @param string $auth_cookie Authentication cookie.
     * @param int    $expire      The time the login grace period expires as a UNIX timestamp.
     *                            Default is 12 hours past the cookie's expiration time.
     * @param int    $expiration  The time when the authentication cookie expires as a UNIX timestamp.
     *                            Default is 14 days from now.
     * @param int    $user_id     User ID.
     * @param string $scheme      Authentication scheme. Values include 'auth', 'secure_auth', or 'logged_in'.
     */
    do_action( 'set_auth_cookie', $auth_cookie, $expire, $expiration, $user_id, $scheme );

    /**
     * Fires immediately before the logged-in authentication cookie is set.
     *
     * @since 2.6.0
     *
     * @param string $logged_in_cookie The logged-in cookie.
     * @param int    $expire           The time the login grace period expires as a UNIX timestamp.
     *                                 Default is 12 hours past the cookie's expiration time.
     * @param int    $expiration       The time when the logged-in authentication cookie expires as a UNIX timestamp.
     *                                 Default is 14 days from now.
     * @param int    $user_id          User ID.
     * @param string $scheme           Authentication scheme. Default 'logged_in'.
     */
    do_action( 'set_logged_in_cookie', $logged_in_cookie, $expire, $expiration, $user_id, 'logged_in' );

    /**
     * Allows preventing auth cookies from actually being sent to the client.
     *
     * @since 4.7.4
     *
     * @param bool $send Whether to send auth cookies to the client.
     */
    if ( ! apply_filters( 'send_auth_cookies', true ) ) {
        return;
    }

    $_COOKIE[$auth_cookie_name] = $auth_cookie;
    $_COOKIE[LOGGED_IN_COOKIE] = $logged_in_cookie;

    setcookie($auth_cookie_name, $auth_cookie, $expire, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN, $secure, true);
    setcookie($auth_cookie_name, $auth_cookie, $expire, ADMIN_COOKIE_PATH, COOKIE_DOMAIN, $secure, true);
    setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure_logged_in_cookie, true);
    if ( COOKIEPATH != SITECOOKIEPATH )
        setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure_logged_in_cookie, true);
}

function getBiggerSnippet($url, $paragraph_limit=6)
{
    $ch = curl_init('https://curationwp.com/parser/public/?url='.$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $JSON = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($JSON, true);
    $all_text = '';
    if($data) {
        if(array_key_exists('success',$data)) {
            $data = $data['success'];
            $content_data = $data['scrapping'];

                $paragraphs = $content_data['paragraphs'];

                if(!is_null($paragraphs)) {
                    if(count($paragraphs) > 0) {
                        $i = 0;
                        foreach ($paragraphs as $p) {
                            if($i==$paragraph_limit) {
                                break;
                            }
                            if($all_text=='') {
                                $all_text = $p;
                            } else {
                                $all_text .= ' ' . $p;
                            }
                            $i++;
                        }
                    }
                }
        }
    }
    return $all_text;
    //var_dump($data);
}
function cs_parser_limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,0,$word_limit));
}
function cs_parser_getSentences($inTextBlock)
{
    $re = '/# Split sentences on whitespace between them.
  (?<=                # Begin positive lookbehind.
	[.!?]             # Either an end of sentence punct,
  | [.!?][\'"]        # or end of sentence punct and quote.
  )                   # End positive lookbehind.
  (?<!                # Begin negative lookbehind.
	Mr\.              # Skip either "Mr."
  | Mrs\.             # or "Mrs.",
  | vs\.              # or "vs.",
  | Corp\.              # or "Corp.",
  | Inc\.              # or "Inc.",
  | U.S\.              # or "U.S.",
  | a.m\.              # or "a.m.",
  | p.m\.              # or "p.m.",
  | Ph.D\.              # or "Ph.D.",
  | Ms\.              # or "Ms.",
  | Jr\.              # or "Jr.",
  | Dr\.              # or "Dr.",
  | Prof\.            # or "Prof.",
  | Sr\.              # or "Sr.",
  | \s[A-Z]\.              # or initials ex: "George W. Bush",
					  # or... (you get the idea).
  )                   # End negative lookbehind.
  \s+                 # Split on whitespace between sentences.
  /ix';
    return preg_split($re, $inTextBlock, -1, PREG_SPLIT_NO_EMPTY);
}

function cs_parser_limit_by_sentence_advanced($text, $limit)
{
    $ret_text = '';
    $sentence_arr = cs_parser_getSentences($text);
    $i = 0;
    foreach ($sentence_arr as $val)
    {
        if($i == 0)
            $ret_text .= $val;
        else
            $ret_text .= ' ' . $val;

        $i++;

        if($i == $limit)
            break;

    }
    return $ret_text;
}

function cs_clean_up_snippet($snippet)
{

    $preg_replace_arr = array(
        "/[\n\r]/", //remove line breaks
        "/^(.+)AMPT/is", // Jan 4, 2018 3:39 AMPT Blockchain, perhaps best known for underpinning its better-known
        "/^by[\n\r](.+) hours ago/is", // By Matta Hanson 3 hours ago Gaming Steam has just released its December results of its Hardware
        "/^written by(.+)20[1-9][1-9]/i", //Written by Russell Blackstock, 02 January 2018 THEY listen, they talk and very soon
        "/^by(.+)20[1-9][1-9]/is", //Written by Russell Blackstock, 02 January 2018 THEY listen, they talk and very soon
        "/^by continuing to use this site(.+) terms of service./i",
        "/These are external links and will open in a new window/is",
        "/^(.+)>/is", // Jon Russell (@jonrussell)”> The rise of ICOs
        "/\[Read the full(.+)]/is", // to develop.” [Read the full article on MarTech Today.]
        "/You are just one step away to get a PERSONALISED EXPERIENCE All you need to do is simply Sign in with us/is", //
        "/^(.+)close the window\./is", // Port Vale’s sales and marketing manager Chris Turner talks to The Sentinel Beginning of dialog window. Escape will cancel and close the window. Former Sheffield Wednesday...
        "/^(jan|feb|mar|apr|may|jun|jul|aug|sep|nov|dec)(.+)\d comments/is", // March 5, 2018	Lisa Beebe	Disneyland	2 Comments The Void offers fully immersive virtual reality experiences, and it partnered with Lucasfilm and immersive entertainment firm ILMxLAB on their latest one, comments Star Wars: Secrets of the Empire. “Something as pioneering as this cannot be done alone.
        "/tagged as:(.*)/is", // ILS conference Tagged as: blockchain,        cat bond,catastrophe bond,ILS funds,
    );


    return trim($snippet);
}

/**
 * Checks a string for bad sentence or what we have classified as bad sentence fragments from common scraping operations.
 *
 * @param string $inString String to be analyzed
 * @param int $paragraph_number used to signify what paragraph this string/sentence to reduce false positives later
 * @param int $paragraph_limit_check this specifies the limit for the first check. This can be overridden so you can update based on when we have to increase or decrease based on site/text being parsed.
 *
 * @return string
 */
function cs_parser_check_for_bad_sentences($inString, $paragraph_number=0, $paragraph_limit_check=10)
{
    $found_bad = false;
    echo '<p>checking... '.$inString.'</p>';

    $inStingLower = trim(strtolower($inString));
    // use this is the check is a string that generally happens early on in the overall text being parsed, that way we don't get false positives
    // if the text is within the limit check then it checks these elements
    if($paragraph_number > 0 && $paragraph_number < $paragraph_limit_check) {
        $bad_string_arr = array(
            '// No Comments',
            'No Comments',
            'minutes ago',
            'Copyright',
            'your local station?',
            'The Latest news for the',
            'Subscribe to',
            'Subscribe Free',
            '#1 Source for',
            'join our free',
            'Click here',
            'Get the most important digital marketing news',
            'Tags',
            'More information',
            'Type to Search',
            'Sign up for the',
            'Forgot your password',
            'Keep abreast of significant corporate, financial and political developments around the world',
            'Stay informed and spot emerging risks',
            'Report a mispronounced word',
            'Sign up or login',
            'selected a PBS station in your area',
            'or choose another station below',
            'AudioEye',
            'continually enhance accessibility and usability',
            'visual display of this site',
            'interact with this site using your voice',
            'listen to this site read aloud',
            'The inside track on Washington politics',
            'Your Queue is empty',
            'Click on the next to articles to add them to your Queue',
            'Share on WhatsApp',
            'Never miss a great news story',
            'Get instant notifications from',
            'AllowNot now',
            'Subscribe and get the top tech news of the day',
            'Delivered to your mailbox',
            'For Immediate Release',
            'Staff Writer',
            'Please visit',
            'for the full article',
            'Learn about our dedicated team',
        );
        foreach ($bad_string_arr as $value)
        {
            if(strpos($inStingLower,strtolower($value)) !== false)
            {
                $found_bad = true;
                echo '<p class="bad">Bad Limit Check: '.$inString .'</p>';
                return $found_bad;
            }
        }

    }
    // use this if you are positive a sentance is bad and really the only way to flag it is if it starts with a phrase or string
    $starts_with_arr = array(
        'Digital-only access only',
        'RT',
        'Returns as of',
    );
    foreach($starts_with_arr as $value) {
        if (strncmp($inString, $value, strlen($value)) === 0) {
            $found_bad = true;
            echo '<p class="bad">StartsWith bad: '.$inString .'</p>';
            return $found_bad;
        }
    }

    // This is an exact match a sentence. Keep in mind when using this including the sentance ending such as . or ? might be important.
    $exact_match_arr = array(
        'News',
        'by',
        'Terms of use',
        '[...]',
        'Invalid email address',
        'Jump to navigation',
        'Read Full Story',
        'Register',
        'Forgot Password',
        'View comments',
        'All rights reserved.',
        'View comments',
        'Need a Profile?',
        'Register Now.',
        'We have sent you a verification email.',
        'Please check your email and click on the link to activate your profile.',
        'If you do not receive the verification message within a few minutes of signing up, please check your Spam or Junk folder.',
        'Subscribe today for full access on your desktop, tablet, and mobile device.',
        'Manage your account settings.',
        'View the E-Newspaper',
        'Manage your Newsletters',
        'View your Insider deals and more',
        'Member ID Card',
        'Get the news',
        'Let friends in your social network know what you are reading about',
        'Enjoy a limited number of articles over the next 30 days',
        'Subscribed, but don\'t have a login?',
        'Please enter a valid zip code',
        'Please select a region',
        'Not an Insider?',
        'We noticed you\'re browsing in private or incognito mode.',
        'Click search or press enter',
        'Copy and paste the embed code below.',
        'The code changes based on your selection.',
        'The code has been copied to your clipboard.',
        'Find this comment offensive?',
        'More information',
        'This is a submitted sponsored story.',
        'Opinions expressed by Forbes Contributors are their own.',
        'Click here.',
        'Learn more.',
        'Terms of use.',
    );
    foreach ($exact_match_arr as $value) {
        if(trim($inStingLower) == strtolower($value)) {
            $found_bad = true;
            echo '<p class="bad">exact bad: '.$inString .'</p>';
            return $found_bad;
        }
    }

    // this is a catch all. If the below strings show up anywhere in the sentence then we flag it as a bad sentence.
    // Be mindful here to to not make these phrases to broad as if they are found anywhere in the sentence it will be flagged.
    $bad_string_arr = array(
        '">',
        '»',
        '→',
        '{{',
        'trimcom',
        'article:',
        'Updated:',
        'Get Unlimited,',
        'This site may earn affiliate commissions from the links on this page',
        'This copy is for your personal non-commercial use only',
        'Ethereum World News',
        'The post appeared first on',
        'category:',
        'Read more on',
        'This is a Techmeme archive page',
        'Manage your account settings',
        'Already a print edition subscriber',
        'Subscribe today for full access',
        'Already a subscriber?',
        'Turn on desktop notifications',
        'Get breaking news alerts from',
        'Desktop notifications are on',
        'Please click here to learn how',
        'enable JavaScript in your web browser',
        'Please enable javascript',
        'you should conduct your own research when making a decision',
        'The views and opinions expressed here',
        'Receive all Cointelegraph news immediately in Telegram.',
        'You don’t want to miss out this price analytics.',
        'To order presentation-ready copies',
        'inquire about permissions/licensing',
        'activate our Facebook Messenger news bot',
        'Once subscribed, the bot will send you trending stories once a day',
        'Click on the button below to subscribe',
        'you verify that you are at least 13 years of age',
        'Refrain from posting comments that are obscene',
        'Help us delete comments that do not follow these guidelines',
        'Once subscribed, the bot will send you a digest of trending stories once a day',
        'You can also customize the types of stories it sends you',
        'YourStory brings to you stories',
        'Click on the button below to set up your account',
        'Purchase a digital-only subscription now',
        'We look forward to seeing you on',
        'free articles of your choice a month',
        'This subscriber-only site gives you exclusive access',
        'Get unlimited access to all of our breaking news',
        'GREAT REASONS TO SUBSCRIBE TODAY!',
        'You have reached your limit of  free articles this month',
        'LINK YOUR ACCOUNT FOR PREMIUM ACCESS',
        'You\'re now logged in',
        'Please resend verification',
        'Thank you for reading our articles on',
        'To continue reading articles, you will need to become a subscriber',
        'click on Subscribe and follow the instructions',
        'You can also contact our circulation department',
        'A link has been posted to your Facebook feed',
        'Enjoy a limited number of articles over the next 30 days',
        'purchase a subscription to continue reading',
        'Get digital access with unlimited web and mobile web access',
        'after three-month intro period',
        'Email Address or Password is incorrect',
        'Email address field is required',
        'Password field is required',
        'To get uninterrupted access',
        'Already a member?',
        'Log in or go back to the homepage',
        'By registering you agree to our privacy policy',
        'essential information you need to do your job better',
        'terms of service and privacy policy',
        'Please select the editions you would like to sign up to',
        'To find out more about Facebook commenting please read',
        'available for your selected zip code',
        'This is a modal window',
        'Thank you for your feedback',
        'please exit incognito mode',
        'Subscribe now for unlimited access to online articles',
        'Visitors are allowed 3 free articles per month',
        'click on the Report button',
        'This will alert our moderators to take action',
        'Reason for reporting:',
        'Your Reason has been Reported',
        'For reprint rights:',
        'committed to providing the fastest, most efficient reading experience possible',
        'Get real time stock quotes, the latest commodities',
        'Business Insider that will consume less data',
        'By using this site you agree to our use of cookies',
        'need a javascript enabled browser',
        'urges readers to conduct their own research',
        'website may use cookies to improve',
        'web site uses cookies to improve',
        'you are accepting the use of cookies',
        'change your cookie settings',
        'view our cookie policy',
        'The author is a Forbes contributor',
        'The opinions expressed are those of the writer',
        'Bloomberg quickly and accurately delivers business',
        'commas to separate multiple email addresses',
        'Your message has been sent',
        'emailing this page',
        'You can unsubscribe at any time',
        'By using this site you agree',
        'uses cookies to personalize content',
        'We do also share that information with third parties for advertising',
        'By using this site you consent',
        'By using this site you consent',
        'This website uses cookies',
        'You are commenting using your',
        'Notify me of new comments via',
        'Complete the form below',
        'For reprint rights:',
        'Disclosure:',
        'Image:',
        'Email Address or Password is incorrect',
        'Email address field is required',
        'Password field is required',
        'To get uninterrupted access',
        'Already a member?',
        'Log in or go back to the homepage',
        'By registering you agree to our privacy policy',
        'essential information you need to do your job better',
        'terms of service and privacy policy',
        'Please select the editions you would like to sign up to',
        'To find out more about Facebook commenting please read',
        'available for your selected zip code',
        'This is a modal window',
        'Thank you for your feedback',
        'please exit incognito mode',
        'Subscribe now for unlimited access to online articles',
        'Visitors are allowed 3 free articles per month',
        'click on the Report button',
        'This will alert our moderators to take action',
        'Reason for reporting:',
        'Your Reason has been Reported',
        'For reprint rights:',
        'Disclosure:',
        'We are committed to protecting your privacy',
        'Privacy Statement and Disclaimer Notice',
        'We constantly review our systems',
        'The Motley Fool helps millions',
        'Comment on stories,',
        'transaction status',
        'some problems with subscriber',
        'still required for our PDFs',
        'read more free articles',
        'read more free articles',
        'subscribe now',
        'read more from',
        'Posted by:',
        'Join the NASDAQ Community',
        'Sign up for our',
        'to manage your',
        'originally published at',
        'subscribe to get best',
        'site uses cookies',
        'our privacy policy',
        'appeared first on',
        'insights:',
        'we cover news related to',
        'Posted by',
        'Contributor on',
        'Last modified on',
    );
    foreach ($bad_string_arr as $value)
    {
        if(strpos($inStingLower,strtolower($value)) !== false)
        {
            $found_bad = true;
            echo '<p class="bad">contains bad: '.$inString .'</p>';
            return $found_bad;
        }
    }
    return $found_bad;
}