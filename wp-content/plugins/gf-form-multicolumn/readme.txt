=== Plugin Name ===
Contributors: webholism
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JHGDAKZ2YLFLN
Tags: gravityforms, gravity forms, multiple columns, multicolumn, multicolumns, multi column, multi columns, responsive, gravity forms multi column, multi row, multirow, multiple rows
Requires at least: 4.6
Tested up to: 4.7
Stable tag: 2.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add multiple columns (and multiple rows of multiple columns) to Gravity Forms.

== Description ==

<blockquote>
  <p>This plugin requires the <a href="http://www.webholism.com/gravity-forms/" rel="nofollow">Gravity Forms plugin</a>.  <strong>Don't use Gravity Forms yet?  <a href="http://www.webholism.com/gravity-forms/" rel="nofollow">Buy the plugin</a></strong> and make your life so much easier!</p>
</blockquote>

This responsive-design plugin allows you to add multiple columns into your Gravity Forms form.  Once this plugin is installed, you can create columns by using 'starting' and 'closing' Section elements which you then place your chosen column fields in-between.  Each opening and closing set of Sections define a column.  Use Section elements to enhance the form by adding separating rows.


== Installation ==

1. In your WordPress admin panel, go to <em>Plugins > New Plugin</em>, search for “Multi Column” for WordPress, find the “Gravity Forms Multi Column” plugin and click “<em>Install now</em>”.
2. Alternatively download the zip file, unzip, and upload the gf-form-multicolumn folder (and files) to your plugins directory, which usually is `/wp-content/plugins/`.
3. Activate the plugin through your <em>Plugins</em> area.
4. Create columns by adding a <em>Standard Fields > Section</em> directly above and below the form elements that you want to be in that particular column. E.g. for a two column form with one Drop Down in each column, create the following form elements in the order listed below:
        <br /><em>Standard Fields > Section</em> - Starting Section 1
        <br /><em>Standard Fields > Drop Down</em> - Column 1 content
        <br /><em>Standard Fields > Section</em> - Ending Section 1
        <br /><em>Standard Fields > Section</em> - Starting Section 2
        <br /><em>Standard Fields > Drop Down</em> - Column 2 content
        <br /><em>Standard Fields > Section</em> - Ending Section 2<br />
5. For starting Sections put “split-start” in the <em>Section > Appearance > Custom CSS Class</em> field. 
6. For ending Sections put “split-end” in the <em>Section > Appearance > Custom CSS Class</em> field.
7. If you want more than one row of multiple columns, add a <em>Standard Fields > Section</em> with “new-row” in the <em>Section > Appearance > Custom CSS Class</em> field, in-between the rows of multiple columns (e.g. if you have 3 rows of multiple columns, you’ll need a “new-row” Section between rows 1 and 2, and also a “new-row” Section between rows 2 and 3).
8. Update Form.

== Frequently Asked Questions ==

= Does it matter if my sections contain a Field Label in the form? =

No.  However you may find it useful to use Field Label text such as ‘Start of Column 1’, ‘End of Column 1’, etc.  These will not be visible in the front end of your website.

= How many columns can I make? =

We’ve tested 2, 3, 4 and 5 columns.  Theoretically you can have more, although this will depend on your theme and the amount of screen space you have.

= How many rows of multiple columns can I make? =

We’ve tested 2 and 3 rows, and theoretically you can have as many as you like.  Go crazy, and let us know how you get on!

= Can I use this plugin with multisite? =

From version 2.1.0 multisite is supported.

= Which version of Gravity Forms is this plugin compatible with? =

It has been tested with version 2.0.7 and above, but please do contact us if you’re experiencing a problem with your version of Gravity Forms.

== Screenshots ==

1. Creating a Section start for the start point of column one.
2. Creating a Section end for the end point of column one.
3. Creating a Section start for the start point of column two.
4. Creating a Section end for the end point of column two.
5. Creating a Section for a new row.
6. Wide screen display of a 3 row, multiple column form.
7. Mobile/narrow screen display of a 3 row, multiple column form.

== Upgrade Notice ==

= 2.1.1 =
This version removed code that had been used for testing multisite in 2.1.0.

= 2.1.0 =
This version resolves issues around the plugin providing network only functionality on multisite installations.  This plugin will now allow admins to activate or deactivate on individual network sites.
A new CSS style has been introduced to remove the left padding and left margin from the first column of each row of the created form, to allow for a form to line up elements as expected.  This is achieved with the style: `li[class*="column-count-1"] > div > ul`
Please note that with this version, the title of this plugin has changed and will now likely appear in a new location in your plugin list.  Do not be alarmed!  This is simply a new naming convention to align with Wordpress recommendations.

= 2.0.1 =
* Code altered to account for web servers with PHP version < 5.4.

= 2.0.0 =
Introduced new feature to allow for multiple rows.  Individual rows will split the columns they contain evenly.

= 1.0.1 =
Altered details related to the supporting files.  No functional alterations.  Upgrade optional.

= 1.0.0 =
Initial Release. Trumpets sound!

== Changelog ==

= 2.1.1 =
* Fix: Removed inaccurate output code that had been used for testing multisite functionality.

= 2.1.0 =
* Fix: Removed Network: True value to allow activation on individual multisite sites.
* Improvement: Introduced new CSS style: li[class*="column-count-1"] > div > ul to remove left margin and padding rules.
* Improvement: Improved readability of the readme.txt file instructions for setup.
* Improvement: Added Plugin URI value to reduce chances of conflict with other plugins of similar naming convention.
* Improvement: Changed name of plugin to align with recommendations provided by Wordpress.

= 2.0.1 =
* Fix: Altered primary file as array syntax [] was not functioning on sites with PHP version < 5.4.

= 2.0.0 =
* Improvement: Introduced row functionality. Changes to primary php file and CSS definitions.

= 1.0.1 =
* Improvement: Alterations to readme.txt
* Improvement: Description altered in primary file.

= 1.0.0 =
* Initial Release
