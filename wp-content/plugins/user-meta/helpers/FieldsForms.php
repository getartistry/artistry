<?php
namespace UserMeta;

/**
 * Get meta_key(s) of all file fields.
 *
 * @since 1.2
 * @author Khaled Hossain
 *        
 * @return array keys
 */
function getFileMetaKeys()
{
    $fields = (new FormBase())->getAllFields();
    $metaKeys = [
        'user_avatar'
    ];
    foreach ($fields as $field) {
        if (isset($field['field_type']) && $field['field_type'] == 'file') {
            $metaKeys[] = $field['meta_key'];
        }
    }
    
    return array_unique($metaKeys);
}