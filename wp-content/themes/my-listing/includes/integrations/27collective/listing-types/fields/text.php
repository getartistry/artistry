<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class TextField extends Field {

	public function field_props() {
		$this->props['type'] = 'text';
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