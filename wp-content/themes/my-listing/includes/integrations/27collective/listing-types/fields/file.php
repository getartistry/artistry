<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class FileField extends Field {

	public function field_props() {
		$this->props['type'] = 'file';
		$this->props['ajax'] = true;
		$this->props['multiple'] = false;
		$this->props['allowed_mime_types'] = new \stdClass;
		$this->props['allowed_mime_types_arr'] = [];
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();

		$this->getAllowedMimeTypesField();

		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}
}