<?php
/**
 * Circle progress template
 */
$perc_position   = $this->get_settings( 'percent_position' );
$labels_position = $this->get_settings( 'labels_position' );

$this->add_render_attribute( 'circle-wrap', array(
	'class'         => sprintf( 'circle-progress-wrap' ),
	'data-duration' => $this->get_settings( 'duration' ),
) );

?>
<div <?php echo $this->get_render_attribute_string( 'circle-wrap' ); ?>>
<?php
	include $this->__get_global_template( 'circle' );
	if ( 'in-circle' === $perc_position || 'in-circle' === $labels_position ) {
		echo '<div class="position-in-circle">';
		$this->__processed_item = 'in-circle';
		include $this->__get_global_template( 'counter' );
		echo '</div>';
	}

	if ( 'out-circle' === $perc_position || 'out-circle' === $labels_position ) {
		echo '<div class="position-below-circle">';
		$this->__processed_item = 'out-circle';
		include $this->__get_global_template( 'counter' );
		echo '</div>';
	}

	$this->__processed_item = false;
?>
</div>