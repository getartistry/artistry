<?php
/* Prevent direct access */
defined( 'ABSPATH' ) or die( "You can't access this file directly." );

$cache_options = wd_asp()->o['asp_caching'];
if (ASP_DEMO) $_POST = null;
?>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/options_search.css?v='.ASP_CURR_VER; ?>" />
<div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asp-accessibility']) ? ' wd-accessible' : ''; ?>'>

    <?php if (wd_asp()->updates->needsUpdate()): ?>
        <p class='infoMsgBox'>Version <strong><?php echo wd_asp()->updates->getVersionString(); ?></strong> is available.
            Download the new version from Codecanyon. <a target="_blank" href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/update_notes.html">How to update?</a></p>
    <?php endif; ?>

    <?php
    $_comp = wpdreamsCompatibility::Instance();
    if ( $_comp->has_errors() ):
        ?>
        <div class="wpdreams-box errorbox">
            <p class='errors'>Possible incompatibility! Please go to the <a
                    href="<?php echo get_admin_url() . "admin.php?page=asp_compatibility_settings"; ?>">error
                    check</a> page to see the details and solutions!</p>
        </div>
    <?php endif; ?>

	<div class="wpdreams-box" style="float:left;">
		<?php ob_start(); ?>
		<div class="item">
			<p class='infoMsg'>Not recommended, unless you have many search queries per minute.</p>
			<?php $o = new wpdreamsYesNo( "caching", "Caching activated", $cache_options["caching"]); ?>
			<p class="descMsg">This will enable search results to be cached into files in the cache directory to bypass database query. Useful if you experience many repetitive queries.</p>
		</div>
		<div class="item">
			<p class='infoMsg'>Turn this OFF if you are experiencing performance issues.</p>
			<?php $o = new wpdreamsYesNo( "image_cropping", "Crop images for caching?", $cache_options["image_cropping"] ); ?>
			<p class="descMsg">This disables the thumbnail generator, and the full sized images are used as cover. Not much difference visually, but saves a lot of CPU.</p>
		</div>
		<div class="item">
			<?php $o = new wpdreamsText( "cachinginterval", "Caching interval (in minutes, default 1440, aka. 1 day)",
                $cache_options["cachinginterval"] ); ?>
		</div>
		<div class="item">
			<input type='submit' class='submit' value='Save options'/>
		</div>
		<?php $_r = ob_get_clean(); ?>


		<?php
		$updated = false;
		if ( isset( $_POST ) && isset( $_POST['asp_caching'] ) && ( wpdreamsType::getErrorNum() == 0 ) ) {
			$values = array(
				"caching"         => $_POST['caching'],
				"image_cropping"  => $_POST['image_cropping'],
				"cachinginterval" => $_POST['cachinginterval']
			);
			update_option( 'asp_caching', $values );
            asp_parse_options();
			$updated = true;
            asp_generate_the_css();
		}
		?>


		<div class='wpdreams-slider'>
			<?php if (ASP_DEMO): ?>
				<p class="infoMsg">DEMO MODE ENABLED - Please note, that these options are read-only</p>
			<?php endif; ?>

			<form name='asp_caching' method='post'>
				<?php if ( $updated ): ?>
					<div class='successMsg'>Search caching settings successfuly updated!</div><?php endif; ?>
				<fieldset>
					<legend>Caching Options</legend>
					<?php print $_r; ?>
					<input type='hidden' name='asp_caching' value='1'/>
				</fieldset>
			</form>


			<fieldset>
				<legend>Clear Cache</legend>
				<div class="item">
					<p class='infoMsg'>Will clear all the images and precached search phrases.</p>
					<input type='submit' class="red" name='Clear Cache' id='clearcache' value='Clear the cache!'>
				</div>
			</fieldset>
		</div>

		<script>
			jQuery(document).ready((function ($) {
				$('#clearcache').on('click', function () {
					var r = confirm('Do you really want to clear the cache?');
					if (r != true) return;
					var button = $(this);
					var data = {
						action: 'ajaxsearchpro_deletecache'
					};
					button.attr("disabled", true);
					var oldVal = button.attr("value");
					button.attr("value", "Loading...");
					button.addClass('blink');
					$.post(ajaxsearchpro.ajaxurl, data, function (response) {
						var currentdate = new Date();
						var datetime = currentdate.getDate() + "/"
							+ (currentdate.getMonth() + 1) + "/"
							+ currentdate.getFullYear() + " @ "
							+ currentdate.getHours() + ":"
							+ currentdate.getMinutes() + ":"
							+ currentdate.getSeconds();
						button.attr("disabled", false);
						button.removeClass('blink');
						button.attr("value", oldVal);
						button.parent().parent().append('<div class="successMsg">Cache succesfully cleared! ' + response + ' file(s) deleted at ' + datetime + '</div>');
					}, "json");
				});
			})(jQuery));
		</script>

	</div>
	<div id="asp-options-search">
		<a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asp-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
	</div>
	<div class="clear"></div>
</div>