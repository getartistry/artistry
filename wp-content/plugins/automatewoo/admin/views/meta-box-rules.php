<?php
/**
 * @var $workflow AutomateWoo\Workflow
 * @var $selected_trigger
 */

if ( ! defined( 'ABSPATH' ) ) exit;

AutomateWoo\Admin::get_view( 'rules', [ 'workflow' => $workflow ] );
