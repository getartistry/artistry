<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class RadioField extends Field {

	public function field_props() {
		$this->props['type'] = 'radio';
		$this->props['options'] = new \stdClass; // when encoded to json, it needs to be {} instead of [].
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
		$this->getOptionsField();
	}
}
