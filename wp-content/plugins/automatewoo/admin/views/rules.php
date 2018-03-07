<?php
/**
 * @var $workflow AutomateWoo\Workflow
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>


<div id="aw-rules-container"></div>



<script type="text/template" id="tmpl-aw-rules-container">

	<div class="aw-rules-container">
		<div class="aw-rule-groups"></div>
	</div>

	<div class="automatewoo-metabox-footer">
		<button type="button" class="js-add-rule-group button button-primary button-large"><?php esc_attr_e('+ Add Rule Group', 'automatewoo') ?></button>
	</div>

</script>



<script type="text/template" id="tmpl-aw-rule-groups-empty">
	<p class="aw-rules-empty-message"><?php printf( esc_attr__( 'Rules can be used to add conditional logic to workflows. Click the %s+ Add Rule Group%s button to create a rule.', 'automatewoo'), '<strong>', '</strong>' )  ?></p>
</script>


<script type="text/template" id="tmpl-aw-rule">

	<div class="automatewoo-rule automatewoo-rule--type-{{ data.rule.object.type ? data.rule.object.type : 'new' }} automatewoo-rule--compare-{{ data.rule.compare }}">

		<div class="automatewoo-rule__fields">

			<div class="aw-rule-select-container automatewoo-rule__field-container">
				<select name="{{ data.fieldNameBase }}[name]" class="js-rule-select automatewoo-field" required>

					<option value=""><?php esc_attr_e('[Select Rule]', 'automatewoo') ?></option>
					<# _.each( data.groupedRules, function( rules, group_name ) { #>
						<optgroup label="{{ group_name }}">
							<# _.each( rules, function( rule ) { #>
								<option value="{{ rule.name }}">{{ rule.title }}</option>
							<# }) #>
						</optgroup>
					<# }) #>
				</select>
			</div>


			<div class="aw-rule-field-compare automatewoo-rule__field-container">
				<select name="{{ data.fieldNameBase }}[compare]" class="automatewoo-field js-rule-compare-field" <# if ( _.isEmpty( data.rule.object.compare_types ) ) { #>disabled<# } #>>

					<# _.each( data.rule.object.compare_types, function( option, key ) { #>
						<option value="{{ key }}">{{ option }}</option>
					<# }) #>

				</select>
			</div>


			<div class="aw-rule-field-value automatewoo-rule__field-container <# if ( data.rule.isValueLoading ) { #>aw-loading<# } #>">

				<# if ( data.rule.isValueLoading ) { #>

					<div class="aw-loader"></div>

				<# } else { #>


					<# if ( data.rule.object.type === 'number' ) { #>

						<input name="{{ data.fieldNameBase }}[value]" class="automatewoo-field js-rule-value-field" type="number" required>

					<# } else if ( data.rule.object.type === 'object' ) { #>

						<?php if ( version_compare( WC()->version, '3.0', '<' ) ): ?>
							<input name="{{ data.fieldNameBase }}[value]"
									 type="hidden"
									 class="{{ data.rule.object.class }} automatewoo-field js-rule-value-field"
									 data-placeholder="{{ data.rule.object.placeholder }}"
									 data-action="{{ data.rule.object.ajax_action }}"
									 data-multiple="false">
						<?php else: ?>
							<select name="{{ data.fieldNameBase }}[value]"
									  class="{{ data.rule.object.class }} automatewoo-field js-rule-value-field"
									  data-placeholder="{{ data.rule.object.placeholder }}"
									  data-action="{{ data.rule.object.ajax_action }}"
							></select>
						<?php endif; ?>

					<# } else if ( data.rule.object.type === 'select' ) { #>

						<# if ( data.rule.object.is_single_select ) { #>
							<select name="{{ data.fieldNameBase }}[value]" class="automatewoo-field wc-enhanced-select js-rule-value-field" data-placeholder="{{{ data.rule.object.placeholder }}}">
								<# if ( data.rule.object.placeholder ) { #>
									<option></option>
								<# } #>
						<# } else { #>
							<select name="{{ data.fieldNameBase }}[value][]" multiple="multiple" class="automatewoo-field wc-enhanced-select js-rule-value-field">
						<# } #>

							<# _.each( data.rule.object.select_choices, function( option, key ) { #>
								<option value="{{ key }}">{{{ option }}}</option>
							<# }) #>

						</select>

					<# } else if ( data.rule.object.type === 'string' && ( data.rule.compare != 'blank' && data.rule.compare != 'not_blank' ) )  { #>

							<input name="{{ data.fieldNameBase }}[value]" class="automatewoo-field js-rule-value-field" type="text" required>

					<# } else if ( data.rule.object.type === 'meta' )  { #>

						<input name="{{ data.fieldNameBase }}[value][]" class="automatewoo-field js-rule-value-field" type="text" placeholder="<?php _e('key', 'automatewoo') ?>">
						<input name="{{ data.fieldNameBase }}[value][]" class="automatewoo-field js-rule-value-field" type="text" placeholder="<?php _e('value', 'automatewoo') ?>">

					<# } else if ( data.rule.object.type === 'bool' )  { #>

						<select name="{{ data.fieldNameBase }}[value]" class="automatewoo-field js-rule-value-field">
							<# _.each( data.rule.object.select_choices, function( option, key ) { #>
								<option value="{{ key }}">{{{ option }}}</option>
								<# }); #>
						</select>

					<# } else { #>

						<input class="automatewoo-field" type="text" disabled>

					<# } #>


				<# } #>



			</div>

		</div>


		<div class="automatewoo-rule__buttons">
			<button type="button" class="js-add-rule automatewoo-rule__add button"><?php _e('and', 'automatewoo') ?></button>
			<button type="button" class="js-remove-rule automatewoo-rule__remove"></button>
		</div>

	</div>

</script>



<script type="text/template" id="tmpl-aw-rule-group">
	<div class="rules"></div>
	<div class="aw-rule-group__or"><span><?php esc_attr_e( 'or', 'automatewoo')  ?></span></div>
</script>
