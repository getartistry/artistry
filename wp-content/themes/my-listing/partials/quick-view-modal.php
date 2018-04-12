<!-- Quick view modal -->
<div id="quick-view" class="modal fade quick-view-modal c27-quick-view-modal" role="dialog">
	<div class="container">
		<div class="modal-dialog">
			<div class="modal-content"></div>
		</div>
	</div>
	<div class="loader-bg">
		<?php c27()->get_partial('spinner', [
			'color' => '#ddd',
			'classes' => 'center-vh',
			'size' => 28,
			'width' => 3,
			]); ?>
	</div>
</div>