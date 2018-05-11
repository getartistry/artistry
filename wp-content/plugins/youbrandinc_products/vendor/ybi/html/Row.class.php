<?php
namespace ybi\html;
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 2/26/2015
 * Time: 11:48 AM
 */

class Row extends HTMLObject {
    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $columns;

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getColumns() {
        return $this->columns;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $columns ARGDESCRIPTION
     */
    public function setColumns($columns) {
        $this->columns = $columns;
    }
    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param Column $column Column to be added to row
     */
    public function addColumn($column) {
        if(!is_array($this->columns))
            $this->columns = array($column);
        else
            $this->columns[] = $column;
    }
    /**
     * This function will takes the passed content and creates a new Column with no classes or IDs and sets the content and adds the column to the row
     *
     * @access public
     * @param String $in_content The content that should be in the column
     * @param String $in_id The CSS id element
     * @param array $in_class_arr Array of CSS classes
     * @param array $in_settings_arr Array of setting elements to be added to column
     */
    public function addColumnContent($in_content, $in_id='',$in_class_arr=array(),$in_settings_arr=array())
    {
        $Column = new Column($in_id,$in_class_arr,$in_settings_arr);
        $Column->setContent($in_content);
        $this->addColumn($Column);
    }
    public function addColumnContentCenter($in_content, $in_id='',$in_class_arr=array(),$in_settings_arr=array())
    {
        $in_class_arr[] = 'center';
        $Column = new Column($in_id,$in_class_arr,$in_settings_arr );
        $Column->setContent($in_content);
        $this->addColumn($Column);
    }


}
