<?php

$designer = CASE27\Integrations\ListingTypes\Designer::instance();

// @todo: json_encode( $designer::$store['structured-data'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );

?>

<script type="text/javascript">
	var CASE27_TypeDesigner = {
		blueprints: {
			layout_blocks: <?php echo json_encode( $designer::$store['profile-layout-blocks'] ) ?>,
			structured_data: <?php echo json_encode( $designer::$store['structured-data'] ) ?>,
			fields: <?php echo json_encode( $designer->fields ) ?>,
		},
		listing_packages: <?php echo json_encode( $designer->get_packages_dropdown() ) ?>,
		schemes: {
			fields: <?php echo json_encode( mylisting()->schemes()->get('fields') ) ?>,
			single: <?php echo json_encode( mylisting()->schemes()->get('single') ) ?>,
			result: <?php echo json_encode( mylisting()->schemes()->get('result') ) ?>,
			search: <?php echo json_encode( mylisting()->schemes()->get('search') ) ?>,
			settings: <?php echo json_encode( mylisting()->schemes()->get('settings') ) ?>,
		},
		fieldAliases: <?php echo json_encode( array_flip( CASE27\Classes\Listing::$aliases ) ) ?>,
	};
</script>

<div class="tabs" id="case27-listing-options-inside" v-cloak>
	<input type="hidden" id="case27-post-id" value="<?php echo esc_attr( $post->ID ) ?>">

	<nav>
		<ul>
			<li :class="currentTab == 'settings' ? 'active' : ''" class="tab-settings">
				<a @click.prevent="setTab('settings', 'general')" href="#section-settings"><i :class="settings.icon ? settings.icon : 'icon-location-pin-add-2'"></i><span>General</span></a>
			</li>
			<li :class="currentTab == 'fields' ? 'active' : ''" class="tab-listing-fields">
				<a @click.prevent="setTab('fields')" href="#section-fields"><i class="mi menu"></i><span>Fields</span></a>
			</li>
			<li :class="currentTab == 'single-page' ? 'active' : ''" class="tab-single-page">
				<a @click.prevent="setTab('single-page', 'style')" href="#section-single-page"><i class="mi web"></i><span>Single Page</span></a>
			</li>
			<li :class="currentTab == 'result-template' ? 'active' : ''" class="tab-result-template">
				<a @click.prevent="setTab('result-template', 'preview-card')" href="#section-result-template"><i class="mi view_day"></i><span>Preview Card</span></a>
			</li>
			<li :class="currentTab == 'search-page' ? 'active' : ''" class="tab-search-page">
				<a @click.prevent="setTab('search-page', 'advanced')" href="#section-search-page"><i class="mi search"></i><span>Search Forms</span></a>
			</li>
		</ul>
	</nav>

	<div class="tabs-content">
		<section v-show="currentTab == 'settings'" class="section" id="section-settings">
			<?php require_once CASE27_INTEGRATIONS_DIR . '/27collective/listing-types/views/tabs/settings.php' ?>
		</section>

		<section v-show="currentTab == 'fields'" class="section" id="section-fields">
			<?php require_once CASE27_INTEGRATIONS_DIR . '/27collective/listing-types/views/tabs/fields.php' ?>
		</section>

		<section v-show="currentTab == 'single-page'" class="section" id="section-single-page">
			<?php require_once CASE27_INTEGRATIONS_DIR . '/27collective/listing-types/views/tabs/single.php' ?>
		</section>

		<section v-show="currentTab == 'result-template'" class="section" id="section-result-template">
			<?php require_once CASE27_INTEGRATIONS_DIR . '/27collective/listing-types/views/tabs/result.php' ?>
		</section>

		<section v-show="currentTab == 'search-page'" class="section" id="section-search-page">
			<?php require_once CASE27_INTEGRATIONS_DIR . '/27collective/listing-types/views/tabs/search.php' ?>
		</section>
	</div>
</div>
