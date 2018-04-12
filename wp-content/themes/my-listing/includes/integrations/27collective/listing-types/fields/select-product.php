<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class SelectProductField extends Field {

	public function field_props() {
		$this->props['type'] = 'select-product';
		$this->props['product-type'] = [];
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getAllowedProductTypesField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}
}