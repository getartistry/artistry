<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class EmailField extends Field {

	public function field_props() {
		$this->props['type'] = 'email';
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