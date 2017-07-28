=== Glossary ===
Contributors: codeat, iGenius, mte90
Donate link: http://codeat.com/
Tags: glossary, vocabulary, dictionary, tooltip, terms, lexicon, knowledgebase, knowledge base, reference, terminology, catalog, directory, index, listing, literature, appendix,
Requires at least: 4.6
Tested up to: 4.8
Stable tag: 1.4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add and manage a glossary with auto-link, tooltips and more. Improve your internal site link building for a better SEO.

== Description ==

If you are looking for a plugin that provides the definitive Glossary Section for WordPress this is the right one!

Write a glossary or a dictionary section in your WordPress website. Every term of the glossary and every variation of them (E.g. "Call To Action" and "CTA" also) will occur in posts, pages or custom post types will became automatically a link to the Term page or even an external URL.

If you are looking for a solid strategy to improve your internal site link building and improve your SEO this is one of the best method we've tried and we incourage you to try this: it's for free!

You can also choose to put some tooltips on the referenced words and improve your website user experience, we provided 3 beautiful tooltip's templates in order to make it fit your design.

Are you an affiliate marketing specialist?
Using this plugin you can also use some affiliations URL in your terms and write a description of them for the tooltip area that will popup on hover so you'll be able to convert more users.

Shortcode list: https://codeat.co/glossary/shortcodes/

[PLUGIN DEMO SITE](http://codeat.co/glossary)

/ ----- FREE VERSION ----- /

* Term Post Type
* Glossary Archive
* Automatic Link Engine
* 3 Tooltip Templates
* Internal or External Linking
* Optional External link icon
* Tooltip Mode: Link and tooltip, Only Link
* Widgets: Latest Glossary Terms, Glossary Categories, Alphabet Categories
* Standard Widgets Template
* Standard Shortcodes
* YARPP plugin support
* Crayon Syntax Highlighter, Ninja Forms, Yoast SEO, YARPP plugin Supported
* Migration from CM Glossary Tooltip supported
* No Direct Support

/ ----- PRO VERSION ------ /

* All Free Feature
* Pro Shortcodes
* 7 Tooltip Templates
* Tooltip Template Customizer
* Disable Tooltip on mobile
* Terms Custom Fields
* Tooltip Mode: Only Tooltip
* Case Sensitive Terms Matching
* Widget Alphabet Categories with 5 themes
* Search Widget for terms
* Case sensitive term match
* Support for RSS Feed
* Prevent term link to appear in the same term page
* Archive/Category order also by alphabetic
* ACF plugin support
* Mobile tooltip on click
* Direct Support

/ ----- ULTIMATE VERSION ------ /

* All Pro Feature
* Media support for Youtube, Vimeo, Soundcloud

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'glossary'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `glossary.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `glossary.zip`
2. Extract the `glossary` directory to your computer
3. Upload the `glossary` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Frequently Asked Questions ==

= Could I use more than a term title pointing to the same term page? =

Yes, you are able to use how many term titles you want using the "Related search terms" field into your WordPress Glossary Editor Page. In this way you'll be able to auto-link more than one term to the same page (E.g. Call To Action, CTA, Action Button)

= Could I use your auto-link engine for affiliation purposes? =

Yes, and we encourage you to do it.
E.g. Amazon Glossary Term links to your Amazon affiliation URL. Everytime one of your users hover on the Amazon link is able to see your tooltip but if they clicks suddenly will redirected on your affiliate link.

= Is it Glossary Genesis Compatible? =

Yes, we love Genesis Framework and we care about other Genesis fans. SEO, Layout and Archive section are fully integrated.

== Screenshots ==

1. Glossary general settings
2. Glossary Single auto-link setting
3. Glossary Terms list in WordPress Dashboard
4. Tooltip Line template
5. Tooltip Box template
6. Tooltip Classic template with featured image
7. Tooltip Classic template without featured image
8. Glossary features

== Changelog ==

= 1.4.4 =
* Fix: With related terms the terms link can be broken
* Improvement: Archive or list of post now are very very fast (caching)
* Feature: New filter `glossary_terms_results`

= 1.4.3 =
* Feature: Support for Crayon Syntax Highlighter
* Feature: Show numbers of total terms in settings
* Feature: Improvement on term queue to ignore duplicated terms
* Improvement: Avoid duplicate execution of Glossary engine on Yoast
* Improvement: Terms sorting implemented
* [PRO] Feature: Support for RSS Feed
* [PRO] Feature: Link only the first occurence of all the term keys

= 1.4.2 =
* Improvement: Removed strip of breakline in content

= 1.4.1 =
* Improvement: Genesis support rewritten from scratch
* Fix: Few fixes in various part

= 1.4.0 =
* Fix: Wrong link for the settings page
* Fix: Improvements on Genesis support
* [PRO] Feature: Mobile support
* [PRO] Fix: Improvements on the tooltip preview
* [ULTIMATE] Feature: First public release of the new plan

= 1.3.6 =
* Improvement: New unit test systems
* Improvement: Reminder to flush the permalink
* [PRO] Feature: Support for ACF 4 & 5 versions
* [PRO] Feature: Support for excerpt in terms
* [PRO] Feature: Widget for search terms

= 1.3.5 =
* Improvement: For shortcode `glossary-list` on Genesis

= 1.3.5 =
* Fix: For empty list of terms
* Fix: For terms inside HTML
* Fix: Load admin.css file in the right pages
* Enhancement: CM Glossary Tooltip supported
* [PRO] Fix: Terms archive wasn't ordered on taxonomy page

= 1.3.4 =
* Fix: Various bugfix

= 1.3.3 =
* Fix: Genesis was not executing shortcodes
* Fix: Fixed behaviour on no-excerpt on Genesis
* Fix: Improved system to calculate strings length to avoid strange html
* [PRO]: Disable tooltip on mobile as settings
* Fix: Various bugfix

= 1.3.2 =
* Fix: For text position
* Fix: Various bugfix
* [ULTIMATE] Feature: Media tooltip for Youtube and Vimeo

= 1.3.1 =
* Enhancement: Improved algoithm to find the right position
* Fix: Improvement to regex for h1,h2* reconize
* Enhancement: Improved code organization

= 1.3.0 =
* Fix: Reset the query in the right way
* Fix: Remove duplicate in excerpt
* Fix: Many bugfix
* Removed: Removed the check for the php version of library included
* Enhancement: Post check outside the method
* Enhancement: Unit test with codeception
* Enhancement: New Internal engine for tooltip/link inject
* Enhancement: New code for Genesis support
* Feature: Disable Glossary in a page with a checkbox

= 1.2.8 =
* Fix: Improvement for the the real string on replacing
* Fix: Adding a try/Catch system to avoid errors in the frontend

= 1.2.7 =
* Features: Support for YARPP
* Improvement: Code improvements for caching
* Fix: Now use the term for the link and not the title term of the post type
* [Pro] New mode Tooltip with no link

= 1.2.6 =
* Fix: Genesis with Glossary created errors

= 1.2.5 =
* Improvement: Updated CMB2 and support for the last version
* Bugfix: Fix on Genesis with no terms
* [PRO]: 5 themes for the alphabet terms widget

= 1.2.4 =
* Fix: really a bug on Genesis

= 1.2.3 =
* Fix: bug on Genesis

= 1.2.2 =

* [PRO] Remove More link settings
* [PRO] Case sensitive term match setting
* [PRO] Prevent term link to appear in the same term page
* Feature: Add an icon to external link setting
* Improvement of the quality of the code

= 1.2.1 =

* Fix: Error on injecting tooltip with breaking HTML

= 1.2.0 =

* Pro version
* Fix: Search support
* Feature: Option to disable public archive for the Glossary taxonomy
* Feature: Option to limit the excerpt by words
* Feature: Filter to change the regular expression `glossary-regex`

= 1.1.2 =

* Fix force sanitization of the terms

= 1.1.1 =

* Fix regression for related terms
* Read more link it is showed only when the text is truncated

= 1.1.0 =

* Search support as option
* Option to change the slugs
* Option to disable archive
* [glossary-terms order="desc" num="20" tax="sport"] new shortcode
* [glossary-cats order="desc" num="20"] new shortcode
* New option for filter by taxonomy in Last Glossary Terms widget
* Choice between the external url or a internal post type for the term
* Code improvements
* Fixed glotpress detection

= 1.0.6 =

* Fix for search
* Enhancement for tooltip ellipses
* Better label names for additional terms

= 1.0.5 =

* Fix for A2Z archive during the search for terms
* Fix before the global search use only terms and posts
* Enhancement for tooltip text by Rasmus Taarnby

= 1.0.4 =
* Fix to scan unlimit glossary terms
* New filters `glossary_tooltip_html` and `glossary_excerpt`

= 1.0.3 =
* New settings to order the Glossary terms archive page alphabetically
* Changed the CSS classes for the tooltip to avoid problems with CSS framework by Diego Betto
* New regular expression to detect the terms

= 1.0.2 =
* Fix to Flush the permalink on activation
* Remove annoying warning by Rasmus Taarnby
* Added composer file by Rasmus Taarnby

= 1.0.1 =
* Fix in case of missing glossary terms

= 1.0 =
* Published on WordPress repo
