<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class TermSelectField extends Field {

	public function field_props() {
		$this->props['type'] = 'term-select';
		$this->props['taxonomy'] = '';
		$this->props['terms-template'] = 'multiselect';
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getTermsTemplateField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}

	public function getTermsTemplateField() { ?>
		<div class="form-group">
			<label>Template</label>
			<div class="select-wrapper">
				<select v-model="field['terms-template']">
					<option value="single-select">Term Select</option>
					<option value="multiselect">Term Multiselect</option>
					<option value="checklist">Term Checklist</option>
				</select>
			</div>
		</div>
	<?php }
}