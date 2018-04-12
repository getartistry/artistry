<?php

namespace CASE27\Integrations\ListingTypes\Fields;

use \CASE27\Integrations\ListingTypes\Designer;

abstract class Field implements \JsonSerializable {

	protected $props = [
			   'type'                => 'text',
			   'slug'                => 'custom-field',
			   'default'             => '',
			   'reusable'            => true,
			   'priority'            => 10,
			   'is_custom'           => true,

			   'label'               => 'Custom Field',
			   'label_l10n'          => ['locale' => 'en_US'],
			   'default_label'       => 'Custom Field',

			   'placeholder'         => '',
			   'placeholder_l10n'    => ['locale' => 'en_US'],
			   'description'         => '',
			   'description_l10n'    => ['locale' => 'en_US'],

			   'required'            => false,
			   'show_in_admin'       => true,
			   'show_in_submit_form' => true,

			   'conditional_logic' => false,
			   'conditions' => [
			   		[
			   			[
			   				'key' => '__listing_package',
			   				'compare' => '==',
			   				'value' => '',
			   			]
			   			// And...
			   		]
			   		// Or...
			   ],
			  ];

	public static $store = null;
	public $listing = null;

	public function __construct( $props = [], $listing = null ) {
		$this->field_props();
		$this->set_props( $props );
		$this->listing = $listing;

		if ( ! self::$store ) {
			self::$store = Designer::$store;
		}
	}

	abstract protected function render();

	abstract protected function field_props();

	final public function print_options() {
		ob_start(); ?>
		<div class="field-settings-wrapper" v-if="field.type == '<?php echo esc_attr( $this->props['type'] ) ?>'">
			<?php $this->render(); ?>
			<?php $this->visibility() ?>
		</div>
		<?php return ob_get_clean();
	}

	public function set_props( $props = [] ) {
		foreach ($props as $name => $value) {
			if ( isset( $this->props[ $name ] ) ) {
				$this->props[ $name ] = $value;
			}
		}
	}

	public function get_props() {
		return $this->props;
	}

	public function jsonSerialize() {
		return $this->props;
	}

	protected function getLabelField() { ?>
		<div class="form-group">
			<label>Label</label>
			<input type="text" v-model="field.label" @input="fieldsTab().setKey(field, field.label)">

			<?php c27()->get_partial('admin/input-language', ['object' => 'field.label_l10n']) ?>
		</div>
	<?php }

	protected function getKeyField() { ?>
		<div class="form-group" v-if="field.is_custom">
			<label>Key</label>
			<input type="text" v-model="field.slug" @input="fieldsTab().setKey(field, field.slug)">
		</div>
	<?php }

	protected function getPlaceholderField() { ?>
		<div class="form-group">
			<label>Placeholder</label>
			<input type="text" v-model="field.placeholder">

			<?php c27()->get_partial('admin/input-language', ['object' => 'field.placeholder_l10n']) ?>
		</div>
	<?php }

	protected function getDescriptionField() { ?>
		<div class="form-group">
			<label>Description</label>
			<input type="text" v-model="field.description">

			<?php c27()->get_partial('admin/input-language', ['object' => 'field.description_l10n']) ?>
		</div>
	<?php }

	protected function getIconField() { ?>
		<div class="form-group">
			<label>Icon</label>
			<iconpicker v-model="field.icon"></iconpicker>
		</div>
	<?php }

	protected function getRequiredField() { ?>
		<div class="form-group full-width">
			<label><input type="checkbox" v-model="field.required" :disabled="field.slug == 'job_title' || field.slug == 'job_description'"> Required field</label>
		</div>
	<?php }

	protected function getMultipleField() { ?>
		<div class="form-group full-width">
			<label><input type="checkbox" v-model="field.multiple"> Multiple?</label>
		</div>
	<?php }

	protected function getShowInSubmitFormField() { ?>
		<div class="form-group full-width">
			<label><input type="checkbox" v-model="field.show_in_submit_form" :disabled="field.slug == 'job_title' || field.slug == 'job_description'"> Show in submit form</label>
		</div>
	<?php }

	protected function getShowInAdminField() { ?>
		<div class="form-group full-width">
			<label><input type="checkbox" v-model="field.show_in_admin" :disabled="field.slug == 'job_title' || field.slug == 'job_description'"> Show in admin edit page</label>
		</div>
	<?php }

	protected function getOptionsField() { ?>
		<div class="form-group full-width options-field">
			<hr>
			<label>Options</label>

			<div class="form-group" v-for="(value, key, index) in field.options" v-show="!state.fields.editingOptions">
				<input type="text" v-model="field.options[key]" disabled="disabled">
			</div>

			<div v-show="!state.fields.editingOptions && !Object.keys(field.options).length">
				<small><em>No options added yet.</em></small>
			</div>

			<textarea
			id="custom_field_options"
			v-show="state.fields.editingOptions"
			placeholder="Add each option in a new line."
			@keyup="fieldsTab().editFieldOptions($event, field)"
			cols="50" rows="7">{{ Object.keys(field.options).map(function(el) { return field.options[el]; }).join('\n') }}</textarea>
			<small v-show="state.fields.editingOptions"><em>Put each option in a new line.</em></small>
			<br><br v-show="state.fields.editingOptions || Object.keys(field.options).length">
			<button @click.prevent="state.fields.editingOptions = !state.fields.editingOptions;" class="btn btn-primary">{{ state.fields.editingOptions ? 'Save Options' : 'Add/Edit Options' }}</button>
		</div>
	<?php }

	protected function getAllowedMimeTypesField() { ?>
		<div class="form-group full-width" v-if="['job_logo', 'job_cover', 'job_gallery'].indexOf(field.slug) <= -1">
			<label>Allowed file types</label>
			<select multiple="multiple" v-model="field.allowed_mime_types_arr" @change="fieldsTab().editFieldMimeTypes($event, field)">
				<?php foreach ( self::$store['mime-types'] as $extension => $mime ): ?>
					<option value="<?php echo "{$extension} => {$mime}" ?>"><?php echo $mime ?></option>
				<?php endforeach ?>
			</select>
			<br><br>
			<label><input type="checkbox" v-model="field.multiple"> Allow multiple files?</label>
		</div>
	<?php }

	protected function getListingTypeField() { ?>
		<div class="form-group full-width">
			<label>Related Listing Type</label>
			<div class="select-wrapper">
				<select v-model="field.listing_type">
					<?php foreach ( self::$store['listing-types'] as $listing_type ): ?>
						<option value="<?php echo $listing_type->post_name ?>"><?php echo $listing_type->post_title ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	<?php }

	public function getAllowedProductTypesField() { ?>
		<div class="form-group">
			<label>Allowed product types</label>
			<select multiple="multiple" v-model="field['product-type']">
				<?php foreach ( self::$store['product-types'] as $type => $label ): ?>
					<option value="<?php echo esc_attr( $type ) ?>"><?php echo $label ?></option>
				<?php endforeach ?>
			</select>
			<p class="form-description">Leave empty for all</p>
		</div>
	<?php }

	protected function getFormatField() { ?>
		<div class="form-group full-width">
			<label>Format</label>
			<div class="select-wrapper">
				<select v-model="field.format">
					<option value="date">Date</option>
					<option value="datetime">Date + Time</option>
				</select>
			</div>
		</div>
	<?php }

	protected function getMinField() { ?>
		<div class="form-group">
			<label>Minimum value</label>
			<input type="number" v-model="field.min" step="any">
		</div>
	<?php }

	protected function getMaxField() { ?>
		<div class="form-group">
			<label>Maximum value</label>
			<input type="number" v-model="field.max" step="any">
		</div>
	<?php }

	protected function getStepField() { ?>
		<div class="form-group">
			<label>Step size</label>
			<input type="number" v-model="field.step" step="any">
		</div>
	<?php }

	protected function visibility() { ?>
		<div class="field-visibility" v-show="field.slug != 'job_title' && field.slug != 'job_description'">
			<div class="form-group full-width">
				<label><input type="checkbox" v-model="field.conditional_logic"> Enable package visibility</label>
			</div>

			<div class="visibility-rules" v-show="field.conditional_logic">
				<label>Show this field if</label>
				<p></p>
				<div class="conditions">
					<div class="condition-group" v-for="conditionGroup, groupKey in field.conditions" v-if="conditionGroup.length">
						<div class="or-divider">
							or
						</div>
						<div class="condition" v-for="condition in conditionGroup">
							<div class="form-group">
								<select v-model="condition.key">
									<option value="__listing_package">Listing Package</option>
								</select>
							</div>

							<div class="form-group">
								<!-- <div class="select-wrapper"> -->
									<select v-model="condition.compare">
										<option value="==">is equal to</option>
										<!-- <option value="!=">is not equal to</option> -->
									</select>
								<!-- </div> -->
							</div>

							<div class="form-group">
								<div class="select-wrapper">
									<select v-model="condition.value">
										<option v-for="package in settings.packages.used" :value="package.package">{{ packages().getPackageTitle(package) }}</option>
									</select>
								</div>
							</div>

							<span class="actions" @click="conditions().deleteConditionGroup(conditionGroup, field)">
								<span><i class="mi close"></i></span>
							</span>
						</div>
					</div>

					<button class="btn btn-primary" @click.prevent="conditions().addOrCondition(field)">Add Rule</button>
					<!-- <pre>{{ field.conditions }}</pre> -->
				</div>
			</div>
		</div>
	<?php }
}
