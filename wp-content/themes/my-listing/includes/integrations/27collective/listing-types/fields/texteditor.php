<?php

namespace CASE27\Integrations\ListingTypes\Fields;

/**
 * A field type that allows switching
 * between a normal textarea and a WP Editor.
 *
 * @since 1.5.1
 */
class TextEditorField extends WPEditorField {

	public function field_props() {
		parent::field_props();

		$this->props['type'] = 'texteditor';
		$this->props['editor-type'] = 'wp-editor';
	}

	public function render() {
		$this->getLabelField();
		$this->getKeyField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getEditorTypeField();
		$this->getEditorControlsField();
		$this->getAllowShortcodesField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}

	protected function getEditorControlsField() { ?>
		<div v-if="field['editor-type'] == 'wp-editor'" class="form-group">
			<?php parent::getEditorControlsField() ?>
		</div>
	<?php }

	protected function getAllowShortcodesField() { ?>
		<div v-if="field['editor-type'] == 'wp-editor'" class="form-group">
			<?php parent::getAllowShortcodesField() ?>
		</div>
	<?php }

	protected function getEditorTypeField() { ?>
		<div class="form-group">
			<label>Type</label>
			<div class="select-wrapper">
				<select v-model="field['editor-type']">
					<option value="textarea">Textarea</option>
					<option value="wp-editor">WP Editor</option>
				</select>
			</div>
		</div>
	<?php }
}