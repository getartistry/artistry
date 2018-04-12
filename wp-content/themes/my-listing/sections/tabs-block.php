<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'rows' => [],
			'wrapper_class' => 'block-element grid-item reveal',
			'ref' => '',
		], $data);
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element tabs-block">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<?php $tab_group_id = uniqid(); ?>
			<div class="tab-element bl-tabs">
				<div class="bl-tabs-menu">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<?php $i = 0; ?>
						<?php foreach ((array) $data['rows'] as $row): $i++; ?>
							<li role="presentation" class="<?php echo $i == 1 ? 'active' : '' ?>">
								<a
									href="#<?php echo esc_attr( "{$tab_group_id}--{$i}" ) ?>"
									aria-controls="<?php echo esc_attr( "{$tab_group_id}--{$i}" ) ?>"
									role="tab"
									class="tab-switch">
									<?php echo esc_html( $row['title'] ) ?>
								</a>
							</li>
						<?php endforeach ?>
					</ul>
				</div>

				<!-- Tab panes -->
				<div class="tab-content">
					<?php $i = 0; ?>
					<?php foreach ((array) $data['rows'] as $row): $i++;
						if ( is_array( $row['content'] ) ) {
							$row['content'] = join( ', ', $row['content'] );
						}
						?>
						<div role="tabpanel" class="tab-pane fade <?php echo $i == 1 ? 'in active' : '' ?>" id="<?php echo esc_attr( "{$tab_group_id}--{$i}" ) ?>">
							<?php echo $row['content'] ?>
						</div>
					<?php endforeach ?>
				</div>

			</div>
		</div>
	</div>
</div>
