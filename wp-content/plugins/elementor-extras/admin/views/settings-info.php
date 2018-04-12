<div class="wrap about-wrap ee-wrap">
	
	<div class="o-layout o-layout--middle ee-info-header">
		<div class="o-layout__item u-5/6@desktop">
			<h1><?php _e( "Introducing <strong>Elementor Extras</strong>", 'elementor-extras' ); ?> <?php echo $version; ?></h1>
			<div class="about-text"><?php _e("With the new Table widget.", 'elementor-extras' ); ?></div>
		</div>
		<div class="o-layout__item u-1/6@desktop"><img class="ee-logo" src="https://shop.namogo.com/wp-content/uploads/2017/08/Logo@2x.png" /></div>
	</div>

	<div class="feature-section">
		<h5><?php _e( "Important:", 'elementor-extras' ); ?></h5>

		<div class="meta-box-sortables ui-sortable">
			<div class="postbox">
				<div class="inside">
					<?php printf( __( 'As usual, review all your widgets before updating to a live environment to make sure everything looks good. <a href="%s">See the full changelog.</a>', 'elementor-extras' ), '#changelog'); ?>
				</div>
			</div>
		</div>
	</div>

	<h2 class="about-headline-callout"><?php _e("Add sortable tables to your design.", 'elementor-extras' ); ?></h2>
	
	<div class="feature-section o-layout">
	
		<div class="o-layout__item u-1/3@desktop">
			<img src="https://shop.namogo.com/wp-content/uploads/features/elementor-extras/settings-table-content.png" />
			<h3><?php _e( "Sortable", 'elementor-extras' ); ?></h3>
			<p><?php _e( "Transform your table header into a control for automatically sorting your rows, with data format detection.", 'elementor-extras' ); ?></p>
		</div>
		
		<div class="o-layout__item u-1/3@desktop">
			<img src="https://shop.namogo.com/wp-content/uploads/features/elementor-extras/settings-table-cells.png" />
			<h3><?php _e( "Advanced cells", 'elementor-extras' ); ?></h3>
			<p><?php _e( "Add icons, set column and row span, link them or declare them as header cell. All tables are responsive, so you don't have to worry about how they look on mobile.", 'elementor-extras' ); ?></p>
		</div>
		
		<div class="o-layout__item u-1/3@desktop">
			<img src="https://shop.namogo.com/wp-content/uploads/features/elementor-extras/settings-table-style.png" />
			<h3><?php _e( "Style", 'elementor-extras' ); ?></h3>
			<p><?php _e( 'Defining the style of your table is easy. You can constrain widths of columns, set typography, colors or remove borders and padding from first and last columns.', 'elementor-extras' ); ?></p>
		</div>
					
	</div>
	
	<h2 class="about-headline-callout"><?php _e( "Complete changelog", 'elementor-extras' ); ?></h2>
	
	<div class="feature-section">
		<a name="changelog"></a>
		<p class="about-description"><?php printf( __( "Here's the complete list of changes in %s.", 'elementor-extras' ), $version ); ?></p>
		
		<?php
			
		$items = file_get_contents( elementor_extras_get_path( 'README.txt' ) );
		$items = explode( '= ' . $version . ' =', $items );
		
		$items = end( $items );
		$items = current( explode( "\n\n", $items ) );
		$items = array_filter( array_map( 'trim', explode( "*", $items ) ) );
		
		?>
		<ul class="changelog">
		<?php foreach( $items as $item ): 
			
			$item = explode('http', $item);
				
			?>
			<li><?php echo $item[0]; ?><?php if( isset($item[1]) ): ?><a href="http<?php echo $item[1]; ?>" target="_blank">[...]</a><?php endif; ?></li>
		<?php endforeach; ?>
		</ul>			
	</div>
		
</div>