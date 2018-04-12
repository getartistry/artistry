<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class TextAreaField extends Field {

	public function field_props() {
		$this->props['type'] = 'textarea';
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}
}