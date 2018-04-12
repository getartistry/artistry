<?php

namespace LivemeshAddons\Blocks;

class LAE_Block_Layout {

    var $row_is_open = false;

    var $columns_open = array();

    function open_row() {

        // do not open unless closed
        if ($this->row_is_open) {
            return;
        }

        $this->row_is_open = true;

        return '<div class="lae-block-row">';
    }

    function close_row() {

        $output = '';

        $this->row_is_open = false;

        $output .= '</div><!--.lae-block-row-->';

        return $output;

    }

    function open_column($column_class, $additional_class = '') {

        // return if column is already open - no nested column support for same column type
        if (in_array($column_class, $this->columns_open))
            return;

        $this->columns_open[] = $column_class;

        return '<div class="lae-block-column ' . ($additional_class !== '' ? $additional_class . ' ' : '') . $column_class . '">';

    }

    function close_column($column_class) {

        if (($index = array_search($column_class, $this->columns_open)) !== false) {

            unset($this->columns_open[$index]);

        }

        return '</div>';

    }

    function close_all_tags() {

        $output = '';

        $to_be_closed = $this->columns_open;

        foreach ($to_be_closed as $column_class) {

            $output .= $this->close_column($column_class);

        }

        if ($this->row_is_open)
            $output .= $this->close_row();

        return $output;
    }
}
