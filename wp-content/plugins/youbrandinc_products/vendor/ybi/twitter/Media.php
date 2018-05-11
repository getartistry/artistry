<?php

/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 9/12/2015
 * Time: 11:04 AM
 */
class Media
{
 private $id, $id_str, $media_url, $media_url_https, $url, $display_url, $expanded_url, $type;

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
    public function getIdStr()
    {
        return $this->id_str;
    }

    /**
     * @param mixed $id_str
     */
    public function setIdStr($id_str)
    {
        $this->id_str = $id_str;
    }

    /**
     * @return mixed
     */
    public function getMediaUrl()
    {
        return $this->media_url;
    }

    /**
     * @param mixed $media_url
     */
    public function setMediaUrl($media_url)
    {
        $this->media_url = $media_url;
    }

    /**
     * @return mixed
     */
    public function getMediaUrlHttps()
    {
        return $this->media_url_https;
    }

    /**
     * @param mixed $media_url_https
     */
    public function setMediaUrlHttps($media_url_https)
    {
        $this->media_url_https = $media_url_https;
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
    public function getDisplayUrl()
    {
        return $this->display_url;
    }

    /**
     * @param mixed $display_url
     */
    public function setDisplayUrl($display_url)
    {
        $this->display_url = $display_url;
    }

    /**
     * @return mixed
     */
    public function getExpandedUrl()
    {
        return $this->expanded_url;
    }

    /**
     * @param mixed $expanded_url
     */
    public function setExpandedUrl($expanded_url)
    {
        $this->expanded_url = $expanded_url;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}