<div class="sub-tabs">
	<ul>
		<li :class="currentTab == 'result-template' && currentSubTab == 'preview-card' ? 'active' : ''" class="result-template-tab-preview-card">
			<a @click.prevent="setTab('result-template', 'preview-card')">Preview Card</a>
		</li>
		<li :class="currentTab == 'result-template' && currentSubTab == 'quick-view' ? 'active' : ''" class="result-template-tab-quick-view">
			<a @click.prevent="setTab('result-template', 'quick-view')">Quick View</a>
		</li>
	</ul>
</div>


<div class="tab-content">
	<input type="hidden" v-model="result_template_json_string" name="case27_listing_type_result_template">

	<div class="result-template-tab-preview-card-content" v-show="currentSubTab == 'preview-card'">
		<h3 class="section-title">
			Customize the preview card
			<span class="subtitle">Need help? Read the <a href="https://27collective.net/files/mylisting/docs/#listings-results" target="_blank">documentation</a> or open a ticket in our <a href="https://helpdesk.27collective.net/" target="_blank">helpdesk</a>.</span>
		</h3>

		<div class="template-wrapper">
			<div class="options">
				<div class="card">
					<h5>Design</h5>

					<div>
						<div class="form-group">
							<label>Template</label>
							<div class="select-wrapper">
								<select v-model="result.template">
									<option value="default">Default</option>
									<option value="alternate">Alternate</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label>Background</label>
							<div class="select-wrapper">
								<select v-model="result.background.type">
									<option value="image">Image</option>
									<option value="gallery">Gallery</option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="card fields-wrapper">
					<h5>Head buttons</h5>
					<draggable v-model="result.buttons" :options="{group: 'result-buttons', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
						<div v-for="button in result.buttons" class="head-button field">
							<h5>
								<span class="prefix">+</span>
								{{ capitalize(button.show_field) }}
								<span class="actions">
									<span title="Delete this button" @click.prevent="resultTab().deleteButton(button)"><i class="mi delete"></i></span>
								</span>
							</h5>
							<div class="edit">
								<div class="form-group">
									<label>Label</label>
									<input type="text" v-model="button.label">
								</div>

								<div class="form-group">
									<label>Use field</label>
									<div class="select-wrapper">
										<select v-model="button.show_field">
											<option value="" disabled="disabled">Select a field...</option>
											<option v-for="field in fieldsByType(['text', 'texteditor', 'wp-editor', 'checkbox', 'select', 'multiselect', 'textarea', 'date', 'time', 'datetime', 'work-hours', 'email', 'url', 'number', 'location'])" :value="field.slug">{{ field.label }}</option>
											<option value="__listing_rating">Rating</option>
										</select>
									</div>
								</div>
							</div>
						</div>

						<a class="btn btn-outline-dashed" @click.prevent="resultTab().addButton()">Add button</a>
					</draggable>
				</div>


				<div class="card fields-wrapper">
					<h5>Fields below title</h5>
					<draggable v-model="result.info_fields" :options="{group: 'result-info_fields', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable info_fields-list menu-list" :class="drag ? 'active' : ''" style="overflow: visible;">
						<div v-for="field in result.info_fields" class="field-below-title field">
							<h5>
								<span class="prefix">+</span>
								{{ capitalize(field.show_field) }}
								<span class="actions">
									<span title="Delete this field" @click.prevent="resultTab().deleteField(field)"><i class="mi delete"></i></span>
								</span>
							</h5>
							<div class="edit">
								<div class="form-group">
									<label>Icon</label>
									<iconpicker v-model="field.icon"></iconpicker>
								</div>
								<div class="form-group">
									<label>Label</label>
									<input type="text" v-model="field.label">
								</div>
								<div class="form-group">
									<label>Use field</label>
									<div class="select-wrapper">
										<select v-model="field.show_field">
											<option value="" disabled="disabled">Select a field...</option>
											<option v-for="field in fieldsByType(['text', 'texteditor', 'wp-editor', 'checkbox', 'select', 'multiselect', 'textarea', 'date', 'time', 'datetime', 'email', 'url', 'number', 'location'])" :value="field.slug">{{ field.label }}</option>
										</select>
									</div>
								</div>
							</div>
						</div>

						<a class="btn btn-outline-dashed" @click.prevent="resultTab().addField()">Add field</a>
					</draggable>
				</div>

				<div class="card fields-wrapper">
					<h5>Footer sections</h5>
					<draggable v-model="result.footer.sections" :options="{group: 'result-footer.sections', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
						<div v-for="section in result.footer.sections" class="footer-section field">
							<h5>
								<span class="prefix">+</span>
								{{ section.title }}
								<span class="actions">
									<span title="Delete this field" @click.prevent="resultTab().deleteSection(section)"><i class="mi delete"></i></span>
								</span>
							</h5>
							<div class="edit">
								<div class="form-group full-width" v-if="typeof section.label !== 'undefined'">
									<label>Label</label>
									<input type="text" v-model="section.label">
								</div>

								<div class="form-group full-width" v-if="typeof section.taxonomy !== 'undefined'">
									<label>Taxonomy</label>
									<div class="select-wrapper">
										<select v-model="section.taxonomy">
											<?php foreach ($designer::$store['taxonomies'] as $tax): ?>
												<option value="<?php echo esc_attr( $tax->name ) ?>"><?php echo esc_html( $tax->label ) ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>

								<div>
									<div class="form-group full-width">
										<label>Buttons</label>
										<div class="form-group">
											<label><input type="checkbox" v-model="section.show_quick_view_button" value="yes"> Quick View</label>
										</div>
										<div class="form-group">
											<label><input type="checkbox" v-model="section.show_bookmark_button" value="yes"> Bookmark</label>
										</div>
									</div>
								</div>

								<div v-if="typeof section.details !== 'undefined'">
									<draggable v-model="section.details" :options="{group: 'result-footer-details', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
										<div v-for="detail in section.details" class="footer-detail field">
											<h5>
												<span class="prefix">+</span>
												{{ capitalize( detail.show_field ) }}
												<span class="actions">
													<span title="Delete this field" @click.prevent="resultTab().deleteDetail(detail, section)"><i class="mi delete"></i></span>
												</span>
											</h5>
											<div class="edit">
												<div class="form-group">
													<label>Icon</label>
													<iconpicker v-model="detail.icon"></iconpicker>
												</div>

												<div class="form-group">
													<label>Label</label>
													<input type="text" v-model="detail.label">
												</div>

												<div class="form-group">
													<label>Use field</label>
													<div class="select-wrapper">
														<select v-model="detail.show_field">
															<option v-for="field in fieldsByType(['text', 'texteditor', 'wp-editor', 'checkbox', 'select', 'multiselect', 'textarea', 'date', 'time', 'datetime', 'email', 'url', 'number', 'location'])" :value="field.slug">{{ field.label }}</option>
														</select>
													</div>
												</div>
											</div>
										</div>

										<a class="btn btn-outline-dashed" @click.prevent="resultTab().addDetail(section)">Add detail</a>
									</draggable>
								</div>

							</div>
						</div>

						<div class="form-group full-width add-new add-new-block footer-add-new-section">
							<label>Add section</label>
							<div class="select-wrapper">
								<select id="result-add-section">
									<option value="none" selected="selected">Select section type...</option>
									<option v-for="section in blueprints.preview.sections" :value="section.type">{{ section.title }}</option>
								</select>
							</div>
							<button class="btn btn-primary pull-right" @click.prevent="resultTab().addSection()">Add block</button>
						</div>
					</draggable>

					<!-- <pre>{{ result.footer.sections }}</pre> -->
				</div>
			</div>

			<div class="template-container">
				<div class="template" :class="result.template">
					<div class="top-buttons">
						<a v-for="button in result.buttons">
							{{ formatLabel(button.label, button.show_field) }}
						</a>
					</div>

					<div v-if="result.background.type == 'gallery'" class="gallery-arrows">
						<span><i class="fa fa-chevron-left"></i></span>
						<span><i class="fa fa-chevron-right"></i></span>
					</div>

					<div class="bottom">
						<div class="listing-info">
							<div class="logo" v-if="result.template == 'alternate'"></div>
							<h2>Title</h2>
							<p v-if="result.template == 'alternate'">Description</p>
							<ul>
								<li v-for="field in result.info_fields">{{ formatLabel(field.label, field.show_field) }}</li>
							</ul>
						</div>

						<div class="footer footer-buttons">
							<a v-for="section in result.footer.sections">{{ formatLabel(section.type) }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="result-template-tab-quick-view-content" v-show="currentSubTab == 'quick-view'">
		<h3 class="section-title">
			Customize the quick view modal
			<span class="subtitle">Need help? Read the <a href="https://27collective.net/files/mylisting/docs/#listings-results" target="_blank">documentation</a> or open a ticket in our <a href="https://helpdesk.27collective.net/" target="_blank">helpdesk</a>.</span>
		</h3>

		<div class="template-wrapper">
			<div class="options">
				<div class="card">
					<h5>Design</h5>

					<div>
						<div class="form-group full-width">
							<label>Template</label>
							<div class="select-wrapper">
								<select v-model="result.quick_view.template">
									<option value="default">Default</option>
									<option value="alternate">Alternate</option>
								</select>
							</div>
						</div>

						<div class="form-group full-width" v-show="result.quick_view.template == 'default'">
							<label>Map Skin</label>
							<div class="select-wrapper">
								<select v-model="result.quick_view.map_skin">
									<option v-for="(skin_name, skin_key) in blueprints.map_skins" :value="skin_key">{{ skin_name }}</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="quick-view-template-container template-container">
				<div class="quick-view-template template" :class="result.quick_view.template">
					<div class="left">
						<div class="quick-view-top">
							<div v-if="result.background.type == 'gallery'" class="gallery-arrows">
								<span><i class="fa fa-chevron-left"></i></span>
								<span><i class="fa fa-chevron-right"></i></span>
							</div>
						</div>

						<div class="quick-view-bottom">
							<div class="description">
								<div class="line"></div>
								<div class="line"></div>
								<div class="line"></div>
							</div>
						</div>
					</div>

					<div class="right">
						<div class="map"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>