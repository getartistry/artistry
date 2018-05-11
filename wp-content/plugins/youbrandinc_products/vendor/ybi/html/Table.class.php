<?php
namespace ybi\html;
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 2/26/2015
 * Time: 11:47 AM
 */

class Table extends HTMLObject {
    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $title_row;

    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $rows;

    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $row_count;


    function __construct($id='', $classes=array(),$settings=array()) {
        parent::__construct($id, $classes,$settings);
        $this->setRowCount(0);
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getTitleRow() {
        return $this->title_row;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param Row $titleRow A row
     */
    public function setTitleRow($titleRow) {
        $this->title_row = $titleRow;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getRows() {
        return $this->rows;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $rows ARGDESCRIPTION
     */
    public function setRows($rows) {
        $this->rows = $rows;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getRowCount() {
        return $this->row_count;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $rows ARGDESCRIPTION
     */
    public function setRowCount($count) {
        $this->row_count = $count;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $rows ARGDESCRIPTION
     */
    public function addRowCount() {
        $this->row_count++;
    }


    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $rows ARGDESCRIPTION
     */
    public function addRow($row) {
        if(!is_array($this->rows))
            $this->rows = array($row);
        else
            $this->rows[] = $row;

        $this->addRowCount();
    }

    public function getFullTableHTML()
    {
        $html = '<table '.$this->getIDString().' class="'.$this->getClassesString().'" cellspacing="0">';


        if(isset($this->title_row))
        {
            $title_row = $this->title_row;
            $html .= '<thead '.$title_row->getIDString().' class="'.$title_row->getClassesString().'">';
            foreach($title_row->getColumns() as $column)
            {
                $colspan_html = '';
                if($column->getColSpan() > 1) // if colspan is greater than 1 we set it.
                    $colspan_html = ' colspan="'.$column->getColSpan().'"';

                $html .= '<th '.$column->getIDString().' class="'.$column->getClassesString().'"'.$colspan_html.'>' . $column->getContent() . '</th>';
            }
            $html .= '</thead>';

        }
        $html .= $this->getBodyRowsHTML();
        $html .= '</table>';


        return $html;

    }
    public function getBodyRowsHTML()
    {
        $do_alternate = false;
        if(isset($this->settings['alternate']))
            $do_alternate = $this->settings['alternate'];

        $tbody_class = '';
        if($this->getSettingValue('tbody_class'))
            $tbody_class = ' class="'.$this->settings['tbody_class'] .'"';

        $i=0;
        $html = '<tbody'.$tbody_class.'>';
        foreach($this->getRows() as $row)
        {
            //getClassesString
            //$html = '<tr class=" scrape_row_' . $i . $alternate .'">';
            $alternate = '';
            if($do_alternate)
                if(0 != $i % 2): $alternate = ' alternate'; endif;



            $html .= '<tr '.$row->getIDString().' class="'.$row->getClassesString(). $alternate.'">';
            foreach($row->getColumns() as $column)
            {
                $colspan_html = '';
                if($column->getSettingValue('colspan')) // if colspan is greater than 1 we set it.
                    $colspan_html = ' colspan="'.$column->getSettingValue('colspan').'"';

                $class_string = '';
                if($column->getClassesString() != '')
                    $class_string = ' class="'.$column->getClassesString().'"';

                $html .= '<td '.$column->getIDString().$class_string . $colspan_html.'>' . $column->getContent() . '</td>';

            }

            $html .= '</tr>';
            //$this->addRowCount(); // now we add a row for the count
            $i++;
        }
        $html .= '</tbody>';
        return $html;
    }

}