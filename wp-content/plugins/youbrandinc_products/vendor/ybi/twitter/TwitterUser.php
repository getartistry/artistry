<?php

/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 9/11/2015
 * Time: 7:46 PM
 */
class TwitterUser
{
    private $id, $twitter_id, $name, $screen_name, $description, $url, $followers_count, $friends_count, $listed_count,$statuses_count,
        $profile_image_url, $created_at, $following, $recomend_follow,$is_default_profile_image,$profile_banner_url, $timezone, $verified, $location,
    $profile_link_urls,$description_urls,$profile_image_entities;

    function addProfileImageEntity($tag, $prob)
    {
        if(is_array($this->profile_image_entities)) {
            if(!array_key_exists($tag,$this->profile_image_entities)) {
                $this->profile_image_entities[$tag] = $prob;
            }
        } else {
            $this->profile_image_entities = array($tag=> $prob);
        }

    }

    function getClarifaiAccessToken($current_local_twitter_user)
    {
        $return_obj = new stdClass();
        $created_date = date("Y-m-d H:i:s");
        $get_new_token = false;
        if($current_local_twitter_user->clarifai_expire_at) {
            if(strtotime($current_local_twitter_user->clarifai_expire_at) > strtotime($created_date)) {
                $return_obj->status = 'success';
                $return_obj->access_token = $current_local_twitter_user->clarifai_access_token;
                return $return_obj;
            } else {
                $get_new_token = true;
            }
        } else {
            $get_new_token = true;
        }
        if($get_new_token) {
            $url = 'https://api.clarifai.com/v1/token/';
            $data = array(
                'client_id' => $current_local_twitter_user->clarifai_client_id,
                'client_secret' => $current_local_twitter_user->clarifai_client_secret,
                'grant_type' => 'client_credentials',
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($response);
            if($json) {
                $access_token = $json->access_token;

                $expires_in = $json->expires_in;
                $expires_in = $expires_in - 7200;
                //$time = date("m/d/Y H:i:s", time() + $expires_in);
                $time = date("Y-m-d H:i:s", time() + $expires_in);

                global $wpdb;
                $wpdb->update(
                    'sse_twitter_accounts',
                    array(
                        'clarifai_access_token' => $access_token,
                        'clarifai_expire_at' => $time
                    ),
                    array( 'id' => $current_local_twitter_user->id ),
                    array(
                        '%s',
                        '%s'
                    ),
                    array( '%d' )
                );
                $return_obj->status = 'success';
                $return_obj->access_token = $access_token;
            }

            return $return_obj;
        }
    }

    function processImageRecognition($current_local_twitter_user)
    {
        $return_arr = array();
        $access_token_obj = $this->getClarifaiAccessToken($current_local_twitter_user);
        if(property_exists($access_token_obj,'status')) {
            if($access_token_obj->status=='success') {
                $access_token = $access_token_obj->access_token;
                if($this->profile_image_url != '') {
                    $url = 'https://api.clarifai.com/v1/tag?access_token='.$access_token.'&url='.$this->profile_image_url;
                    $process = curl_init($url);
                    curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    //curl_setopt($process, CURLOPT_USERPWD, "username:XXXX");
                    curl_setopt($process, CURLOPT_TIMEOUT, 30);
                    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($process);
                    curl_close($process);
                    $json = json_decode($response);
                    $status_code = $json->status_code;
                    if($status_code && $status_code=='OK') {
                        //var_dump($json);
                        $search_results = $json->results[0]->result->tag;
                        $tags = $search_results->classes;
                        $probs = $search_results->probs;
                        //var_dump($tags);
                        $c = count($tags);
                        $i = 0;
                        $tag_prob_arr = array();
                        foreach ($tags as $tag) {

                            $prob_round = round( ($probs[$i] * 100));
                            $this->addProfileImageEntity($tag,$prob_round);
                            //$tag_prob_arr[$tag] =$prob_round;
                            $i++;
                        }
                        $return_arr['status'] = 'success';
                    } else {
                        $status_msg = $json->status_msg;
                        $return_arr['status'] = 'error';
                        $return_arr['message'] = 'Problem with image recognition: ' . $status_msg;
                    }

                }

            } else {
                $return_arr['status'] = 'error';
                $return_arr['message'] = 'Problem with image recognition: access token';
            }
        } else {
            $return_arr['status'] = 'error';
            $return_arr['message'] = 'Problem with image recognition.';
        }


        return $return_arr;

    }

    function isPerson()
    {
        $good_tag_arr = array(
            'human',
            'face',
            'portrait',
            'person',
            'people',
            'adult',
            'guy',
            'girl',
            'man',
            'woman',
        );
        if(isset($this->profile_image_entities) && is_array($this->profile_image_entities)) {
            foreach($this->profile_image_entities as $tag => $prob) {
                if(in_array($tag,$good_tag_arr)) {
                    if($prob > 90) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getProfileImageEntities()
    {
        return $this->profile_image_entities;
    }

    /**
     * @param mixed $profile_image_entities
     */
    public function setProfileImageEntities($profile_image_entities)
    {
        $this->profile_image_entities = $profile_image_entities;
    }

    public function addURLItem($UrlArr, $location='')
    {
        if($location=='profile') {
            if(is_array($this->profile_link_urls)) {
                $this->profile_link_urls[] = $UrlArr;
            } else {
                $this->profile_link_urls = array($UrlArr);
            }
        } else {
            if(is_array($this->description_urls)) {
                $this->description_urls[] = $UrlArr;
            } else {
                $this->description_urls = array($UrlArr);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getProfileLinkUrls()
    {
        return $this->profile_link_urls;
    }

    /**
     * @param mixed $profile_link_urls
     */
    public function setProfileLinkUrls($profile_link_urls)
    {
        $this->profile_link_urls = $profile_link_urls;
    }

    /**
     * @return mixed
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param mixed $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @return mixed
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * @param mixed $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    function GCD ($a, $b)
    {
        while ( $b != 0)
        {
            $remainder = $a % $b;
            $a = $b;
            $b = $remainder;
        }
        return abs ($a);
    }
    public function insertToQueueTable()
    {
        global $wpdb;
        $wpdb->insert('sse_blocked_twitter_users',
            array('id_str'=>$this->id,'username'=>$this->twitter_id),
            array('%s','%s')
        );
    }
    public function getFollowerToFollowingRatio()
    {
        return $this->getFollowersCount() / $this->getFriendsCount();
        //return $this->GCD($this->getFollowersCount(), $this->getFriendsCount());
    }
    public function getFollowingToFollowersRatio()
    {
        return $this->getFollowersCount() / $this->getFriendsCount();
    }

    /**
     * @return mixed
     */
    public function getProfileBannerUrl()
    {
        return $this->profile_banner_url;
    }

    /**
     * @param mixed $profile_banner_url
     */
    public function setProfileBannerUrl($profile_banner_url)
    {
        $this->profile_banner_url = $profile_banner_url;
    }

    /**
     * @return mixed
     */
    public function getIsDefaultProfileImage()
    {
        return $this->is_default_profile_image;
    }

    /**
     * @param mixed $is_default_profile_image
     */
    public function setIsDefaultProfileImage($is_default_profile_image)
    {
        $this->is_default_profile_image = $is_default_profile_image;
    }

    public function calculateRecommendFollow()
    {
        $recommend_to_follow = false;
        if($this->getFriendsCount() < 500) {
            $recommend_to_follow = true;
        }
        if(!$recommend_to_follow) {
            if($this->getFollowersCount() < 500) {
                $recommend_to_follow = true;
            }
        }
        if($recommend_to_follow) {
            if($this->getDescription() != '') {
                if(strlen($this->getDescription()) < 5)
                    $recommend_to_follow = false;
            } else {
                $recommend_to_follow = false;
            }

        }
        $this->setRecomendFollow($recommend_to_follow);
    }

    /**
     * @return mixed
     */
    public function getRecomendFollow()
    {
        return $this->recomend_follow;
    }

    /**
     * @param mixed $recomend_follow
     */
    public function setRecomendFollow($recomend_follow)
    {
        $this->recomend_follow = $recomend_follow;
    }

    /**
     * @return mixed
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @param mixed $following
     */
    public function setFollowing($following)
    {
        $this->following = $following;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTwitterId()
    {
        return $this->twitter_id;
    }

    /**
     * @param mixed $twitter_id
     */
    public function setTwitterId($twitter_id)
    {
        $this->twitter_id = $twitter_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getScreenName()
    {
        return $this->screen_name;
    }

    /**
     * @param mixed $screen_name
     */
    public function setScreenName($screen_name)
    {
        $this->screen_name = $screen_name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getFollowersCount()
    {
        return $this->followers_count;
    }

    /**
     * @param mixed $followers_count
     */
    public function setFollowersCount($followers_count)
    {
        $this->followers_count = $followers_count;
    }

    /**
     * @return mixed
     */
    public function getFriendsCount()
    {
        return $this->friends_count;
    }

    /**
     * @param mixed $friends_count
     */
    public function setFriendsCount($friends_count)
    {
        $this->friends_count = $friends_count;
    }

    /**
     * @return mixed
     */
    public function getListedCount()
    {
        return $this->listed_count;
    }

    /**
     * @param mixed $listed_count
     */
    public function setListedCount($listed_count)
    {
        $this->listed_count = $listed_count;
    }

    /**
     * @return mixed
     */
    public function getStatusesCount()
    {
        return $this->statuses_count;
    }

    /**
     * @param mixed $statuses_count
     */
    public function setStatusesCount($statuses_count)
    {
        $this->statuses_count = $statuses_count;
    }

    /**
     * @return mixed
     */
    public function getProfileImageUrl()
    {
        return $this->profile_image_url;
    }

    /**
     * @param mixed $profile_image_url
     */
    public function setProfileImageUrl($profile_image_url)
    {
        $this->profile_image_url = $profile_image_url;
    }


    /**
     * @return mixed
     */
    public function getDescriptionUrls()
    {
        return $this->description_urls;
    }

    /**
     * @param mixed $description_urls
     */
    public function setDescriptionUrls($description_urls)
    {
        $this->description_urls = $description_urls;
    }


}