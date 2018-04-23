<?php
/**
 * UAEL Button Module Template.
 *
 * @package UAEL
 */

?>
<?php
$classname = '';
if ( 'yes' == $settings['gf_radio_check_custom'] ) {
	$classname = '';
}

?>
<div class="uael-gf-style <?php echo 'uael-gf-check-style'; ?> elementor-clickable">
	<?php
		$title       = '';
		$description = '';
	if ( 'yes' === $settings['form_title_option'] ) {
		if ( class_exists( 'GFAPI' ) ) {
			$form        = GFAPI::get_form( absint( $settings['form_id'] ) );
			$title       = $form['title'];
			$description = $form['description'];
		}
	} elseif ( 'no' === $settings['form_title_option'] ) {
		$title       = $settings['form_title'];
		$description = $settings['form_desc'];
	} else {
		$title       = '';
		$description = '';
	}
	if ( '' !== $title ) {
	?>
	<<?php echo $settings['form_title_tag']; ?> class="uael-gf-form-title"><?php echo esc_attr( $title ); ?></<?php echo $settings['form_title_tag']; ?>>
	<?php
	}
	if ( '' !== $description ) {
	?>
	<p class="uael-gf-form-desc"><?php echo esc_attr( $description ); ?></p>
	<?php
	}
	if ( '0' == $settings['form_id'] ) {
		_e( 'Please select a Gravity Form', 'uael' );
	} elseif ( $settings['form_id'] ) {
		$ajax = ( true == $settings['form_ajax_option'] ) ? 'true' : 'false';
		echo do_shortcode( '[gravityform id=' . absint( $settings['form_id'] ) . ' ajax="' . $ajax . '" title="false" description="false" tabindex=' . $settings['form_tab_index_option'] . ']' );
	}

	?>

</div>
