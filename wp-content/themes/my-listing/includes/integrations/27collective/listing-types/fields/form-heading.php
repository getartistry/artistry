<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class FormHeadingField extends Field {

	public function field_props() {
		$this->props['type'] = 'form-heading';
		$this->props['label'] = 'Heading';
		$this->props['is_ui'] = true;
		$this->props['icon'] = 'icon-pencil-2';
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getIconField();
	}
}