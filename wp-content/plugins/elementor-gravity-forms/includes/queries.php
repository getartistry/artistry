<?php
/**
 * Get Gravity Form [ if exists ]
 */

function eael_select_gravity_form_stand_alone() {

    $forms = RGFormsModel::get_forms( null, 'title' );
    foreach( $forms as $form ) {
      $options[ $form->id ] = $form->title;
    }
    return $options;

}