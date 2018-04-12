<div class="sub-tabs">
	<ul>
		<li :class="currentTab == 'search-page' && currentSubTab == 'advanced' ? 'active' : ''" class="search-page-tab-advanced">
			<a @click.prevent="setTab('search-page', 'advanced')">Advanced form</a>
		</li>
		<li :class="currentTab == 'search-page' && currentSubTab == 'basic' ? 'active' : ''" class="search-page-tab-basic">
			<a @click.prevent="setTab('search-page', 'basic')">Basic form</a>
		</li>
		<li :class="currentTab == 'search-page' && currentSubTab == 'order' ? 'active' : ''" class="search-page-tab-order">
			<a @click.prevent="setTab('search-page', 'order')">Listings order</a>
		</li>
	</ul>
</div>

<div class="tab-content">
	<input type="hidden" v-model="search_page_json_string" name="case27_listing_type_search_page">

	<div class="search-page-tab-advanced-content" v-show="currentSubTab == 'advanced' || currentSubTab == 'basic'">
		<div class="advanced-search-form">
			<h3 class="section-title" v-show="currentSubTab == 'advanced'">
				Customize the advanced search form for this listing type
				<span class="subtitle">Not sure what's this? <a href="https://27collective.net/files/mylisting/docs/#listings-search" target="_blank">View the docs</a>.</span>
			</h3>

			<h3 class="section-title" v-show="currentSubTab == 'basic'">
				Customize the basic search form for this listing type
				<span class="subtitle">Not sure what's this? <a href="https://27collective.net/files/mylisting/docs/#listings-search" target="_blank">View the docs</a>.</span>
			</h3>

			<div class="fields-wrapper">
				<div class="fields-column left">
					<h4>Facets <span>Click on a facet to edit. Drag & Drop to reorder.</span></h4>

					<draggable v-model="search[state.search.active_form].facets" :options="{group: 'facet-types', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
						<div v-for="facet in search[state.search.active_form].facets">
							<div class="facet field" v-if="facet">
								<h5>
									<span class="prefix">+</span>
									{{facet.label}}
									<small>({{ facet.type }})</small>
									<span class="actions">
										<span title="Delete this field" @click.prevent="searchTab().deleteFacet(facet, state.search.active_form)"><i class="mi delete"></i></span>
									</span>
								</h5>
								<div class="options edit">
									<div class="form-group">
										<label>Label</label>
										<input type="text" v-model="facet.label">
									</div>
									<div class="form-group" v-if="typeof facet.placeholder !== 'undefined'">
										<label>Placeholder</label>
										<input type="text" v-model="facet.placeholder">
									</div>
									<div class="form-group" v-if="typeof facet.show_field !== 'undefined'">
										<label>Use Field</label>
										<div class="select-wrapper">
											<select v-model="facet.show_field">
												<option v-for="field in fieldsByFacetType(facet.type)" :value="field.slug">{{ field.label }}</option>
											</select>
										</div>
									</div>
									<div v-if="facet.options && Object.keys(facet.options).length > 0" v-for="option in facet.options" class="form-group">
										<div v-if="!option.form || option.form === state.search.active_form">
											<div v-if="option.type == 'text'" class="select-option">
												<label>{{ option.label }}</label>
												<input type="text" v-model="option.value">
											</div>

											<div v-if="option.type == 'number'" class="select-option">
												<label>{{ option.label }}</label>
												<input type="number" v-model="option.value">
											</div>

											<div v-if="option.type == 'checkbox'" class="select-option">
												<label>&nbsp;</label>
												<label><input type="checkbox" v-model="option.value"> {{ option.label }}</label>
											</div>

											<div v-if="option.type == 'select'" class="select-option">
												<label>{{ option.label }}</label>
												<div class="select-wrapper">
													<select v-model="option.value">
														<option v-for="(choice_label, choice) in option.choices" :value="choice">{{ choice_label }}</option>
													</select>
												</div>
											</div>

											<div v-if="option.type == 'multiselect'" class="select-option">
												<label>{{ option.label }}</label>
												<select v-model="option.value" multiple="multiple">
													<option v-for="(choice_label, choice) in option.choices" :value="choice">{{ choice_label }}</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</draggable>
					<div class="placeholder-fields">
						<div class="field-wrapper">
							<div class="field"><h5>Placeholder field</h5></div>
							<div class="field"><h5>Placeholder field</h5></div>
							<div class="field"><h5>Placeholder field</h5></div>
							<div class="field"><h5>Placeholder field</h5></div>
						</div>
					</div>
				</div>
				<div class="fields-column right">
					<h4>Add a new facet</h4>

					<div class="form-group field add-new-field">
						<label>Select facet</label>
						<div class="select-wrapper">
							<select v-model="state.search.new_facet_type">
								<option v-for="facet in blueprints.facet_types" v-if="!facet.form || facet.form === state.search.active_form" :value="facet.type">{{ facet.label }}</option>
							</select>
						</div>

						<button class="btn btn-primary pull-right" @click.prevent="searchTab().addFacet(state.search.active_form)">Add</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="search-page-tab-order-content" v-show="currentSubTab == 'order'">
		<h3 class="section-title">
			Set how results are ordered on Explore page, and what listings should appear first
		</h3>

		<div class="fields-wrapper">
			<div class="fields-column left">
				<h4>Ordering options
					<span>These options will appear in the "Order By" dropdown in the Explore page. Click on an option to edit. Drag & Drop to reorder.</span>
				</h4><br><br>
				<draggable v-model="search.order.options" :options="{group: 'search-order-options', animation: 100, handle: 'h5'}" @start="drag=true" @end="drag=false" class="fields-draggable" :class="drag ? 'active' : ''">
					<div v-for="option, opt_key in search.order.options" class="field filter-option">
						<h5>
							<span class="prefix">+</span>
							{{ option.label }}
							<span class="actions">
								<span title="Remove" @click.prevent="searchTab().removeOption(option)"><i class="mi delete"></i></span>
							</span>
						</h5>
						<div class="edit">
							<div class="form-group">
								<label>Label</label>
								<input type="text" v-model="option.label" @input="searchTab().setOptionKey(option)">
							</div>

							<div class="form-group">
								<label>Key</label>
								<input type="text" :value="option.key" @input="option.key = slugify( $event.target.value )">
								<p class="form-description">
									This key can be used to automatically select this option using a url parameter,
									such as example.com/explore-places?order=latest
								</p>
							</div>
							<div style="clear: both;"></div>
							<div class="clauses">
								<div class="clause" v-for="clause, key in option.clauses">
									<hr class="divider">

									<p class="clause-heading">
										Edit clause <em v-show="option.clauses.length > 1">#{{ key + 1 }}</em>
										<span class="pull-right" v-show="key >= 1" @click="searchTab().removeClause(clause, option)"><i class="mi close"></i> Remove</span>
									</p>

									<div class="form-group">
										<label>Order by</label>
										<div class="select-wrapper">
											<select v-model="clause.context">
												<option value="option">Option</option>
												<option value="meta_key">Custom Field</option>
												<option value="raw_meta_key">Raw Field</option>
											</select>
										</div>

										<div>
											<p class="form-description" v-show="clause.context == 'option' || ! clause.context">
												Order by Option: Use one of the ordering methods provided next.
											</p>

											<p class="form-description" v-show="clause.context == 'meta_key'">
												Order by Custom Field: Use one of the fields you've added in the "Fields" tab for ordering.
											</p>

											<p class="form-description" v-show="clause.context == 'raw_meta_key'">
												Order by Raw Field: Use a listing meta field that hasn't been added in the "Fields" tab, but either programatically, or by another plugin.
											</p>
										</div>
									</div>

									<div class="form-group">
										<div v-show="clause.context == 'option' || ! clause.context">
											<label>Select option</label>
											<div class="select-wrapper">
												<select v-model="clause.orderby">
													<option value="date">Date</option>
													<option value="title">Title</option>
													<option value="author">Author</option>
													<option value="comment_count">Comments Count</option>
													<option value="relevance">Relevance</option>
													<option value="menu_order">Menu Order</option>
													<option value="rand">Random</option>
													<option value="ID">Listing ID</option>
													<option value="name">Slug</option>
													<option value="none">None</option>
												</select>
											</div>
										</div>

										<div v-show="clause.context == 'meta_key'">
											<label>Select field</label>
											<div class="select-wrapper">
												<select v-model="clause.orderby">
													<option v-for="field in fieldsByType(['number', 'date', 'checkbox', 'radio', 'select', 'text', 'password'])" :value="field.slug">
														{{ field.label }}
													</option>
												</select>
											</div>
										</div>

										<div v-show="clause.context == 'raw_meta_key'">
											<label>Enter field key</label>
											<input type="text" v-model="clause.orderby">
										</div>
									</div>

									<div class="form-group" v-if="clause.context == 'meta_key' || clause.context == 'raw_meta_key'">
										<label>Data type</label>
										<div class="select-wrapper" v-show="!clause.custom_type">
											<select v-model="clause.type">
												<option value="CHAR">Text</option>
												<option value="NUMERIC">Numeric</option>
												<option value="DATE">Date</option>
												<option value="DATETIME">Datetime</option>
												<option value="TIME">Time</option>
												<option value="DECIMAL">Decimal</option>
												<option value="UNSIGNED">Unsigned</option>
												<option value="SIGNED">Signed</option>
												<option value="BINARY">Binary</option>
											</select>
										</div>
										<input type="text" v-show="clause.custom_type" v-model="clause.type">
										<p class="form-description">
											<label><input type="checkbox" v-model="clause.custom_type"> Enter manually</label>
											<span v-show="clause.custom_type">Use this to specify precision and scale if using the 'DECIMAL' or 'NUMERIC' types (for example, 'DECIMAL(10,5)' or 'NUMERIC(10)' are valid).</span>
										</p>
									</div>

									<div class="form-group">
										<label>Order</label>
										<label><input type="radio" v-model="clause.order" value="ASC" :name="'clause-order-' + key + '-option-' + opt_key">Ascending</label>
										<label><input type="radio" v-model="clause.order" value="DESC" :name="'clause-order-' + key + '-option-' + opt_key">Descending</label>
									</div>
								</div>
							</div>

							<p class="add-clause" style="clear: both;">
								<span class="pointer" @click="searchTab().addClause(option)">{{ option.clauses.length === 1 ? 'Add secondary clause' : 'Add ordering clause' }}</span><br>
								{{ option.clauses.length > 1 ? 'Careful: Adding multiple ordering clauses could drastically decrease search performance.' : '' }}
							</p>

							<div style="clear: both;"></div>
						</div>
					</div>

				</draggable>
			</div>

			<div class="fields-column right">
				<h4>Add new option</h4>
				<div class="form-group field add-new-field text-center add-new-order">
					<button class="btn btn-primary block" @click.prevent="searchTab().addOption()">Add option</button>
					<small>or choose a preset</small>
					<button class="btn btn-secondary block" @click.prevent="searchTab().addOption( 'Latest', 'latest', 'date', 'DESC', 'option' )">Latest listings</button>
					<button class="btn btn-secondary block" @click.prevent="searchTab().addOption( 'Top rated', 'top-rated', '_case27_average_rating', 'DESC', 'raw_meta_key', 'DECIMAL(10,2)', true )">Top rated</button>
					<button class="btn btn-secondary block" @click.prevent="searchTab().addOption( 'A-Z', 'a-z', 'title', 'ASC', 'option' )">A-Z</button>
					<button class="btn btn-secondary block" @click.prevent="searchTab().addOption( 'Random', 'random', 'rand', 'DESC', 'option' )">Random</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- <pre>{{ search.order }}</pre> -->
