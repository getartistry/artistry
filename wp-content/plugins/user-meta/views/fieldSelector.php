<?php
global $userMeta;

$wpFields = null;
foreach ($userMeta->getFields('field_group', 'wp_default', 'title') as $fieldKey => $fieldValue) {
    if ($fieldKey == 'blogname') {
        if (! $userMeta->isPro() || ! is_multisite())
            continue;
    }
    $wpFields .= "<div field_type='$fieldKey' class='button um_field_selecor' onclick='umNewField(this)'>$fieldValue</div>";
}

$standardFields = null;
foreach ($userMeta->getFields('field_group', 'standard', 'title') as $fieldKey => $fieldValue) {
    if (! $userMeta->isPro()) {
        $fieldData = $userMeta->getFields('key', $fieldKey);
        if ($fieldData['is_free'])
            $standardFields .= "<div field_type='$fieldKey' class='button um_field_selecor' onclick='umNewField(this)'>$fieldValue</div>";
        else
            $standardFields .= "<div field_type='$fieldKey' disabled='disabled' class='button um_field_selecor' onclick='umGetProMessage(this)'>$fieldValue</div>";
    } else {
        $standardFields .= "<div field_type='$fieldKey' class='button um_field_selecor' onclick='umNewField(this)'>$fieldValue</div>";
    }
}

$formattingFields = null;
foreach ($userMeta->getFields('field_group', 'formatting', 'title') as $fieldKey => $fieldValue) {
    if ($userMeta->isPro())
        $formattingFields .= "<div field_type='$fieldKey' class='button um_field_selecor' onclick='umNewField(this)'>$fieldValue</div>";
    else
        $formattingFields .= "<div field_type='$fieldKey' disabled='disabled' class='button um_field_selecor' onclick='umGetProMessage(this)'>$fieldValue</div>";
}

$sharedFields = null;
$fields = $userMeta->getData('fields');
if ($fields && is_array($fields)) {
    foreach ($fields as $id => $field) {
        $value = 'ID:' . $id . ' (' . $field['field_type'] . ') ' . $field['field_title'];
        $sharedFields .= "<button type='button' style='background: #f7f7f7;' class='btn btn-default um_field_selecor col-xs-12' onclick='umNewField(this)'>$value</button>";
    }
}

return array(
    'wp_default' => $wpFields,
    'standard' => $standardFields,
    'formatting' => $formattingFields,
    'shared' => $sharedFields
);