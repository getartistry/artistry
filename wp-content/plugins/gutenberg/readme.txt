=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 4.9.8
Tested up to: 4.9
Stable tag: 3.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A new editing experience for WordPress is in the works, with the goal of making it easier than ever to make your words, pictures, and layout look just right. This is the beta plugin for the project.

== Description ==

Gutenberg is more than an editor. While the editor is the focus right now, the project will ultimately impact the entire publishing experience including customization (the next focus area).

<a href="https://wordpress.org/gutenberg">Discover more about the project</a>.

= Editing focus =

> The editor will create a new page- and post-building experience that makes writing rich posts effortless, and has “blocks” to make it easy what today might take shortcodes, custom HTML, or “mystery meat” embed discovery. — Matt Mullenweg

One thing that sets WordPress apart from other systems is that it allows you to create as rich a post layout as you can imagine -- but only if you know HTML and CSS and build your own custom theme. By thinking of the editor as a tool to let you write rich posts and create beautiful layouts, we can transform WordPress into something users _love_ WordPress, as opposed something they pick it because it's what everyone else uses.

Gutenberg looks at the editor as more than a content field, revisiting a layout that has been largely unchanged for almost a decade.This allows us to holistically design a modern editing experience and build a foundation for things to come.

Here's why we're looking at the whole editing screen, as opposed to just the content field:

1. The block unifies multiple interfaces. If we add that on top of the existing interface, it would _add_ complexity, as opposed to remove it.
2. By revisiting the interface, we can modernize the writing, editing, and publishing experience, with usability and simplicity in mind, benefitting both new and casual users.
3. When singular block interface takes center stage, it demonstrates a clear path forward for developers to create premium blocks, superior to both shortcodes and widgets.
4. Considering the whole interface lays a solid foundation for the next focus, full site customization.
5. Looking at the full editor screen also gives us the opportunity to drastically modernize the foundation, and take steps towards a more fluid and JavaScript powered future that fully leverages the WordPress REST API.

= Blocks =

Blocks are the unifying evolution of what is now covered, in different ways, by shortcodes, embeds, widgets, post formats, custom post types, theme options, meta-boxes, and other formatting elements. They embrace the breadth of functionality WordPress is capable of, with the clarity of a consistent user experience.

Imagine a custom “employee” block that a client can drag to an About page to automatically display a picture, name, and bio. A whole universe of plugins that all extend WordPress in the same way. Simplified menus and widgets. Users who can instantly understand and use WordPress  -- and 90% of plugins. This will allow you to easily compose beautiful posts like <a href="http://moc.co/sandbox/example-post/">this example</a>.

Check out the <a href="https://wordpress.org/gutenberg/handbook/reference/faq/">FAQ</a> for answers to the most common questions about the project.

= Compatibility =

Posts are backwards compatible, and shortcodes will still work. We are continuously exploring how highly-tailored metaboxes can be accommodated, and are looking at solutions ranging from a plugin to disable Gutenberg to automatically detecting whether to load Gutenberg or not. While we want to make sure the new editing experience from writing to publishing is user-friendly, we’re committed to finding  a good solution for highly-tailored existing sites.

= The stages of Gutenberg =

Gutenberg has three planned stages. The first, aimed for inclusion in WordPress 5.0, focuses on the post editing experience and the implementation of blocks. This initial phase focuses on a content-first approach. The use of blocks, as detailed above, allows you to focus on how your content will look without the distraction of other configuration options. This ultimately will help all users present their content in a way that is engaging, direct, and visual.

These foundational elements will pave the way for stages two and three, planned for the next year, to go beyond the post into page templates and ultimately, full site customization.

Gutenberg is a big change, and there will be ways to ensure that existing functionality (like shortcodes and meta-boxes) continue to work while allowing developers the time and paths to transition effectively. Ultimately, it will open new opportunities for plugin and theme developers to better serve users through a more engaging and visual experience that takes advantage of a toolset supported by core.

= Contributors =

Gutenberg is built by many contributors and volunteers. Please see the full list in <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTORS.md">CONTRIBUTORS.md</a>.

== Frequently Asked Questions ==

= How can I send feedback or get help with a bug? =

We'd love to hear your bug reports, feature suggestions and any other feedback! Please head over to <a href="https://github.com/WordPress/gutenberg/issues">the GitHub issues page</a> to search for existing issues or open a new one. While we'll try to triage issues reported here on the plugin forum, you'll get a faster response (and reduce duplication of effort) by keeping everything centralized in the GitHub repository.

= How can I contribute? =

We’re calling this editor project "Gutenberg" because it's a big undertaking. We are working on it every day in GitHub, and we'd love your help building it.You’re also welcome to give feedback, the easiest is to join us in <a href="https://make.wordpress.org/chat/">our Slack channel</a>, `#core-editor`.

See also <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTING.md">CONTRIBUTING.md</a>.

= Where can I read more about Gutenberg? =

- <a href="http://matiasventura.com/post/gutenberg-or-the-ship-of-theseus/">Gutenberg, or the Ship of Theseus</a>, with examples of what Gutenberg might do in the future
- <a href="https://make.wordpress.org/core/2017/01/17/editor-technical-overview/">Editor Technical Overview</a>
- <a href="https://wordpress.org/gutenberg/handbook/reference/design-principles/">Design Principles and block design best practices</a>
- <a href="https://github.com/Automattic/wp-post-grammar">WP Post Grammar Parser</a>
- <a href="https://make.wordpress.org/core/tag/gutenberg/">Development updates on make.wordpress.org</a>
- <a href="https://wordpress.org/gutenberg/handbook/">Documentation: Creating Blocks, Reference, and Guidelines</a>
- <a href="https://wordpress.org/gutenberg/handbook/reference/faq/">Additional frequently asked questions</a>


== Changelog ==

= Latest =

* Add an edit button to embed blocks to modify the source.
* Improve margin collapse within column blocks.
* De-emphasize inline tokens within the inserter for a better user experience.
* Polish focus and active styles around buttons and inputs.
* Polish styles for checkbox component, update usages of toggle to checkbox where appropriate. Update documentation.
* Improve pre-publish panel styling and textual copy.
* Prevent duplicate DotTips from appearing.
* Integrate "queries data" into the entities abstraction for data module.
* Hide block movers if there are no blocks before and after.
* Initial improvements for responsive image handling in galleries.
* Use correct color for primary button bottom border.
* Allow transitioning post status from scheduled to draft.
* Improvements for auto-completer keyboard interactions.
* Place strikethrough formatting button after link as it's less important.
* Resolve issue with preview sometimes opening redundant tabs.
* Align timepicker with calendar on pre-publish panel.
* Expand date filter select box width within media library.
* Constrain media blocks to content area width in front-end.
* Reapply box-sizing to slider thumbs.
* Avoid showing line separator in block settings menu when it's the last item.
* Introduce additional keyboard shortcuts to navigate through the navigateRegions component.
* shift+alt+n to go to the next region.
* shift+alt+p to go to the previous region.
* Replace all withAPIData usage and deprecate the higher-order component.
* Add persistence via data plugin interface.
* Introduce new redux-routine package for synchronous generator in data module.
* Move embed API call out of block and into data module.
* Remove no longer needed workaround targeted at resolving a TinyMCE error.
* Abort selection range set on unset range target. Resolves an issue when merging two empty paragraph blocks created while at the end of an inline boundary.
* Removing or merging RichText should only trigger if the selection is collapsed:
* Fix issue with backspace not working as expected when deleting text content from the first block.
* Fix case where paragraph content could move to previous paragraph when deleted.
* Remove provisional block behaviour to improve reliability of various interactions.
* Restore horizontal edge traversal implementation to address issue where pressing Backspace may not place the caret in the correct position if within or after a RichText field.
* Ensure Gutenberg is disabled when editing the assigned blog posts page.
* Initialize the Autosaves controller even if revisions are disabled. Fixes several bugs around saving with revisions turned off.
* Display warning when Cloudflare blocks REST API requests.
* Improve validation for attribute names in serializer.
* Add Slot to block menu settings for extensibility.
* Fix File Block center align behavior.
* Fix behaviours when deleting on an empty RichText field.
* Fix parent-dropdown missing for custom post-types.
* Fix import style statements in ColorIndicator.
* Fix height of used-once block warning.
* Fix link for innerBlocks docs.
* Fix link to server-side-render component.
* Fix race condition with DomReady.
* Fix awkward capitalisation in demo post content.
* Fix warning for unrecognised forwardedRef prop.
* Fix regression with URL input focus box.
* Fix error in custom HTML preview when block is empty.
* Fix colspan bug in table block for tables with thead tags.
* Fix issue with image inspector controls disappearing once an image block is set to wide/full alignment.
* Fix issue when image size remains blurry if manually set to a smaller size (i.e., medium) and then changed alignment to wide/full.
* Fix issue with meta boxes being absent when script enqueued in head depends on wp-edit-post.
* Resolve an issue where removing all text from a Button block by backspace would cause subsequent text changes to not be accurately reflected. Broader issue with TinyMCE inline elements as containers.
* Avoid using remove() because it's unavailable in IE11.
* Address further feedback on duplicated DotTips implementation.
* Update re-resizable to version 4.7.1 — fix image & spacer blocks resizing on IE.
* Use a unique querystring package instead of three different ones.
* Introduce filters to allow developers the ability to customize the Taxonomy Selector UI for custom taxonomies.
* Introduce RichText component for mobile native and implement the Paragraph Block with it.
* Use standard label for Alt Text input.
* Consolidate similar i18n strings.
* Remove title attributes from the Classic Editor warning.
* Remove unused code in taxonomies panel.
* Remove oEmbed fixture files.
* Remove jQuery dependency from @wordpress/api-fetch.
* Remove filler spaces from empty constructs.
* Remove REST API shims for code introduced in WP 4.9.8.
* Remove unused terms, taxonomies, and categories code.
* Replace the apiRequest module with api-fetch module.
* Add inline comment that explains a stopPropagation() within tips implementation.
* Add gutenberg_can_edit_post filter.
* Add watch support for stylesheets in packages.
* Add JSDoc comment to Popover's focus() method.
* Add readme docs for all components.
* Autogenerate documentation from readme files.
* Add doc note about automatically applied attributes in save.
* Add test for block mover.
* Allow demo content to be translatable.
* Update CSS selectors from :before to ::before.
* Export the description for server-registered blocks.
* Export getBlockTypes on react native interface.
* Expose redux-routine to react native.
* Expose unknown-type handler methods for mobile.
* Specify missing wp-url dependencies.
* Improve JS packages descriptions.
* Downgrade Docker image version for WordPress for test validation.
* Move CI back to latest WordPress version and bump minimum version to 4.9.8
* Use @wordpress/compose instead of @wordpress/components.
* Update docs for Button component.
* Update package-lock.json.
* Updated dependencies: jest, npm-package-json-lint and read-pkg-up.
* Add Babel runtime dependency to redux routine.
* Prevent Travis from running when changes are only made to .md files.
* Add stylelint for SCSS linting.
* Set babel dependencies to fixed version and add core-js2 support.
* Trigger E2E test failure on console logging.
* Update doc links to resources moved to packages folder.
* Update api-fetch package documentation.
* Update Lerna to 3.0.0-rc.0.
* Generate source maps and read those from the webpack build.
* Rewrite e2e tests using jest-puppeter preset.
* Introduce a new Extending Editor document specific to editor filters.
* Improve test configuration and mocking strategy.
