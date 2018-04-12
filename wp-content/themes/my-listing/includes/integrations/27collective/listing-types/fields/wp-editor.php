<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class WPEditorField extends Field {

	public function field_props() {
		$this->props['type'] = 'wp-editor';
		$this->props['editor-controls'] = 'basic';
		$this->props['allow-shortcodes'] = false;
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getEditorControlsField();
		$this->getAllowShortcodesField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}

	protected function getEditorControlsField() { ?>
		<div class="form-group">
			<label>Editor Controls</label>
			<label><input type="radio" v-model="field['editor-controls']" value="basic"> Basic Controls</label>
			<label><input type="radio" v-model="field['editor-controls']" value="advanced"> Advanced Controls</label>
			<label><input type="radio" v-model="field['editor-controls']" value="all"> All Controls</label>
		</div>
	<?php }

	protected function getAllowShortcodesField() { ?>
		<div class="form-group">
			<label></label>
			<label><input type="checkbox" v-model="field['allow-shortcodes']"> Allow shortcodes in the editor?</label>
		</div>
	<?php }
}