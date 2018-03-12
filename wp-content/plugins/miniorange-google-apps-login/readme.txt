=== Google Apps Login ===
Contributors: miniOrange
Donate link: https://miniorange.com
Tags: google apps, G suite, google apps login, login with google apps, single sign on, google, google login, login with google, auth, authentication, single sign-on, sso, saml,oauth, oauth2,saml sso
Requires at least: 3.5
Tested up to: 4.9
Stable tag: 5.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Google Apps Login provides simple secure login to your WordPress site via user's Google Apps account using SAML.(ACTIVE SUPPORT)

== Description ==

Google Apps Login (G Suite) allows users with google apps account to login to your WordPress website with Google Apps. It acts as a SAML 2.0 Service Provider which can be configured to establish the trust between the plugin and google apps to securely authenticate the user to the Wordpress site. 

Single Sign-On (SSO) with Google Apps uses the latest secure SAML authentication recommended by Google, including 2-factor Authentication if enabled for your Google Apps accounts.

Plugin setup requires you to have admin access to any Google Apps (G suite) domain to register a service provider. You can restrict your users from logging in from outside your private network.

= Free Version Features =
*	Unlimited authentication/ Login with Google Apps.
*	Automatic user registration after login if the user is not already registered with your site.
*	Use Widgets to easily integrate the login link with your Wordpress site.
*	Use Basic Attribute Mapping feature to map wordpress user profile attributes like First Name, Last Name to the attributes provided by Google Apps.
*	Select default role to assign to users on auto registration.
*   Force authentication with Google Apps (G suite) on each login attempt.
*   Use step-by-step guide to configure google apps.
*   Enable Multi-Factor Authentication (MFA) for google apps.

= Premium Version Features =
*	All the Free version features.
*	**SAML Single Logout** - Support for SAML Single Logout
*	**Auto-redirect** - Auto-redirect to Google Apps for authentication without showing them your WordPress site's login page.
*	**Protect Site** - Protect your complete site. Have users authenticate themselves before they could access your site content.
*	**Advanced Attribute Mapping** - Use this feature to map your IDP attributes to your WordPress site attributes like Username, Email, First Name, Last Name, Group/Role, Display Name.
*	**Advanced Group/Role Mapping** - Use this feature to assign WordPress roles your users based on the group/role sent by your IDP.
*   **Short Code** - Use Short Code (PHP or HTML) to place the login link wherever you want on the site.
*	**Reverse-proxy Support** - Support for sites behind a reverse-proxy.
*	**Select Binding Type** - Select HTTP-Post or HTTP-Redirect binding type to use for sending SAML Requests. 
*   **Multiple Google Apps Domain Support** - We support configuration of Multiple Google Apps (G suite) Domains (IDPs) in the plugin to authenticate the different group of users with different domains. You can give access to users by users to Domain mapping (which IDP to use to authenticate a user) is done based on the domain name.
*   **IP Restriction** - Restrict users from logging in from outside the private network.
*   **Google Apps Directory (G suite Directory) Integration** [This feature will provide on a request]- perform scheduled user sync from G 
Suite.


If you require any Single Sign On (SSO) application or need any help with installing this plugin, please feel free to email us at info@miniorange.com or <a href="https://miniorange.com/contact">Contact us</a>.

= Website - =
Check out our website for other plugins <a href="https://miniorange.com/plugins" >https://miniorange.com/plugins</a> or <a href="https://wordpress.org/plugins/search.php?q=miniorange" >click here</a> to see all our listed WordPress plugins.
For more support or info email us at info@miniorange.com or <a href="https://miniorange.com/contact" >Contact us</a>. You can also submit your query from plugin's configuration page.

== Installation ==

= From your WordPress dashboard =
1. Visit `Plugins > Add New`.
2. Search for `Login with Google Apps`. Find and Install `Google Apps Login`.
3. Activate the plugin from your Plugins page.

= From WordPress.org =
1. Download Login with Google Apps plugin.
2. Unzip and upload the `miniorange-google-apps-login` directory to your `/wp-content/plugins/` directory.
3. Activate Login with Google Apps from your Plugins page.

== Frequently Asked Questions ==

= I am not able to configure the Identity Provider with the provided settings =
Please email us at info@miniorange.com or <a href="https://miniorange.com/contact" >Contact us</a>. You can also submit your app request from plugin's configuration page.

= For any query/problem/request =
Visit Help & FAQ section in the plugin OR email us at info@miniorange.com or <a href="https://miniorange.com/contact">Contact us</a>. You can also submit your query from plugin's configuration page.

== Screenshots ==

1. Configure Google Apps in your Wordpress site.
2. Add "Login with Google" link on your website using widget
3. User login to google apps and gets auto logged in into wordpress

== Changelog ==

= 1.0.55 =
* Compatibilty with WordPress 4.9

= 1.0.54 =
* Launched a premium plugin for small sized customers.

= 1.0.53 =
* Updated the pricing of the premium plans

= 1.0.4 =
*Added screenshots and changes into readme file

= 1.0.3 =
* Fixed the bug for default role while user creation

= 1.0.2 =
* IP Restriction*
* Tested up Wordpress 4.6

= 1.0.1 =
* this is the first release.

== Upgrade Notice ==

= 1.0.55 =
* Compatibilty with WordPress 4.9

= 1.0.54 =
* Launched a premium plugin for small sized customers.

= 1.0.4=
*Added screenshots and changes into readme file

= 1.0.3 =
* Fixed the bug for default role while user creation

= 1.0.2 =
* IP Restriction*
* Tested up Wordpress 4.6

= 1.0.1 =
* this is the first release.