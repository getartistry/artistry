<?php
/**
 * Add new field for contact customize panel.
 *
 * Page for adding new field to contact module.
 *
 * @package    Wordpress
 * @subpackage Kassyopea
 * @since      1.1
 */

@header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );

?>
<html <?php if ( yit_ie_version() < 9 && yit_ie_version() > 0 ) {
	echo 'class="ie8"';
} ?>xmlns="http://www.w3.org/1999/xhtml" <?php do_action( 'admin_xml_ns' ); ?> <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php echo get_option( 'blog_charset' ); ?>" />
	<title><?php _e( 'Add shortcode', 'yit' ) ?></title>

	<?php

	wp_admin_css( 'wp-admin', true );

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );

	wp_enqueue_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2' . $suffix . '.js', array( 'jquery' ), '3.5.2' );
	wp_enqueue_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js', array( 'jquery', 'select2' ), WC_VERSION );

	wp_localize_script( 'wc-enhanced-select', 'wc_enhanced_select_params', array(
		'i18n_matches_1'            => _x( 'One result is available, press enter to select it.', 'enhanced select', 'woocommerce' ),
		'i18n_matches_n'            => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'woocommerce' ),
		'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'woocommerce' ),
		'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'woocommerce' ),
		'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'woocommerce' ),
		'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'woocommerce' ),
		'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'woocommerce' ),
		'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'woocommerce' ),
		'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'woocommerce' ),
		'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'woocommerce' ),
		'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'woocommerce' ),
		'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'woocommerce' ),
		'ajax_url'                  => admin_url( 'admin-ajax.php' ),
		'search_products_nonce'     => wp_create_nonce( 'search-products' ),
		'search_customers_nonce'    => wp_create_nonce( 'search-customers' )
	) );

	remove_action( 'admin_print_styles', array( 'WC_Name_Your_Price_Admin', 'add_help_tab' ), 20 );

	do_action( 'admin_print_styles' );
	do_action( 'admin_print_scripts' );
	do_action( 'admin_head' );

	?>
	<style type="text/css">

		body {
			padding: 10px;
		}

		html, body {
			background: #fff;
			height: auto;
		}

		.button {
			background: #00a0d2;
			border-color: #0073aa;
			-webkit-box-shadow: inset 0 1px 0 rgba(120, 200, 230, .5), 0 1px 0 rgba(0, 0, 0, .15);
			box-shadow: inset 0 1px 0 rgba(120, 200, 230, .5), 0 1px 0 rgba(0, 0, 0, .15);
			color: #fff;
			text-decoration: none;
			display: inline-block;
			font-size: 13px;
			line-height: 26px;
			height: 28px;
			margin: 0;
			padding: 0 10px 1px;
			cursor: pointer;
			border-width: 1px;
			border-style: solid;
			-webkit-appearance: none;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			white-space: nowrap;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			font-family: inherit;
			font-weight: inherit;
		}

		.button:focus {
			border-color: #0e3950;
			-webkit-box-shadow: inset 0 1px 0 rgba(120, 200, 230, .6), 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, .8);
			box-shadow: inset 0 1px 0 rgba(120, 200, 230, .6), 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30, 140, 190, .8);
		}

		.button:hover {
			background: #0091cd;
			border-color: #0073aa;
			-webkit-box-shadow: inset 0 1px 0 rgba(120, 200, 230, .6);
			box-shadow: inset 0 1px 0 rgba(120, 200, 230, .6);
			color: #fff;
		}

	</style>
</head>
<body>

<div class="widget-content">
	<p>
		<label for="ywqcdg_product_search"><?php _e( 'Select product', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?></label>
		<?php

		$select_args = array(
			'class'            => 'wc-product-search',
			'id'               => 'ywqcdg_product_search',
			'name'             => 'ywqcdg_product_search',
			'data-placeholder' => __( 'Search for a product&hellip;', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
			'data-allow_clear' => false,
			'data-selected'    => '',
			'data-multiple'    => false,
			'data-action'      => 'ywqcdg_json_search_products_and_variations',
			'value'            => '',
			'style'            => 'width: 100%'
		);

		yit_add_select2_fields( $select_args )

		?>
	</p>
</div>
<div class="widget-control-actions">
	<div class="alignright">
		<input
			type="submit"
			name="ywqcdg_shortcode_insert"
			id="ywqcdg_shortcode_insert"
			class="button"
			value="<?php _e( 'Insert', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?>"
		/>
	</div>
	<br class="clear">
</div>
<script type="text/javascript">

	jQuery(function ($) {

		$(document).on('click', '.button', function () {

			var code = $('#ywqcdg_product_search').val(),
				str = '',
				win = window.dialogArguments || opener || parent || top;

			if (code == '') {

				window.alert('<?php _e( 'You should select at least one product', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?>');

			} else {

				str = '[ywqcdg_shortcode id="' + code + '"]';

			}

			if (str != '') {

				win.send_to_editor(str);
				var ed;

				if (typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden()) {
					ed.setContent(ed.getContent());
				}

			}

		});

	});

</script>
</body>
</html>