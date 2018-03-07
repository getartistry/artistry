3.6.1 *February 22, 2018*
---
* New - Add rules **Order Status**, **Customer State**, **Customer State - Text Match**, **Customer Postcode**, **Customer City**
* New - Add option to **Send Email - Raw HTML** which allows merging the basic AutomateWoo CSS to the supplied HTML 
* Fix - Issue where orders created in admin area were not triggering order created events
* Fix - Issue with the **Campaign Monitor - Remove Subscriber** action where some emails failed to be removed


3.6.0 *February 13, 2018*
---

**Database Upgrade Required** - Migrates unsubscribe data

* New - **BEHAVIOUR CHANGED** - Unsubscribes now apply to all workflows instead of a single workflow
* New - Add action **Send Email - Raw HTML** which is a version of **Send Email** that accepts raw HTML/CSS
* New - Refresh **Campaign Monitor** integration: improve UX, add **Remove Subscriber From List** action
* New - Add variables **customer.city** and **customer.postcode**
* New - Add flag to **logs view** showing if a log has any failed actions
* New - Add flag to **logs view** showing if any emails failed to send due to a customer being unsubscribed
* New - Add ability to disable email open/click tracking for a single recipient by adding the parameter '--notracking', useful when sending to admins
* New - Add filter to completely [blacklist email addresses](https://gist.github.com/danielbitzer/7000bd17a72060df3f0d60d0762a71c0)
* Tweak - Include cancelled orders when the option to 'include pending and failed orders with abandoned cart triggers' is enabled
* Tweak - Exclude customers with an active subscription from the **Customer Win Back** trigger
* Tweak - Remove orders with the status 'pending' from the **Customer Order Count** rule


3.5.0 *January 16, 2018*
---
**Database Upgrade Required** - Optimizes unsubscribe data

* New - Add support for email preheaders 
* New - Add variable **order_item.quantity**
* New - Add rule **Order Item Tags**
* New - Add rule **Order Line Item Quantity**
* New - Add rule **Customer's Active Membership Plans**
* New - Add action **Add Order Note**
* New - Add action **Send Subscription Invoice**
* Tweak - Unsubscribe data now only references the AutomateWoo customer ID instead of the user ID or email  
* Tweak - Unsubscribe URLs now use a unique customer key instead of email for better security
* Tweak - Add ability to set a reply to address for workflow emails via filter
* Tweak - On all order triggers, the variables for customer email and name will now pull values from the order data in favour of the user account data
* Tweak - Prevent click tracking redirect from redirecting to a different domain for security reasons
* Fix - Hide cart tax totals when tax is disabled
* Fix - **cart.link** variable, the option to link the checkout had stopped working
* Deprecated - The following triggers have been deprecated because their functionality is now better achieved with rules **Order Includes a Specific Product**, **Order Includes Product From Category** and **Order Includes Product From Tag**


3.4.2.2 *December 11, 2017*
---
* Fix - Multisite background processor issue


3.4.2.1 *December 8, 2017*
---
* Fix - Potential for order status changes to be missed when multiple changes happened in a short space of time 


3.4.2 *December 5, 2017*
---
* New - Add variable **customer.state**
* Fix - Object cache issue, affecting carts
* Various other minor improvements


3.4.1 *November 23, 2017*
---
* New - Add rule **Order Line Count**
* Fix - Issue with object cache invalidation, this could cause customer data to be lost on some workflow logs


3.4.0 *November 21, 2017*
---
* New - Add support for MailChimp Groups with new actions **Add Contact To Group** and **Remove Contact From Group**
* New - Add support for cart fees
* New - Add internal event system to improve stability of asynchronous workflows
* Tweak - When an email is clicked also record as an 'open'
* Tweak - In emails replace `<del>` and `<ins>` HTML tags with CSS to support Outlook 
* Tweak - The wc_enqueue_js() function is now used for inline javascript
* Fix - Issue where cron jobs could sometimes be incorrectly locked
* Fix - Session tracking issue with cookies found on some servers
* Fix - Rare issue where background processor stop


3.3.2 *November 7, 2017*
---
* New - Add option to include pending and failed orders in abandoned cart triggers
* Performance - Optimize cart tracking, particularly important for WC 3.2
* Performance - Optimize various queries
* Tweak - Add first and last name fields to the MailChimp subscribe action
* Fix - Issue when using WPML, the **shop.products** variable would sometimes not be language filtered
* Fix - Issue when using WPML, **MailChimp for WordPress** trigger not working for languages other than the site default


3.3.1.3 *October 26, 2017*
---
* Fix - Use of wc_get_coupon_id_by_code() function not backwards compatible before WC 3.0


3.3.1.2 *October 23, 2017*
---
* Fix - Error when using the log **Re-run workflow** for delayed workflows


3.3.1.1 *October 19, 2017*
---
* Fix - Issue with cart totals being incorrectly calculated in some cases


3.3.1 *October 18, 2017*
---
* Fix - For issue in WooCommerce 3.2 when manually creating orders or subscriptions
* Tweak - Improve expired coupon clearing scalability
* Tweak - Use async hooks for order and subscription status changes
* Tweak - Add filter to allow modification of cart price currency
* Tweak - Add filter to allow modification of admin settings 


3.3.0 *October 4, 2017*
---
* Tweak - **BEHAVIOUR CHANGED** - The triggers **Subscription Payment Complete** and **Subscription Payment Failed** now only trigger for renewal payments and have been renamed accordingly.
* New - Add order data to **Subscription Renewal Payment Complete** and **Subscription Renewal Payment Failed** triggers
* New - Added rule **Customer Is Subscribed To MailChimp List?**
* New - Added rule **Customer's Purchased Categories - All Time**
* New - Added rule **Cart Item Tags**
* New - Added rule **Subscription Status**
* New - Ability to re-run a workflow from a log
* New - Support for multiple recipients on SMS action
* New - Ability to filter AutomateWoo admin pages by guest customers
* New - Ability to change number of items per page in AutomateWoo admin pages
* New - Improvements to coupon generation including a filter to modify length and option to remove prefix


3.2.7 *September 19, 2017*
---
* New - Add option to disable session tracking
* Tweak - Improve display of variable product titles 
* Tweak - Hide shipping message from carts if shipping is not required
* Fix - Issue with strings in the 'Order Table' not being translated with WPML


3.2.6 *September 10, 2017*
---
* New - Add the shipping total to cart tracking and templates
* New - Add variation meta data to cart templates
* New - Add trigger **Subscription Before End**
* New - Add option to link the **cart.link** variable to the checkout page instead of the cart page
* New - Add setting to change the cart deletion date
* New - Add settings to change the email sender name and email
* Fix - Issue with manual order trigger when using order item data


3.2.5 *August 28, 2017*
---
* New - Added detailed admin view for guest data
* New - Added sorting options to the **shop.products** variable
* Tweaks - Improvements to background processor stability


3.2.4.1 *August 23, 2017*
---
* Fix - Issue with **Subscription Before Renewal** trigger since 3.2.3


3.2.4 *August 21, 2017*
---
* Performance - Optimized numerous database queries across in different areas of the plugin
* Performance - Implemented new background processor for abandoned cart and queued workflow processing
* Tweak - Improvements to abandoned cart tracking logic


3.2.3 *August 14, 2017*
---
* New - Added unsubscribe links to emails using the plain text template
* New - Added variables **order.shipping_method** and **order.payment_method**
* New - Added rules **Product** and **Product Categories**
* Performance - Implemented new background processor for **Subscription Before Renewal** and **Wishlist Item On Sale** triggers
* Tweak - Improvements to multilingual cart tracking for WPML


3.2.2 *August 8, 2017*
---
* Fix - Issue with the **Customer Win Back** trigger sending duplicate emails to guest customers
* Tweak - Add support for cheque, bank, invoice payments to the **Order Payment Received** trigger
* Tweak - Renamed **Order Payment Received** trigger to **Order Paid**


3.2.1 *August 7, 2017*
---
* Tweak - Switch to a more widely supported modulus method


3.2.0 *August 1, 2017*
---
* New - Implemented use of background processor to improve stability and scalability
* New - Added support for guest customers to the **Customer Win Back** trigger 
* New - Optimized the **Customer Win Back** trigger using the background processor
* New - Added notices / explanation for failed queued events
* New - Added support for variables in the Google Analytics tracking workflow option
* Tweak - Removed blocking of certain CSS properties in emails
* Tweak - Removed the product description from the product rows template based on customer feedback
* Fix - UI conflict with the new version of the Members plugin
* Fix - Improved emoji support
* Fix - Improved compatibility with WPML
* Fix - Issue where the pending payment trigger could fire twice when using the **Change Order Status** action


3.1.1 *July 21, 2017*
---
* New - Guest and cart admin tables are now sortable by column
* New - Add variable **subscription.meta**
* Various minor tweaks


3.1.0 *July 14, 2017*
---
* New - Added rule **Order Coupons - Text Match**
* New - Added rule **Review Rating**
* New - Added rule **Customer Review Count**
* New - Added a 'maximum days since last purchase' field to the **Customer Win Back** trigger so the trigger now supports a date range
* Tweak - Improved license page UX and separated the page from the settings area
* Tweak - Added support for subscription renewals events to the triggers **Order Pending Payment** and **Order Created**
* Fix - Issue with the trigger **Subscription Before Renewal** that could potentially cause duplicate triggers to fire


3.0.5 *July 10, 2017*
---
* Fix - Object caching issue with W3 Total Cache that affected customer objects


3.0.4 *July 2, 2017*
---
* New - Added variable **customer.company**
* Fix - Issue where fatal error was being caused by the generate coupon variable


3.0.3 *June 26, 2017*
---
* New - Added option to append a string to product URLs in product templates, useful for linking directly to **#tab-reviews** for example
* New - Added a simple product template that displays products in a comma separated list
* New - Added variable **customer.tags** which displays a list of the customers tags
* New - Guests admin list view is now searchable
* New - Queue admin list view now includes more info in a modal view
* Tweak - Conversion tracking is now based on the order placed date rather than order completed date
* Fix - Issue where guest customers were not properly converted to user customers on account creation, this was causing some issues with missing conversions


3.0.2 *June 20, 2017*
---
* Tweak - Improved performance and UX of **Clear Queued Events** action 
* Fix - WPML issue with customer triggers since 3.0


3.0.1 *June 13, 2017*
---
* Tweak - Improved compatibility with LocoTranslate plugin
* Tweak - Update German translation and fixed some incorrect text domains
* Fix - Issue with the **product.meta** variable not supporting product variations
* Fix - Issue with new **Cart Abandoned** trigger, guest carts were failing in the queue


3.0.0 *June 9, 2017*
---
**Database Upgrade Required** - Migrates the user data type to the newly added customer data type

* New - Added the **customer** data type which supports both guest and registered customers which means many triggers can now support both customer types instead of having to create duplicate workflows
* New - Added the **review** data type to be used instead of the **comment** data type for future review based features
* New - Added trigger **Cart Abandoned** to support both guest and registered customers
* New - Added trigger **New Review Posted** to support both guest and registered customers
* New - Added variables **review.content** and **review.rating**
* New - Added trigger **Subscription Created**
* New - Added trigger **MailChimp for WordPress - Form Submission**
* New - Added fixed workflow timing option
* New - German translation added
* Tweak - The trigger **Customer Order Count Reaches** now supports guest customers
* Tweak - The trigger **Customer Total Spend Reaches** now supports guest customers
* Tweak - Abandoned cart triggers have been rebuilt, improved efficiency and timing functions


2.9.17 *May 24, 2017*
---
* New - Added variable **product.description**
* Tweak - Improvements to abandoned cart tracking
* Tweak - Added cart created date to cart modals


2.9.16 *May 11, 2017*
---
* Tweak - Added an extra check to ensure carts are cleared after order is placed
* Fix - Potential issue where order tables in emails could appear differently in preview due to payment gateway classes not being instantiated 
* Fix - Fix a few minor WooCommerce 3.0 notices


2.9.15 *April 28, 2017*
---
* New - Added rules **Subscription Items** and **Subscription Item Categories**
* Tweak - Improve efficiency of update checks
* Tweak - Minor admin UX improvements


2.9.14 *April 26, 2017*
---
* Fix - Issue where generated coupons could be mistakenly cleared if running WooCommerce 3.0


2.9.13 *April 21, 2017*
---
* Tweak - Change license status check timeout to option instead of transient due to unexpected behaviour from some cache plugins leading to excessive API checks
* Tweak - Improvements to cart restore process, success / errors notices are now displayed
* Fix - Issue with some admin enhanced selects not initiating


2.9.12 *April 13, 2017*
---
* Tweak - Allow colons and commas in variables, disallow single quotes
* Fix - Add support for WooCommerce Subscriptions 2.2.0 date changes
* Fix - Potential bug with WPML integration
* Fix - Potential URL encoding issue with guest tracking javascript
 

2.9.11 *April 10, 2017*
---
* Tweak - The trigger **Wishlist Item On Sale** now only triggers for newly on sale products and is now processed in batches
* Fix - Tax issue when using the 'Shop base address' calculation option
* Fix - Potential admin js conflict


2.9.10 *April 4, 2017*
---
* New - Improve support for WooCommerce email customizer plugins
* Fix - Make User Tag related strings translatable
* Fix - Minor WooCommerce 3.0 update for subscription items
* Fix - Rare issue with generated coupons


2.9.9 *April 4, 2017*
---
* New - Added rule **Cart Item Categories**
* Fix - Missing data item on the **Wishlist Item On Sale** trigger


2.9.8 *April 3, 2017*
---
* New - Added variables **guest.first_name** and **guest.last_name**
* New - Added option to enable or disable auto deletion of expired coupons
* Refactor - Namespace all variables are now, backwards compatibility maintained for all base classes
* Tweak - A few more changes required for WooCommerce 3.0
* Fix - Issue with the **Wishlist Item On Sale** trigger for YITH Wishlists


2.9.7.1 *April 1, 2017*
---
* Fix - Issue where generated coupons could be mistakenly deleted if no expiry date was set
 

2.9.7 *March 31, 2017*
---
* Data Update - Added meta table for queue data which is needed for future planned features
* New - Scheduled day timing field now supports multiple days
* Fix - Potential issue when scheduling the **User Win Back** trigger
* Fix - Variable validation was missing on email body fields


2.9.6 *March 27, 2017*
---
* New - Added variable **subscription.items**
* New - Added variable **subscription.billing_address**
* New - Added variable **subscription.shipping_address**
* New - Added variable **order.customer_details** 
* New - Added variable **user.billing_country** 
* Tweak - Run the **User Account Created** trigger action asynchronous for better compatibility with signup forms workflows
* Tweak - Renamed the trigger **User Has Not Purchased For Period** to **User Win Back**
* Tweak - Added workflow timing support to the **User Win Back** trigger
* Fix - Bug in the manual order trigger tool
* Fix - Log view button not working on the dashboard


2.9.5 *March 21, 2017*
---
* New - **BEHAVIOUR CHANGED** - Generate coupon variables now have a **limit** parameter (defaults to 1) that is used instead of the usage limit field on the template coupon
* New - Added meta data rules - user meta, order meta, order item meta and subscription meta 
* New - Add two new rule string compare types - **Blank** and **Not Blank**
* New - Added support for new date handling in WooCommerce 3.0
* Tweak - Improved the dashboard by added a 90 day report option and added the date range to full report links
* Tweak - Checkout capture fields are now stored on the 'change' event in addition to 'blur' due to varying behaviour from some browsers
* Tweak - Field classes are now all namespaced for consistency, backwards compatibility has been maintained
* Tweak - Improved logic for the **User Role** rule when given a guest customer
* Tweak - Added support for guest customers to the **User Total Spent** rule
* Fix - Issue that could cause rules to wrongly return true if the value of the rule was zero


2.9.4 *March 13, 2017*
---
* New - Added **Note Type** option to **Order Note Added** trigger
* New - Added rule **Order Customer Provided Note**
* New - Added variable **order_note.content**
* New - Added support for new enhanced selects boxes in WooCommerce 3.0


2.9.3 *March 10, 2017*
---
* Fix - Issue with the **Order Includes Product From Category** trigger
* New - Internal changes to support WooCommerce 3.0


2.9.2 *March 8, 2017*
---
* Fix - Issue with the **User Has Not Purchased For Period** trigger


2.9.1 *March 3, 2017*
---
* UX - Improvements to workflow edit screen
* Fix - Potential issue with Order Pending trigger logic
* Fix - Reduce ActiveCampaign cache timeout to prevent issues when manually creating contacts


2.9 *February 25, 2017*
---
* New - Added **Scheduled** workflow timing option
* New - Auto-delete expired AutomateWoo generated coupons
* New - Added trigger **Membership Created**
* New - Added trigger **Order Placed - Each Line Item**
* New - Added trigger **Order Status Change - Each Line Item**
* New - Added trigger **Order Payment Received - Each Line Item**
* New - Added trigger **Subscription Status Changed - Each Line Item**
* New - Added rule **Order Item Categories**
* New - Added rule **Order Items Names - Text Match**
* New - Added rule **Guest Order Count**
* New - Added rule **Order Is Subscription Renewal?**
* New - Added variable **order.customer_note**
* New - Added variable **comment.content**
* New - Added action **Delete User Membership**
* New - Added action **MailChimp - Update List Contact Field**
* New - Added action **ActiveCampaign - Update Contact Custom Field**
* New - Added action **Add/Update Customer Provided Note**
* New - Added meta table for guest data, internally record more guest data
* New - Internal changes to support WooCommerce 3.0
* New - Internal changes to object caching 
* New - Internal changes to improve loading of triggers and actions
* New - Plugin is now namespaced 
* Fix - Updated ActiveCampaign API library
* Fix - Issue with subscription variations workflow logic
* Fix - Missing user name data when using the new user account trigger at checkout


2.8.6 *January 10, 2017*
---
* New - Added variable order.reorder_url, when clicked it fills the cart with the items from the previous order
* New - Record guest IP addresses
* New - Improved support for the Ultimate Members plugin, specifically with frontend user registrations
* New - Added action 'Add / Update Customer Shipping Note'
* New - The trigger 'User Has Not Purchased For Period' now supports order data from the customer's most recent order 
* Tweak - Email URL endpoints have been changed e.g. **?aw-click-track=1** is now **?aw-action=click**, backwards compatibility will be maintained until the next major release
* Tweak - Performance and security improvements and some refactoring around the AW_Mailer class
* Fix - Issue with the trigger **Subscription Before Renewal**
* Fix - Issue where duplicate unsubscribe notices could appear when unsubscribing


2.8.5 *December 27, 2016*
---
* Fix - Rare issue related to WPML integration
* Fix - Rare plugin conflict


2.8.4 *December 18, 2016*
---
* New - Added separate admin tab for coupons generated by AutomateWoo
* Tweak - Improve date display for translations
* Fix - Date comparision issue on some server environments
* Fix - Issue that could allow duplicate memberships for a user


2.8.3 *December 13, 2016*
---
* New - Added trigger: Membership Status Changed for use with the WooCommerce Memberships plugin
* New - The Change Membership Plan action now also supports membership creation and has been renamed as such
* Tweak - Variable has been slightly changed, parameter values now use single quote delimiters but the old style is still supported
* Tweak - New abandoned cart workflows no longer trigger for carts that were created more than five days before the workflow was created, 
since carts are stored for 45 days this is to prevent new workflows from triggering for old carts at strange times
* Fix - Admin area: Fix email preview error when the WordPress visual editor is disabled


2.8.2 *December 5, 2016*
---
* New - Added rule: User's Order Statuses
* New - Added rule: Workflow Run Count For Order
* Tweak - Now when a workflow is deleted all related logs, queue and unsubscribes are also deleted
* Tweak - Improved phone number support for some countries
* Tweak - When user name is not set fallback to subscription billing name if present
* Security and performance improvements
* Other minor fixes
 

2.8.1 *November 23, 2016*
---
* Fix - Potential dashboard issue


2.8.0 *November 22, 2016*
---
* New - Added a Dashboard page
* New - Added action to enable changing a WooCommerce Memberships plan for a user


2.7.8 *November 15, 2016*
---
* New - Moved table views for queue, carts, guests and unsubscribes from reports to plugin sub-menu for quicker access
* New - Improved UI for table views and added a short description for each view
* New - Added filtering and bulk editing to unsubscribes table view
* New - Added basic validation when using variables, warns if an invalid variable type is used
* Tweak - More parameters are now passed to product display templates for greater customization


2.7.7 *November 15, 2016*
---
* New - Add date created columns for queue and carts tables for future reports
* Fix - Mailer bug affecting the Refer A Friend add-on


2.7.6.1 *November 12, 2016*
---
* Fix - System check for database tables false positive issue


2.7.6 *November 11, 2016*
---
* New - Added trigger: Order Payment Complete
* New - Unsubscribe functions have been expanded to support guest emails
* New - Added system check for installed database tables
* Fix - Issue where guests records were not cleared when after checkout sign up
* Fix - PHP warning in admin reports


2.7.5 *November 9, 2016*
---
* New - Added unsubscribe importer tool
* Fix - Issue where product prices would incorrectly display excluding tax for some templates and tax settings
* Tweak - Various improvements to abandoned cart tracking logic


2.7.4 *November 5, 2016*
---
* Tweak - Provide more error info for email and SMS actions in the logs and from sent tests


2.7.3 *November 4, 2016*
---
* Fix - Issue when editing user tags in the user profile area and via bulk edit


2.7.2 *November 4, 2016*
---
* Tweak - Added option to trigger 'User Has Not Purchased For Period' that allows control over whether the trigger fires repeatedly or just once for each purchase
* Tweak - Added a filter 'automatewoo/workflow/is_user_unsubscribed'


2.7.1 *November 2, 2016*
---
* New - Added rule 'User Is Active Subscriber?'
* Tweak - Changed queue checking to five minute intervals and reduced default batch size
* Tweak - Order counting functions now excludes cancelled, failed and refunded orders


2.7 *October 28, 2016*
---

**Database Upgrade Required** - Migrates the ActiveCampaign actions as required by the new action format.

* New - Added detailed cart info to the active cart report, shows products, coupons and taxes 
* New - Added flexibility to ActiveCampaign actions, previously they only supported the user data type, now they can support guests and advocates. 
* New - Added the product template 'Order Table' for the 'order.items' variable which renders the same order table used in the standard WooCommerce transactional emails
* New - Added Variable 'user.billing_phone'
* Performance - Separated frontend and admin ajax endpoints to reduce overhead
* Performance - Added database indexes for all custom tables
* Performance - Removed use of Campaign Monitor and Mad Mimi PHP API wrappers as they were not PHP7 ready and majority of the code was not in use
* Performance - Removed the original 'Add To MailChimp' action that was deprecated about a year ago and was replaced by an improved alternative
* Tweak - Changed email preview popup JS so that the popup doesn't get blocked by browsers
* Tweak - Trigger 'User Has Not Purchased For Period' no longer treats failed/cancelled orders as purchases
* Tweak - Images in the Product Rows and Cart Table templates no longer get filter through frontend filters as this was causing unexpected results for some users
* Tweak - Trigger 'Order Includes Product from a Specific Category' now supports the data types 'product' and 'order_item'
* Fix - Trigger 'User Has Not Purchased For Period' now treats subscription renewals as purchases


2.6.10 *October 14, 2016*
---
* New - Added rule 'User Purchased Products'
* New - Added order_item data to the 'User Purchases Product' trigger
* New - Added variable order_item.meta 
* New - Improvements to queue report
* Fix - Potential encoding issue with email click tracking URLs
* Fix - Typo in unsubscribe form template
* Tweak - Refactored how log data was stored and retrieved
* A number of other minor fixes and internal improvements


2.6.9 *October 7, 2016*
---
* Fix - Compatibility issues with older versions of WordPress and WooCommerce


2.6.8 *September 30, 2016*
---
* New - Added support for subscription variations to all subscription triggers
* Tweak - Added action 'automatewoo/email/before_send'


2.6.7 *September 22, 2016*
---
* New - Allow custom email templates to have a custom email 'from name' and 'from email'
* Tweak - Rules admin box text
* Fix - Compatibility issue with older WooCommerce versions (only affected Refer A Friend add-on)


2.6.6 *September 15, 2016*
---
* New - Added constant AW_PREVENT_WORKFLOWS which, if true, prevents all workflows from running and instead adds a WooCommerce log entry for each run
* Tweak - Improve auto fixing of URLs in email content


2.6.5 *September 8, 2016*
---
* Tweak - Avoid conflict with YITH email customizer plugin
* Fix - Compatibility issue with older WooCommerce versions


2.6.4 *September 5, 2016*
---
* Fix - Missing settings field type for the Refer A Friend add-on
* Minor admin text tweaks and fixes


2.6.3 *September 2, 2016*
---
* New - Added variables order.billing_address and order.shipping_address
* Tweak - When sending a test email coupons will now be generated and labeled as test coupons, previously coupons did not persist
* Fix - Issue with user first and last name's not found for queued workflows based on guest orders 
* Fix - Degradation issue with unsupported PHP versions


2.6.2.2 *August 31, 2016*
---
* Fix - Issue with the 'Order Is Customer's First' rule


2.6.2.1 *August 31, 2016*
---
* Fix - Config issue with 'Abandoned Cart (Users)' trigger


2.6.2 *August 29, 2016*
---
* New - Added Trigger 'Subscription Before Renewal'
* New - Support added for the plugin [Email Customizer for WooCommerce](https://codecanyon.net/item/email-customizer-for-woocommerce/8654473)
* Tweak - Improvements to 'User Has Not Purchased For a Set Period' trigger. 
It now checks for inactive users once a day rather than once a week and the queries are far more efficient.
* Tweak - Refactored admin settings code and abandoned cart triggers
* Fix - Issue where the order count and total spent for the user was not correct due to event ordering
* Fix - Rare issue where AW admin menu did not appear
* Fix - Admin list table issue with older WP versions


2.6.1 *August 23, 2016*
---
* New - Improve workflow statuses - now they are either Active/Disabled rather than the standard WordPress post statuses. 
Also there is a new UI that gives admins a nice way to manage which workflows are active. Please note there is a database migration for the new statuses.
* New - Added Rule: Order Coupons
* New - Added Rule: Cart Coupons
* New - Added Rule: Cart Items
* New - Added Variable: order.date
* New - Abandoned carts now support coupons
* Tweak - Internal improvements to ActiveCampaign integration
* Fix - Issue with sending test emails


2.6 *August 18, 2016*
---
* New - [Rules](https://automatewoo.com/version-2-6/) - Workflow trigger options have been completely rebuilt into rules with a better UI and more flexibility. 
If you are developing custom triggers please note that a number of methods have been deprecated more info can be found in the [release post](https://automatewoo.com/version-2-6/)
* New - Add multi status support to 'Order Status Changes' trigger
* Fix - Better tax support for the Cart Table product display template


2.5.2 *August 9, 2016*
---
* New - Add 'Top Selling' option for the shop.products variable


2.5.1 *August 2, 2016*
---
* Fix - Issue with saving settings from v2.5


2.5 *August 1, 2016*
---
* New - Manual Order Trigger Tool - Can be used to run a workflow on existing orders
* New - Bulk deletion is now supported on logs, guests, conversions, carts and queued event
* Tweak - Logs list view has been moved to its own menu item (one less click to get to)
* Tweak - Tools also moved from settings to its own menu item
* Tweak - Dropped support for WP shortcodes in the email body because the markup outputted by shortcodes is generally not intended for use in HTML emails
* Fix - Issue where empty carts could stored if capturing emails from non checkout pages
* Fix - {{ order_item.attribute }} variable was not loading correctly


2.4.14 *July 28, 2016*
---
* New - Add variables user.order_count user.total_spent
* Tweak - Add filter for guest capture selectors 'automatewoo/guest_capture_fields'


2.4.13 *July 27, 2016*
---
* New - Add limit parameter to product display templates
* Fix - Issue with the loading of admin wysiwyg editors
* Fix - Issue where abandoned carts could be restored multiple times if all items were removed and the restore token was still present in the URL
* Tweak - Tidy up admin page URLs


2.4.12 *July 20, 2016*
---
* Fix - Issue where the 'Cart Table' product template could display zero as the line total
* Fix - Validation issue on subscription products field
* Tweak - Minor improvement to abandoned cart tracking
* Tweak - Internal changes for better add-on integration


2.4.11 *July 18, 2016*
---
* New - Add support for multiple categories/tags when using the {{ shop.products }} variable
* New - Automatically add country codes for SMS recipients when using the {{ order.billing_phone }} variable
* Tweak - Refactor how trigger options should be accessed to improve efficiency. Two methods have been deprecated


2.4.10 *July 14, 2016*
---
* Tweak - Add guests support for MailChimp actions


2.4.9 *July 12, 2016*
---
* New - Added Trigger - New Guest Captured
* New - Added Report - Guests
* Tweak - Add setting to enable/disable abandoned cart tracking (default is enabled)
* Tweak - Add filter for session tracking cart cookie name
* Tweak - Make dates inclusive for the manual subscription trigger tool


2.4.8 *July 7, 2016*
---
* Fix - Issue where conversion tracking logic could miss a newly registered customer's conversion
* Fix - Issue with guest abandoned carts caused by the variable refactoring in v2.4.7


2.4.7 *July 5, 2016*
---
* New - User type select boxes on triggers now allow multiple selections
* Tweak - Refactor variable filters to allow for easier integration from 3rd party developers
* Tweak - Improve session tracking to better support varnish caching
* Tweak - Remove short descriptions from the product grid templates 


2.4.6 *June 30, 2016*
---
* Tweak - Improve performance by loading some classes with dependency injection
* Tweak - Improvements to session tracking logic
* Tweak - Automatically close variable modal after copy to clipboard action
* Tweak - Code refactoring around variables and data types
* Tweak - Add filters **automatewoo/mailer/from_address**, **automatewoo/mailer/from_name**


2.4.5 *June 24, 2016*
---
* New - Improve tools UI and add a new tool 'Manual Subscriptions Trigger'
* Tweak - On order triggers the 'Is Users First Order?' also checks for any guest orders that match a users email
* Tweak - Added support product variation images in product display templates
* Tweak - Internal improvements and code refactoring


2.4.4 *June 20, 2016*
---
* Fix - Issue with 'Subscription Payment Failed' trigger


2.4.3 *June 15, 2016*
---
* New - Consolidate AutomateWoo pages under a single admin menu item. Required for when new pages will be added in the future.
* New - Add system check for PHP version
* New - 'User Has Not Purchased For a Set Period' trigger is now processed in batches to support huge user counts
* New - Improvements to license page UI
* Other minor fixes and performance improvements


2.4.2 *June 10, 2016*
---
* Tweak - Add a filter to allow modification of a workflow variable's value
* Fix - Issue on some servers where variable modals did not display correctly


2.4.1 *June 7, 2016*
---
* Fix - Issue where pending payment triggers would not fire when 'pending' was the unchanged initial status of an order


2.4 *June 6, 2016*
---
* New - [New UI for workflow variables](https://automatewoo.com/introducing-new-ui-workflow-variables/)
* Tweak - Cart restore links are now use a token rather than ID for added security


2.3.4 *May 6, 2016*
---
* Fix - Product images sizing issue


2.3.3 *April 26, 2016*
---
* Tweak - Additional check for the **New User Account Created** trigger


2.3.2 *April 24, 2016*
---
* New - Added template **cart-table.php** added for use with the {{ cart.items }} variable
* Fix - Issue where product images width could overflow on some custom email templates 


2.3.1 *April 20, 2016*
---
* Tweak - Improvements to MailChimp API Integration 


2.3 *April 13, 2016*
---
* New - WPML Support
* New - Upgrade MailChimp API to 3.0
* New - WooPOS Support
* New - Added trigger 'Wishlist - User Adds Product' (YITH only)
* New - Added trigger 'Trigger Order Action'
* New - Add system checker tool that can check if WP cron is functioning 
* New - Add current queue count to workflows admin column
* Tweak - Abandoned cart data is now split into 2 tables, 1 for guests, 1 for carts
* Tweak - Refactor and improve session tracking code
* Fix - Gmail image aspect ratio issue


2.2.1 *March 25, 2016*
---
* New - Added option on abandoned cart triggers to limit send frequency for a user/guest
* Tweak - Add fallback for user.firstname and user.lastname to order billing fields
* Tweak - Minor improvements to Wishlist triggers, add descriptions
* Tweak - Add new email/styles.php template, add image alignment classes


2.2 *March 16, 2016*
---
* New - Logs Report now has a modal which displays additional info
* New - Added report that shows a details conversions list
* New - Added 'Unique Clicks' dimension to click tracking report
* New - Added action 'Resend Order Emails'
* New - Added trigger 'Order Note Added'
* New - Added variable {{ shop.products }} supports displaying products by category, tag or custom filter
* New - Added variable {{ order.related_products }}
* New - Custom email templates can have dynamic content via the new AW_Mailer_API 
* New - Added trigger 'Order Note Added'
* New - Added tool added that lets you reset all records for a workflow  
* New - Added action 'Clear Queued Events'
* Tweak - Email tracking click events now also count as an open if one has not already been recorded (images may be have been blocked)
* Tweak - Email content has a new filter specifically designed for sending instead of using 'the_content'
* Fix - Some dates we're being shown as GMT
* Fix - Bug where Google Analytics tracking codes we're not being appended to URLs


2.1.14 *February 28, 2016*
---

* Tweak - Minor improvement to conversion tracking logic
* Tweak - Improve display of date and time fields in admin area
* Tweak - Improve 'Unsubscribe' link flexibility 
* Tweak - Add CHANGELOG.md file

2.1.13 *February 20, 2016*
---

* Fix – Allows plugin to continue to work as normal after license expiry
* Tweak – Remove license email field, activation to happen via license key only
* Tweak – Add a dismissible admin notice when license has expired
* Tweak – Dev installs now require a valid license key

2.1.12 *February 8, 2016*
---

* New – Added trigger – Guest leaves review
* Fix – Issue where reviews that were immediately approved did not get caught by user review trigger

2.1.11 *February 6, 2016*
---

* New – Improved UI for email preview
* New – Ability to send an email preview as a test
* New – Ability to define an order in which workflows will run when triggered
* New – Trigger Order Includes a Specific Product now supports product variations

2.1.10 *February 3, 2016*
---

* New – Support for the [WooThemes Shipment Tracking](https://www.woothemes.com/products/shipment-tracking/) plugin with new variables
	* {{ order.tracking_number }}
	* {{ order.tracking_url }}
	* {{ order.date_shipped }}
	* {{ order.shipping_provider }}
* New – Improved abandoned cart delay accuracy, 15 minute intervals are now possible
* New – Support for triggers to have descriptions in the backend
* Tweak – User type and user tag fields will be revalidated before a queued run
* Fix – Removed the guest select option on the Abandoned Cart (Users) trigger

2.1.9.1 *January 29, 2016*
---

* Fix – Potential fatal error on some servers

2.1.9 *January 29, 2016*
---

* New – Google Analytics tracking on URLs in SMS body
* New – Added trigger: Order Placed fires as soon as an order is created in the database regardless of status
* New – Added variable {{ order.view_url }}
* New – Added variable {{ order.payment_url }}
* Fix – Issue for email tracking URLs with ampersands in them
* Improvement to payment gateway select box stability
* Internal improvements and code refactoring

2.1.8 *January 18, 2016*
---

* Fix – Bug preventing the user.meta variable from working
* Tweak – Abandoned cart are processed every 30 mins rather than every hour to improve time accuracy
* Tweak – Minor improvements to cron

2.1.7 *January 12, 2016*
---

* Fix – Issue where user tags could not be managed in WP 4.1.1

2.1.6 *December 28, 2015*
---
* New – Add option to delete or unsubscribe user on the Remove from MailChimp list action
* Tweak – Improvements to abandoned cart clearing logic
* Tweak – Improvement to automatewoo_custom_validate_workflow filter
* Tweak – Simulate signed out user when previewing emails
* Fix – MailChimp lists transient key was incorrect

2.1.5 *December 15, 2015*
---
* New action: Change Subscription Status
* New variable: {{ subscription.view_order_url }}
* Internationalize phone number for SMS actions
* Cron stability improvements

2.1.4 *December 5, 2015*
---

* Option to add Google Analytics Campaign tracking params to links in emails
* Fix to subscription products field logic
* Make Times Run value a link to a filtered logs view

2.1.3 *December 3, 2015*
---

* Add ability to filter logs by workflow and by user
* Logic fix for subscriptions skip first payment option
* Fix issue with {{ shop.products_on_sale }} variable
* Ensure WooCommerce Subscriptions is at least version 2.0
Fix admin display issue with product select field

2.1.2 *November 28, 2015*
---
* Fix issue where the reports graph dates were not being converted to the site timezone
* Fix an issue with email preview display
* Improvement to the User Leaves Review trigger so that doesn’t fire until the comment is approved
* New feature allowing export of users in a tag to CSV
* Improvement to the user tag query for the user list admin view

2.1.1 *November 25, 2015*
---
[Check out the version 2.1 blog post](https://automatewoo.com/version-2-1/)

2.0.2 *October 27, 2015*
---
* Abandoned Cart email capturing can now be enabled on any form field, not just the checkout
* Internal improvements

2.0.1 *October 17, 2015*
---
* Fix an issue where Send Email actions created before 2.0 might not be styled

2.0.0 *October 14, 2015*
---
[Check out the version 2.0 blog post](https://automatewoo.com/version-2/)

* New: Plain text emails and custom email templates
* New: Conversion tracking expanded to any workflow, not just abandoned cart
* New: Customer tags
* New: ActiveCampaign integration
* New: Added report for currently stored carts
* New: Once Per User checkbox has been changed to Limit Per User number field
* New: Added trigger Workflow Times Run Reaches
* New: Added trigger User Order Count Reaches
* New: Added action Change Post Status
* New: Order Status Changes trigger now lets you select a from and to status. This trigger also has support for custom order statuses.
* New: Added options to target orders for specific countries or orders that used a certain payment method.
* Tweak: Refactor abandoned cart code into model
* Fix: Issue where some fields where not cloning on coupon generation
* Fix: Issue where visitor key sometimes wasn’t stored for abandoned carts

1.1.10 *September 26, 2015*
---
* New Trigger: Order Payment Pending
* Fix issue where {{ order.total }} was blank

1.1.9 *September 23, 2015*
---
* SMS Integration via Twilio
* Performance Improvements

1.1.8 *September 21, 2015*
---
* Performance improvements
* Bug fixes

1.1.7 *September 14, 2015*
---
* New Trigger: Order Includes Product from Taxonomy Term
* New option on order triggers to select payment method

1.1.6 *September 5, 2015*
---
* New Trigger: Order Includes Product Variation with Specific Attribute
* New Text Variable: {{ order_item.attribute | slug: … }}
* New Action: Add User to Mad Mimi List

1.1.5 *September 2, 2015*
---
* Fix an issue where the license check could fail if WP Cron occurred over SSL

1.1.4 *September 1, 2015*
---
* New Trigger:  Order Includes Product from a Specific Tag
* New Action: Change Order Status
* New Action: Add/Update Product Meta

1.1.3 *August 27, 2015*
---
* Add an additional check to ensure stored abandoned carts are cleared when an order is created
* Fix an issue where checking ‘Once Per User’ prevents order triggers firing for guests
* Improvements to Conversion Tracking

1.1.2 *August 24, 2015*
---
* New report: Conversion tracking (Only tracks Abandoned Carts for now)
* New Trigger Option: Check Status Hasn’t Changed Before Run (useful when queuing)
* New Text Variable Parameter: template lets you define alternative templates for:
	* {{ cart.items }}
	* {{ order.items }
	* {{ order.cross_sells }}
	* {{ wishlist.items }}
* New Email Product List Templates:  
	* product-grid-2-col.php
	* product-grid-3-col.php
	* product-rows.php
* Important: template removed product-listing.php. Instead use product-grid-2-col.php
* Admin area: Expanded actions will now stay expanded after saving

1.1.1 *August 20, 2015*
---
* Add conversion tracking on abandoned carts (report coming soon)
* Fix an issue that prevented triggers firing after guest order

1.1.0 *August 19, 2015*
---
* Total overhaul of the Abandoned Cart system to now use pre-submit email capturing as well as detecting registered users when they aren’t logged in. Implement with new trigger: Abandoned Cart (Guests)

1.0.6 *August 16, 2015*
---
* New Text Variable {{ wishlist.itemscount }}
* New Text Variable {{ order.number }}
* Order Queue by Run Date
* Improve Coupon Generation

1.0.5 *August 13, 2015*
---
* Adds integration with the free YITH Wishlists plugin
* Change the image size of the ‘product.featured_image’ text variable
* Improvements to cart tracking
* Improvements to wishlist triggers

1.0.4 *August 11, 2015*
---
* Improvements to Abandoned Cart
* Allow coupon prefix to be blank
* Minor other fixes

1.0.3 *July 30, 2015*
---
* New feature: Preview ability on emails!
* Security improvements
* Internal improvements

1.0.2 *July 28, 2015*
---
* Fix some license issues

1.0.1 *July 26, 2015*
---
* UI Improvements to Text Variables
	* Single click to select a  Text Variable
	* Change editor to sans serif
* Small changes to some labels

1.0.0 *July 20, 2015*
---
* Launch it!