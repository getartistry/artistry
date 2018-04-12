<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class RelatedListingField extends Field {

	public function field_props() {
		$this->props['type'] = 'related-listing';
		$this->props['listing_type'] = '';
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getListingTypeField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}
}
