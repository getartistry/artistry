=== Users Insights WordPress Plugin ===

- Plugin Name: Users Insights
- Plugin URI: https://usersinsights.com/
- Description: Everything about your WordPress users in one place
- Version: 3.3.1
- Author: Pexeto
- License: GPLv2 or later
- License URI: http://www.gnu.org/licenses/gpl-2.0.html
- Copyright: Pexeto 2017

Users Insights is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.


== Installation ==

1. Upload the users-insights.zip file from the Plugins -> Add New -> Upload Plugin page.
2. Activate the plugin
3. Go to the UsersInsights page to access the Users Insights users table, filters and other functionality.
4. Visit the Features page: https://usersinsights.com/features/ to learn more about all of the Users Insights functionality


== Using the Geolocation Module ==

1. Copy the Geolocation API license key that comes with your purchase - you should have received an email containing the license key. You can also access your API keys from your account page: http://usersinsights.com/account/
2. Go to the Module Options -> Geolocation, click on the "Settings" button of the Geolocation Module and paste the Geolocation API license key into the license field.
3. Activate the license and then Activate the module

== Updating Users Insights ==

1. Go to your account on the UsersInisghts site: http://usersinsights.com/account/ and download the latest version of the plugin
2. Delete the currently installed plugin from the Plugins page of your WordPress installation
3. Upload and activate the latest version of the plugin (you can follow the instructions from the "Installation" section above)


== Changelog ==

3.3.1
- Fixed: WooCommerce review stars not displayed in user profile section (since 3.3.0 update)
- Fixed: Overflow issue of the custom fields table

3.3.0
- New: Introduced Segments - you can now save your frequently used filters as segments and easily apply them later
- Introduced compatibility with the upcoming Ultimate Member 2.0
- Fixed: Column ordering from the eye icon menu sometimes doesn't work properly
- Fixed: Do not show the bulk action button if the current user is not allowed to update users
- Fixed: Updated the Browser library to fix a PHP7 deprecation notice & detect the Edge browser
- General code improvements

3.2.0
- New: WooCommerce "Has used coupon" filter, showing all customers that have used a selected coupon/discount code
- New: WooCommerce number of reviews column & filter, showing the number of product reviews that each customer has left
- New: List WooCommerce reviews in the user profile page
- New: LearnDash "Has/has not enrolled to course" filters, showing all the users that have/have not enrolled to a particular course, regardless of whether they have completed it or not
- New: LearnDash Number of courses in progress column & filter, showing the number of courses that each user has started but not completed
- New: Added First Name and Last Name as separate columns
- Improved the way the roles are displayed on the table - lists all the role names assigned to the user
- WooCommerce query optimizations - improved the way the Number of Orders, Last Order and Lifetime Value columns are loaded on the table, especially when the table is not sorted by any of these fields


3.1.1
- Fixed: Alignment issue in the user table footer
- Improved: Allow HTML data in the user table
- General code improvements

3.1.0
- Added: Bulk add/remove group functionality
- Improved: The way the WooCommerce Lifetime Value data is loaded - since WooCommerce doesn't always
update this value correctly (it is sometimes set to null), instead of using the WooCommerce value,
we now compute it in the database query
- Improved: General UI improvements of the checkboxes and the dialogs
- Fixed: Compatibility issues with the upcoming WooCommerce 2.7
- General code improvements


3.0.0
- Added: LearnDash Module - detects the LearnDash user activity and makes it available in the user table and filters
- Added: Icons to the user activity list in the user profile section
- Added: A refresh button in the license section of the Module Options page, allowing to refresh the license status
- Fixed: WooCommerce Memberships - cannot filter by membership status when there are no columns from the memberships module visible on the table
- General code improvements

2.9.0
- Added: WooCommerce Memberships module (beta) - retrieves and displays the user data from the WooCommerce Memberships extension
- Added: Next Payment field to the WooCommerce Subscriptions module
- Improved: The style of the elements like EDD & WooCommerce orders in the user profile section
- General code improvements

2.8.0
- Added: WooCommerce Subscriptions module (beta) - retrieves and displays the WooCommerce Subscriptions extension user data, such as number of subscriptions and subscription status
- Added: WooCommerce Lifetime Value field, showing the total amount spent by each user
- Minor bug fixes

2.7.0
- Improved: Introduced custom capabilities for accessing the Users Insights page, managing groups & custom fields and managing options
- Improved: The maps design in the map view and user profile sections
- Improved: The design of the filters - added a search to the option list when it's too long and added icons to the fields to improve the visibility
- Added: Option to filter BuddyPress users by the groups that they belong/don't belong to
- Added: "View Ultimate Member Profile" button in the user profile section
- Added: A read-only date type for the custom fields section. This field can be used to retrieve already stored user meta from a date type. The filters will provide date-based operators and also the table will allow sorting by this field in a chronological order.
- Improved: Replaced the year/month/day selects with a date picker
- General code and design improvements - better dialogs, tooltips on the action buttons, etc.

2.6.1
- Fixed: bug with the date filters

2.6.0
- Replaced Google maps with Leaflet maps (http://leafletjs.com/). Using map tiles by OpenStreetMap contributors(http://www.openstreetmap.org/copyright) and map layers by Stamen Design (http://stamen.com/)
- General code improvements

2.5.0:
- Added: "Is set" and "Is not set" operators for the option fields in the filters
- Added: support for the User Tags extension of Ultimate Member
- Improved: Query optimizations for the single user profile section when a large number of custom fields are registered
- Improved: Ultimate Member Module - provide a drop-down with the available options for the multi-option and checkbox fields in the filters
- Fixed: Issue with the available year range when filtering by a date field
- Fixed: Ultimate Member Module - add support for radio fields that store the data in a PHP serialized format
- Code improvements and minor bug fixes

2.4.2:
- Fixed: Issue with the database query when using special characters
- Added: A debug page that can be helpful to troubleshoot issues

2.4.1:
- Fixed: User table not loading when the Gravity Forms & User Registration Add-on are active, but the Gravity Forms Module of Users Insights is inactive

2.4.0:
- Added: Gravity Forms Module - Provides Gravity Forms related filters and data. Detects and displays the custom user data saved with the Gravity Forms User Registration Add-on.
- Added: New multi-option filter type that works like the text type, but only searches strings for a query - it doesn't include string options like "starts with" or "ends with", as usually those options are saved as serialized or JSON data
- Improved: BuddyPress & Ultimate Member: for performance and usability reasons make the custom user profile fields hidden on the table by default, so that when there are too many fields registered they won't be all displayed on the table
- General code improvements


2.3.0
- Added: BuddyPress Module - automatically detects and displays the custom user profile fields in the user table
- Fixed: Saved year not selected when editing a date filter and date not reset properly when changing the field to filter by option

2.2.0
- Added: Ultimate Member Module - automatically detects and displays the custom user fields data generated with the Ultimate Member forms
- Added: Option to change the default columns order in the Users Insights table
- Added: Option to set the year range for the date fields filters
- Improved: General design and responsive layout improvements
- Fixed: WP 4.5 compatibility issue - color options not displayed when editing a group

2.1.0
- Added: automatic plugin updates from the dashboard. Added a Users Insights License section in the Module Options page that allows adding one global license for both the geolocation and automatic updates
- Improved: Query Optimizations - major refactoring to optimize the query in the users table and the export
- Improved: Design improvements on the modules page
- Fixed: issues with the BuddyPress module in some cases on multi-site
- Fixed: issue with filtering users by role in some cases
- Fixed: issue with the bbPress query when the table includes a left join that returns more than one row per user (e.g. applying a filter "group is set" and the user has more than one group set)
- Fixed: EDD filtering by product ordered not working in some cases
- Fixed: cannot remove all the assigned groups from a user
- Fixed: BuddyPress Groups Created list not displayed in the user profile section on multisite


2.0.1
- Made the CRM features (groups, notes and custom fields) more customizable - added hooks that can be used to change some of their options and functionality from 3rd party plugins_url
- Fixed: Empty map element displayed on the user profile section when the user has a location saved, but the geolocation module is disabled
- Fixed: WooCommerce module - exclude trashed orders from the orders column
- Fixed: Issue with editing a custom field value from the user profile - when the field is a number field and the value of the field is deleted, it shows "null" instead of an empty value
- Fixed: Filtering by role not showing any results
- Improved: The geolocation lookup functionality
- General code improvements

2.0.0
- Added CRM Features, such as:
- Added an option to assign groups to users
- Added notes section where you can add notes for each user
- Added custom user meta fields - added an interface to register custom fields and after the fields are registered, they are available in the users table and filters and they can be updated in the user profile section
- General code improvements and minor bug fixes

1.1.1
- Improved: EDD Module - changed the URL of the View Orders link in the profile section to open the default EDD Payments page filtered by the selected customer (rather than Users Insights generating the payments info)
- Improved: EDD + Geolocation Module - Run the check to save location on purchase confirmation
- Improved: EDD Module - general DB query improvements: made the query joins use the EDD customer ID, instead of relying that the customer will be an author of the payment post
- Fixed: EDD Module - issue with the Lifetime Value filter


1.1.0
- Added: Easy Digital Downloads support, included as a separate module, it retrieves and displays data from the Easy Digital Downloads orders made by the WordPress users
- Fixed: WP 4.4 issue - the line height of the number inputs in the filters section is too big
- Fixed: issue with columns that are casted - apply the casting when ordering by the column as well
- General code improvements and minor bug fixes
