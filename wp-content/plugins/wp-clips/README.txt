=== WP Clips ===
Contributors: jon-barratt, krolyn
Tags: theme, child theme, plugin, custom, customization, code, update-safe, safe-guard, protection, framework
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 2.0.2
License: GPLv2 or later


Protect your code customizations from theme and plugin updates with a Clip.


== Description ==

A Clip is a plugin-activated directory of custom files which can be used to safely customize WordPress themes and plugins.

The 'core' Clip (/clip_core/) contains a functions file for customizing WordPress core functions. It is always enabled.

The 'custom' Clip (/clip_custom/) contains functions, jquery and stylesheet files for customizing themes and/or plugins. Compatible themes and/or required plugins are declared in a vitals file and the Clip is enabled only when these items are installed and active, or when no declarations have been made.

Custom code is added directly to files within the Clip folders, and can be performed locally, via FTP or from within the WordPress plugin editor (unless installed as an mu-plugin). Included/enqueued files and folders may also be added to each Clip.

Precoded Clips are easily installed and managed, offering developers a safe and convenient deliverable for clients. A library of Precoded Clips is also available at http://clipbank.wpclips.net

= Features =

* Protection from all theme and plugin updates
* Safely customize framework child themes (e.g. Genesis)
* Starter plugin is tiny (approx 10kb zipped)
* Translation-ready (incl updatable POT file)
* Easy to update, upgrade, back-up, copy and transfer
* Database independent
* Simple to install, update and manage Precoded Clips
* A worry-free deliverable for developers
* ClipBank™ library of Precoded Clips available


== Installation / Updates ==

1. Install as a new plugin from 'Plugins > Add New' in WordPress admin, or unzip and upload to the /wp-content/plugins/ directory. Activate the plugin.

If you wish to install as a must-use (mu-) plugin (recommended), upload the /wp-clips/ folder 'contents' (not the folder) to a new or existing /wp-content/mu-plugins/ directory.

2. Login to admin to auto-install Clip folders. No further action required.

= Updates / Upgrades =

DO NOT DELETE the plugin folder, Clips or /precoded/ folders as this will delete your custom code. Always back-up the plugin before proceeding, and perform updates/upgrades via 'WP-Clips Control' under admin 'Settings > Clips', or by unzipping the plugin update and adding/replacing only those files/folders included in the new plugin folder. Note that mu-plugins must be updated/upgraded manually.

== Frequently Asked Questions ==

= How do I declare compatible themes and required plugins in the custom Clip? =

Open the /clip_custom/vitals.php file. Add compatible themes to the $themes array using the folder name (e.g 'theme-folder-name'). Add required plugins to the $plugins array using the the plugin’s folder and filename (e.g. 'plugin-folder-name/plugin-filename.php'). Array values are comma-separated.

= What if I want to edit a theme or plugin’s native file? =

A log.txt file is included within the custom Clip to record any such changes.

= Is WP Clips compatible with multisite? =

No. You must upgrade to WP Clips Multisite. Visit http://wpclips.net and download WP Clips Multisite. Refer to previous updating/upgrading instructions.

= Where can I learn more? =

More information is available at http://wpclips.net


== Changelog ==

http://wpclips.net/changelog

v2.0.2
Remove redundant code