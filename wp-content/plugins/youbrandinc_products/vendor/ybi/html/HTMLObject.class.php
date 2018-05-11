<?php
namespace ybi\html;
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 2/26/2015
 * Time: 11:44 AM
 */

class HTMLObject {
    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $id;

    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $classes;

    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $settings;
    public $data_elements;

    public function __construct($id='', $classes=array(),$settings=array(),$data_elements=array())
    {
        $this->id = $id;
        $this->classes = $classes;
        $this->settings = $settings;
        $this->data_elements = $data_elements;
    }



    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getId() {
        return $this->id;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $id ARGDESCRIPTION
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $classes ARGDESCRIPTION
     */
    public function setClasses($classes) {
        $this->classes = $classes;
    }

    public function getClassesString()
    {
        $html = '';
        $i=0;
        foreach($this->getClasses() as $class_name)
        {
            if($i > 0)
                $html .= ' ';

            $html .= $class_name;
            $i++;
        }
        return $html;
    }

    public function getClassesStringElement()
    {
        $html = 'class="';
        $i=0;
        foreach($this->getClasses() as $class_name)
        {
            if($i > 0)
                $html .= ' ';

            $html .= $class_name;
            $i++;
        }
        $html .= '"';
        return $html;
    }

    public function getDataElementsString()
    {
        $html = '';
        $i = 0;
        foreach($this->data_elements as $key => $value) {
            if($i>0)
                $html .= ' ';

            $html .= $key.'="'.$value.'"';
            $i++;
        }
        return $html;
    }
    public function getIDString()
    {
        $html = '';
        if(isset($this->id) && $this->id != '')
            $html = ' id="'.$this->id.'"';

        return $html;
    }
    public function getSettingValue($in_setting)
    {
        if(isset($this->settings))
        {
            if(array_key_exists($in_setting,$this->settings))
                return $this->settings[$in_setting];
            else
                return false;

        }
        else
            return false;
    }

}