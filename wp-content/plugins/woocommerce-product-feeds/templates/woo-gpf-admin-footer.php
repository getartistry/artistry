	</table>

	{include_variations}

	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.woocommerce_gpf_field_selector').change(function(){
				group = jQuery(this).parent('.woocommerce_gpf_field_selector_group');
				defspan = group.children('div');
				defspan.slideToggle('fast');
			});
		});
	</script>
</div>