=== WooCommerce - ActiveCampaign ===
Contributors: equalserving
Donate link: https://equalserving.com/donate
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: woocommerce, activecampaign
Requires at least: 4.4
Tested up to: 4.9.8
Stable tag: 1.9

Easily add ActiveCampaign integration to WooCommerce.

== Description ==

Integrates WooCommerce with ActiveCampaign by adding customers to ActiveCampaign at time of purchase. 

Easily tag your customers with product tags so that Automations can be triggered once the purchase is made.

= Support =
The EqualServing team does not always provide active support for the WooCommerce ActiveCampaign plugin on the WordPress.org forums. One-on-one email support is available at [EqualServing Help Desk](http://equalserving.com/support).

= Opt-in On Checkout =
If configured, add a checkbox on your WooCommerce Checkout page prompting your customers to subscribe to your newsletter or email updates.

= Tag Customer With Products Purchased =
If configured, tag your customers with the ids of products they purchased so that ActiveCampaign Automations can be triggered.

= Purchased Product Additional Tags =
If this field is blank, the option is ignored. If not blank, the contact will be tagged with the information provided. Use commas to separate tags. If you would like to tag contacts with the product SKU or product category. Use placeholder #SKU# for the product SKU and/or #CAT# for the product category. If you would like to assign both separate the items in the field above with a comma. EXAMPLE: sku: #SKU#, category: #CAT#.

= More Information About ActiveCampaign =
Do you want to know more about ActiveCampaign?
Go to The [ActiveCampaign Website](https://equalserving.com/likes/activecampaign/).

== Installation ==

= From within WordPress =
1. Visit 'Plugins > Add New'
2. Search for 'WooCommerce ActiveCampaign'
3. Activate WooCommerce ActiveCampaign from your Plugins page.
4. Go to "after activation" below.

= After activation =
1. Mouse over the WooCommerce menu item and select Settings.
2. Click on the Integration tab.
3. The ActiveCampaign configuration panel should display. If other integrations are available, just select the link labled 'ActiveCampaign.' 
4. Enable the plugin and enter the necessary API information.
5. You're done!

== Frequently Asked Questions ==

= Where can I get support? =
You'll find answers to many of your questions on [EqualServing Help Desk](http://equalserving.com/support).

= Is ActiveCampaign Free? =
No! ActiveCampaign does have a free trial account where you can test for yourself the robust application. [For more information about ActiveCampaign](https://equalserving.com/likes/activecampaign)

== Screenshots ==

1. The WooCommerce ActiveCampaign plugin general options configuration. You'll get to this screen by mousing over (1) WooCommerce menu item, select (2) Settings, click on (3) Integration tab and (4) be sure that the ActiveCampaign configuration screen is displayed.
2. The WooCommerce Checkout page shown with opt-in field. If enabled, this is how the opt-in
will display on the WooCommerce Checkout page.
3. Test contact in ActiveCampaign shown with product tag assigined and subscribed to selected list.

== Support ==
The EqualServing team does not always provide active support for the WooCommerce ActiveCampaign plugin on the WordPress.org forums. One-on-one email support is available at [EqualServing Help Desk](http://equalserving.com/support).

== Changelog ==

= 1.9 =

Release Date: Oct 7, 2018 

* Add a link to reset ActiveCampaign Lists and Tags dropdowns.

= 1.8 =

Release Date: May 10, 2018 

* Bug fix: Contact tags were not being applied. Error reported: Tag contact failed.
* Fixed calls to deprecated WooCommerce methods.

= 1.7 =

Release Date: May 6, 2018 

* Contact Tag: Permit the possiblity of not assigning any tags at all.
* Purchased Product Additional Tags: fix bug that applied category tags but prevented sku tags when #CAT# placeholder was not used.

= 1.6 =

Release Date: April 22, 2018 

* Add ability to track purchases by SKU and/or category.
* Add ability to assign a tag to all contacts making a purchase via WooCommerce.

= 1.5 =

Release Date: February 26, 2018 

* Provide more informative error messages.

= 1.4 =

Release Date: January 9, 2018 

* Capture error from Connector Class.

= 1.3 =

Release Date: August 21, 2017 

* Added error logging. API errors generated will appear in WooCommerce | Status | Logs

= 1.2 =

Release Date: August 14, 2017 

* Added error check in Connector.class.php

= 1.1 =

Release Date: March 18th, 2017

* Changes to readme file.

= 1.0 =

Release Date: February 3nd, 2017

* Initial release.

== Upgrade Notice ==

None