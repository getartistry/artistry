<?php

namespace CASE27\Integrations\ListingTypes\Fields;

class LocationField extends Field {

	public function field_props() {
		$this->props['type'] = 'location';
		$this->props['map-skin'] = false;
	}

	public function render() {
		$this->getLabelField();
		$this->getPlaceholderField();
		$this->getDescriptionField();
		$this->getMapSkinField();
		$this->getRequiredField();
		$this->getShowInSubmitFormField();
		$this->getShowInAdminField();
	}

	public function getMapSkinField() { ?>
		<div class="form-group">
			<label>Map Skin</label>
			<div class="select-wrapper">
				<select v-model="field['map-skin']">
					<?php foreach ( c27()->get_map_skins() as $skin => $label ): ?>
						<option value="<?php echo esc_attr( $skin ) ?>"><?php echo esc_html( $label ) ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	<?php }
}