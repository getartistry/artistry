<?php

$cover_buttons = apply_filters( 'case27\types\cover_buttons', [
	'custom-field' => [
		'label' => 'Show a field\'s value',
		'action' => 'custom-field',
	],

	'share' => [
		'label' => 'Share Listing',
		'action' => 'share',
	],

	'bookmark' => [
		'label' => 'Bookmark Listing',
		'action' => 'bookmark',
	],

	'add-review' => [
		'label' => 'Add Review',
		'action' => 'add-review',
	],

	'book' => [
		'label' => 'Book',
		'action' => 'book',
	],

	'display-rating' => [
		'label' => 'Display Rating',
		'action' => 'display-rating',
	],
] );
?>

<div class="sub-tabs">
	<ul>
		<li :class="currentTab == 'single-page' && currentSubTab == 'style' ? 'active' : ''" class="single-page-tab-style">
			<a @click.prevent="setTab('single-page', 'style')">Cover style</a>
		</li>
		<li :class="currentTab == 'single-page' && currentSubTab == 'buttons' ? 'active' : ''" class="single-page-tab-buttons">
			<a @click.prevent="setTab('single-page', 'buttons')">Cover buttons</a>
		</li>
		<li :class="currentTab == 'single-page' && currentSubTab == 'pages' ? 'active' : ''" class="single-page-tab-pages">
			<a @click.prevent="setTab('single-page', 'pages')">Content &amp; Tabs</a>
		</li>
	</ul>
</div>

<div class="tab-content">
	<input type="hidden" v-model="single_page_options_json_string" name="case27_listing_type_single_page_options">

	<div class="single-page-tab-style-content" v-show="currentSubTab == 'style'">
		<h3 class="section-title">
			Customize the cover style
			<span class="subtitle">Not sure what's this? <a href="https://27collective.net/files/mylisting/docs/#listings-single" target="_blank">View the docs</a>.</span>
		</h3>

		<div class="form-group cover-type">
			<label><input type="radio" v-model="single.cover.type" value="image"> Cover image</label>
			<label><input type="radio" v-model="single.cover.type" value="gallery"> Gallery slider</label>
			<label><input type="radio" v-model="single.cover.type" value="none"> None</label>
			<div class="bg" :class="single.cover.type">
				<div class="item"></div>
				<div class="item"></div>
				<div class="item"></div>
				<div class="cover-footer">
					<div class="profile-picture"></div>
					<div class="menu-pages">
						<div class="page"></div>
						<div class="page"></div>
						<div class="page"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="single-page-tab-buttons-content" v-show="currentSubTab == 'buttons'">
		<h3 class="section-title">
			Add cover buttons
			<span class="subtitle">Not sure what's this? <a href="https://27collective.net/files/mylisting/docs/#listings-single" target="_blank">View the docs</a>. To edit a button, click on it.</span>
		</h3>

		<draggable v-model="single.buttons" :options="{group: 'single-buttons', animation: 100, filter: '.no-drag'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
			<div v-for="button in single.buttons" class="field" @click="state.single.active_button = button" :class="state.single.active_button == button ? 'active' : ''">
				<i :class="button.icon"></i> {{ formatLabel(button.label, button.custom_field) }}
			</div>

			<a class="btn btn-primary add-new no-drag" @click.prevent="addCoverButton">
				+ Add New
			</a>
		</draggable>

		<div class="edit-cover-button" v-if="state.single.active_button">
			<h5>Edit button</h5>
			<div class="cover-button-wrapper">
				<div class="cover-button-options">
					<div class="form-group">
						<label>Button Icon</label>
						<iconpicker v-model="state.single.active_button.icon"></iconpicker>
					</div>
					<div class="form-group">
						<label>Label <small v-if="state.single.active_button.action == 'custom-field'">Use [[field]] to get the contents of the custom field.</small></label>
						<input type="text" v-model="state.single.active_button.label">

						<?php c27()->get_partial('admin/input-language', ['object' => 'state.single.active_button.label_l10n']) ?>
					</div>
					<div class="form-group">
						<label>Action</label>
						<div class="select-wrapper">
							<select v-model="state.single.active_button.action">
								<?php foreach ($cover_buttons as $button): ?>
									<option value="<?php echo esc_attr( $button['action'] ) ?>"><?php echo esc_attr( $button['label'] ) ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="form-group" v-if="state.single.active_button.action == 'custom-field'">
						<label>Select Field</label>
						<div class="select-wrapper">
							<select v-model="state.single.active_button.custom_field">
								<option v-for="field in cover_button_fields" :value="field.slug">{{ field.label }}</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>Button Style</label>
						<div class="select-wrapper">
							<select v-model="state.single.active_button.style">
								<option value="primary">Primary</option>
								<option value="secondary">Secondary</option>
								<option value="outlined">Outlined</option>
								<option value="plain">Plain</option>
							</select>
						</div>
					</div>
					<div class="footer form-group">
						<label>&nbsp;</label>
						<a @click.prevent="state.single.active_button = null" class="btn btn-primary btn-xs">Save</a>
						<a @click.prevent="deleteCoverButton(state.single.active_button)" class="btn btn-plain btn-xs"><i class="mi delete"></i>Delete button</a>
					</div>

					<div style="clear: both;"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="single-page-tab-pages-content" v-show="currentSubTab == 'pages'">
		<h3 class="section-title">
			Create and organize listing content
			<span class="subtitle">Not sure what's this? <a href="https://27collective.net/files/mylisting/docs/#listings-single" target="_blank">View the docs</a>. To edit a page, click on it.</span>
		</h3>

		<draggable v-model="single.menu_items" :options="{group: 'single-menu', animation: 100, draggable: '.field'}" @start="drag=true" @end="drag=false" class="fields-draggable menu-list" :class="drag ? 'active' : ''">
			<div v-for="menu_item in single.menu_items" class="field" @click="state.single.active_menu_item = menu_item">
				{{ menu_item.label }}
			</div>

			<div class="add-new">
				<div class="form-group">
					<div class="select-wrapper">
						<select @change="addMenuItem" id="single-add-menu-item">
							<option value="none" selected="selected">Add new menu page</option>
							<option value="main">Profile</option>
							<option value="comments">Comments/Reviews</option>
							<option value="related_listings">Related Listings</option>
							<option value="store">Store</option>
							<option value="bookings">Bookings</option>
							<option value="custom">Custom</option>
						</select>
					</div>
				</div>
			</div>
		</draggable>

		<div class="form-group edit-page" v-if="state.single.active_menu_item">
			<div class="cover-button-wrapper">
				<div class="cover-button-options">

					<div class="form-group">
						<label>Label</label>
						<input type="text" v-model="state.single.active_menu_item.label">

						<?php c27()->get_partial('admin/input-language', ['object' => 'state.single.active_menu_item.label_l10n']) ?>
					</div>

					<div class="form-group" v-if="state.single.active_menu_item.page == 'store'">
						<label>Display products from field:</label>
						<div class="select-wrapper">
							<select v-model="state.single.active_menu_item.field">
								<option v-for="field in fieldsByType(['select-products'])" :value="field.slug">{{ field.label }}</option>
							</select>
						</div>
					</div>

					<div class="form-group" v-if="state.single.active_menu_item.page == 'related_listings'">
						<label>Of Listing Type:</label>
						<div class="select-wrapper">
							<select v-model="state.single.active_menu_item.related_listing_type">
								<option value="">-- All --</option>
								<?php foreach ($designer::$store['listing-types'] as $listing_type): ?>
									<option value="<?php echo $listing_type->post_name ?>"><?php echo $listing_type->post_title ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>

					<div class="form-group" v-if="state.single.active_menu_item.page == 'bookings'">
						<label>Booking Service Provider:</label>
						<div class="select-wrapper">
							<select v-model="state.single.active_menu_item.provider">
								<option value="basic-form">Basic Form</option>
								<option value="timekit">Timekit</option>
							</select>
						</div>
					</div>

					<div class="form-group" v-if="state.single.active_menu_item.page == 'bookings' && state.single.active_menu_item.provider == 'basic-form'">
						<label>Submission sends email to:</label>
						<div class="select-wrapper">
							<select v-model="state.single.active_menu_item.field">
								<option v-for="field in fieldsByType(['email'])" :value="field.slug">{{ field.label }}</option>
							</select>
						</div>
					</div>

					<div class="form-group" v-if="state.single.active_menu_item.page == 'bookings' && state.single.active_menu_item.provider == 'basic-form'">
						<label>Contact Form ID:</label>
						<input type="text" v-model="state.single.active_menu_item.contact_form_id">
					</div>

					<div class="form-group" v-if="state.single.active_menu_item.page == 'bookings' && state.single.active_menu_item.provider == 'timekit'">
						<label>TimeKit Widget ID:</label>
						<div class="select-wrapper">
							<select v-model="state.single.active_menu_item.field">
								<option v-for="field in fieldsByType(['text'])" :value="field.slug">{{ field.label }}</option>
							</select>
						</div>
					</div>

					<div class="footer form-group">
						<label>&nbsp;</label>
						<button @click.prevent="state.single.active_menu_item = null" class="btn btn-primary btn-xs">Save</button>
						<button @click.prevent="deleteMenuItem(state.single.active_menu_item)" class="btn btn-plain btx-xs"><i class="mi delete"></i>Delete menu item</button>
					</div>
				</div>
			</div>
		</div>

		<div v-if="state.single.active_menu_item && (state.single.active_menu_item.page == 'main' || state.single.active_menu_item.page == 'custom')" class="page-layout-wrapper">
			<div class="page-layout">
				<div class="fields-wrapper">
				<h5>Edit page layout</h5>
				<draggable v-model="state.single.active_menu_item.layout" :options="{group: 'layout-blocks', animation: 100, draggable: '.field', filter: '.no-drag', handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
					<div v-for="block in state.single.active_menu_item.layout" class="field">
						<h5>
							<span class="prefix">+</span>
							{{block.title}}
							<small>({{block.type}})</small>
							<span class="actions">
								<span title="Delete this field" @click.prevent="deleteBlock(block)"><i class="mi delete"></i></span>
							</span>
						</h5>

						<div class="options edit">
							<div class="form-group">
								<label>Label</label>
								<input type="text" v-model="block.title">

								<?php // c27()->get_partial('admin/input-language', ['object' => 'block.title_l10n']) ?>
							</div>
							<div class="form-group" v-if="typeof block.show_field !== 'undefined'">
								<label>Use Field:</label>
								<div class="select-wrapper">
									<select v-model="block.show_field">
										<option v-for="field in fieldsByType(block.allowed_fields)" :value="field.slug">{{ field.label }}</option>
									</select>
								</div>
							</div>

							<div class="form-group full-width" v-if="typeof block.content !== 'undefined'">
								<label>Content (Type @ or [[ to see list of all fields you can use.)</label>
								<atwho :data="fieldsByType(block.allowed_fields)" v-model="block.content" placeholder="Example use:
&lt;iframe src=&quot;https://facebook.com/[[facebook-id]]&quot; title=&quot;[[listing-name]]&quot;&gt;&lt;/iframe&gt;
or
[show_tweets username=&quot;[[twitter-username]]&quot;]"></atwho>

								<!-- <pre>{{ block }}</pre> -->
							</div>

							<div v-if="block.options" v-for="option in block.options" class="form-group" :class="option.type" :style="option.type == 'textarea' ? 'width: 100%; float: none;' : ''">
								<div v-if="option.type == 'select'" class="select-option">
									<label>{{ option.label }}</label>
									<div class="select-wrapper">
										<select v-model="option.value">
											<option v-for="(choice_label, choice) in fieldsByTypeFormatted(option.choices)" :value="choice">{{ choice_label }}</option>
										</select>
									</div>
								</div>

								<div v-if="option.type == 'multiselect'" class="select-option">
									<label>{{ option.label }}</label>
									<select v-model="option.value" multiple="multiple">
										<option v-for="(choice_label, choice) in fieldsByTypeFormatted(option.choices)" :value="choice">{{ choice_label }}</option>
									</select>
								</div>

								<div v-if="option.type == 'number'" class="select-option">
									<label>{{ option.label }}</label>
									<input type="number" v-model="option.value">
								</div>

								<div v-if="option.type == 'textarea'">
									<label>{{ option.label }}</label>
									<textarea rows="10" v-model="option.value"></textarea>
								</div>

								<div v-if="option.type == 'repeater'" class="repeater-option">
									<label>{{ option.label }}</label>
									<draggable v-model="option.value" :options="{group: 'repeater', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable buttons-list menu-list" :class="drag ? 'active' : ''">
										<div v-for="(row, row_id) in option.value" class="repeater-row field">
											<h5>
												<span class="prefix">+</span>
												{{ row.label }}
												<span class="actions">
												<span title="Delete this row" @click.prevent="option.value.splice(row_id, 1)"><i class="mi delete"></i></span>
												</span>
											</h5>
											<div class="edit">
												<div class="form-group" v-if="option.fields.indexOf('icon') > -1">
													<label>Icon</label>
													<iconpicker v-model="row.icon"></iconpicker>
												</div>

												<div class="form-group" v-if="option.fields.indexOf('label') > -1">
													<label>Label</label>
													<input type="text" v-model="row.label">
												</div>

												<div class="form-group" v-if="option.fields.indexOf('show_field') > -1">
													<label>Field to use</label>
													<div class="select-wrapper">
														<select v-model="row.show_field">
															<option value="" disabled="disabled">Use Field:</option>
															<option v-for="field in fieldsByType(['text', 'texteditor', 'wp-editor', 'checkbox', 'select', 'multiselect', 'textarea', 'date', 'time', 'datetime', 'work-hours', 'email', 'url', 'number', 'location'])" :value="field.slug">{{ field.label }}</option>
															<option value="__listing_rating">Rating</option>
														</select>
													</div>
												</div>

												<div class="form-group" v-if="option.fields.indexOf('content') > -1">
													<label>Content</label>
													<input type="text" v-model="row.content">
												</div>
											</div>
										</div>

										<br>

										<a class="btn btn-primary" @click.prevent="option.value.push({label: '', show_field: '', content: '[[field]]', icon: ''})">Add row</a>
									</draggable>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group add-new add-new-block">
						<label>Add a new block</label>
						<div class="select-wrapper">
							<select id="single-add-block">
								<option value="none" selected="selected">Select block type</option>
								<option v-for="block in blueprints.layout_blocks" :value="block.type">{{ block.title }}</option>
							</select>
						</div>
						<button class="btn btn-primary pull-right" @click.prevent="addBlock">Add block</button>
					</div>
				</draggable>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- <pre>{{ single.menu_items[0] }}</pre> -->
