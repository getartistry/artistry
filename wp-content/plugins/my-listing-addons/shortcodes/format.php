<?php

namespace CASE27\Shortcodes;

/**
 * Format Content Shortcode.
 */
class Format {

	public $name = '27-format',
		$title = '',
		$description = '',
	    $content = '',
	    $attributes = [
		    'type' => 'date',
		    'prefix' => '',
		    'postfix' => '',
		    'gradient' => '',
		    'include-markup' => 'yes',
		    'date-format' => 'M-d',
		    'custom-format' => '',
	    ],
	    $data = [
	    	'gradients' => [],
	    ];

	public function __construct()
	{
		$this->title = __( 'Format', 'my-listing' );
		$this->description = __( 'Format a piece of text to be displayed in various ways.', 'my-listing' );
		$this->data['gradients'] = c27()->get_gradients();
		add_shortcode($this->name, [$this, 'add_shortcode']);
	}

	public function add_shortcode($atts, $content = null)
	{
		$atts = shortcode_atts( $this->attributes, $atts );

		$markup = $atts['include-markup'] !== 'no';

		ob_start();
		?>

		<?php if ($atts['type'] == 'date'):
			$time = strtotime($content);
			if ( ! $time && ! empty( $GLOBALS['c27_active_shortcode_content'] ) ) {
				$time = strtotime($GLOBALS['c27_active_shortcode_content']);
			}

			?>

			<?php if ( $time ): ?>
				<?php if ( ! $atts['date-format'] || $atts['date-format'] == 'M-d' ): ?>
					<div class="event-date inside-date">
						<span class="e-month">
							<?php echo date('M', $time) ?>
						</span>
						<span class="e-day">
							<?php echo date('d', $time) ?>
						</span>
					</div>
				<?php elseif ( $atts['date-format'] == 'H:i' ): ?>
					<?php echo date( 'g:iA', $time ) ?>
				<?php elseif ( $atts['date-format'] == 'M-d H:i' ): ?>
					<?php 	echo date( 'M d \a\t g:iA', $time ) ?>
				<?php elseif ( $atts['date-format'] == 'custom' && trim( $atts['custom-format'] ) ): ?>
					<?php echo date( $atts['custom-format'], $time ) ?>
				<?php endif ?>
			<?php endif ?>
		<?php endif ?>

		<?php if ($atts['type'] == 'price'):
			if ($content == '[[field]]' && isset($GLOBALS['c27_active_shortcode_content'])) {
				$content = $GLOBALS['c27_active_shortcode_content'];
			}
			?>

			<?php if ($content && is_numeric($content)): ?>
				<div class="rent-price inside-rent-price">
					<span class="value"><?php echo $atts['prefix'] ?><?php echo number_format_i18n($content) ?></span>
					<sup class="out-of"><?php echo $atts['postfix'] ?></sup>
				</div>
			<?php endif ?>
		<?php endif ?>

		<?php if ($atts['type'] == 'gradient'): ?>
			<span class="text-gradient <?php echo $atts['gradient'] ?>">
				<?php echo $content ?>
			</span>
		<?php endif ?>

		<?php return $markup ? ob_get_clean() : wp_strip_all_tags( ob_get_clean() );
	}

	public function output_options()
	{
		?>
			<div class="form-group">
				<label><?php _e( 'Type', 'my-listing' ) ?></label>
				<select v-model="shortcode.attributes.type" @change="shortcode.attributes.prefix = ''; shortcode.attributes.postfix = ''; shortcode.attributes.gradient = '';">
					<option value="date"><?php _e( 'Date/Time', 'my-listing' ) ?></option>
					<option value="price"><?php _e( 'Price', 'my-listing' ) ?></option>
					<option value="gradient"><?php _e( 'Text Gradient', 'my-listing' ) ?></option>
				</select>
			</div>

			<div class="form-group">
				<label><?php _e( 'Content', 'my-listing' ) ?></label>
				<textarea v-model="shortcode.content"></textarea>
			</div>

			<div class="form-group" v-if="shortcode.attributes.type == 'date'">
				<label><?php _e( 'Date Format', 'my-listing' ) ?></label>
				<label for="date-option-M-d">
					<input type="radio" v-model="shortcode.attributes['date-format']" value="M-d" id="date-option-M-d">
					Aug 21 <small title="Check the 'include-markup' option below to show the date in a box layout.">[?]</small>
				</label>
				<label for="date-option-H-i">
					<input type="radio" v-model="shortcode.attributes['date-format']" value="H:i" id="date-option-H-i">
					10:30PM
				</label>
				<label for="date-option-M-d-H-i">
					<input type="radio" v-model="shortcode.attributes['date-format']" value="M-d H:i" id="date-option-M-d-H-i">
					Aug 21 at 10:30PM
				</label>
				<label for="date-option-custom">
					<input type="radio" v-model="shortcode.attributes['date-format']" value="custom" id="date-option-custom">
					Custom
				</label>
				<label for="custom-format" v-show="shortcode.attributes['date-format'] == 'custom'">
					<input type="text" v-model="shortcode.attributes['custom-format']" placeholder="F j, Y">
					<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Read about custom datetime formats</a>
				</label>

			</div>

			<div class="form-group" v-if="shortcode.attributes.type == 'date'">
				<label>
					<?php _e( 'Include the HTML markup?', 'my-listing' ) ?><br>
					<small><?php _e( 'Use this option to display the date in a box layout, if possible. Otherwise, it will be plain text.' ) ?></small>
				</label>
				<select v-model="shortcode.attributes['include-markup']">
					<option value="yes">Yes</option>
					<option value="no">No</option>
				</select>
			</div>

			<div class="form-group" v-if="shortcode.attributes.type == 'gradient'">
				<label><?php _e( 'Choose Gradient', 'my-listing' ) ?></label>

				<div class="gradient" v-for="(gradient, gradient_name) in shortcode.data.gradients"
					 :style="'background: -webkit-linear-gradient(180deg, ' + gradient.from + ' 0%, ' + gradient.to + ' 100%);'"
					 style="width: 33.33333%; height: 80px; display: inline-block; color: transparent; cursor: pointer;"
					 @click="shortcode.attributes.gradient = gradient_name"
					>
				</div>
			</div>

			<div class="form-group" v-if="shortcode.attributes.type == 'price'">
				<label><?php _e( 'Prefix', 'my-listing' ) ?></label>
				<input type="text" v-model="shortcode.attributes.prefix">
			</div>

			<div class="form-group" v-if="shortcode.attributes.type == 'price'">
				<label><?php _e( 'Postfix', 'my-listing' ) ?></label>
				<input type="text" v-model="shortcode.attributes.postfix">
			</div>

		<?php
	}
}

return new Format;