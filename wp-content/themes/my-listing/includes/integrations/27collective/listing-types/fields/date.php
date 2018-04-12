<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class DateField extends Field {

	public function field_props() {
		$this->props['type'] = 'date';
		$this->props['format'] = 'date';
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getFormatField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}
}