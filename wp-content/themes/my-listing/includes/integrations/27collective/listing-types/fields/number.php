<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class NumberField extends Field {

	public function field_props() {
		$this->props['type'] = 'number';
		$this->props['min']  = '';
		$this->props['max']  = '';
		$this->props['step'] = 1;
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();

		$this->getMinField();
		$this->getMaxField();
		$this->getStepField();

		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}
}