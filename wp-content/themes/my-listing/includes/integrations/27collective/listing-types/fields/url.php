<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class URLField extends Field {

	public function field_props() {
		$this->props['type'] = 'url';
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