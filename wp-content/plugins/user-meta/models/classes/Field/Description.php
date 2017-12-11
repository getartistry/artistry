<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handle description and rich_text field.
 *
 * @author Khaled Hossain
 *        
 * @since 1.2.0
 */
class Description extends Base
{

    private function renderRichText()
    {
        if (isset($this->field['title_position']) && 'left' == $this->field['title_position']) {
            $this->inputBefore .= '<div class="um_left_margin">';
            $this->inputAfter .= '</div>';
        }
        
        ob_start();
        $editorID = preg_replace("/[^a-z0-9 ]/", '', strtolower($this->inputID));
        wp_editor($this->fieldValue, $editorID, [
            'tinymce' => ! empty($this->readOnly) ? false : true,
            'textarea_name' => $this->inputName,
            'editor_height' => ! empty($this->field['field_height']) ? str_replace('px', '', $this->field['field_height']) : null,
            'editor_class' => ! empty($this->field['field_class']) ? $this->field['field_class'] : null,
            'editor_css' => ! empty($this->field['field_style']) ? $this->field['field_style'] : null
        ]);
        $editorOutput = $this->inputBefore . ob_get_clean() . $this->inputAfter;
        
        if (! empty($this->readOnly)) {
            $this->javascript = "jQuery('#wp-{$editorID}-editor-container textarea').attr('readonly','readonly');";
        }
        
        return ! empty($this->field['field_size']) ? "<div style=\"width:{$this->field['field_size']}\">$editorOutput</div>" : $editorOutput;
    }

    protected function renderInput()
    {
        if (('description' == $this->fieldType) && empty($this->field['rich_text'])) {
            return Html::textarea($this->fieldValue, $this->inputAttr);
        }
        
        if (! empty($this->field['use_previous_editor'])) {
            $this->inputAttr['class'] .= ' um_rich_text';
            return Html::textarea($this->fieldValue, $this->inputAttr);
        }
        
        return $this->renderRichText();
    }
}



