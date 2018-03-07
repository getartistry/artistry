<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * @class Admin_List_Table
 * @since 3.0
 */
abstract class Admin_List_Table extends \WP_List_Table {

	/** @var string - name of the table, used for classes */
	public $name;

    /** @var string  */
	public $nonce_action = 'automatewoo-report-action';

	/** @var bool */
	public $enable_search = false;

	/** @var string  */
	public $search_input_id = 'automatewoo_search_table';

	/** @var string */
	public $search_button_text;

	/** @var string */
	protected $default_param_orderby = '';

	/** @var string */
	protected $default_param_order = 'DESC';

	/** @var int */
	public $max_items;


	/**
	 * @param array|string $args
	 */
	function __construct( $args ) {
	    $this->search_button_text = __( 'Search', 'automatewoo' );
		wp_enqueue_script('automatewoo-modal');
		parent::__construct( $args );
	}


	/**
	 * Output the report
	 */
	function output_report() {
		$this->prepare_items();
		echo '<div id="poststuff" class="woocommerce-reports-wide">';
		$this->display();
		echo '</div>';
	}


	/**
	 * @param $email
	 * @return string
	 */
	function format_email( $email ) {
		$email = esc_attr( $email );
		return '<a href="mailto:'.$email.'">'.$email.'</a>';
	}


	/**
	 * @param \WP_User $user
	 * @return string
	 */
	function format_user( $user ) {
		if ( $user ) {
			$name = esc_attr( sprintf( _x( '%1$s %2$s', 'full name', 'automatewoo' ), $user->first_name, $user->last_name ) );
			$email = esc_attr( $user->user_email );
			return "$name <a href='mailto:$email'>$email</a> ";
		}
		else {
			return $this->format_blank();
		}
	}


	/**
	 * @param $email
     * @return string
	 */
	function format_guest( $email ) {
		if ( $email ) {
			$email = esc_attr( $email );
			return esc_attr( __( '[Guest]', 'automatewoo' ) ) . ' <a href="mailto:'.$email.'">'.$email.'</a>';
		}
		else {
           return $this->format_blank();
		}
	}


	/**
	 * @return array
	 */
	protected function get_table_classes() {
		return [ 'automatewoo-list-table', 'automatewoo-list-table--' . $this->name, 'widefat', 'fixed', 'striped', $this->_args['plural'] ];
	}


	/**
	 * @param $date
	 * @param bool $is_gmt
	 * @return string
	 */
	function format_date( $date, $is_gmt = true ) {

		$output = '';

		if ( $date instanceof \DateTime ) {
			$date = $date->getTimestamp();
		}

		if ( $date ) {
			$output = Format::datetime( $date, false, $is_gmt );
		}

		return $output ? $output : $this->format_blank();
	}


	/**
	 * @param $workflow Workflow|false
     * @return string
	 */
	function format_workflow_title( $workflow ) {

		if ( ! $workflow || ! $workflow->exists ) {
		    return $this->format_blank();
		}
		else {
		    $return = '<a href="' . get_edit_post_link( $workflow->get_id() ) . '"><strong>' . $workflow->get_title() . '</strong></a>';

			if ( Language::is_multilingual() ) {
				$return .= ' [' . $workflow->get_language() . ']';
			}

			return $return;
		}

	}


	/**
	 * @return string
	 */
	function format_blank() {
		return '-';
	}



	protected function extra_tablenav( $which ) {

	    if ( $which !== 'top' ) {
	        return;
        }

	    ?>
		 <?php if ( method_exists( $this, 'filters' ) ): ?>
            <div style="display: inline-block">
					<?php $this->filters(); ?>
					<?php submit_button( __( 'Filter' ), 'button', 'submit', false ); ?>
            </div>
		 <?php endif ?>
	    <?php
    }


    function output_form_open() {
	    echo '<form class="automatewoo-list-table-form" method="get">';
	    Admin::get_hidden_form_inputs_from_query([ 'page', 'section', 'tab' ] );
	    wp_nonce_field( $this->nonce_action, '_wpnonce', false );
    }


    function output_form_close() {
	    echo '</form>';
    }


	/**
	 * Display the table plus the form elements
	 */
	function display() {
		$this->output_form_open();

		if ( $this->enable_search ) {
		    $this->output_search();
        }

		$this->output_table();
		$this->output_form_close();
	}


	function output_search() {
	    $this->search_box( $this->search_button_text, $this->search_input_id );
    }


	/**
	 * Output the table only
	 */
	function output_table() {
	    parent::display();
    }


	function output_workflow_filter() {

		$workflow_id = '';
		$workflow_name = '';

		if ( ! empty( $_GET['_workflow'] ) ) {
			$workflow_id = absint( $_GET['_workflow'] );
			$workflow_name = get_the_title( $workflow_id );
		}

		$placeholder = __( 'Search for a workflow&hellip;', 'automatewoo' );
		$ajax = 'aw_json_search_workflows';

		?>
		<?php if ( version_compare( WC()->version, '3.0', '<' ) ): ?>

			<input type="hidden" class="wc-product-search" style="width:203px;" name="_workflow"
					 data-placeholder="<?php echo $placeholder; ?>"
					 data-selected="<?php echo wp_kses_post( $workflow_name ); ?>"
					 value="<?php echo $workflow_id; ?>"
					 data-action="<?php echo $ajax ?>"
					 data-allow_clear="true">
		<?php else: ?>

			<select class="wc-product-search" style="width:203px;" name="_workflow"
					  data-placeholder="<?php echo $placeholder; ?>"
					  data-action="<?php echo $ajax ?>"
					  data-allow_clear="true"
			>
				<?php
				if ( $workflow_id ) {
					echo '<option value="' . $workflow_id . '"' . selected( true, true, false ) . '>' . wp_kses_post( $workflow_name ) . '</option>';
				}
				?>
			</select>
		<?php endif; ?>


		<?php

	}


	function output_customer_filter() {
		$customer_string = '';

		if ( $customer_id = absint( aw_request('filter_customer' ) ) ) {
			$customer = Customer_Factory::get( $customer_id );
			$customer_string = esc_html( $customer->get_full_name() ) . ' (' . esc_html( $customer->get_email() ) . ')';
		}

		$placeholder = esc_attr__( 'Search for a customer&hellip;', 'automatewoo' );
		$ajax = 'aw_json_search_customers';

		?>

		<?php if ( version_compare( WC()->version, '3.0', '<' ) ): ?>

			<input type="hidden" class="wc-product-search" name="filter_customer"
			       data-placeholder="<?php echo $placeholder; ?>"
			       data-selected="<?php echo htmlspecialchars( $customer_string ); ?>"
			       value="<?php echo $customer_id; ?>"
			       data-action="<?php echo $ajax ?>"
			       data-allow_clear="true"
			>

		<?php else: ?>

			<select class="wc-product-search" style="width:203px;" name="filter_customer"
			        data-placeholder="<?php echo $placeholder; ?>"
			        data-action="<?php echo $ajax ?>"
			        data-allow_clear="true"
			>
				<?php if ( $customer_id ) { echo '<option value="' . $customer_id . '"' . selected( true, true, false ) . '>' . wp_kses_post( $customer_string ) . '</option>'; } ?>
			</select>

		<?php endif; ?>
		<?php
	}




	/**
     * Override nonce used in this table, to use nonces declared in controllers
     *
	 * @param string $which
	 */
	protected function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			//wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}
		?>
       <div class="tablenav <?php echo esc_attr( $which ); ?>">

			 <?php if ( $this->has_items() ): ?>
                <?php if ( $this->get_bulk_actions() ): ?>
                  <div class="alignleft actions bulkactions">
                      <?php $this->bulk_actions( $which ); ?>
                  </div>
               <?php endif; ?>
			 <?php endif;
			 $this->extra_tablenav( $which );
			 $this->pagination( $which );
			 ?>

           <br class="clear" />
       </div>
		<?php
	}


	/**
	 * @return string
	 */
	protected function get_param_search() {
	    return Clean::string( aw_request('s' ) );
    }


	/**
	 * @return string
	 */
	protected function get_param_orderby() {
		return aw_request('orderby' ) ? Clean::string( aw_request('orderby' ) ) : $this->default_param_orderby;
	}


	/**
	 * @return string
	 */
	protected function get_param_order() {
		return aw_request('order' ) ? Clean::string( aw_request('order' ) ) : $this->default_param_order;
	}



}
