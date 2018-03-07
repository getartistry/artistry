<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @var Workflow $workflow
 */

$option_base = 'aw_workflow_data[workflow_options]';

?>

<table class="automatewoo-table">

    <tr class="automatewoo-table__row">
        <td class="automatewoo-table__col">

            <label class="automatewoo-label"><?php _e( 'Timing', 'automatewoo' ) ?> <?php echo Admin::help_link( Admin::get_docs_link( 'timing', 'workflow-edit' ) ) ?></label>

			  <?php
			  $field = new Fields\Select( false );
			  $field
				  ->set_name_base( $option_base )
				  ->set_name('when_to_run')
				  ->set_options([
					  'immediately' => __( 'Run immediately', 'automatewoo' ),
					  'delayed' => __( 'Delayed', 'automatewoo' ),
					  'scheduled' => __( 'Scheduled', 'automatewoo' ),
					  'fixed' => __( 'Fixed', 'automatewoo'),
					  'datetime' => __( 'Schedule with a variable', 'automatewoo')
				  ])
				  ->add_data_attr( 'automatewoo-bind', 'timing' )
				  ->render( $workflow ? $workflow->get_timing_type() : '' );
			  ?>

        </td>
    </tr>


    <tr class="automatewoo-table__row" data-automatewoo-show="timing=datetime">
        <td class="automatewoo-table__col">

            <label class="automatewoo-label"><?php _e( 'Variable', 'automatewoo' ) ?></label>

			  <?php
			  $field = new Fields\Text_Area();
			  $field
				  ->set_rows(3)
				  ->set_name_base( $option_base )
				  ->set_name( 'queue_datetime' )
				  ->add_classes( 'automatewoo-field--monospace' )
				  ->add_extra_attr( 'spellcheck', 'false' )
				  ->set_placeholder( __('e.g. {{ subscription.next_payment_date | modify: -1 day }}', 'automatewoo') )
				  ->render( $workflow ? $workflow->get_option('queue_datetime') : '' );
			  ?>

        </td>
    </tr>


    <tr class="automatewoo-table__row" data-automatewoo-show="timing=scheduled">
        <td class="automatewoo-table__col">

            <label class="automatewoo-label"><?php _e( 'Scheduled time', 'automatewoo' ) ?> <span class="automatewoo-label__extra"><?php _e( '(24hr)', 'automatewoo' ) ?></span></label>

			  <?php

			  $options = [];
			  $minute_interval = 15;

			  for ( $hours = 0; $hours < 24; $hours++ ) {

				  for ( $min = 0; $min < 60 / $minute_interval; $min++ ) {
					  $options[] = zeroise( $hours, 2 ) . ':' . zeroise( $min * $minute_interval, 2 );
				  }
			  }

			  $options = array_combine( $options, $options );

			  $field = new Fields\Select( false );
			  $field->set_name_base( $option_base );
			  $field->set_default( '09:00' );
			  $field->set_name( 'scheduled_time' );
			  $field->set_options($options);
			  $field->render( $workflow ? $workflow->get_scheduled_time() : '' );
			  ?>

        </td>
    </tr>


    <tr class="automatewoo-table__row" data-automatewoo-show="timing=scheduled">
        <td class="automatewoo-table__col">

            <label class="automatewoo-label"><?php _e( 'Scheduled days', 'automatewoo' ) ?> <span class="automatewoo-label__extra"><?php _e( '(optional)', 'automatewoo' ) ?></span></label>

			  <?php

			  $options = [];

			  for ( $day = 1; $day <= 7; $day++ ) {
				  $options[$day] = Format::weekday( $day );
			  }

			  $field = new Fields\Select( false );
			  $field->set_name_base( $option_base );
			  $field->set_name( 'scheduled_day' );
			  $field->set_placeholder( __( '[Any day]', 'automatewoo' ) );
			  $field->set_multiple();
			  $field->set_options( $options );
			  $field->render( $workflow ? $workflow->get_scheduled_days() : '' );

			  ?>

        </td>
    </tr>


    <tr class="automatewoo-table__row" data-automatewoo-show="timing=delayed|scheduled">
        <td class="automatewoo-table__col">

            <div class="field-cols">

                <div class="automatewoo-label" data-automatewoo-show="timing=delayed"><?php _e( 'Length of the delay', 'automatewoo' ) ?></div>

                <div class="automatewoo-label" data-automatewoo-show="timing=scheduled"><?php _e( 'Minimum wait', 'automatewoo' ) ?>
                    <span class="automatewoo-label__extra"><?php _e( '(optional)', 'automatewoo' ) ?></span>
                </div>

                <div class="col-1">
						 <?php
						 $field = new Fields\Number();
						 $field
							 ->set_name_base( $option_base )
							 ->set_name('run_delay_value')
							 ->set_min( '0' )
							 ->add_extra_attr( 'step', 'any' )
							 ->render( $workflow ? $workflow->get_option('run_delay_value') : '' );
						 ?>
                </div>

                <div class="col-2">
						 <?php
						 $field = new Fields\Select( false );
						 $field->set_name_base( $option_base );
						 $field->set_name('run_delay_unit');
						 $field->set_options([
							 'h' => __('Hours', 'automatewoo'),
							 'm' => __('Minutes', 'automatewoo'),
							 'd' => __('Days', 'automatewoo'),
							 'w' => __('Weeks', 'automatewoo')
						 ]);
						 $field->render( $workflow ? $workflow->get_option('run_delay_unit') : '' );
						 ?>
                </div>
            </div>


        </td>
    </tr>


    <tr class="automatewoo-table__row" data-automatewoo-show="timing=fixed">
        <td class="automatewoo-table__col">

            <div class="field-cols">

                <label class="automatewoo-label"><?php _e( 'Date', 'automatewoo' ) ?>
                    <span class="automatewoo-label__extra"><?php _e( '(24 hour time)', 'automatewoo' ) ?></span>
                </label>

                <div class="col-1">
						 <?php
						 $field = new Fields\Date();
						 $field
							 ->set_name_base( $option_base )
							 ->set_name( 'fixed_date' )
							 ->render( $workflow ? $workflow->get_option('fixed_date') : '' );
						 ?>
                </div>

                <div class="col-2">
                    <div class="automatewoo-time-field-group">
                        <?php

                        if ( $workflow && $workflow->get_option('fixed_time') ) {
                            $value = Clean::recursive ( (array) $workflow->get_option('fixed_time') );
                        }
                        else {
                            $value = ['', ''];
                        }

						 $field = new Fields\Number();
						 $field
							 ->set_name_base( $option_base )
							 ->set_name('fixed_time')
							 ->set_min( 0 )
							 ->set_max(23)
                             ->set_multiple()
                             ->set_placeholder( _x( 'HH', 'time field', 'automatewoo' ) )
							 ->render( $value[0] );

						 echo '<div class="automatewoo-time-field-group__sep">:</div>';

						 $field = new Fields\Number();
						 $field
							 ->set_name_base( $option_base )
							 ->set_name('fixed_time')
							 ->set_min( 0 )
							 ->set_max(59)
                             ->set_multiple()
                             ->set_placeholder( _x( 'MM', 'time field', 'automatewoo' ) )
							 ->render( $value[1] );
                        ?>
                    </div>
                </div>
            </div>

        </td>
    </tr>

</table>
