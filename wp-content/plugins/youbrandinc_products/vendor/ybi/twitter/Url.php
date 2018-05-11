<?php

/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 10/18/2015
 * Time: 9:57 PM
 */
class Url
{
private $url, $expanded_url, $display_url, $parsed_url, $domain_name;

    /**
     * @return mixed
     */
    public function getDomainName()
    {
        return $this->domain_name;
    }

    /**
     * @param mixed $domain_name
     */
    public function setDomainName($domain_name)
    {
        $this->domain_name = $domain_name;
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
    public function getParsedUrl()
    {
        return $this->parsed_url;
    }

    /**
     * @param mixed $parsed_url
     */
    public function setParsedUrl($parsed_url)
    {
        $this->parsed_url = $parsed_url;
    }
    public function parseDisplayUrlToGetFinalUrl()
    {
        if($this->getExpandedUrl() != '') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->getExpandedUrl());
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);

            $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $this->setParsedUrl($url);
            curl_close($ch);
        } else {
            $this->setParsedUrl($this->getExpandedUrl());
        }
        $this->setDomainName(ybi_get_domain_name($this->getParsedUrl()));
    }
}