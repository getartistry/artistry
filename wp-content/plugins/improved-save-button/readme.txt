=== Improved Save Button ===
Contributors: LabelBlanc
Tags: publish, save, close, list, edit, editing, return to list, close post, posts list, update, save and new, save and return, save and list, save and next, next post, save and previous, save and view, previous post, admin, administration, editor, multisite, custom post type, page, post, save and return, duplicate, save and duplicate
Requires at least: 3.5.1
Tested up to: 4.7.2
Stable tag: 1.2.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Improve your productivity with this "2-in-1" save button! It saves the post and immediately takes you to your next action.

== Description ==

This plugin adds a new and improved "Save" button to the Post Edit screen that, in a single click, saves the current post and immediately takes you to your next action. The following actions are available:

* **Save and New**: in a single click, saves the current post and goes to New Post Screen.
* **Save and Duplicate**: saves the post, duplicates it and goes to this new post's Edit Screen.
* **Save and List** (a.k.a. Save and Close): saves the post and goes to the Posts List.
* **Save and Return**: saves the post and returns you to the page where you were just before (no matter which page).
* **Save and Next**: saves the post and goes to the next post's Edit Screen.
* **Save and Previous**: saves the post and goes to the previous post's Edit Screen.
* **Save and View** (same or new window): saves the post and goes to the post's frontend page. Can show the post in the same window or in a new one.

This plugin saves you a lot of time when you have multiple posts, pages or custom posts to create or modify!

Works with pages, posts and custom post types!

Through the plugin's settings page, you can choose which actions to show and which one to use as the button's default action.

**Want to help translate?**
This plugin now uses the Wordpress Translation Service for translations. [Please visit the plugin's page to submit your translation!](https://translate.wordpress.org/projects/wp-plugins/improved-save-button)

== Installation ==

1. Download Improved Save Button.
2. Upload the 'improved-save-button' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate Improved Save Button from your Plugins page.

= Extra =
Visit 'Settings > Improved Save Button' to adjust the configuration to your needs.

== Screenshots ==

1. The new button
2. The down arrow reveals all the possible actions
3. The settings page

== Changelog ==

= 1.2.1 =
Release Date: February 22, 2017

* Bug fix: "Save and next/previous" actions were skipping posts when multiple posts had the exact same date (which happens when batch importing posts).

= 1.2 =
Release Date: August 27, 2016

* New action: A "Save and Return" action was added! This action redirects you to the page where you were before (no matter the page).
* New action: A "Save and Duplicate" action was added! This action duplicates the current post and redirects you to this new post's Edit Screen.
* Languages: for translators, contexts were added to strings. Also, all translation files were removed from the plugin and moved to the plugin's project on [Wordpress Translation Service](https://translate.wordpress.org/projects/wp-plugins/improved-save-button).
* Some text updates and minor improvements.

= 1.1.1 =
Release Date: June 25, 2016

* Misc: code updated to allow Wordpress Translation service (text domain changed to the plugin's slug)
* Languages: added fr_CA and fr_BE languages

= 1.1 =
Release Date: October 17, 2015

* New action: As requested, a "Save and View" action was added! This action shows the post's frontend page after the save. Two behaviors are available: show in the same window or show in a new window.
* Enhancement: A title attribute on the 'Save and next/previous' action now shows the name of the next/previous post.
* Enhancement: A big part of the code was rewritten to ease the addition of future new actions (no documentation yet, but you can develop plugins that add new actions, look in the code if interested!).
* Some bug fixes, including one with required fields of ACF.

= 1.0.2 =
Release Date: August 13, 2015

* Misc: Changed the title of the settings page from h2 to h1, like other settings pages in Wordpress 4.3

= 1.0.1 =
Release Date: April 30, 2015

* Enhancement: Post Edit Spinner: Up to date with Wordpress 4.2 behavior.
* Enhancement: Wordpress 4.2's new "removable query args" is now used.
* Bug Fix: The "1 post updated" message was not always shown after a "Save and list".
* Misc: Checked for add_query_arg() XSS attack possibility.

= 1.0 =
Release date: February 19, 2015

Initial version
