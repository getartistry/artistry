<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class LinksField extends Field {

	public function field_props() {
		$this->props['type'] = 'links';
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