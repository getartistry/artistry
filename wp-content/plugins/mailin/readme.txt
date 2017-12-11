=== SendinBlue Subscribe Form And WP SMTP ===
Contributors: neeraj_slit
Tags: sendinblue, marketing automation, email marketing, email campaign, newsletter, wordpress smtp, subscription form, phpmailer, SMTP, wp_mail, massive email, sendmail, ssl, tls, wp-phpmailer, mail smtp, mailchimp, newsletters, email plugin, signup form, email widget, widget, plugin, sidebar, shortcode
Requires at least: 4.4
Tested up to: 4.8.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily send emails from your WordPress blog using your preferred SMTP server

== Description ==

<a href="https://www.sendinblue.com/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=addons_page" target="_blank">SendinBlue</a>’s official plugin for WordPress is a powerful all-in-one email marketing plugin. At a glance:
<ul>
<li><b>Subscription forms</b> - Create custom subscription forms and easily integrate them into your posts, pages or sidebars</li>
<li><b>Contact lists</b> - Manage your contact lists and take advantage of advanced segmentation to improve your campaign performance</li>
<li><b>Marketing campaigns</b> - Easily create and send beautiful newsletters using our mobile-friendly, drag and drop builder to create custom emails or select from our template library</li>
<li><b>Transactional emails</b> - The wp_mail() function automatically uses SendinBlue’s SMTP for enhanced deliverability and tracking</li>
<li><b>Statistics</b> - Real-time report dashboard gives you advanced insights into deliverability and performance: opens, clicks, bounce reports, etc.</li>
<li><b>Marketing automation</b> - Enable Automation on WordPress to auto-install the script on your website and the identify tag on the SendinBlue forms and Wordpress Login </li>
</ul> 

= Subscription Forms =
<ul>
<li>Form designer with WYSIWYG and direct HTML / CSS editing (if desired)</li>
<li>Integration as widget or shortcode [sibwp_form]</li>
<li>Send a confirmation email - you choose the template and the sender</li>
<li>Use a double opt-in confirmation - you choose the template and the sender</li>
<li>URL redirection</li>
<li>Confirmation / error message customization</li>
</ul> 

= Contact Lists =
<ul>
<li>Folder and lists management</li>
<li>CSV and TXT file import</li>
<li>Unlimited custom fields</li>
<li>Advanced segmentation. Example: Search for contacts who are less than 45 years who clicked links in my last 3 campaigns.</li>
</ul> 

= Marketing Campaigns =
<ul>
<li>Responsive, drag and drop email design tools</li>
<li>Library of mobile-friendly, creative email design templates</li>
<li>Subject and content personalization. Example: Hello {NAME},</li>
<li>Inbox and design rendering tests for multiple devices and email clients</li>
<li>Schedule campaigns in advance</li>
</ul> 

= Transactional Emails & Statistics =
<ul>
<li>Automatic replacement of default SMTP when you use wp_mail function</li>
<li>Create transactional email templates that are easy to reuse via the API</li>
<li>Real-time and exhaustive statistics: delivered, opened, clicked, etc.</li>
</ul>

= Marketing Automation =
<ul>
<li>Auto-installation of the automation script on your website</li>
<li>Auto-deployment of the identify tag for SendinBlue’s forms and Wordpress plugin</li>
<li>Access to the SendinBlue online dashboard for workflow creation and management</li>
</ul>  

= Plugin Support =
To get support, please send an email to <a href="mailto:contact@sendinblue.com">contact@sendinblue.com</a>, we will be happy to help you!

The plugin is available in English and French. 

== Installation ==

1.	In your WordPress admin panel, go to Plugins > New Plugin, search for "SendinBlue for WP" and click "Install now". Alternatively, download the plugin and upload the contents of mailin.zip to your plugins directory, which may be  /wp-content/plugins/. 
2.	Activate the SendinBlue plugin through the 'Plugins' menu in WordPress. 
3.	The "SendinBlue" tab must appear in your WordPress side navigation panel, then set your <a href="https://my.sendinblue.com/advanced/apikey/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=addons_page" target="_blank">SendinBlue API key</a> in the plugin homepage.

To get a SendinBlue API key, you have to <a href="https://www.sendinblue.com/users/signup/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=addons_page" target="_blank">create an account</a>. It's free and takes less than 2 minutes!

== Screenshots ==
1. First, your are invited to create an account on SendinBlue then enter your API keys
2. The Homepage gives you an overall view over your campaigns and allows you to activate SendinBlue SMTP for all transactional emails and Marketing Automation to better target your customers
3. The Forms page gives you access to your forms list
4. When you click on a specific form, you can configure your sign up process and customize it
5. The Lists page allows you to see, edit or filter, your lists and your contacts
6. The Campaigns page summarizes your most recent campaign performance and allows you to create a new campaign using our responsive and user-friendly email designer 
7. The Statistics page gives you a global view over your performance: delivered, opened, clicked, etc.
8. From the Widget page, you are able to add the SendinBlue widget in one or more sidebars. For each form, you can choose the fields displayed and the list where contacts are saved.
9. The Workflows page (Marketing Automation activated) allows you to create new workflows in Sendinblue or access your logs or existing workflows

== Frequently Asked Questions ==

= What is SendinBlue? =
SendinBlue is a powerful all-in-one marketing platform. Over 15,000 companies around the world trust SendinBlue to deliver their emails and SMS messages. Combining many powerful features, competitive pricing  and excellent deliverability thanks to our proprietary cloud-based infrastructure, SendinBlue is available and supported in 6 languages: English, French, Spanish, German, Italian, and Portuguese.

= Why use SendinBlue as an SMTP relay for my website? =
By using SendinBlue’s SMTP, you will avoid the risk of having your legitimate emails ending up in the spam folder and you will have statistics on emails sent: deliverability, opens, clicks, etc. SendinBlue’s proprietary infrastructure optimizes your deliverability, enabling you to focus on your content.

= Why do I need a SendinBlue account? =
The SendinBlue for WordPress plugin uses SendinBlue’s API to synchronize contacts, send emails and get statistics. Creating an account on SendinBlue is free and takes less than 2 minutes. Once logged into your account, you can get the API key.
 
= Do I have to pay to use the plugin and send emails? =
No, the plugin is totally free and SendinBlue offers a free plan with 9,000 emails per month. If you need to send more than 9,000 emails / month, we invite you to see our pricing. For example, the Micro plan is $7.37 / month and allows you to send up to 40,000 emails per month. All SendinBlue plans are without any commitment.

= How do I get my get synchronize my lists? =
You have nothing to do - synchronization is automatic! It doesn't matter whether your lists were uploaded on your WordPress interface or on your SendinBlue account: they will always remain up-to-date on both sides.

= How can I get support? =
If you need some assistance, you can post an issue in the Support tab, or send us an email at contact@sendinblue.com.

= How do I create a signup form? =
In order to create a signup form, you need to:
1. Go to Wp admin > SendinBlue > Settings in order to define your form’s fields and settings
2. Integrate the form in a sidebar using a widget from WP panel > Appearance > Widgets. The SendinBlue widget form should appear in your widgets list, you just to have to drag and drop the widget into the sidebar of your choice. 


== Changelog ==
= 2.8.3 =
* fix compatible issue with old forms

= 2.8.2 =
* fix missing alt text for loading gif image
* update user attributes when user already exists in contact list
* Allow to use google recaptcha v2 and invisible recaptcha
* Change MA automation script

= 2.8.1 =
* fix double opt_in issue
* fix language support notice issue

= 2.8.0 =
* add compatibility with wpml plugin
* add invisible google captcha feature
* fix several security issues
* fix date format issue

= 2.7.3 =
* add independence between SendinBlue plugins

= 2.7.2 =
* add some note on plugin forms page
* fix responsive issue on plugin home page
* change the tutorial link

= 2.7.1 =
* fix version upgrade issue

= 2.7.0 =
* Integrate a term acceptance checkbox
* Change redirection for campaigns on plugin home page
* fix re-subscription issue for unsubscribed users

= 2.6.13 =
* Fix sendinblue dev url to prod url in list page
* Change google captcha function

= 2.6.12 =
* Sync users for all user roles such as forum role
* fix some typo in plugin homepage

= 2.6.11 =
* fix forms disappeared issue in v2.6.10

= 2.6.10 =
* fix google captcha issue in form preview
* add prefix to the custom tables

= 2.6.9 =
* fix conflict with other plugin's google Captcha

= 2.6.8 =
* add google Captcha box on the form
* fix MA automation issue
= 2.6.7 =
* Fix some browser compatibility issue for safari

= 2.6.6 =
* Fix browser compatibility issue for safari 

= 2.6.5 =
* Add new feature to sync old your users to the desired list 
* Fix email validation issue
* Fix warning issue of active_ma
* Use wordpress function for CURL request
* Add apply_filter() to the wp_mail() function

= 2.6.4 =
* Fix pre tag issue
* Fix unsubscribe link in email template

= 2.6.3 =
* Fix warning issue by WP_Error
* Fix p tag issue in javascript

= 2.6.2 =
* Fix set_magic_quotes_runtime() error
* Fix subscribe form issue

= 2.6.1 =
* Fix some language issues
* Fix attachement in confirmation email
* Apply nl2br on text/plain only 

= 2.6.0 =
* Integrate Marketing Automation
* Update to use multi forms

= 2.5.5 =
* Fix send email issue

= 2.5.4 =
* Fix warning issue by get sender detail

= 2.5.3 =
* Fix some warning issue to send email

= 2.5.2 =
* Fix send email issue on php 7.0

= 2.5.1 =
* Fix sender list issue
* Fix attachment issue in transactional email
* Update form ajax process

= 2.5.0 =
* Improvement the sender list

= 2.4.15 =
* Fix transactional email issue

= 2.4.14 =
* Fix SMTP issue using wp_mail
* Fix some warning issue

= 2.4.13 =
* Fix some warning issue

= 2.4.12 =
* Fix issue for double optin redirection

= 2.4.11 =
* Fix some errors related to SSL certificate

= 2.4.10 =
* Fix page reload problem on submitting form data

= 2.4.9 =
* Improve transaction template with tags
* Improve subscriber's ip attribute
* Fix some warning issue

= 2.4.8 =
* Update email credits.
* Fix language issue in iframe

= 2.4.7 =
* Fix exception functionality of curl.

= 2.4.6 =
* Fix some issue of curl request.
* Improve subscriber's attributes for double optin.

= 2.4.5 =
* Fix some warning issue and translation

= 2.4.4 =
* Update sendinblue API library into V2.0

= 2.4.3 =
* Fix some warning issue

= 2.4.2 =
* Fix sender issue

= 2.4.1 =
* Fix ajax warning bug

= 2.4.0 =
* Security update to prevent XSS attack.
* Improve transaction template with personalize data.
* Improve widget.

= 2.3.13 =
* No changes in "Settings" after update.

= 2.3.12 =
* Improve validation process.

= 2.3.11 =
* Update validation process.
* Improve error message.

= 2.3.10 =
* Add the functionality to integrate the category attributes of sendinblue.
* Improve loading of setting page.

= 2.3.9 =
* Change iframe url.

= 2.3.7 =
* Update the process for help message.

= 2.3.6 =
* Update the process for blacklisted contact.

= 2.3.5 =
* Improve the function that send template for confirm & double optin.
* Update the process for blacklisted contact.
* Fix the issue of wrong subject in selected template.

= 2.3.4 =
* Fix the issue that user can't send selected template for confirm & double optin.
* Fix the error if user don't have any sender on his setting.

= 2.3.3 =
* Improvement help message.

= 2.3.2 =
* Check with wordpress version 4.1.
* Add function to select mail template for double optin.
* Improvement help message.
* Fix padding issue of subscribe form.
* Update the state of smtp activation automatically.

= 2.3.1 =
* Update sender setting.

= 2.3.0 =
* Updated sendinblue api into v2.0.
Please use the Access Key of API 2.0 in setting of plugin after update plugin.

= 2.2.5 =
* Add exception functionality.

= 2.2.4 =
* Fix some warning issues.

= 2.2.3 =
* Fix sender's details when send email by using wp_mail().

= 2.2.2 =
* Fixed some issue of curl request.

= 2.2.1 =
* Update the french encoding.
* Fixed multi-language issue

= 2.2.0 =
* Update the feautre of smtp activation

= 2.1.2 =
* Update button UI CSS of subscription form

= 2.1.1 =
* Fix login issue
* Test on Wordpress 4.0

= 2.1.0 =
* Update the default form UI
* Update french translation
* Add functionality to remove "white space" when input api info for login.

= 2.0.4 =
* Add security functionality

= 2.0.3 =
* Fix the encode error of French language
* Add the translation of some text
* Fix the Button size at French 

= 2.0.2 =
* Fix the error of account detail

= 2.0.1 =
* Fix compatible error

= 2.0 =
* update sendinblue api
* Add functionality (List,Contact,Stat,Form Management)
* Update UI user-friendly
