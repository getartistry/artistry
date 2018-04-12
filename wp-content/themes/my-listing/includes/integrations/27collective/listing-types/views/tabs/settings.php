<div class="sub-tabs">
	<ul>
		<li :class="currentTab == 'settings' && currentSubTab == 'general' ? 'active' : ''" class="settings-tab-general">
			<a @click.prevent="setTab('settings', 'general')">General</a>
		</li>
		<li :class="currentTab == 'settings' && currentSubTab == 'packages' ? 'active' : ''" class="settings-tab-packages">
			<a @click.prevent="setTab('settings', 'packages')">Packages</a>
		</li>
		<li :class="currentTab == 'settings' && currentSubTab == 'reviews' ? 'active' : ''" class="settings-tab-reviews">
			<a @click.prevent="setTab('settings', 'reviews')">Reviews</a>
		</li>
	</ul>
</div>

<div class="tab-content">
	<input type="hidden" v-model="settings_page_json_string" name="case27_listing_type_settings_page">

	<div class="settings-tab-general-content" v-show="currentSubTab == 'general'">
		<div class="listing-type-settings">
			<div class="column">
				<div class="card">
					<h4>Labels</h4>

					<div class="form-group">
						<label>Icon</label>
						<iconpicker v-model="settings.icon"></iconpicker>
					</div>

					<div class="form-group">
						<label>Singular name <small>(e.g. Business)</small></label>
						<input type="text" v-model="settings.singular_name">
					</div>

					<div class="form-group">
						<label>Plural name <small>(e.g. Businesses)</small></label>
						<input type="text" v-model="settings.plural_name">
					</div>
				</div>

				<div class="card">
					<h4>Structured data</h4>
					<div class="form-group mb0">
						<p class="form-description">
							Structured data helps search engines understand the contents of listings, and display better search results. <a href="https://developers.google.com/search/docs/guides/intro-structured-data" target="_blank">Read more</a>.
							You can modify the default structure to better suit this listing type.<br>
							<strong><a href="http://schema.org/docs/full.html" target="_blank">Click here</a></strong> to view all supported schema properties.
						</p>
						<p class="form-description">
						</p>
						<a @click.prevent="setTab('settings', 'seo')" class="btn btn-secondary">Edit structured data</a>
					</div>
				</div>

				<div class="card">
					<h4>Listing type configuration</h4>
					<div>
						<a @click.prevent="exportConfig" class="btn btn-primary">Export config file</a>
						<a @click.prevent="startImportConfig" class="btn btn-secondary">Import config file</a>
						<input type="file" name="c27-import-config" id="c27-import-config" @change="importConfig"
						onclick="return confirm('Imported configuration will overwrite your current settings. Do you want to proceed?')">
					</div>
				</div>

				<div class="card plain text-right">
					<a @click.prevent="state.settings.view_more = !state.settings.view_more" class="btn btn-secondary">
						<i class="fa fa-chevron-down" v-show="!state.settings.view_more"></i>
						<i class="fa fa-chevron-up" v-show="state.settings.view_more"></i>
						Advanced
					</a>
				</div>

				<div class="card" v-show="state.settings.view_more">
					<h4>Global listing type</h4>
					<div class="form-group mb0">
						<p class="form-description">
							If checked, you can use this listing type in the Explore page to display a global search form, that will look for results within all other listing types. A site shouldn't have more than one global listing type. They also shouldn't be used in the Add Listing page or anywhere else besides the Explore page.
						</p>
						<label><input type="checkbox" v-model="settings.global"> This is a global listing type</label>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="settings-tab-packages-content" v-show="currentSubTab == 'packages'">
		<div class="listing-type-packages">
			<div class="column">
				<div class="card">
					<h4>
						Listing Packages
						<p>Set what packages the user can choose from when submitting a listing of this type.</p>
					</h4>

					<div class="fields-wrapper">
						<draggable v-model="settings.packages.used" :options="{group: 'settings-packages', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
							<div v-for="package in settings.packages.used" class="field">
								<h5>
									<span class="prefix">+</span>
									{{ packages().getPackageTitle(package) }}
									<small v-show="package.label.length">({{ packages().getPackageDefaultTitle(package) }})</small>
									<span class="actions">
										<span title="This package will be highlighted" class="highlighted" v-show="package.featured"><i class="mi star"></i></span>
										<span title="Remove" @click.prevent="packages().remove(package)"><i class="mi delete"></i></span>
									</span>
								</h5>
								<div class="edit">
									<div class="form-group">
										<label>Label</label>
										<input type="text" v-model="package.label" :placeholder="packages().getPackageDefaultTitle(package)">
										<p class="form-description">Leave blank to use the default package label.</p>
									</div>

									<div class="form-group">
										<label>Description</label>
										<textarea v-model="package.description" placeholder="Put each feature in a new line"></textarea>
										<p class="form-description">Leave blank to use the default package description.</p>
									</div>

									<div class="form-group">
										<label><input type="checkbox" v-model="package.featured"> Featured?</label>
										<p class="form-description">Featured packages will be highlighted.</p>
									</div>

									<div style="clear: both;"></div>

									<!-- <pre>{{ package }}</pre> -->
								</div>
							</div>

						</draggable>

						<div class="form-group field add-new-field">
							<label>List of packages</label>
							<div class="select-wrapper">
								<select v-model="state.settings.new_package">
									<option v-for="name, id in state.settings.packages" :value="id" v-if="! packages().isPackageUsed(id)">{{ name }}</option>
								</select>
							</div>

							<button class="btn btn-primary pull-right" @click.prevent="packages().add()">Add</button>
							<p class="form-description">You can create listing packages as WooCommerce products.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="settings-tab-reviews-content" v-show="currentSubTab == 'reviews'">
		<div class="listing-type-reviews">
			<div class="column">
				<div class="card">
					<h4>
						Listing Reviews
						<p>Customize how listing reviews work, enable star ratings, add multiple rating categories, etc.</p>
					</h4>

					<div class="form-group">
						<label>
							<input type="checkbox" v-model="settings.reviews.gallery.enabled">
							Enable gallery upload
						</label>
					</div><br>

					<div class="form-group">
						<label>
							<input type="checkbox" v-model="settings.reviews.ratings.enabled">
							Enable star ratings
						</label>
					</div><br>

					<div class="form-group" v-show="settings.reviews.ratings.enabled">
						<label>Ratings mode</label>
						<label>
							<input type="radio" v-model="settings.reviews.ratings.mode" value="5">
							5 stars
						</label>
						<label>
							<input type="radio" v-model="settings.reviews.ratings.mode" value="10">
							10 stars
						</label>
					</div><br>

					<div class="fields-wrapper" v-show="settings.reviews.ratings.enabled">
						<div class="form-group">
							<label>Rating Categories</label>
						</div>

						<draggable v-model="settings.reviews.ratings.categories" :options="{group: 'settings-reviews-categories', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
							<div v-for="category in settings.reviews.ratings.categories" class="field">
								<h5>
									<span class="prefix">+</span>
									{{ category.label }}
									<span class="actions" v-show="settings.reviews.ratings.categories.length > 1 && category.id !== 'rating'">
										<span title="Remove" @click.prevent="reviews().removeCategory(category)"><i class="mi delete"></i></span>
									</span>
								</h5>
								<div class="edit">
									<div class="form-group">
										<label>Label</label>
										<input type="text" v-model="category.label" @input="category.is_new ? category.id = slugify( category.label ) : null">
									</div>

									<div class="form-group">
										<label>Key</label>
										<input type="text" v-model="category.id" @input="category.is_new ? category.id = slugify( category.id ) : null" :disabled="!category.is_new">
										<p class="form-description" v-show="category.is_new">Needs to be unique. This isn't visible to the user.</p>
									</div>

									<div style="clear: both;"></div>

									<!-- <pre>{{ category }}</pre> -->
								</div>
							</div>

						</draggable>

						<div class="form-group">
							<button class="btn btn-primary pull-right" @click.prevent="reviews().addCategory()">Add rating category</button>
						</div>

						<div style="clear: both;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="settings-tab-seo-content" v-show="currentSubTab == 'seo'">
		<div class="listing-type-seo">
			<div class="column">
				<div class="card">
					<h4>
						Schema Markup
						<p>Optimize your listing's visibility in search engine results.</p>
					</h4>

					<div class="form-group schema-markup">
						<label>Markup</label>
						<div v-pre id="lte-seo-markup"></div>
						<!-- <pre>{{ settings.seo.markup }}</pre> -->
					</div><br>
					<div class="text-right">
						<a @click.prevent="setDefaultSeoMarkup" class="btn btn-secondary">Reset</a>
						<a @click.prevent="setTab('settings', 'general')" class="btn btn-primary">Save</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- <pre>{{ settings }}</pre> -->
