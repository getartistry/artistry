<?php
namespace ybi\html;
/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 10/14/2015
 * Time: 6:33 PM
 */
class Link extends HTMLObject
{
    public $href, $text, $is_new_window;

    /**
     * @return mixed
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param mixed $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getIsNewWindow()
    {
        return $this->is_new_window;
    }

    /**
     * @param mixed $is_new_window
     */
    public function setIsNewWindow($is_new_window)
    {
        $this->is_new_window = $is_new_window;
    }
    function __construct($id, $classes,$settings,$data_elements, $href, $text,$is_new_window=false) {
        parent::__construct($id, $classes,$settings,$data_elements);
        $this->setHref($href);
        $this->setText($text);
        $this->setIsNewWindow($is_new_window);

    }
    public function getJavaScriptLink()
    {
        $html = '<a href="javascript:;" ' . $this->getIDString() . $this->getClassesStringElement() . $this->getDataElementsString() . '>' . $this->getText() . '</a>';
        return $html;
    }
    public function getLink()
    {
        if($this->getIsNewWindow())
            return '<a href="'.$this->getHref().'" ' . $this->getIDString() . $this->getClassesStringElement() . $this->getDataElementsString() . ' target="_blank">' . $this->getText() . '</a>';
        else
            return '<a href="'.$this->getHref().'" ' . $this->getIDString() . $this->getClassesStringElement() . $this->getDataElementsString() . '>' . $this->getText() . '</a>';

    }
}