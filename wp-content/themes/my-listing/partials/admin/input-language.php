<?php
    $data = c27()->merge_options([
            'languages' => function_exists( 'pll_languages_list' ) ? pll_languages_list(['fields' => 'locale']) : [],
            'object' => '', // l10n object, e.g. field.label_l10n
        ], $data);
	?>

<?php if ( $data['languages'] ): ?>
	<div class="translate-input" v-if="typeof <?php echo esc_attr( $data['object'] ) ?> !== 'undefined'">
		<div class="language-toggle">Localize</div>
		<div class="languages-wrapper">
			<?php foreach ($data['languages'] as $locale): ?>
				<div class="language-wrapper">
					<label><?php echo esc_html( $locale ) ?></label>
					<input type="text" :value="<?php echo esc_attr( $data['object'] ) ?>['<?php echo esc_attr( $locale ) ?>']"
					@input="$set(<?php echo esc_attr( $data['object'] ) ?>, '<?php echo esc_attr( $locale ) ?>', $event.target.value)">
				</div>
			<?php endforeach ?>
			<hr>
		</div>
	</div>

	<!-- <pre>{{ <?php echo esc_attr( $data['object'] ) ?> }}</pre> -->
<?php endif ?>