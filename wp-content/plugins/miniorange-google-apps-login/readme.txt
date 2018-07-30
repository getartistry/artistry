=== Google Apps Login (OAuth/SAML) ===
Contributors: cyberlord92
Donate link: https://www.miniorange.com/
Tags: google apps login,login with google,google saml,google login, google oauth
Requires at least: 3.5
Tested up to: 4.9.6
Requires PHP: 5.3+
Stable tag: 6.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Most secure Google Apps Login Plugin for WordPress. Quick & Easy Configuration with Oauth/SAML which allows authentication through Gsuite (Formerly Known as Google Apps).

== Description ==
Now Secure your website login by Google Apps Login which allows Single Sign On(SSO) to WordPress using Google credentials (Gsuite) for your users or employees. Google Apps Login use Google credentials to provide better experience compared to WordPress login options. For example - User can do One-Click Login to WordPress account with their Google Credentials so it’s not required of them to remember multiple sets of username & password.

Google Apps Login is trusted enterprise plugin & used by many organizations for Single Sign On(SSO). Google Apps Login supports both OAuth & SAML to provide secure authentication. You can choose from either SAML or OAuth protocol to Single Sign On(SSO) into WordPress. Which protocol to choose depends on your requirements. You can contact us at info@miniorange.com for help with deciding the protocol you choose.

Google Apps Login maps attribute like username, firstname, lastname & email to your WordPress user details. With the help of Google Apps Login, you can manage user attributes from Gsuite Profile data to your WordPress site & WordPress updates user details according to the Gsuite user profile data at the time of registration/login. Also, you can control user access through role mapping which helps you to assign specific roles to users in WordPress according to the user's group in Google Apps/Gsuite.

= FREE VERSION FEATURES =
*   Unlimited authentication with your Google Apps as IDP or OAuth Provider.
*   Automatic user registration after login if the user is not already registered with your site.
*   Use Widgets to easily integrate the login link with your WordPress site.
*	Attribute Mapping- Basic Attribute Mapping feature to map wordpress user profile attributes like email and first name. Manage username & email with data provided
*	Oauth Provider Support- It Supports only one Oauth Provider. (ENTERPRISE : Supports Multiple Oauth Provider)
*	Redirect URL after Login- Automatically Redirect user after successful login. Note: Does not include custom redirect URL

= No SSL restriction =
*	Login to WordPress using google credentials.

= Premium Version Features(SAML) =
*   All the Free version features.
*   **SAML Single Logout [Premium]** - Support for SAML Single Logout (Works only if your IDP supports SLO).
*   **Auto-redirect to IDP [Premium]** - Auto-redirect to your IDP for authentication without showing them your WordPress site's login page.
*   **Protect Site [Premium]** - Protect your complete site. Have users authenticate themselves before they could access your site content.
*   **Advanced Attribute Mapping [Premium]** - Use this feature to map your IDP attributes to your WordPress site attributes like Username, Email, First Name, Last Name, Group/Role, Display Name.
*   **Advanced Role Mapping [Premium]** - Use this feature to assign WordPress roles your users based on the group/role sent by your IDP.
*   **Short Code [Premium]** - Use Short Code (PHP or HTML) to place the login link wherever you want on the site.
*   **Reverse-proxy Support [Premium]** - Support for sites behind a reverse-proxy.
*   **Select Binding Type [Premium]** - Select HTTP-Post or HTTP-Redirect binding type to use for sending SAML Requests.
*   **Integrated Windows Authentication [Premium]** - Support for Integrated Windows Authentication (IWA)
*   **Step-by-step Guides [Premium]** - Use step-by-step guide to configure your Identity Provider like ADFS, Centrify, Google Apps, Okta, OneLogin, Salesforce, SimpleSAMLphp, Shibboleth, WSO2, JBoss Keycloak, Oracle.
*   **WordPress Multi-site Support [Premium]** - Multi-Site environment is one which allows multiple subdomains / subdirectories to share a single installation. With multisite premium plugin, you can configure the IDP in minutes for all your sites in a network. While, if you have basic premium plugin, you have to do plugin configuration on each site individually as well as multiple service provider configuration's in the IDP.

    For Example - If you have 1 main site with 3 subsites. Then, you have to configure the plugin 3 times on each site as well as 3 service provider configurations in your IDP. Instead, with multisite premium plugin. You have to configure the plugin only once on main network site as well as only 1 service provider configuration in the IDP.

= Premium Version Features(OAuth) =

*	Attribute Mapping- Custom Attribute Mapping feature to map wordpress user profile attributes like username, firstname, lastname, email and profile picture. Manage username & email with data provided
*	Support for Shortcode- Use shortcode to place login button anywhere in your Theme or Plugin
*	Advanced Role Mapping- Assign roles to users registering through Oauth Login based on rules you define.
*	Customize Login Buttons / Icons / Text- Wide range of Oauth login Buttons/Icons and it allows you to customize Text shadow
*	Custom Redirect URL after Login- Provides Auto Redirection and this is useful if you wanted to globally  protect your whole site
*	Redirect URL after logout- Auto Redirect Users to custom url after logout in WordPress
*	OpenID Connect Support- Supports login with any 3rd party OpenID Connect server.
*	Multiple Userinfo Endpoints Support- It Supports multiple Userinfo Endpoints.
*	Account Linking- Supports the linking of user accounts from OAuth Providers to WordPress account.
*	App domain specific Registration Restrictions- Restricts registration on your site based on the person's email address domain
*	Multi-site Support- Unique ability to support multiple sites under one account
*	Reverse Proxy Support- Support for sites behind a reverse-proxy or on-prem instances with no internet access.
*	Email notifications- You can customize the E-mail templates used for the automatic email notifications related to user registration.
*	Extended OAuth API support- Extend OAuth API support to extend functionality to the existing OAuth client.[ENTERPRISE]
*	BuddyPress Attribute Mapping- It allows BuddyPress Attribute Mapping.[ENTERPRISE]
*	Page Restriction according to roles- Limit Access to pages based on user status or roles. This WordPress plugin allows you to restrict access to the content of a page or post to which only certain group of users can access.[ENTERPRISE]
*	Login Reports- Creates user login and registration reports based on application used. [ENTERPRISE]

= SUPPORT =
Customized solutions and support options are available. Email us at info@miniorange.com.

== Installation ==
= From your WordPress dashboard =
1. Visit `Plugins > Add New`
2. Search for `Google Apps Login`. Find and Install `Google Apps Login (OAuth/SAML)`
3. Activate the plugin from your Plugins page

= From WordPress.org =
1. Download miniOrange otp verification.
2. Unzip and upload the `Google Apps Login` directory to your `/wp-content/plugins/` directory.
3. Activate Google Apps Login from your Plugins page.

== Frequently Asked Questions ==
= I don't see any OAuth applications to configure. I only see Register to miniOrange? =
Our very simple and easy registration lets you register to miniOrange. OAuth login works if you are connected to miniOrange. Once you have registered with a valid email-address and phone number, you will be able to configure applications for OAuth.

= How to configure the OAuth applications? =
When you want to configure a particular application, you will see a Save Settings button, and beside that a Help button. Click on the Help button to see configuration instructions.

= I am not able to configure the Identity Provider with the provided settings =
Please email us at info@miniorange.com or <a href="http://miniorange.com/contact" >Contact us</a>. You can also submit your app request from plugin's configuration page.

= I need to customize the plugin or I need support and help? =
Please email us at info@miniorange.com or <a href="http://miniorange.com/contact" target="_blank">Contact us</a>. You can also submit your query from plugin's configuration page.

= For any query/problem/request =
Visit Help & FAQ section in the plugin OR email us at info@miniorange.com or <a href="http://miniorange.com/contact">Contact us</a>. You can also submit your query from plugin's configuration page.

== Screenshots ==
1. Add OAuth Applications
2. General settings like auto redirect user to your IdP.
3. Guide to configure your WordPress site as Service Provider to your IdP.
4. Configure your IdP in your WordPress site.

== Changelog ==

= 6.0.3 =
* Error Description Handling

= 6.0.2 =
* Tested upto WordPress 4.9.6

= 6.0.1 =
* Minor Improvements in readme.

= 6.0.0 =
* Added support for OAuth SSO.

= 5.0.7 =
* Compatible with WordPress 4.9.4 and Removed External Links

= 1.2.2=
* Fixed OTP related bug

= 1.2.1 =
* Added Custom Display Button

= 1.1.1 =
* First version with supported applications as slack, discord, aws, google, facebook