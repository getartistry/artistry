=== AnyWhere Elementor Pro ===
Contributors: webtechstreet
Tags: page-builder, elementor
Requires at least: 4.4
Tested up to: 4.9.1
Stable tag: 4.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Global layouts to use with shortcodes, global post layouts for single and archive pages. Supports CPT and ACF

== Description ==

Global layouts to use with shortcodes, global post layouts for single and archive pages. Supports CPT and ACF


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


== Changelog ==

= 2.6.1 =
Fixed issue in previous release with editing existing search template

= 2.6 =
* Enhancement: Carousel skin added in ACF Gallery
* Enhancement: Ordering by custom field in Post Blocks Widget
* Enhancement: WPML compatibility. All widgets are not completely translatable through WPML
* Enhancement: Custom Field widget - Dynamic link text from custom field (Type: Link)
* Enhancement: Custom Field Widget - Link image to current post/full image (Type: Image)
* Bug Fix: Woo Content widget not rendering shortcodes.
* Bug fix: Post Image widget - overlay not working in Elementor editor
* Along with some other minor bug fixes.

= 2.5.1 =
* Fixed issue with fatal error in existing some of the old AE Templates

= 2.5 =
* New Feature: Added support for a theme – Page Builder Framework
* New Feature: Added option in Post Blocks widget to show Related Posts.
* New Feature: Added option in Post Blocks widget to show posts from Relationship Field (ACF and Pods)
* Enhancement: Added option in Post Meta widget to disable links. Now you can disable links from post meta items like category, tag, author, and date.
* Enhancement: Added option to disable links in Taxonomy widget.
* Enhancement: Added “Enable Canvas” option for Single Post AE Templates.
  Now you won’t have to set canvas template on individual posts. Just check “Enable Canvas” option for AE Template and all your single post will work with Elementor Canvas.
* Enhancement: AE Template frontend preview won’t be accessible for non logged in users.
* Bug Fix: Fixed bug in ACF Gallery widget when there is no image available.
* And many other minor fixes and enhancements.

= 2.4.2 =
* Fixed issue with namespace.
* Fixed select2 library conflict with other plugins
* WooCommerce gallery issue fix (Lightbox was not closing).
* Strip shortcodes from Post Excerpt
* Fixed issue with hiding blank custom fields in Post Block widget.
* Fixed issue with Post Block widget - Pagination not working in some cases.
* Custom Field widget: added support for mailto & tel link.


= 2.4.1 =
* Fixed issue with Custom Field Map widget.
* Fixed issue with Global Shortcode.

= 2.4 =
New Features

* Ability to design Author Archives
* A new Render mode “Author Archive” has been added to allow creating layouts for Author Archive Pages
* Ability to design Date Archives
* A new Render mode “Date Archive” has been added to create the layout for your date archives.
* Added support for Hestia Theme (Free version)
* Map widget to render map using custom field data. It also allows styling map using Snazzy Maps.

Enhancements  & Tweaks

* Added option in AE – Content widget to show Category/Term description on Taxonomy Archive Pages.
* Added option to trigger the_content hooks for Post content.
* This will allow third-party plugins that automatically add some content before or after the post content. Eg. Social Media sharing plugins that added share buttons at the end of the post.
* Added support for shortcodes in custom field widget. Default and HTML mode now supports shortcodes.
* Custom Field widget – Hide area if no content is available in the custom field.
* Background Slider – Added option to disable Kenburns effect.

Bug Fixes

* Fixed issue with embeding Form in AE Templates


= 2.3.2 =
* Fixed: Elementor editor hanging in some cases.
* Tweak: BG Slider - Added option to disable Kendburns effect.

= 2.3.1 =
* Fixed bug causing issue with php 5.x
* Fixed conflict with admin script.


= 2.3 =
* Option to create global taxonomy archive layout.
* Design search page layout.
* Now support Astra Theme
* New Widget: Search Form
* New Widget: Breadcrumb (Required Yoast SEO installed)
* Bug fixes
    - Author widget: border radius not working when no link is selected
    - CSS issue in Post Navigation widget
    - Fixed issues in license activation.


= 2.2.1 =
* Fixed issue with OceanWP single template which got broken after last update.
* Removed alert message from ACF gallery widget.

= 2.2 =
* New: Background Slider (Add Background Slider to Sections & Columns)
* New Widget: ACF Gallery
* New Widget: Woo Products (Show related and upsell products on Woo Single Product Layouts)
* New Widget: Woo Notices (Show WooCommerce message section on top of Woo Product Page)
* New Widget: Post Comments (allows you to place your theme's comments section into AE Templates. More enhanced layout with customization options will come soon)
* Tweaks
    - Post Image widget - Added option to disable or change link type(Full Image/Post Link)
    - Now allows you to edit post content with Elementor even if AE Post layout is applied over it.
* Bug Fixes
    - AE Template for Pages were not working properly after last update.
    - AE template were not working if a custom post type is created with slug ‘product’
    - WooCommerce Scheme Structured Data was missing when using Ae Template for Single Product layout
    - Woo Add to Cart - Styling controls were not working for Variable products.

= 2.1 =
* New: Post Blocks Widget (Show posts in grid/list with layout of your choice)
* New: Author Widget (Display & Design author data like avatar,author name, author meta, author bio etc.)
* New: Full control over taxonomy archive layout with elementor canvas support.
* New: Full control over blog page/ CPT Archive layout with elementor canvas support.
* New: Ability to design 404 Template along with option to choose canvas template.
* Tweak: Added ACF Pro formatting support for date field.
* Tweak: Overlay option for Post Image widget.
* Fix: Issues with AE Template export.
* Fix: Compatibility with Elementor 1.5
* And lot of other minor enhancements, fixes and code improvements.

= 2.0 =
* New: WooCommerce Layout Designer
* New: Custom Post Type Archive Supported now.
* New: Support for oEmbed in custom field widget
* Tweak : Custom field link mode - option to open in new tab

= 1.3 =
* Fixed conflict with WooCommerce
* Corrected typo in author uri
* Fixed warning on Post Type Archive pages. (Post Type Archive support will be there in next release)


= 1.2 =
* Fixed issues in Post Navigation widget
* Post Meta Widget: Now supports modified date and published date
* Post Custom Field Widget: Now allows link, image and video from custom fields

= 1.1 =
* Fixed some issues with OceanWP single post layout.

= 1.0 =
* Plugin initial release