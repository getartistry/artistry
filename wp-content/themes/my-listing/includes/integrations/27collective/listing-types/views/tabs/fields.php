<div class="tab-content">
	<input type="hidden" v-model="fields_json_string" name="case27_listing_type_fields">

	<h3 class="section-title">
		Select or create fields for this listing type
		<span class="subtitle">Need help? Read the <a href="https://27collective.net/files/mylisting/docs/#listing-types" target="_blank">documentation</a> or open a ticket in our <a href="https://helpdesk.27collective.net/" target="_blank">helpdesk</a>.</span>
	</h3>

	<div class="fields-wrapper">
		<div class="fields-column left">
			<h4>Used fields <span>Click on a field to edit. Drag & Drop to reorder.</span></h4>

			<draggable v-model="fields.used" :options="{group: 'listing-fields', animation: 100, handle: 'h5'}" :move="onFieldMove" @start="drag=true" @end="drag=false" class="fields-draggable used-fields" :class="	drag ? 'active' : ''">
				<div v-for="field in fields.used" :class="'field-wrapper-type-' + field.type + ' field-wrapper-name--' + field.slug">
					<div class="field-wrapper" :class="'field--' + field.slug" v-if="field">
						<div class="field toggleable" :class="'field-type-' + field.type + ' ' + (field === state.fields.active ? 'open' : '')">
							<h5 @click="state.fields.active = ( field !== state.fields.active ) ? field : null">
								<span class="prefix">{{ field === state.fields.active ? '-' : '+' }}</span>
								{{field.label}}
								<small v-show="!field.is_custom">({{ field.default_label ? field.default_label : field.label }})</small>
								<small v-show="field.is_custom">({{capitalize( field.type )}})</small>
								<span class="actions">
									<span title="Delete this field" @click.prevent="fieldsTab().deleteField(field)" v-show="field.slug != 'job_title' && field.slug != 'job_description'"><i class="mi delete"></i></span>
									<span title="This field cannot be deleted" v-show="field.slug == 'job_title' || field.slug == 'job_description'"><i class="icon-lock-1"></i>&nbsp;</span>
								</span>
							</h5>
							<div class="edit">

								<?php foreach ($designer->fields as $field): ?>

									<?php echo $field->print_options() ?>

								<?php endforeach ?>

							</div>
						</div>
					</div>
				</div>
			</draggable>
			<div class="placeholder-fields">
				<div class="field-wrapper">
					<div class="field"><h5>Placeholder field</h5></div>
					<div class="field"><h5>Placeholder field</h5></div>
					<div class="field"><h5>Placeholder field</h5></div>
					<div class="field"><h5>Placeholder field</h5></div>
				</div>
			</div>
		</div>

		<div class="fields-column right">
			<h4>All available fields</h4>

			<draggable v-model="fields.available" :options="{group: 'listing-fields', animation: 100}" @start="drag=true" @end="drag=false" class="fields-draggable available-fields" :class="drag ? 'active' : ''"	>
				<div v-for="field in fields.available">
					<div class="field" :class="'field-type-' + field.type">
						<h5><span title="Drag to add" class="drag-to-add" @click.prevent="fieldsTab().useField(field)"><i class="mi compare_arrows"></i></span>{{field.label}}</h5>
					</div>
				</div>
			</draggable>

			<div class="form-group field add-new-field">
				<label>Create a custom field</label>
				<div class="select-wrapper">
					<select v-model="state.fields.new_field_type">
						<optgroup label="Direct Input">
							<option value="text">Text</option>
							<option value="textarea">Text Area</option>
							<option value="wp-editor">WP Editor</option>
							<option value="password">Password</option>
							<option value="date">Date</option>
							<option value="number">Number</option>
							<option value="url">URL</option>
							<option value="email">Email</option>
						</optgroup>
						<optgroup label="Choices">
							<option value="select">Select</option>
							<option value="multiselect">Multiselect</option>
							<option value="checkbox">Checkbox</option>
							<option value="radio">Radio Buttons</option>
							<!-- @todo: <option value="related-listing">Related Listing Select</option> -->
							<option value="select-product">Product Select</option>
							<option value="select-products">Products Multiselect</option>
						</optgroup>
						<optgroup label="Form UI">
							<option value="form-heading">Heading</option>
						</optgroup>
						<optgroup label="Others">
							<option value="file">File</option>
						</optgroup>
					</select>
				</div>

				<button class="btn btn-primary pull-right" @click.prevent="fieldsTab().addField()">Create</button>
			</div>
		</div>
	</div>
</div>

<!-- <pre>{{ fields.used }}</pre> -->
