=== YITH WooCommerce Stripe ===

Contributors: yithemes
Tags: stripe, simple stripe checkout, stripe checkout, credit cards, online payment, payment, payments, recurring billing, subscribe, subscriptions, bitcoin, gateway, yithemes, woocommerce, shop, ecommerce, e-commerce
Requires at least: 4.5
Tested up to: 4.9.4
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Changelog ==

= 1.5.0 - Released on Feb 08, 2018 =

* New: WooCommerce 3.3.1 support
* New: WordPress 4.9.4 support
* New: updated Stripe library to 6.0 revision
* New: updated plugin-fw library

= 1.4.0 - Released on Jan 09, 2018 =

* New: WooCommerce 3.2.6 support
* New: updated plugin-fw to version 3.0
* New: updated Stripe library to 5.8 revision
* Tweak: added check over save_cards flag before token creation; this way cards won't be actually saved if admin disable related option
* Fix: check on captured flag on payment_complete
* Fix: stripe script not being loaded in Add Payment Method page
* Fix: token error when remember card functionality is disabled
* Fix: trial start/end time when pausing/resuming subscriptions
* Dev: added yith_wcstripe_subscription_amount to let third party plugin to change plan amount
* Dev: added yith_wcstripe_card_number_dots filter to let dev change "dots" in cc number
* Dev: added filters to change default CC form labels
* Dev: added yith_wcstripe_environment filter
* Dev: added yith_wcstripe_metadata filter to let third party developers change metadata sent to Stripe servers

= 1.3.0 - Released on Apr 04, 2017 =

* New: WordPress 4.7.3 compatibility
* New: WooCommerce 3.0.0-RC2 compatibility
* New: added italian - ITALY translation
* Fix: plan amount with recurring shipping payment, for YITH WooCommerce Subscription plugin
* Fix: added ajax to refresh amount when hosted checkout needs to be refreshed
* Fix: "Renewal failed" message repeated on my-account page
* Fix: subscription renew link inside MyAccount message
* Fix: guest checkout when purchasing subscription
* Tweak: added check over gateway existence
* Tweak: updated Stripe library to 3.23.0
* Tweak: improved failed renew message, when YITH WooCommerce Subscription active
* Tweak: changed text domain to yith-woocommerce-stripe
* Dev: added yith_wcstripe_capture_payment filter
* Dev: added yith_stripe_locale filter to change locale used in hosted checkout

= 1.2.10 - Released on Jun 16, 2016 =

* Added: ufficial support to WC 2.6
* Fixed: minor bug fixes

= 1.2.9.1 - Released on Jun 13, 2016 =

* Added: notification for failed and success renewal, with yith subscription plugin
* Fixed: bugs for final release of WC 2.6

= 1.2.9 - Released on May 31, 2016 =

* Added: support to WC 2.6 Beta 3
* Tweak: improved exception catcher
* Fixed: bug on Stripe Checkout mode when pay order create manually by admin

= 1.2.8 - Released on Apr 27, 2016 =

* Fixed: amount doesn't shown on stripe checkout
* Fixed: fatal error on card validation on checkout
* Fixed: duplicate cancel notification when triggered "cancel" action from my account
* Fixed: payment due date duplicate on renew

= 1.2.7 - Released on Mar 29, 2016 =

* Tweak: hash on plan name, on avoid subscription configuration no product (like changing price, interval, trial period, etc..)
* Fixed: improved webhooks on payment succedeed
* Fixed: credit card form isn't shown if selected "New card" on checkout page
* Fixed: fatal error with Stripe\Error\API
* Fixed: wrong cart total on hosted checkout
* Fixed: internal server error if the import is lower then .50 cent
* Fixed: a refund from website is marked double, dued an error from webhook
* Fixed: can't create blacklist table and feature not working
* Fixed: total without tax in plan amount

= 1.2.6 - Released on Feb 16, 2016 =

* Added: ability to add new credit card by my account
* Fixed: localization for "Stripe checkout"

= 1.2.5 - Released on Feb 16, 2016 =

* Added: "Stripe checkout" mode directly on checkout page, without button on second page.
* Added: 'order_email' parameter in metadata of Stripe charge
* Added: order note when there is an error during the payment (card declined or card validation by stripe)
* Fixed: stripe library loading causing fatal error in some servers
* Fixed: ccv2 help box not opening on checkout
* Fixed: validation of extra billing fields below credit card form 
* Fixed: bitcoin option didn't work
* Fixed: better response for webhooks, because they remains in pending in some cases

= 1.2.4 - Released on Jan 19, 2016 =

* Added: compatibility with WooCommerce 2.5
* Added: compatibility with YITH WooCommerce Subscriptions and YITH WooCommerce Membership, so now ability to open and manage new subscriptions with Stripe (available only for "Standard" mode of checkout)
* Added: language support for "Stripe checkout" mode
* Added: ability to show extra address fields below credit card info, if you are using any plugin that change fields on checkout, to reduce fraudolent payment risk
* Updated: Stripe API library with latest version

= 1.2.3 - Released on Dec 14, 2015 =

* Fixed: no errors for wrong cards during checkout

= 1.2.2 - Released on Dec 10, 2015 =

* Added: compatibility to multi currency plugin
* Added: compatibility with one-click checkout
* Fixed: bug on refunds for orders not captured yet
* Fixed: localization of CVV suggestion text
* Fixed: bitcoin receivers errors on logs

= 1.2.1 - Released on Aug 19, 2015 =

* Fixed: Minor bug

= 1.2.0 - Released on Aug 12, 2015 =

* Added: Support to WooCommerce 2.4
* Updated: Plugin core framework
* Updated: Language pot file

= 1.1.4 - Released on Jul 24, 2015 =

* Fixed: blacklist table not created on database
* Fixed: blacklist table on admin without pagination

= 1.1.3 - Released on Jul 21, 2015 =

* Added: ability to ban automatically the users with errors during the payment and ability to manage them in a blacklist page

= 1.1.2 - Released on Jun 09, 2015 =

* Fixed: localization of cvv help popup content

= 1.1.1 - Released on Apr 24, 2015 =

* Fixed: creation on-hold orders and flushing checkout session after card error on checkout

= 1.1.0 - Released: Apr 22, 2015 =

* Added: support to WordPress 4.2
* Added: CVV Card Security Code suggestion
* Fixed: bug on checkout

= 1.0.4 - Released: Apr 21, 2015 =

* Added: languages pot catalog

= 1.0.3 - Released: Apr 15, 2015 =

* Added: Name on Card field on Credit Card form of checkout
* Fixed: bug with customer profile creating during purchase

= 1.0.2 - Released: Mar 04, 2015 =

* Updated: Plugin core framework

= 1.0.1 - Released: Mar 03, 2015 =

* Fixed: minor bugs

= 1.0.0 =

* Initial release
