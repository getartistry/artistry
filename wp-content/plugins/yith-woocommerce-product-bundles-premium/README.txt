=== YITH WooCommerce Product Bundles ===

== Changelog ==

= Version 1.2.11 - Released: Apr 23, 2018 =

* New - possibility to manually force price sync of bundle with 'per item pricing' option enabled
* New - Spanish translation
* Fix - order item meta saving issue
* Fix - integration with YITH WooCommerce Catalog Mode
* Fix - order again when order contains bundle products
* Fix - issue with shipping tab in virtual simple products
* Tweak - fixed doc url

= Version 1.2.10 - Released: Jan 31, 2018 =

* New - support to WooCommerce 3.3
* Update - Plugin Framework
* Fix - replaced woocommerce_add_order_item_meta hook with woocommerce_new_order_item
* Dev - added yith_wcpb_bundled_item_thumbnail_size filter

= Version 1.2.9 - Released: Jan 10, 2018 =

* New - added bundle_add_to_cart shortcode
* Update - Plugin Framework 3

= Version 1.2.8 - Released: Dec 05, 2017 =

* New - Dutch language
* New - Italian language
* Fix - issue in combination with YITH WooCommerce Catalog Mode
* Fix - removed bottom borders in variation select
* Fix - hidden table if all bundled items are hidden
* Fix - default variation issue when the item is optional
* Update - language files
* Tweak - removed price suffix in Price Html to prevent price issues
* Tweak - show default price for variable product if no-variation is selected
* Dev - added yith_wcpb_bundled_item_show_default_price_for_variables filter
* Dev - added yith_wcpb_bundled_item_displayed_price filter
* Dev - added yith_wcpb_bundled_item_is_hidden filter
* Dev - added yith_wcpb_bundled_item_is_optional filter
* Dev - added product_id param to yith_wcpb_bundled_item_calculated_discount filter
* Dev - added woocommerce_after_add_to_cart_quantity action

= Version 1.2.7 - Released: Oct 11, 2017 =

* New - support to Support to WooCommerce 3.2.0 RC2
* Fix - YITH WooCommerce Request a Quote integration
* Tweak - replaced 'Clear selection' text with 'Clear' to reset variations

= Version 1.2.6 - Released: Sep 11, 2017 =

* Fix - per item pricing bundle sorting
* Fix - issue when click on Add Product and no product is selected
* Fix - purchasable issue with variable products
* Fix - wpml integration issues
* Tweak - added indicator for not-purchasable bundled items in backend
* Tweak - added check to prevent errors
* Dev - added yith_wcpb_bundled_item_calculated_discount filter
* Dev - added yith_wcpb_cart_error_notice_minimum_not_reached filter
* Dev - added yith_wcpb_cart_error_notice_maximum_exceeded filter
* Dev - added yith_wcpb_after_bundled_item_quantity_input action

= Version 1.2.5 - Released: Jun 30, 2017 =

* Fix - prevent issue allowing simple and variable product only as bundled items
* Fix - exclude bundled product from discount in combination with YITH WooCommerce Dynamic Pricing and Discounts
* Fix - quantities in cart in YITH WooCommerce Dynamic Pricing integration
* Fix - WPML integration issue with hidden variable items
* Tweak - improved report performances

= Version 1.2.4 - Released: Jun 27, 2017 =

* Fix - YITH WooCommerce Role Based Prices integration
* Fix - compatibility issue
* Fix - help-tip
* Dev - added yith_wcpb_help_tip function
* Tweak - prevent open item detail when click on product edit link

= Version 1.2.3 - Released: Jun 06, 2017 =

* Fix - integration with YITH WooCommerce Role Based Prices
* Fix - fatal error if the bundle item is hided
* Tweak - prevent fatal error in metabox
* Tweak - refactoring

= Version 1.2.2 - Released: May 11, 2017 =

* New - possibility to add shortcodes to bundled item descriptions
* Fix - slashes in bundled item titles and descriptions
* Fix - email issue in combination with YITH WooCommerce Request A Quote

= Version 1.2.1 - Released: Apr 24, 2017 =

* New - support to WooCommerce 3.0.4
* Fix - bundle product saving
* Fix - js error in frontend_add_to_cart.js
* Fix - 'Read more' text localization

= Version 1.2.0 - Released: Mar 09, 2017 =

* New - support to WooCommerce 2.7.0-RC1
* New - WPML Multi-currency support
* Fix - WPML integration issues
* Fix - hidden variable bundled item issue

= Version 1.1.7 - Released: Jan 18, 2017 =

* Fix - price sorting issue
* Fix - variable price issues
* Dev - added yith_wcpb_add_cart_item_data_check filter

= Version 1.1.6 - Released: Jan 11, 2017 =

* Fix - js price issue
* Fix - missing hook attribute

= Version 1.1.5 - Released: Jan 10, 2017 =

* New - out of stock synchronization
* New - choose how to view order pricing for "per item pricing" bundle products
* New - decimal discount percentage for bundled items
* Fix - hidden items in order table
* Fix - variable price issues
* Fix - js bundle form issues
* Fix - responsive cart table
* Tweak - updated language file

= Version 1.1.4 - Released: Dec 15, 2016 =

* New - integration with YITH WooCommerce Catalog Mode 1.4.8
* New - support to YITH WooCommerce PDF Invoice and Shipping List
* New - show the download links in the bundle if the bundled items are hidden in the order details
* Fix - issues with variable custom attributes
* Fix - issues with hidden bundled items
* Fix - show the variation prices if a variation is selected by default
* Fix - issues with YITH WooCommerce Role Based Prices
* Fix - hide bundled items in Cart and Checkout
* Dev - added jQuery trigger yith_wcpb_ajax_update_price_request
* Dev - added filter yith_wcpb_bundle_pip_bundled_items_subtotal
* Dev - added filter yith_wcpb_show_bundled_items_prices
* Dev - added filter yith_wcpb_ajax_update_price_enabled

= Version 1.1.3 - Released: Oct 17, 2016 =

* Fix - displayed price in cart in combination with YITH WooCommerce Dynamic Prices and Discounts

= Version 1.1.2 - Released: Oct 12, 2016 =

* Fix - integration to YITH WooCommerce Role Based Prices 1.0.9
* Fix - compatibility with YITH WooCommerce Dynamic Prices and Discounts 1.1.4
* Fix - issues with orders including virtual and downloadable items only, which did not automatically switch to completed

= Version 1.1.1 - Released: Sep 30, 2016 =

* New - integration with YITH WooCommerce Role Based Prices 1.0.9
* Fix - frontend issue in combination with themes that customize select fields
* Fix - issue during add to cart validation for stock quantity of bundled items

= Version 1.1.0 - Released: Sep 28, 2016 =

* New - integration with YITH WooCommerce Request a Quote 1.5.7
* New - possibility to show only bundles including the currently viewed product in widget
* Fix - issue during checkout
* Fix - display quantity input for optional variable bundled items
* Fix - issue in combination with YITH WooCommerce Role Based Prices
* Fix - display variation prices
* Tweak - improved frontend style

= Version 1.0.27 - Released: Aug 29, 2016 =

* New - compatibility with YITH WooCommerce Quick View 1.1.2

= Version 1.0.26 - Released: Aug 26, 2016=

* New - hidden link when a bundled item is an "hidden" product

= Version 1.0.25 - Released: Aug 09, 2016=

* Fix - "Add to cart" issue in combination with WooCommerce Multilingual

= Version 1.0.24 - Released: Aug 02, 2016=

* New - possibility to set the maximum and the minimum for the sum of the bundled item quantity in a bundle
* New - show price for variable products
* New - show price for optional bundled items
* New - change thumbnails when variation is selected
* Fix - displayed price when no variation is selected in variable products
* Tweak - improved frontend style
* Tweak - tab label "Bundle Options" changed in "Bundled Items"
* Tweak - created new tab "Bundle Options" for setting minimum and maximum quantity for bundled item quantity

= Version 1.0.23 - Released: Jul 07, 2016 =

* New - support to WooCommerce 2.6.2
* Fix - display price for bundle with optional and variable products
* Fix - general tab display issue
* Fix - issue depending on bundle item sorting

= Version 1.0.22 - Released: Jun 23, 2016 =

* Fix - product percentage discount coupon for bundles
* Fix - update price as soon as a bundle single page is loading

= Version 1.0.21 - Released: Jun 03, 2016 =

* Fix - WPML compatibility issue
* Fix - issue appearing in combination with YITH Woocommerce Role Based Price Premium

= Version 1.0.20 - Released: May 10, 2016 =

* Fix - price calculation
* Fix - issue appearing in combination with YITH Woocommerce Role Based Price Premium

= Version 1.0.19 - Released: Apr 19, 2016 =

* New - edit button for bundled items
* Fix - frontend add to cart JS (disable/enable add to cart button)
* Fix - memory error caused by ajax add to cart support for bundle products
* Fix - display price for bundled items when the option "show price without tax" is selected in WooCommerce settings page
* Fix - enqueue-style-and-script protocol

= Version 1.0.18 - Released: Mar 30, 2016 =

* Tweak - added ajax-add-to-cart support for bundle products

= Version 1.0.17 - Released: Mar 17, 2016 =

* Tweak - added possibility to override templates
* Tweak - fixed product search bug
* Tweak - fixed minor bugs

= Version 1.0.16 - Released: Mar 11, 2016 =

* New - possibility to hide bundled items in cart, mini-cart and checkout page

= Version 1.0.15 - Released: Mar 04, 2016 =

* Fix - hidden stock info label in bundled items that don't have stock management

= Version 1.0.14 - Released: Feb 17, 2016 =

* Tweak - fixed widget bug

= Version 1.0.13 - Released: Jan 19, 2016 =

* New - support to WooCommerce 2.5
* Tweak - fixed html price from-to

= Version 1.0.12 - Released: Jan 15, 2016 =

* New - automatic set virtual for bundle if it contains only virtual products
* New - support to WooCommerce 2.5 RC2
* Tweak - fixed cart item count

= Version 1.0.11 - Released: Dec 30, 2015 =

* New - WPML compatibility

= Version 1.0.10 - Released: Dec 18, 2015 =

* Tweak - fixed tax calculation for bundles with per-price-items options enabled
* Tweak - fixed items count in cart

= Version 1.0.9 - Released: Dec 15, 2015 =

* New - compatibility with WordPress 4.4
* New - compatibility with WooCommerce 2.4.12
* Tweak - added icon for bundle products in admin product list
* Tweak - fixed SKU bug in bundles with variable items

= Version 1.0.8 - Released: Dec 10, 2015 =

* New - shortcode to bundled items and add to cart button
* Tweak - fixed price calculation for bundle product with variables and discount

= Version 1.0.7 - Released: Dec 01, 2015 =

* Fix - minor bugs

= Version 1.0.6 - Released: Oct 29, 2015 =

* Fix - minor bugs

= Version 1.0.5 - Released: Aug 25, 2015 =

* Fix - minor bugs

= Version 1.0.4 - Released: Aug 20, 2015 =

* New - Support to WordPress 4.3
* Fix - minor bugs

= Version 1.0.3 - Released: Aug 19, 2015 =

* Fix - minor bug

= Version 1.0.2 - Released: Aug 18, 2015 =

* New - Support to WordPress 4.2.4
* New - Support to WooCommerce 2.4.4

= Version 1.0.1 - Released: Jul 24, 2015 =

* New - autoupdate price on Bundle single product page
* New - setting to hide/show bundled items in WC Reports

= Version 1.0.0 - Released: Jul 21, 2015 =

* Initial release