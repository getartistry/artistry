<?php
namespace ybi\html;
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 2/26/2015
 * Time: 11:49 AM
 */

class Column extends HTMLObject {
    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var string
     */
    public $content;

    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var int
     */
    public $col_span;


    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return String RETURNDESCRIPTION
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param string $content ARGDESCRIPTION
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return int RETURNDESCRIPTION
     */
    public function getColSpan() {
        return $this->col_span;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param int $col_span ARGDESCRIPTION
     */
    public function setColSpan($col_span) {
        $this->col_span = $col_span;
    }

}