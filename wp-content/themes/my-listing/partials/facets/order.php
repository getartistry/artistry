<?php
/**
 * Handle the "Sort By" dropdown in top of search results.
 *
 * @since 1.0.0
 */

$options = $type->get_ordering_options();
$value = ! empty( $_GET['sort'] ) ? $_GET['sort' ] : false;

// Select first option if no other value is provided via url.
if ( ! $value && ! empty( $options[0] ) && ! empty( $options[0]['key'] ) ) {
    $value = $options[0]['key'];
}

$GLOBALS['c27-facets-vue-object'][ $type->get_slug() ]['sort'] = $value;
?>

<div v-show="state.activeListingType == '<?php echo esc_attr( $type->get_slug() ) ?>'">
	<a class="toggle-rating" href="#" data-toggle="dropdown" aria-expanded="false"><i class="mi sort"></i>
		<?php foreach ( $options as $option ): ?>
			<span v-show="<?php echo esc_attr( sprintf( "facets['%s']['sort']", $type->get_slug() ) ) ?> == <?php echo esc_attr( json_encode( $option['key'] ) ) ?>">
				<?php echo esc_attr( $option['label'] ) ?>
			</span>
		<?php endforeach ?>
	</a>
	<ul class="i-dropdown dropdown-menu">
		<?php foreach ( $options as $option ): ?>
			<li>
				<a href="#" @click.prevent="<?php echo esc_attr( sprintf( "facets['%s']['sort']", $type->get_slug() ) ) ?> = <?php echo esc_attr( json_encode( $option['key'] ) ) ?>; _getListings();">
					<?php echo esc_attr( $option['label'] ) ?>
				</a>
			</li>
		<?php endforeach ?>
	</ul>
</div>
