Video SEO
=========
Requires at least: 4.4<br/>
Tested up to: 4.7<br/>
Stable tag: 4.8<br/>
Depends: wordpress-seo

Video SEO adds Video SEO capabilities to WordPress SEO.

Description
-----------

This plugin adds Video XML Sitemaps as well as the necessary OpenGraph markup, Schema.org videoObject markup and mediaRSS for your videos.

Installation
------------

1. Go to Plugins -> Add New.
2. Click "Upload" right underneath "Install Plugins".
3. Upload the zip file that this readme was contained in.
4. Activate the plugin.
5. Go to SEO -> Extensions and enter your license key.
6. Save settings, your license key will be validated. If all is well, you should now see the XML Video Sitemap settings.
7. Make sure to hit the "Re-index videos" button if you have videos in old posts.

Frequently Asked Questions
--------------------------

You can find the [Video SEO FAQ](https://kb.yoast.com/kb/category/video-seo/) in our knowledge base.

Changelog
=========
### 4.8: May 23rd, 2017
* Compatibility with Yoast SEO 4.8

### 4.7: May 2nd, 2017
* Compatibility with Yoast SEO 4.7.

### 4.6: April 11th, 2017
* Compatibility with Yoast SEO 4.6.

### 4.5: March 21st, 2017
* Only invalidate sitemaps on configured post types.
* Fixes a bug where there was a fatal error thrown when the plugin was active without Yoast SEO or Yoast SEO Premium.

### 4.4: February 28th, 2017

* Adds a minimum and maximum value to the video rating field.
* Adds the `og:video:secure_url` meta tag.

### 4.3: February 14th, 2017

* Compatibility with Yoast SEO 4.3.

### 4.2.1: February 3rd, 2017

* Bugfixes
	* Fixes "Fatal error: Class 'yoast_i18n' not found". 

### 4.2: January 31st, 2017

* Bugfixes
	* Fixes translator comments that were missing or didn't follow the guidelines.

### 4.1: January 17th, 2017

* Bugfixes
    * Fixes link to google article about video sitemaps. 
    * Fixes a bug where the video-seo menu would overwrite the go premium menu item.
    * Fixes: If a post uses a custom title/description template with variables, the variables were not being replaced correctly for the Video sitemap.
    * Minor spelling & grammar fixes.
    * If a video was previously detected, but the post type has since been excluded from VideoSEO, the video opengraph and schema tags would still be added to the front-end page. This has been fixed now.
    * Fix case-sensitivity issues with video object meta tags.
    * Minor XHTML syntax fix.
    
* Enhancements
    * Improves styling for notices.
    * Minor improvements for compatibility with Yoast SEO.
    * Minor UI improvements for buttons and translations.
    * Add the video:duration tag to video page headers. 
    * Clarify what effect the option to allow videos to be embedded by other sites has.
    * Clarify the description of the "Family Friendly" option used in the metabox.
    * Improve support for the Yandex search engine by adding some Yandex specific tags. This can be turned off using the new wpseo_video_yandex_support filter (return false to turn it off).
    * Allow for adding additional schema meta tags - such as transcript to a video object using the new wpseo_video_object_meta_content filter.
    * Clarify the description for the family friendly checkbox.  

### 4.0: December 13th, 2016

* Fixes the YouTube video player URL to always use a protocol. This solves issues where the Google invalidates the sitemap and where Facebook does not recognize the player. (needs force re-index for existing posts)

### 3.9: November 29th, 2016

* Enhancements
    * Added support for the additional Wistia video urls and embed codes. If you use the Wistia video service, re-indexing your videos is highly recommended.
    * Added fallback for the detail retrieval of private Vimeo videos. This will allow these to be recognized. (needs force re-index for existing posts).
    * Added recognition of //player.vimeo.com/... type URLs. (needs force re-index for existing posts).
    * Change the 'og:type' meta value to the more accurate 'video.other'.
    * Change the 'og:video:type' meta value HTML5 which is now more accurate than Flash in most cases.
    * Minor improvements in behaviour when installed on WP multi-site.

* Bugfixes
    * Fixed the YouTube video player URL. This should solve black screens and/or "Unable to resolve DNS" errors when embedding videos on Facebook and other sites. (needs force re-index for existing posts)
    * Updated the Vimeo video player URL to the new HTML5 player format (with Flash fallback). This should solve black screens and/or "Unable to resolve DNS" errors when embedding these videos on Facebook and other sites. (needs force re-index for existing posts).

### 3.8: November 8th, 2016

* Enhancements
    * The wpseo_sitemaps_base_url filter will now be respected by the VideoSEO plugin.
    * Makes the oEmbed recognition compatible with the upcoming WP 4.7.

* Bugfixes
    * Minor improvements in video URL recognition.
    * Fixes a fatal error on PHP 5.2 when adding a YouTube video (_undefined method DateTime::add()_ / _undefined class DateInterval()_).
    * Fixes a bug where adding a video in a custom post type would show an undefined index `content_width` when used in combination with non-compliant themes
    * Fixes support for Advanced Responsive Video Embedder plugin.
    * Fixes support for Automatic YouTube Video Post plugin.
    * Fixes a bug where the sitemap had the wrong style when a custom post type 'video' exists.
    * Makes sure that the video sitemap will be available as soon as this plugin is activated and unavailable after deactivation.
    * Fixes "Disable video for this post" per-post setting not being respected for the og: meta tags which led to Facebook still displaying the video even if the video for the post was disabled.
    * If an invalid date is encountered for the publication date of a video post, the publication date will be re-evaluated.
    * If a video post title or content/excerpt is - or has been - updated, this will now be reflected in the sitemap and the video metadata. (needs force re-index for existing posts)
    * If a video post SEO title or SEO description is - or has been - added/adjusted, this will now be reflected in the sitemap. (needs force re-index for existing posts)
    * If a SEO description template had been set for the post type which includes the video, this will now be respected. (needs force re-index for existing posts)
    * If a video post was first saved as draft and only published later, the publication date would be stuck on the draft date in the sitemap, this has been fixed. (needs force re-index for existing posts)
    * The "Force re-index" functionality was broken with the implementation of the progress bar. This has now been fixed. Checking the "Force re-index" checkbox will now work again as expected, including the regeneration of thumbnails.
    * The "Re-index" functionality did not properly respect the post types to be indexed for the Video sitemap as set on the VideoSEO settings page, which unintentionally led to fewer items being re-indexed than they should. This has now been fixed.
    * The re-index functionality has been made more efficient and should now - for the same number of posts - be faster.
    * The sitemap cache was not automatically cleared after a re-index. This has now been fixed.
    * Fixes the minimum requirement checks on activation of the plugin.
    
### 3.7: October 11th, 2016

* Enhancements
    * Added iframe-based support for uStudio videos.
    * Added missing index.php files.

### 3.6: September 27th, 2016

* Changes
    * Updated translations.

### 3.5: September 7th, 2016 

* Changes
    * Adds support for Featured Video Plugin, props [ahoereth](https://github.com/ahoereth)


### 3.4: July 19th, 2016

* Changes
	* Updated translations.

### 3.3: June 14th, 2016

* Enhancements
	* Adds the Yoast i18n module to the Yoast SEO Video settings page, which informs users the plugin isn't available in their language and what they can do about it.

* Bugfixes
    * Fixes a bug where the support beacon for Yoast SEO Video was added to all Yoast SEO settings pages.
    * Fixes a bug where updates were not working reliably when multiple paid Yoast plugins were active.

### 3.2: April 20th, 2016

* Fixes a bug where the video sitemap cache wasn't cleared on activation. 
* Fixes a bug where video specific checks that were added to the content analysis would no longer work in combination with Yoast SEO 3.2 and higher.
* Fixes a bug where clicking the 'Update now' button on the plugin page didn't update correctly.

### 3.1: March 1st, 2016

* Bug fixes
	* Fixes a JS error on the post edit page causing the content analysis to break in combination with Yoast SEO versions higher than 3.0.7.
	* Fixes a bug where our license manager could sometimes not reach our licensing system due to problems with ssl.

* Enhancements
	* Makes sure users don't have to reactivate their license after updating or disabling/enabling the plugin.
	* Adds a support beacon on the Video SEO settings page enabling users to ask for support from the WordPress backend.

### 3.0: November 18th, 2015

* Synchronized plugin version with all other Yoast SEO plugins for WordPress.

* Bug fixes
	* Fixes a fatal error that could occur while reïndexing the video sitemap.
	* Fixes the video metabox that was broken in combination with Yoast SEO 3.0.
	* Fixes deprecation warnings for filters that have been removed in Yoast SEO 3.0

* Enhancements
	* Made sure video specific content analysis checks work well with the Real Time content analysis tool in Yoast SEO 3.0.


== Upgrade Notice ==

1.6
---
* Please make sure you also upgrade the WordPress SEO plugin to version 1.5 for compatibility.
