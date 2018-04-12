<div id="c27-bp-listings-wrapper">
	<div class="hide">
		<input type="hidden" id="case27-author-id" value="<?php echo esc_attr( bp_displayed_user_id() ) ?>">
	</div>

	<div class="container reveal">
		<div class="row listings-loading" v-show="listings.loading">
			<div class="loader-bg">
				<?php c27()->get_partial('spinner', [
					'color' => '#777',
					'classes' => 'center-vh',
					'size' => 28,
					'width' => 3,
					]); ?>
			</div>
		</div>
		<div class="row section-body c27-bp-listings-grid i-section" v-show="!listings.loading">
			<div v-html="listings.html" :style="!listings.show ? 'opacity: 0;' : ''"></div>
		</div>
		<div class="row" v-show="!listings.loading">
			<div class="c27-bp-listings-pagination" v-html="listings.pagination"></div>
		</div>
	</div>
</div>
