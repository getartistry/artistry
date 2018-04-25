<?php
/**
 * The title bar style 1 for our theme.
 *
 * This template generates markup required for the title bar style 1
 *
 * @todo Update this template for Default Advanced Headers Style
 *
 * @package Astra Addon
 */

$show_breadcrumb       = Astra_Ext_Advanced_Headers_Loader::astra_advanced_headers_layout_option( 'breadcrumb' );
$is_breadcrumb_enabled = '';
$title                 = apply_filters( 'astra_advanced_header_title', astra_get_the_title() );
$description           = apply_filters( 'astra_advanced_header_description', get_the_archive_description() );

if ( $show_breadcrumb ) {
	$is_breadcrumb_enabled = $show_breadcrumb;
}

?>
<div class="ast-inside-advanced-header-content">
	<div class="ast-advanced-headers-layout ast-advanced-headers-layout-1" >
		<div class="ast-container">
			<div class="ast-advanced-headers-wrap">
				<?php if ( $title ) { ?>
				<h1 class="ast-advanced-headers-title">
					<?php echo $title; ?>
				</h1>
				<?php } ?>
				<?php if ( $description ) { ?>
				<div class="taxonomy-description">
					<?php echo $description; ?>
				</div>
				<?php } ?>
			</div>
	<?php if ( $is_breadcrumb_enabled ) { ?>
				<div class="ast-advanced-headers-breadcrumb">
					<?php Astra_Ext_Advanced_Headers_Markup::advanced_headers_breadcrumbs_markup(); ?>
				</div><!-- .ast-advanced-headers-breadcrumb -->
	<?php } ?>
		</div>
	</div>
</div>
