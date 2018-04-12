=== Elementor Extras ===
Contributors: 		Namogo
Tags: 				elementor, addons, hotspots, image comparison, heading, parallax, sticky, widgets, button groups, text separator, circle progress, devices, timeline
Requires at least: 	4.5
Tested up to: 		4.9.4
Stable tag: 		1.9.2
License: 			GPLv3 or later
License URI: 		http://www.gnu.org/licenses/gpl-3.0.html

Elementor Extras is a premium Wordpress plugin for Elementor, extending its capability with seriously useful new widgets and extensions

== Description ==

Elementor Extras is a premium Wordpress plugin for Elementor. It extends its capability with a lot of new widgets and extensions that make Elementor even more powerful.

== Installation ==

1. Make sure Elementor is installed and active on your website
2. Activate the plugin through the 'Plugins' menu in WordPress
2. Edit a page (or post) using Elementor
3. Find and use the new widgets and extensions

== Frequently Asked Questions ==

= Does Elementor Extras work with any version of Elementor? =

* We usually check compatibility whenever a new Elementor or WordPress update is available. Like with any other products that depend on a particular plugin, we have no way of knowing what those updates are and how that impacts this product. We always encourage waiting a few days before updating plugin on a live environment.

= Can I use this plugin with the free version of Elementor? =

* Most of the widget work with the free version. Extensions such as Parallax for the Portfolio widget (which comes with the Pro version of Elementor) will only work with Elementor Pro. The Posts Extra widget required Elementor Pro installed and activated.

= On the Single Site license, can I activate the plugin on a development environment? =

* Yes. You can activate licenses on local environments without limit and they won't take from your allowed activations count.

== Changelog ==

= 1.9.2 =
* Fixed: Switcher — Bug with arrow loader position and stroke width in older versions of Safari
* Fixed: Posts Extra — Compatibility with IE 11
* Fixed: Parallax Elements — Control value undefined check

= 1.9.1 =
* Fixed: Parallax Elements — Max distance not taking into account scroll position
* Fixed: Posts Extra — Filters not stacking on responsive.
* Fixed: Switcher — Arrow loader not displaying in firefox
* Fixed: Parallax Elements — Wrong position for scroll with start position
* Fixed: GSAP dependencies

= 1.9.0 =
* Added: New Switcher Widget! — Switch photos and text using powerful javascript driven transitions. Features menu and arrows navigation, autoplay with progress loader, entrance animations, page background color switcher, two skins (default and overlay) and much more.
* Added: New Feature for Parallax Elements! — Option to parallax columns and widgets on mouse move
* Added: Posts Extra — Controls to style only sticky posts
* Added: Image Comparison — Option to enable clicking on labels to uncover images
* Added: Hotspots — Separate color controls for each hotspot
* Fixed: Posts Extra — Sticky posts not working when a category is selected in the query
* Fixed: Gallery Slider — Invisible gallery when preview position is top on desktop
* Fixed: Image Comparison — Remove labels if no text is present
* Fixed: Posts Extra — Hide carousel until properly rendered
* Fixed: Gallery Extra — Added alt and title tags to image element
* Fixed: Gallery Slider — Issue with negative margin resulting in right whitespace for the gallery
* Fixed: Timeline — Controls conditions
* Fixed: Timeline — Item point color override
* Fixed: Timeline — Properly override point content
* Tweak: Parallax Elements — Removed group control
* Tweak: Gallery Extra, Gallery Slider, Posts Extra — Added notice with link to blend mode browser support for blend mode controls
* Tweak: Timeline — Added repeater setting key method to card render function

= 1.8.8 =
* Added: Gallery Extra — Control width and ratio of each individual image in manual mode
* Added: Timeline — Option for automatic numbering and letters instead of icons to points
* Added: Timeline — New control to set excerpt length
* Added: Unfold — Option to change icon when unfolded
* Added: Sticky Elements — Option to turn off bottoming of elements inside the parent
* Added: Posts Extra — Option to enable sticky posts in query
* Added: Posts Extra — Arrows sizing controls
* Added: Posts Extra — Transition control for carousel arrows and pagination
* Added: Posts Extra — Option to set how many slides to scroll
* Added: Image Comparison — Control to define initial position of separator
* Added: Image Comparison — Option to enable moving separator by clicking anywhere on the image
* Added: Buttons — Control for label min width
* Added: Hotspots — Control to disable pulsating animation
* Fixed: Sticky Elements — Controls not showing on inner sections
* Fixed: Gallery Extra — Images wider than 100% on mobile in manual mixed masonry layout mode
* Fixed: HTML5 Video — CSS bottom whitespace
* Fixed: Buttons — Remove block label for buttons icon, id and class controls
* Tweak: Timeline — Take sticky posts into consideration in query
* Tweak: Unfold — Organise controls
* Tweak: WPML Compatibility — Change method to fix fields not saving

= 1.8.7 =
* Fixed: Posts Extra — Filtering one widget's posts affects all other widgets
* Fixed: Hotspots — Tooltips have an extra space at the bottom when first hovering
* Fixed: HTML5 Video — Volume functionality breaks when controls are hidden
* Fixed: HTML5 Video — Video reloading after each loop
* Added: Global Tooltips — Global controls for distance and width
* Tweak: HTML5 Video — Added width and horizontal alignment control to player
* Tweak: Global Tooltips — Remove default global styling values after Elementor enqueue fix in 1.9.5

= 1.8.6 =
* Added: Posts Extra — Term slug classes to filters and post terms
* Fixed: Replaced create_function with anonymous function for PHP 7.2 support
* Fixed: Inline SVG — Handle internal CSS in non color overriding situations
* Fixed: Hotspots — Tooltips showing even if they have no content
* Tweak: Sticky Elements — Prevent sticky on a section that is stretched with js
* Tweak: Buttons — Move typography control above tabs
* Tweak: Posts Extra — Move hover animation controls to separate section for better ux
* Tweak: Gallery Extra — Moved hover controls to separate section
* Tweak: Gallery Slider — Moved hover controls to separate section
* Tweak: Gallery Slider — Default font size for thumbnail captions
* Tweak: Posts Extra — Fix condition
* Tweak: Hotspots — Show label for tooltip content in editor

= 1.8.5 =
* Fixed: Gallery Extra — Missing js dependency

= 1.8.4 =
* Fixed: Gallery Extra — Random ordering not working
* Fixed: Timeline — RTL optimisation
* Fixed: Gallery Extra — Custom image size not working on manual mode
* Tweak: Add dismissible notice for recommended php version
* Tweak: Gallery Extra — Add title and description caption types
* Tweak: Timeline — Add link control description to avoid linking cards when content already contains links


= 1.8.3 =
* Fixed: HTML5 Video — Video not playing on Safari and some mobile environments
* Fixed: Buttons — Safari buttons vertical alignment
* Fixed: Devices — device not displaying on Safari when portrait mode is enabled
* Fixed: Gallery Extra — Grid broken on Safari
* Fixed: Gallery Extra — reinit masonry when images done loading
* Tweak: Move all Extras admin settings into Elementor menu
* Tweak: Image Comparison — Remove default value for width control
* Tweak: Gallery Slider — default mobile and tablet gallery columns to larger numbers

= 1.8.2 =
* Added: Parallax Background — Support for section resize in elementor preview mode
* Fixed: Parallax Background — Compatibility with stretched section
* Fixed: HTML5 Video — Interface style controls conditions
* Fixed: Gallery Slider — Right side white-space after thumbnails on top preview position
* Fixed: Unfold — Page stops responding when entering invalid html in text editor mode
* Tweak: Unfold — Allow accordions, tabs and all resizing elements inside content


= 1.8.1 =
* Fixed: Posts Extra — Masonry broken when infinite scroll is enabled

= 1.8.0 =
* New extension: Global Tooltips — Add a tooltip to any widget
* New: Disable Widgets & Extensions — You can now disable Extras widgets and extensions via the admin settings page
* Added: WPML compatibility — String Translation and Translation Editor support for all widgets
* Added: Circle Progress — Icon support inside circle
* Added: Update .pot translation file
* Added: Buttons — Tooltips duration control
* Added: Global settings for Extras tooltips
* Fixed: Posts Extra — Widget not available on multisite installations
* Fixed: Posts Extra — Infinite scroll breaks when using multiple widgets on same page
* Fixed: Parallax Background — Conflict with Anywhere Elementor Pro featured image background
* Tweak: Parallax Background — requestAnimationFrame method for better performance
* Tweak: Buttons — Rename text align label
* Tweak: Update nicons font

= 1.7.4 =
* Fixed: Gallery Extra — Custom link broken and attributes not added

= 1.7.3 =
* Added: Posts Extra — Column vertical alignment controls for carousel
* Added: Timeline —  Color controls for post title and excerpt
* Fixed: Posts Extra — Carousel rows number control not impacting preview
* Fixed: Posts Extra — Avatar vertical alignment on stretched media content
* Fixed: Sticky Elements — Incorrect spacer height
* Tweak: Posts Extra — Improve editing flow by with advanced controls conditioning
* Tweak: Posts Extra — Controls position
* Tweak: Sticky Elements — Remove popover from group control
* Tweak: Parallax Elements — Remove popover from group control


= 1.7.2 =
* Added: Gallery Slider — Overlay captions for preview and thumbnails
* Added: Gallery Slider — Controls for caption effects, layout and hover properties
* Added: Gallery Slider — Controls for active thumbs caption
* Fixed: Buttons — Removed popover control on effects
* Tweak: Gallery Slider — Improved carousel adapt on preview resize
* Tweak: Gallery Slider — Improved thumbnails grid
* Tweak: Gallery Slider — Better controls for stacking and position of preview
* Tweak: Gallery Slider — Replace background color with background control for preview captions and overlay
* Tweak: Gallery Slider + Gallery Extra — Better BEM classes

= 1.7.1 =
* Added: Gallery Extra — Caption horizontal and vertical alignment and hover effects
* Added: Gallery Extra — Ratio and alignment controls to masonry layout
* Added: Gallery Extra — New border and border radius controls for captions
* Fixed: Gallery Extra — Bring image to front when hovered
* Fixed: Posts Extra — Images not displaying on infinite load on iOS Safari and Chrome
* Tweak: Image Comparison — Force override of modified image max-width attribute
* Tweak: Posts Extra — Added extra classes to media elements

= 1.7.0 =
* Added: Gallery Extra — Masonry layout with mixed mode
* Added: Gallery Extra — Tilt effect
* Added: Gallery Extra — Caption horizontal and vertical alignment and hover effects
* Added: Gallery Extra — Overlay, caption and image transition controls
* Added: Gallery Extra — Overlay blend mode control
* Added: Gallery Extra — Image scale controls for default and hover states
* Added: Gallery Extra — Overlay margin controls for default and hover states
* Added: Gallery Extra — Manual mode that allows entering of every image manually and linking it to any URL
* Added: Inline SVG widget
* Added: Posts Extra — Controls to disable linking for post title, media and terms
* Added: Posts Extra — Control to select default applied filter for filter bar
* Added: Timeline — New controls for post title typography and spacing, post excerpt typography and padding
* Added: Image Comparison — Image size control
* Added: New css transition control for Gallery Extra, Gallery Slider, Hotspots, Posts Extra, Timeline, Table and Inline SVG widgets
* Added: HTML5 Video — M4V format support
* Added: Devices — M4V format support for video
* Fixed: Buttons — Flicker on flip effect
* Fixed: Timeline — CSS class conflict
* Fixed: Table — ID and class don't apply to header
* Tweak: Improved Elementor 1.9 compatibility for Extras group controls
* Tweak: Update TweenMax to latest version (1.20.2)
* Tweak: Posts Extra — Simplified CSS
* Tweak: Unfold — Disconnect button and content from control alignment logic

= 1.6.3 =
* Fixed: Breadcrumbs — Display correctly when used within an Elementor library template
* Tweak: Posts Extra — Condition terms separator
* Tweak: Breadcrumbs — Avoid naming conflict with Elementor Pro widget

= 1.6.2 =
* Added: Posts Extra — New autoplay, autoheight and fade effect controls
* Added: Posts Extra — "Top" option to media position on horizontal layout
* Added: Posts Extra — Allowed media to be positioned horizontally on 2 columns layouts
* Added: Posts Extra — New border controls for media
* Added: Table — Allow table responsive behaviour to be turned off
* Tweak: Buttons — Default button content alignment to justify
* Fix: Timeline — Potential fatal error
* Fix: Posts Extra — Post media position controls conditions
* Fix: Buttons — Delay on transitioned properties
* Fix: Posts Extra — Wrong tablet breakpoint set for number of columns
* Fix: Parallax Background — Remove initial background image once parallax is activated



= 1.6.1 =
* Added: Posts Extra — Link author name and avatar to author posts pages
* Added: Posts Extra — Read more link to excerpt
* Tweak: Posts Extra — Added separate hide option for Position for better UX 
* Tweak: Posts Extra — Improved position icons

= 1.6.0 =
* New Widget — Posts Extra - Create ANY layout for posts with masonry, infinite load, filtering and custom post type support. Works only with Elementor Pro
* Tweak: Breadcrumbs — Added horizontal position and text alignment controls
* Tweak: Buttons — Improved CSS and better aligment and stacking options
* Fixed: Breadcrumbs — Error outputting separator
* Fixed: Buttons — Transition on button content when effects are turned on

= 1.5.6 =
* Fixed: Parallax Background — Replaced parallax class name to avoid future conflicts
* Fixed: Timeline — Remove flex from left aligned timeline and allow image spacing for horizontal layouts
* Fixed: PHP fatal error
* Tweak: Unfold — Switched Settings with Content sections in editor for better UX

= 1.5.5 =
* Added: Buttons — Inline editing
* Added: Breadcrumbs — Inline editing
* Added: Circle Progress — Inline editing
* Added: Hotspots — Inline editing
* Added: Image Comparison — Inline editing
* Added: Table — Inline editing
* Added: Text Divider — Inline editing
* Added: Timeline — Inline editing
* Added: Unfold — Inline editing
* Fixed: Tooltips — Error "size" of undefined appears for delay_in in some cases
* Fixed: Unfold — Issue that resulted in wrong calculation of unfolded height
* Tweak: Default to text cursor for text where inline editing is available

= 1.5.4 =
* Added: HTML5 Video — Option to end video at last frame
* Tweak: HTML5 Video — Removed auto pausing when interacting with progress bar

= 1.5.3 =
* Fixed: Unfold — Content folds back automatically when scrolling on mobile browsers that vertically resize the viewport

= 1.5.2 =
* Added: Gallery Extra — Allow gallery navigation in lightbox
* Fixed: Gallery Extra — Image box shadow not applying
* Fixed: Buttons — Issue with transition delays on buttons without effects

= 1.5.1 =
* Added: Table — Control to show header on mobile as a block or a column
* Added: Table — Border control for table rows
* Added: Table — Option to automatically try to fetch correct headers for mobile with override possibility
* Added: Table — Control to set width for mobile headers
* Tweak: Table — Remove column rules from mobile
* Tweak: Table — Removed first / last child border rules from mobile
* Tweak: Table — Added pointer cursor on sortable header cells
* Tweak: Table — Moved padding to text for better handling of responsive layout and removed negative margin technique
* Tweak: Table — Responsive cells and header cells padding controls
* Tweak: Table — Move cell header control to top
* Tweak: Table — Added option to hide headers completely on mobile
* Tweak: Table — Hide mobile headers that have no content
* Tweak: Sticky — Set default z-index to 1 for stickable elements
* Fixed: Image Comparison — Separator not showing
* Fixed: Image Comparison — Remove space between images and widget container
* Fixed: Table — PHP Warning when allowing empty on repeater fields
* Fixed: Table — Width and aligment not working
* Fixed: Table — Added missing custom class and id from markup

= 1.5.0 =
* New Widget: Table
* Fixed: Prefix CSS helper classes to avoid potential conflicts

= 1.4.0 =
* New: Buttons Effects
* Added: Breadcrumbs — Control to manually specify which post or page to generate breadcrumbs for
* Fixed: Devices — Horizontal orientation image not visible

= 1.3.1 =
* Fixed: Parallax Background — Wrong position when loading page off viewport
* Fixed: Timeline — Enlarged points not aligned to line on tablet and mobile
* Added: Timeline — Responsive controls for point size and icon size

= 1.3.0 =
* Added: New Widget! — HTML5 Video
* Added: Devices — Added HTML5 Video controls
* Added: Devices — Added option to stretch the video to fit into the device screen
* Added: Devices — Added option to choose wether or not to stop the video when leaving viewport
* Added: Timeline — Hover properties for points
* Added: Timeline — More style override options for each card
* Added: Gallery Slider — More responsive controls for preview position and spacing
* Fixed: Timeline — Removed unnecessary z-index from cards
* Fixed: Gallery Slider — Preview spacing doesn't work on mobile when the layout is vertical
* Fixed: Devices — Replaced SVG IDs with classes to avoid code invalidation
* Fixed: Circle Progress — Canvas distorted on responsive
* Fixed: Hotspots — Links concatenating from one repeater field to another
* Fixed: Parallax Background — Images move up after scrolling up and down multiple times
* Fixed: Parallax Background — Background position is off when section Stretch is turned on
* Fixed: Hotspots — Links inside tooltips appear outside of the tooltip
* Tweak: Timeline — Changed "Size Ratio" name to "Scale"
* Tweak: Gallery Extras — Bring 3D items to front on hover
* Tweak: Hotspots — Moved tooltip content outside hotspot to avoid invalid nodes
* Tweak: Hotspots — Don't activate tooltips that have no content set

= 1.2.5 =
* Tweak: Replaced text align icons with horizontal align icons where appropriate
* Fixed: Sticky elements not bottoming out correctly when using custom parent selector
* Fixed: Removing unstick for any breakpoint would break sticky functionality

= 1.2.4 =
* Added: Sticky — Custom selector for sticky parent to allow for elements to stick in any parent element
* Tweak: Timeline — Responsive padding controls
* Tweak: Timeline — Refactoring and improved performance and accuracy on progress bar
* Tweak: Image Comparison — Adjust spacing method for labels and vertical alignment improvements
* Tweak: Image Comparison — Allow for widget border radius control to affect images
* Fixed: Parallax Background — Scroll breaks on mobile when hiding a parallaxed section
* Fixed: Timeline — Cards not animating when first in view
* Fixed: Timeline — Content wrapper present even if no card content is set


= 1.2.3 =
* Fixed: Parallax Background — Section background not reverting after parallax turned on again
* Fixed: Parallax Background — Calculating wrong dimensions when using Elementor JS stretch option
* Fixed: Issue where updating Elementor when Extras is active would result in an error
* Tweak: Hotspots — Adjusted default tooltip width

= 1.2.2 =
* Fixed: Heading Extra — Complete html support

= 1.2.1 =
* Fixed: Heading Extra — Gradient would show only for the first line
* Fixed: Parallax Background — When using padding on sections, image position calculation would not be correct

= 1.2.0 =
* Added: New Feature! — Parallax Background — Now you can parallax section backgrounds and have it move up, down, left or right while scrolling
* Added: New Widget! — Unfold — Lets you hide content and reveal it on demand
* Added: New Widget! — Breadcrumbs
* Added: Parallax Elements — Allow to select whether to parallax an element relative to its own position or the middle of the viewport
* Added: Hotspots — Ability to add links to hotspots if tooltips are triggered on hover
* Added: Tooltips — Added left and right tooltips for both Hotspots and Button Groups
* Added: Tooltips — Better handling of repositioning tooltips when they flow outside of the viewport
* Added: Tooltips — Ability to override tooltip position for each button of hotspot individually
* Added: Heading Extra — Text fill now supports clipped images
* Tweak: Heading Extra — Allow to have both text shadow and long shadow on the same text
* Tweak: Text Divider — Responsive alignment and spacing controls
* Tweak: Text Divider — Proper horizontal alignment of text and separators
* Fixed: Heading Extra — Multiple z-index related issues
* Fixed: Parallax Elements — Fix issue where elements would jump when scrolling

= 1.1.4 =
* Added: Sticky sections!
* Added: Sticky — New controls to unstick for mobile and tablet
* Added: Parallax Elements — New controls to set different speeds for different breakpoints
* Added: Gallery Slider — Links on images and support for the new Elementor lightbox feature
* Tweak: Improved shortcode support for all widgets, especially tooltips
* Tweak: Parallax Elements — Greatly improved performance for parallaxed items
* Tweak: Parallax Elements — Changed speed control logic to allow for reverse parallax
* Tweak: Created custom group controls for Sticky and Parallax features
* Fixed: Removed z-index from sticky elements to allow z-index setting from Elementor controls
* Fixed: Gallery Extras — Some styling controls were conditioned by the "Link to" control
* Fixed: Removed potentially buggy call to Elementor class

= 1.1.3 =
* Tweak: Devices — Refactored and cleaned up code for editor functionality
* Tweak: Gallery Extra — Moved distance control after parallax controls
* Added: Controls to add parallax to any widget or column
* Added: Global control for turning off parallax functionality for tablet and mobile
* Added: Gallery Extra — Control for settings horizontal overlap of images
* Added: Image Comparison — Separator between images
* Added: Global — Implemented the new Text Shadow controls for all widgets
* Added: Button Groups — Responsive control of spacing for stacked buttons
* Fixed: Devices — Issue where frames would not properly change in editor mode
* Fixed: Changed license page capability to 'manage_options'
* Fixed: Compatibility with Elementor 1.6

= 1.1.2 =
* Fixed: Class namespace change in Elementor Pro 1.5.7 resulting in fatal error

= 1.1.1 =
* Fixed: Devices — Overlay not visible for laptop and desktop
* Fixed: Timeline — PHP empty function fatal error
* Fixed: Gallery Slider — Width remained 50% even if vertical layout selected

= 1.1.0 =
* Added: New Widget! — Gallery Extra — We've taken aside the parallax functionality and created a whole new widget with nots of posibilities.
* Added: New Widget! — Gallery Slider — A gallery layout with inline preview.
* Added: New Feature! — Button tooltips — You can now use the Button Groups widget to add buttons with tooltips.
* Added: New Feature! — Timeline — Integration with Woocommerce products.
* Added: New Feature! — Hotspots — Added support for icons inside hotspots.
* Added: New Feature! — Hotspots — Ability to set the delay for both entrance close animations.
* Added: Circle Progress — Control too choose start angle for the circle progress.
* Added: Devices — Support for multiple video formats
* Added: Timeline — Add featured images from separate control.
* Added: Elementor Portfolio Widget — Speed control for parallax feature.
* Added: Timeline — Turn on and off for excerpts, content, title and featured image.
* Fixed: Image Comparison — Handle now goes all the way until the end of the image.
* Fixed: Timeline — Date color wasn't changing properly.
* Fixed: Issue where disabling and changing license was not possible.
* Fixed: Sticky - Issue where in preview mode wrong height of the element was calculated.
* Fixed: Image Gallery — Issue with displaying on Firefox.
* Fixed: Image Comparison — Handle jumps when using multiple widgets on same page.
* Fixed: Timeline — Scheme style override issue for points.
* Fixed: Devices — Video would not play on iOS devices

= 1.0.5 =
* Added: Button Groups — Box shadow control.
* Fixed: Ensured compatibility with other jquery appear plugins.
* Fixed: Issue where disabling and changing license was not possible.

= 1.0.4 =
* Added: Added translation support
* Fixed: Devices — Issue where controls would always display as a white rectangular shape.

= 1.0.3 =
* Fixed: Image Galleries — Issue where galleries with no links would break the layout
* Fixed: Hotspots — When using multiple hotspots widgets on the same page additional widgets would show the first widget's tooltips instead of their own

= 1.0.2 =
* Added: Hotspots — New controls: text align, padding, border and typography
* Fixed: Hotspots — Hide arrow control not working
* Fixed: Hotspots — Issue where tooltips don't remain open on click trigger option and thus preventing clicking off links inside tooltips
* Fixed: Button Groups — Replaced custom border controls with border group control
* Fixed: Extended Heading — Compability with Elementor 1.5
* Fixed: Extended Heading — Heading size not working
* Fixed: Image Comparison — Issue where colors of labels would not change colors for printed templates

= 1.0.1 =
* Fixed: Image Comparison — Issue where dragging the handle on mobile would not work

= 1.0.0 =
* Added: New "Timeline" widget with posts support for Elementor Pro
* Added: Image Comparison — Different colors for both labels
* Tweak: Image Comparison — Moved alignment & scaling options to widget wrapper
* Tweak: Aligned default widgets colors to selected color scheme
* Tweak: Removed sticky from sections temporarily
* Tweak: Removed parallax scrolling from section backgrounds
* Tweak: Button Groups — Reorganized repeater controls under tabs
* Tweak: Full compatibility with global color schemes for all widgets
* Tweak: Hotspots — Reorganized repeater controls under tabs
* Fixed: Timeline — Issue where arrow colors would not apply
* Fixed: Extended Heading — Entrace animation hides the shadow when animation is complete
* Fixed: Devices — Border type not present in hover state for video controls
* Fixed: Image Comparison — Removed small space after widget
* Fixed: Hotspots — Syntax error in JS template
* Fixed: Hotspots — JS error when adding new hotpots
* Fixed: Hotspots — Tooltips z-index issue
* Fixed: Hotspots — Bottom tooltips animated down instead of up
* Fixed: Hotspots — Html being added as string when hotspots are triggered through the open_editor hook
* Fixed: Hotspots — Tooltips not editable immediately when dragged into preview
* Fixed: Image Comparison — images are not set to occupy full width


= 0.1.2 =
* Added: Hotspots — Rewrote code entirely to allow for preview mode support, customisation of tooltips and faster rendering
* Added: Circle progress — Better handling of value suffix
* Fixed: Scripts dependencies
* Fixed: Devices — Issue where global colors where used instead of skins
* Fixed: Various compatibility issues with the Elementor color scheme
* Fixed: Image Gallery Extension — Styling issues when choosing to justify gallery items
* Fixed: Sticky Extension — Sitcky offset would not preview correctly and sometimes having multiple sticky widgets would cause interference amongst them
* Fixed: Parallax Gallery — Removed any kinds of transformation for odd children

= 0.1.1 =
* Fixed: Buttons groups — Responsive alignment of buttons
* Added: Buttons groups — option to stack buttons on tablet or mobile

= 0.1.0 =
* Initial Private Beta

== Upgrade Notice ==

= 1.0.1 =
This update fixes a bug on the Image Comparison widget that prevented dragging functionality on mobile devices

= 0.1.2 =
This version adds responsive stacking of buttons inside the Buttons Groups widget and fixes some aligment issue on certain breakpoints

= 0.1.1 =
In this version tooltips for Hotspots widget now work inside the Elementor editor, plus other great enhancements. See the changelog.

= 1.1.0 =
Important release with new widgets and a lot of updates. Please test on a development environment before updating your live installation.

= 1.1.1 =
Critial release: Fixes a very important PHP fatal error

== Copyright and licensing ==

This plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/

You can contact us at office@namogo.com

Elementor Extras incorporates code from:

— jquery-circle-progress v1.2.2, Copyright Rostyslav Bryzgunov Licenses: MIT Source: link http://kottenator.github.io/jquery-circle-progress/
— jQuery appear plugin v0.3.6, Copyright 2012 Andrey Sidorov Licenses: MIT Source: link https://github.com/morr/jquery.appear/
— LongShadow jQuery Plugin v1.1.0, Copyright 2013 - 2016 Dang Van Thanh Licenses: MIT Source: link git://github.com/dangvanthanh/jquery.longShadow.git
— Sticky-kit v1.1.3, Copyright 2015 Leaf Corcoran Licenses: MIT Source: link http://leafo.net
— jQuery Mobile v1.4.3, Copyright 2010, 2014 jQuery Foundation, Inc. Licenses: jquery.org/license
— jquery-visible, Copyright 2012, Digital Fusion, License: http://teamdf.com/jquery-plugins/license/ Source: http://teamdf.com/jquery-plugins/license/
— Parallax Background v1.2, by Eren Suleymanoglu Licenses: MIT Source: link https://github.com/erensuleymanoglu/parallax-background
— TableSorter v2.0.5b, Copyright 2007 Christian Bach Licenses: Dual licensed under the MIT and GPL licenses Source: link http://tablesorter.com
— Isotope PACKAGED v3.0.4, Copyright 2017 Metafizzy License: GPLv3 Source: link http://isotope.metafizzy.co
— Infinite Scroll PACKAGED v3.0.2, Copyright 2017 Metafizzy License: GPLv3 Source: link https://infinite-scroll.com
— Packery layout mode PACKAGED v2.0.0 Copyright 2017 Metafizzy License: GPLv3 Source: link http://isotope.metafizzy.co
— javascript-detect-element-resize 0.5.3 Copyright (c) 2013 Sebastián Décima License: MIT Source: link https://github.com/sdecima/javascript-detect-element-resize
— tilt.js 1.2.1 Copyright (c) 2017 Gijs Rogé License: MIT Source: link https://github.com/gijsroge/tilt.js