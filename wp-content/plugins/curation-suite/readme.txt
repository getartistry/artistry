=== Curation Suite Plugin ===
Contributors: You Brand, Inc.
Tags: content curation, curation, round up, long form curation
Requires at least: 3.5+
Tested up to: 4.9
Stable tag: 2.9.7
License: See Members area - https://members.youbrandinc.com/
License URI: https://members.youbrandinc.com/

== Description ==
Easily curate content in WordPress.

== Installation ==

Install as usual for any plugin. Check members area for tutorial video and full manual.

== Frequently Asked Questions ==

= How Do I Use the Curation Suite Plugin? =

Visit the members area at https://members.youbrandinc.com/dashboard/curation-suite/curation-suite-product-tutorials/ to get full tutorials.

== Changelog ==
= 2.9.7 =
* Improved Curation Parser
* UI - Listening Engine page enhancements
* Update for false positive report of malware URL from some scanners. This was literally a comment in code and not anything malicious at all.

= 2.9.6 =
* New Feature - Limit by Timeframe. Now you can choose to limit your results by timeframe. See it in action here:  https://curationsuite.com/product-news/update-2017-1/
* Update - Trending feed updated to include limit by timeframe option
* UI Update - on the Listening Engine search we moved a few things around so it looks better.

= 2.9.5 =
* Hotfix for Quick Curation Editor - under some sites the attribution link included extra elements that caused errors on the link, this update fixes that.
* 6 Hour Listening Engine Time Frame - We added an additional "Last 6 Hours" option for content sorting in the Listening Engine.
* Google News Update - Google has depreciated the Google News API we were using so the on demand search needed to be updated. This new update does not have snippets. We are looking at a way to bring snippets to the Google News search in a future update.

= 2.9.4 =
* New Feature - CommentaryIzer: Get quick ideas for adding your own commentary when using the Quick Curation Editor. See it in action: https://curationsuite.com/product-news/commnetaryizer/
* CommentaryIzer Notes: This is version 1. We want to expand beyond just a simple prompt to get you started adding top notch commentary. We are currently testing a few models of how to expand this functionality. We aren't quite ready to share those updates yet but I think you'll be excited once we drop those as well.
* Update - We moved a few things around (includes and code) in this release to get ready for future updates
* Fix - We found a minor error that popped up in multi-site environments. Basically there were cases where the Curation Suite admin screen didn't display or wasn't available. That's resolved now.
* Fix - We updated the API call to the Listening Engine to handle some edge cases where certain versions of PHP behave differently.

= 2.9.3 =
* New Feature - A new quicker way to publish curations with the Quick Curation Editor: https://curationsuite.com/product-news/quick-curation-editor/ Now in the Listening Engine Reading Page you'll find a new icon on each piece of content (2nd from the left).
Clicking this will open the Quick Curation Editor allowing you to fully edit the title, snippet, category, tags and add your own commentary.
Once you're done editing simply click Publish Post and you're post will be published.
You also have the options to select if you want the post to be published right away, set as a draft, or ready for publishing.
* Enhancement - We streamlined the Options on the Reading Page a bit by having the video & article sort options contained on one row.
This provides a little more screen real estate without having to scroll, always a welcome addition.
* Update - New 6 hour time-frame for the Listening Engine sort. Now see stories published in the last 6 hours, the shortest time frame used to be 12. We will most likely be updating this even lower but have to put in a few scaling changes before that.
* Update - We added a shortcut to see your saved content from the Listening Engine. You'll find this shortcut in 2 places. On the Reading Page it's in th euppper right right next to the "Show Content" shortcut.
In the Curation Suite sidebar you'll find the shortcut link in the Listening tab on the upper right. Clicking on this link will load your saved content within that Listening Engine.
* Enhancement - Platform Status Indicator: We added a Platform health indicator to the search for the Listening Engine. You'll find this on the Reading Page at the top next to the Platform Setup shortcut.
If the Listening Engine is down for any reason you'll see a notice here. Otherwise it should show a green indicator.
* Update - Brick size change: with the addition of the new Quick Editor button we had to increase the brick size of content in the Listening Engine.
* Update - Added Curation Suite Admin screen floating save button: We have along admin screen for Curation Suite, because of that we found it annoying to change a setting and have to scroll up or down to click save.
So we added one more save button that is always visible no matter how much you scroll. You'll find it at the bottom right in the Curation Suite admin screen. Hey, every scroll and second matters!
* Update - We increased the font size of the "Content Added" notice that shows up when you add content using the Curation Suite sidebar.
* Enhancement - We updated the action links (curate, ignore, save) in the Curation Suite sidebar Listening Tab to be bigger and also did a few more minor layout fixes to make things a bit more readable.
* Enhancement - We are starting to add titles to all searches or results in the Listening Engine. The first one is when you click on saved content you'll see a title at the top of your results that says "Save Content".
We want to provide indicators on where you are or what you're searching for... plus this section might be used for a future update.
* Fix - for some reason the video sort drop down wasn't set right to display the icons, we fixed that in this release.

= 2.9.2 =
* New Feature - Override Direct Link - https://curationsuite.com/product-news/direct-curation-links-override-update/ - If you have direct links turned on you can override the direct linking on each individual post.
    At the top of your post edit screen you'll see the direct curated link and below that will be a new checkbox that says "override direct link".
    Clicking this will mean the link will now to to your post URL for that post.
* Update - Website block update - https://curationsuite.com/product-news/website-blocking-update/. Now block a website/domain for 2 hours, 4 hours, 12 hours, 1 day, 2 days, 7 days, or forever. Yes we added more options (the hours - before we only had permanent, 2 day, 7 day).
    This works as you would expect, click on the BLOCK next to domain name on a piece of content and click on whatever option you want.
    2 Hours is a great option if you are actively curating and you've already curated from a site, simply block it for a short time.
    We also updated the text, before the options said "block for 1 day".
    We thought that was a little unnecessary in the UI because the only way to get there was by clicking block. So it was a little redundant.
* Update - We reduced the size of a content item in the Reading Page for the Listening Engine. This should help provide a wider view for content, trying to get a 4 in a row for most standard sized setups or screens.
* Fix - Screen shot feature - The screenshot feature needed an update to a secure https connection, that was added in this release.
* Fix - There were some layout issues with results on the Slideshare search, we fixed those in this release
* Fix - Slideshare Featured Image Upload - A recent change at SlideShare caused the featured image option to not function properly. We found the issue and resolved it in this release.
* Fix - Updated certain errors that popped up on the Curated News Page and Soon to be... well we aren't ready to announce this one yet.
* Fix - Kreativa theme fix their layout issue in the post area-- bad CSS from this theme created issues with the Curation Suite sidebar.
* Fix - FusionBuilder plugin assumes some stuff and breaks if our plugin is installed, so we fix their layout issue.
* Refactor - Moved some things around so only admin functions are loaded in admin
* Refactor - now when you first activate the Curation Suite plugin the Listening Engine is turned on by default. You can turn it off by visiting the Curation Suite Admin screen. WordPress admin menu: You Brand, Inc -> Curation Suite

= 2.9.1 =
* Hotfix for error: Uncaught ArgumentCountError. On certain versions of PHP this error would popup, this release resolves this error.

= 2.9.0 =
* New Awesome Feature: Direct Curation Links - Now you can have all your curations (single source) link directly to the curated article. See overview: https://curationsuite.com/product-news/direct-link-curation/. Note: Please watch tutorial before you use this feature so you fully understand what it does.
* Curation Suite Admin - added option to turn on direct curation links. You'll see the option near the bottom of the options screen.
* Fix - In the last release the Image Credit shortcut was broke for certain sites in certain circumstances -- that has been resolved in this release.
* CQS Removal - In the last update we failed to remove CQS from the Slideshare and Link Buckets. Since this feature has been deprecated we removed the CQS link in those places.

= 2.8.9 =
* New Feature - Curation Tracking - now in the on demand search you can choose to highlight content you've previously curated. See video: https://youtu.be/G9_wjdyUHrk
* Curation Tracking - You'll see a new checkbox on the on demand searches that says "highlight curated content". When this is clicked and you do a search any content you've curated (using Curation Suite) on the site you're on will give you a notice. This notice will say "Story Curated". You'll also get a link to the story, a link to your post it was curated on and a edit link to edit the post.
* Free Search Feature Update - Now on the free search in the Listening Engine you can choose what type of content to search for: articles, videos, or both. Those options are in the second drop down and should be pretty intuitive to use.
* Action Notifications - we started adding some notifications in the post area for actions or when you click on certain buttons. We've always tried to have a minimalistic approach to our features but we also noticed that sometimes you click a button you want to know it did what it's supposed to do. A good example of this is clicking the little "H" link next a headline in your searches. This will add the headline to your Post Title. Now when you click this there will be a notice that tells you the headline was added. Minor thing but notices can be helpful.
* Layout Fixes - we adjusted some of the styling of the Curation Suite sidebar so the layout works on all screens.
* Improved News Widget - we updated some layout issues we've discovered with the News Widget
* Code Refactoring - We spent a little bit of time and did some minor code refactoring to improve performance
* Removed CQS code - The CQS feature was removed from Curation Suite a few releases ago. Now we officially removed all the code.
* Removed Image Credit Tab - We mentioned this a few releases ago and this release we finally did remove the Image Credit Tab. Those actions are now in the editor buttons with the Curation Suite icon Shortcuts. Here you'll also find the image shortcuts.

= 2.8.8 =
* Hotfix for "missing file errors". In the latest release there was a minor error on a files check that popped up, this release resolves that error.

= 2.8.7 =
* New Feature: News Widget. Now you can display your curated news from the Listening Engine with the new News Widget. See it in action:  https://curationsuite.com/product-news/news-widget-for-wordpress/
* News Widget Detail: You'll find the widget in the Widgets section. Drag the widget to where you want it to be placed and update the settings. You also must curate content to your news feed/page: https://curationsuite.com/product-news/creating-curated-news-page-wordpress/
* News Widget Options: With this release you have various options for your news widget. Title of Widget, number of news items to display, to display an image (with alignment), show or not show the snippet and Platform (for people with multiple platforms).
* Reworked shared assets for both the public and admin sections of Curation Suite

= 2.8.6 =
* Pinterest Search - Now you can curate content from Pinterest right within the Curation Suite sidebar. See it in action here: https://curationsuite.com/product-news/curate-content-from-pinterest/. We have plans to add to the Pinterest feature in the near future so this release is just the start.
* Note: To use the Pinterest search you must have PHP version 5.5.9+ installed on your site. This shouldn't be a problem as most hosts already support and recommend running versions higher than PHP 5.5.9.
* Curation Suite Admin Screen - Added Pinterest credentials for Pinterest search
* Minor Enhancements to Layout of Sidebar
* Note: With the recent addition of the Curation Suite shortcuts (https://curationsuite.com/product-news/post-shortcuts-images-quickly-change-sizes-layout-add-image-credits/) we will be removing the Image Credit tab in the next release. There is no need to have this functionality it 2 places. This also opens up the Curation Suite sidebar for other new features we are planning on adding.

= 2.8.5 =
* Narrative Block - Brand new power feature for the Listening Engine called Narrative Block - See more info: https://curationsuite.com/product-news/narrative-block-advanced-content-discovery/ Now you have the power to easily block a trending story or narrative that you're simply not interested in. This gives you the power to refine your content discovery and only see the stories or narratives that you really wan to see or discover.
* Curation Suite Admin Screen Fixes - There were some notices that popped up that we resolved in this release
* Foundation Updates - Some foundation updates for future features and new releases coming in the next 30 days

= 2.8.4 =
* Hotfix for Instagram search issues. We fixed an issue that was causing the Instagram search to not work in a rare case of a certain type of server setup. Took us awhile to track down this issue but we finally got it.

= 2.8.3 =
* New Feature! Post Image/Content Shortcuts - Quickly Change Sizes, Layout, Add Image Credits, and More... See it all in action: https://curationsuite.com/product-news/post-shortcuts-images-quickly-change-sizes-layout-add-image-credits/
* ImgUr fix. On some sites (less than 2%) the ImgUR search didn't work. We've found the issue and resolved it in this release
* Power Search Fix - when saving a search with double quotes it caused bad search results. This is fixed in this release.
* New Feature Update - Quick Post Link Text - Added the ability to change the attribution link text on the Quick Post feature of the Listening Engine. You'll find the option by clicking on "Settings" in the top right of the reading page of the Listening Engine.
This new feature allows you change your attribution text to something like "Read More..." or "See the full story..." or really anything you want. Before this defaulted to the headline and was sort of redundant for quick curations.
If you don't have anything in the Quick Post Link Text the attribution link text will default to the headline of your curation.

= 2.8.2 =
* Hotfix for notice display issue in ALL Posts page. At the top of the page a notice was showing up saying "gathering image credit info". That's been fixed in this hotfix.

= 2.8.1 =
* Trending Feeds! Now you can create and use your own trending RSS feeds. See it in action here: https://curationsuite.com/product-news/trending-rss-feeds/
* Use Trending Feeds to automate social sharing or with services like Snip.ly, Sharing tools, IFTTT or Zapier. See link above for tutorials.
* News Page. We updated the News page feature to fix some minor layout issues and also some encoding of returned content. For instance, certain HTML elements weren't displaying correctly. All that is fixed now.
* CurationBot Update - Fixed error message on active Listening Engine searches. On some searches if less than 50 results came back then a message appeared that stated you could get more results if you had an active Listening Engine. If you have an active Listening Engine you should see this message and get full CurationBot results.
* WordPress 4.8 Updates
* Foundation for the next update.

= 2.8.0 =
* CurationBot - Get 50+ real time content results with the new content discovery wizard CurationBot, see it in action: https://curationsuite.com/product-news/meet-curationbot/
* CurationBot Notes: we've taken our 5+ years of experience to build in another awesome content discovery tool for all Curation Suite owners. If you have an active Listening Engine you'll get up to 50 real time results with the new CurationBot search (for Curation Suite plugin owners your results are limited due to the ongoing cost to discover content and provide the CurationBot service).
* Layout Fixes - we fixed a few minor layout issues that popped up in the latest release
* Code Updates - on some sites the Curation Suite admin screen was showing PHP notices. This release ensures the code is compliant with future versions of PHP7.0+.
* Foundation Updates - in this release like many of our others we lay the foundation for other updates soon to be released

= 2.7.0 =
* New Feature - Power Search. Discover even more content with the new free power search in the Listening Engine - Check out full overview here:  https://curationsuite.com/product-news/power-content-search/
* Added Search Tab for Power Search to Listening Engine - You can access this in the reading page and within the Curation Suite sidebar
* Added Listening Engine as search option in the On Demand Search in the Curation Suite Sidebar. Works like any other search but added this shortcut for LE users.
* Layout fixes - we enhanced some of the Curation Suite layout of core features, you probably won't notice but made it a little more streamlined
* Code Enhancements - we refactored some code to reduce the size of the Curation Suite codebase. This has never been an issue but it's always good to be as efficient and lean as possible. Plus helps with adding new features quickly.

= 2.6.9 =
* New Feature - Custom Curated RSS Feeds. Easily create a email newsletter, automate social sharing with just a click with your new custom curated RSS feeds. See all help and tutorial here: https://curationsuite.com/help/news-curated-rss-feed-help
* WordPress 4.7.5 Updates
* Foundation for a slew new updates soon to be released

= 2.6.8 =
* WP 4.7.4 Updates
* Bing On Demand Fix - This fixes the on demand search issues for Bing.
* Slideshare Fix - For some users the SlideShare search wasn't working because of SSL issues, this release resolves those issues and the search now works as it should.

= 2.6.7 =
* Hotfix for post editor layout issue. On some sites when scrolling on the post screen the headline or editor buttons layout would not work correctly. This hotfix fixes those issues.

= 2.6.6 =
* Instagram! Now you can easily search Instagram to curate. With this release you can search and easily embed content from Instagram by searching by Instagram tags. You'll find the Instagram search in the Search tab. Watch this video for more:  https://curationsuite.com/product-news/curate-from-instagram/
* Critical Update - This update continues to fix reliability and memory saving (memory and CPU load isn't an issue we've faced but it's always good to improve where we can)
* If you don't update this version there's a risk that in the next release your plugins will not work properly

= 2.6.5 =
* Critical Update - This update fixes many things that improve speed and reliability.
* Menu layout fix. In some installs there was a conflict in the post screen with menus and the sidebar, this releases fixes that.
* Foundation for future updates

= 2.6.4 =
* New Feature - Curate images and memes from ImgUr.com - See video: https://www.youtube.com/watch?v=t7bxsccJ8Jg&t=61s
* New APP - This update also requires you to update the Licensing plugin. This will add ImgUr to the connected apps screeen
* Foundation updates for future features
* Note: In the next release we'll be taking out the CQs - Curation Quality Score.

= 2.6.3 =
* Curate From Facebook - Easily curate videos and posts from public Facebook pages. We've revamped our parsing so you can easily embed Facebook videos and posts. See it in action here: https://curationsuite.com/product-news/how-to-curate-from-facebook/
* New Feature: CurateThis default behavior. Now you have the option to choose how the CurateThis shortcut works. The options are: Show Curate Action Screen, Create a New Post, or Create a New Page. For most setups you'll either choose the first 2 options. If you are always creating a new post then you'll want to set the default to "Create New Post". See Tutorial: https://www.youtube.com/watch?v=0iM5DJSAMdo
* New Feature: Link images. Now you have the option to create a link for images that you curate. If you have this turned on (or click the option) it will link any image you add to post box when using one of the Add to Post buttons. See tutorial: https://www.youtube.com/watch?v=U33nj3WxCxc
* UI Update - Now next to the image you are curating there will be a Link Image checkbox. Click it if you want the image selected to have an attribution link
* UI Update - Now on quick add there is also an option to Link Images. Check if if you want to have an attribution link when using the quick curation
* Curation Suite Admin Screen - Added option to default CurateThis behavior and set Link Images to be the default setting.
* Added tutorial links in admin screen. Will add more as we update tutorials and videos. These tutorial links will open up in a popup right within your dashboard.
* Fix: WE fixed the issue some users were having with errors showing up on the curate action screen when using the CurateThis shortcut
* UI Enhancements - We also made a few minor UI enhancements

= 2.6.2 =
* New! Now you can find and curate more videos with the new DailyMotion search. You'll find DailyMotion in the Search tab in the Curation Suite Sidebar.
* Headline Feature - now when you curate if the headline of your post is empty the headline will be automatically added. This works in the CS Visual Editor, the Quick Adds, and even when curating videos.
* Added content popup for videos in on demand search (currently beta).
* Fix - For some users the Google News location search wasn't working-- this update should fix those issues.
* WordPress 4.7 Compatibility Updates
* Foundation updates for future features

= 2.6.1 =
* Pocket connection fix. If you were getting header errors on connecting your Pocket account this fixes those issues.

= 2.6.0 =
* New Pocket Integration. Now you can easily load your links and curate from the bookmarking service Pocket (GetPocket.com). You'll find the pocket search in the drop down in the Search tab. Watch this video here for an overview: https://curationsuite.com/product-news/announcing-new-pocket-integration/
* UI improvements - We moved around a few things to make it a bit ore intuitive. Most of the UI changes are minor and show up in the Curate tab.
* Removed admin file backups
* Changed sidebar tab name from Sharing Actions to Sharing

= 2.5.3 =
* Foundation for future updates and integrations to be added before the end of the year. Cool stuff!

= 2.5.2 =
* Hotfix - for some sites the QuickAdd wasn't working right in the last version. That's been resolved now. If it still doesn't work as it should (quickly open window and save link) then go to the Curation Suite Admin screen and click on  Manual Curation Files Copy.

= 2.5.1 =
* WordPress 4.6.1 Compatibility Updates
* Hotfix for Google News default language

= 2.5.0 =
* WordPress 4.6 Compatibility Updates
* New Addition! Video for the Listening Engine - You can now discover videos within your Listening Engine. For an overview watch this video here:
* Added Content Type Options - In the platform tab of the Listening Engine you'll see 2 options. One for Articles and one for Videos. You can sort each one of these content types separately. You can also choose to display or not display each content type.
* New Feature - When a Video content type is selected you can choose to load the video player (keep in mind this might be slow for less powerful computers)
* Content Type Visuals - In the Listening Engine each content type will be displayed with it's own color. This allows you to quickly see at a glance what content type you're looking at. Blue = article and red = video.
* Curate to Post Rework - With the addition of videos we've added some new features on how the curate to post works. Now you can choose how videos are handled when curating from the reading page. You'll see the new options in the "Settings" link.
* Feature Update - added options for adding videos in the new post screen when using the Listening Engine (you'll now see the same easy add shortcuts).
* Feature Update - Increased the amount of results that come back from the Google News on demand search.
* Update - We increased the amount of content coming back from the Listening Engine. Now it will return 32 stories by default. When doing a search with both articles and videos you'll be returned a mixed content result. Usually a 2-1 ratio of articles to videos.

= 2.4.3 =
* Hotfix for minor error in Listening Engine reading page.
* Also added some foundation for future updates to come

= 2.4.2 =
* Shared assets namespace update
* Minor fixes for searching logic
* Foundation for new updates to drop for Curation Suite, Listening Engine, and Super Social Engagement

= 2.4.1 =
* New Feature - Quick Post. This updates the old Curate to Post feature with new options.
* Quick Post - Now in the Reading Page of the Listening Engine you can quickly create posts that are either set as Drafts, Published, or Pending Review. You'll find these new options in the Settings Link in the Reading Page. Default post status is Draft. Note if you choose Publish this means your post will be published right away.
* Option Added - Added an option to remove the blockqoute from the Quick Post feature
* Feed Websites - Now all your feeds that you've added will be under the Websites tab. If you don't see all your feed sites click on "Refresh Feed Sites.
* Feature Addition - Now you can sort and get content by only your Feed websites or your Keywords. You'll find this option in the 2nd dropdown "All Sources". Selecting All Sources will show content from Feeds and Keywords. Selecting "Just Feeds" will show content found just from your feed sites. Selecting "Just Keywords" will show content found from your keywords. Hopefully your reading this and saying... Thanks Captain Obvious.
* Enhancement - Now when you click on a keyword or a website it will be highlighted with yellow making it easier to see which option you clicked on
* Enhancement - Added a link to refresh keywords. You'll want to use this if your keywords are ever out of sync with your Listening Engine
* Option Added - Now in the Topic Sites tab you can view only your feed websites. This is the first option titled "Just Your Feeds". All other options remain the same.
* Option Added - Added an option to hide the shortcuts sidebar in the Reading Page. You'll find this option in Settings Link at the top. The shocrcuts sidebar is the sidebar that has "go to top" and "Ignore All".
* Language - We also updated the language support for searches. Now with Google News (if it is possible) you are able to select the region and default language for searches.
* WordPress 4.5.2 comparability updates
* Menu placement fix
* Fix - For some sites if the News Feature was active it was properly showing the shortcode, that is fixed now.

= 2.4.0 =

* New Feature - Reddit added! Now in the On Demand Search you can find content to curate from Reddit. See video https://www.youtube.com/watch?v=Rx0-H-A-8VM . Search by Hot, New, Top, Comments, or Relevance. You can also choose a timeframe (day thru year) and to show or ignore threds. (more to come for this on future releases). Also check the Curation Suite admin screen to set the Reddit default search parameters for your site.
* Enhancement - We streamlined much of the sidebar with some minor tweaks
* Enhancement - Updated the Platform Access screen for the Listening Engine
* Enhancement - Added option to turn of Daily Email Digest for the Listening Engine - You'll find this in the Platform Setup Overview tab.
* Enhancement - Added keyword type to keywords on a Topic within the Listening Engine. Right now it only displays "Article". This will change in the near future.
* Layout - We streamlined much of the tutorial links. Now we group tutorial links together. Clicking on these tutorial links will open up Tutorial videos.
* Fix - Some default values were not always being set correctly in the Curation Suite sidebar. This version fixes any of those issues or conflicts.
* Enhancements - Other minor improvements through out both the Listening Engine and Curation Suite Plugin.

= 2.3.2 =
* Hotfix - In some browsers on MAC the scrollbar is not present in the Curation Suite sidebar. This releases fixes this issue.

= 2.3.1 =
* Enhancement - Now you can customize the size of the Curation Suite sidebar. You'll find the options to customize the size in the Curation Suite Admin screen.
* Enhancement - Increased usability in the visual editor. Now when curating content in the visual editor the content you can cite now scrolls and will fill the sidebar. This change will make it easier to cite content, images, videos, and social media embeds.
* Enhancement - Direct content citing. When using the curation visual editor and you highlight over a piece of text you'll see two new icons. This allows you to cite that piece of text right to your post box. The first icon is a to place raw text in your post box, the second icon (looks like a quote) will add the text wrapped in blockquote.
* Enhancement - Direct link citing. In the visual editor you'll now see a new blue arrow next to the Link Text box. Clicking this will add a link attribution to your post box.
* Enhancement - Minor tweaks to the Reading page of the Listening Engine.
* Fix - In the previous version if you used the Curate to Draft feature it didn't countdown the content amount total. This is fixed in this version.

= 2.3.0 =
* Enhancement - Listening Engine connection error handling update.

= 2.2.9 =
* Hotfix for HTTPS urls - In a previous version when using the CurateThis shortcut some HTTPS URLs were added to the post box with an error due to a security check. Using a plugin like Broken Link Checker will help track down if you've been effected by this bug.
* Hotfix - With some sites the default value of Blockquote settings was not set properly when curating. That is fixed in this version.
* Hotfix for Twitter search - in some installs the credentials were not properly passed to Twitter. Minor error but was causing connection issues.
* Fix - Image credit fix for when an image is added using the "Add to Post" link on an individual image. Now the Image Credit tab will give proper credit for these images.
* Admin Screen - Change to Twitter credentials entry boxes-- they are no longer password boxes and standard text boxes

= 2.2.8 =
* New Feature - Now you can sort the Discover Sites by: Moz Score, Average Total Shares, Last Content, and Alphabetical - https://www.youtube.com/watch?v=nPfCdpZl70g
* New Shortcut - Show Content. In the Listening Engine reading page you'll see a new shortcut at the top right that will take you to the default content of your Listening Engine with one click.
* Hotfix - This fix solves the long keyword problem in the On Demand search. If you had a very long keyword this hide the delete action for keywords-- this fixes that.
* HotFix - Image upload default setting. The image upload setting was not taking the correct default properly on the visual editor. This is fixed.

= 2.2.7 =
* Hotfix - New topics were not being added to the topics dropdowns when they were added. It required a page load for these to show up. Now this is fixed and requires no page load.
* Tutorials - Now in the Reading Page of the Listening Engine there is a link at the top right with Tutorial videos for using the Listening Engine.
* Tutorials - We added 2 new tutorial videos for setting up a brand new Listening Engine. These only show up if you have a newly created Listening Engine.

= 2.2.6 =
* New Feature - Listening Engine Topic Management - Now you can change, edit, rename and delete a topic. Even create a new blank topic and completely customize your keywords and websites/feeds.
* New Feature - Master Topic - Not sure where to start? Search our over 1,000+ master topics with pre-set keywords and websites. Then fully customize from there.
* New Feature - Platform Name - now you can easily rename you Listening Engine platforms. You'll find this option int he Platform Control overview tab
* UI Improvement - made some improvements to the UI when using the Listening Engine in the Curation Suite sidebar
* Fix - For certain sites there was an error with default values being set for Curation Suite. We tracked down the issue and resolved it in this release.

= 2.2.5 =
* Hotfix for minor error when switching between Listening Engines

= 2.2.4 =
* New Feature - Listening Engine Keyword Management - Now you can edit, update, and delete your Keywords in your Listening Engine. See tutorial video: https://youtu.be/iBiBdvcqGrQ
* Fix - The adding new feed tool had an error that popped up in some sites that caused it not to work properly. This is resolved in this release.
* UI Addition - Now at the top of your Listening Engine in the reading page you'll see a link that says "Platform Setup". Clicking this link will take you to the selected platforms setup screen.
* Minor UI Change - Added Platform Name and total feeds and keywords to the top of the Platform Setup screen.
* Minor UI Change - Fixed drop downs displaying correctly on change that some users reported.
* Feature Fix - improved the Keywords syncing tab. Sometimes these keywords were out of sync with users LE's. Resolved the issue in this release.
* New Option - Now in the Listening Engine settings (click "Settings") you can also refresh keywords if it's out of sync. Click on "Refresh Keywords from Listening Engine".
* Future Update Foundation - Added New Topic tab for next release when you'll be able to add/edit topics and add from our over 900+ Master Topics
* UI Improvements - As usual we included minor UI improvements and a few tutorial videos. More tutorial videos to come shortly.

= 2.2.3 =
* New Feature - Listening Engine Feed Management - Now you can manage feeds in your Listening Engine. In your Listening Engine select Platform Control and you'll see a new tab "New Feed". See this tutorial: https://www.youtube.com/watch?v=Nv0CWVtVZI8
* New Feature - Topic Sites - So this feature is super overkill but it is pretty powerful. Now you can select a topic and a get a list (sometimes in the 1000s) of all the sites/domains that have been discovered by the content in that Topic. Then you can save that site to your Websites Tab. See tutorial on how this can be useful: https://youtu.be/m-aFxy5GPsc
* Helpful Feature - We added blue links that are tutorial videos within Curation Suite and the Listening Engine. Clicking on the blue tutorial links will popup a video covering that feature or the features on the screen your on. All new tutorials we are focusing on making shorter and concise. Some are still longer than we would like but we will eventually get them updated.
* Minor UI Changes - We updated a few elements to make the UI a bit clearer. Also improved some of the loading indicators to stand out more so you know when items or content is loading or it's at least more visible.
* Minor Change - Platform & Topics tab now is title Platform
* Minor Change - New tab is added to the Platform Control screen title "Help/Tutorials" - here you'll find tutorials on setting up and managing your Listening Engine

= 2.2.2 =
* Hotfix - in some hosts when curating a include file couldn't be found. This issue is resolved in this release. This didn't get picked up in our test enviroments for some reason-- sorry for all who had this issue.

= 2.2.1 =
* Hotfix for default values for main Curate tab (in some WordPress installs default values weren't being used, we apologize to all users who experienced this behavior).
* Negative Keywords - New Feature Addition for Listening Engine. Before you use this feature please watch this tutorial video: https://www.youtube.com/watch?v=RBKRwBI0hT8&index=13
* Foundation changes for up and coming Listening Engine changes (awesomeness on the way)
* Speed improvements on common loaded elements in Post screen

= 2.2.0 =
* Fix for Google News on demand searches
* Due to Google discontinuing Google Blogs API this search option is no longer available and removed in this version.
* Giphy On Demand Search Added - Now you can easily search for Gifs to add to your content. Giphy added as search option in the Search Tab (on demand).
* Listening Engine Updates:
* Keywords - Now you can dive into your Listening Engine by keywords, you'll see a new keyword tab in your Listening Engine now. Clicking on the keyword will show you the content from just that keyword. From there you can sort on the same sort options your used to. You can also click on the magnify icon next to a keyword on an individual piece of content.
* Website Following - Now you can follow your favorite websites. Clicking on the magnifying glass next to a domain/website will show you the content from just that website. From there you can save that website and sort on the same sort options your used to.
* Website Following Notes: Keep in mind that we don't pull all content from all websites, we are working on potentially adding this to the Listening Engine platform but at this time the content that comes back on a website is what we have within our system. It is not a real time pull of that website.
* Keyword Searches: For advanced Listening Engine customers you can now perform keyword searches on all of our content in the Listening Engine platform. This option is only available to customers with Advanced Listening Engines.
* As usual we have a host of stability fixes and minor tweaks to make things more stable and clear.

= 2.1.1 =
* Instagram share discovery logic was adding an extra http to the instagram update. This is fixed in this version
* Tweak of image discovery logic to improve what images are shown and when (basically if an image can't be loaded it won't be displayed).
* Updated script calling to deal with possible caching issues some users are facing with new version 2.1.0

= 2.1.0 =
* Official Version 2.0 release (all 2.0.0, 2.0.1,2.0.2, .2.0.3) listed in this release
* Major release and major update
* New sidebar UI - now all Curaiton Suite actions are found in a new sidebar UI. From searching for content, the Listening Engine, and curating. It's all in a handy side bar.
* New functionality: content will now be added to your post box where you have your cursor -- no longer added to the end of your post.
* Quick Add to Post on content on demand searches added
* Add to Post and Add Featured added to all images when searching, etc.
* A new H will display next to headlines when searching. Clicking on the H will add that headline to the Title of your post.
* Twitter added as a search type. Now you can easily search Twitter to add Tweets to your content. Note: this feature requires you to create a Twitter APP, that is simple and easy to do.
* Social Actions - after you add a few tweets to you content you can then go to the social actions to mention people you've added
* Social Actions - now you can choose what elements in your content to ignore to create social actions (bold|italic|headlines|links). This gives you the power to create create just the diverse updates that you want to see when creating sharing actions.
* Social Actions - Now you can easily choose where you want the added text to show up, either before the link or after the link. Please note: this only works for some social networks like Twitter.
* Listening Engine: New option for Curate to Draft feture. You can now choose to have the curate to draft button either work as a mouse over or a mouse click.
* Yahoo! search removed - unfortunately due to changes in Yahoo! policies and some features we had to remove Yahoo! search.
* Various other stability and minor fixes (improved loading of scripts, shared assets now added to YBI plugin, etc).
* Search in your native language - Now all content on demand searches have language support
* Set language defaults in Curation Suite Admin screen - You can default Google searches, Bing search, YouTube and Twitter (defaults are set to US - English)
* Improved image discover logic (now if images can't be rendered they won't be shown)
* Vine added as a social discovery when curating content. Now when you curate (pull) content if there are Vine updates you can curate those and embed
* Added quick shortcut for headline in on demand searches and Listening Engine - You'll see a H icon next to each headline, clicking that will place that headline in your Posts Title box.
* Added Go To Top shortcut. Now when you open up Curation Suite in the sidebar you'll see a new arrow above the close tab. Clicking this arrow will bring you to the top of any screen you're in within Curation Suite
* Quick Add added to all social media content when curating. Now you'll see a arrow pointing left on each social media update, clicking on that will add that social media update to your post where your cursor is located.
* Refactor and quick add added to videos. Now in the visual curation editor when a video is present you can also click on the left arrow to add a video right to your post.
* Video thumbnails can be now easily added to your post or set as a featured image
* Re-factored the curation pull logic drastically. This will improve the content it finds and also sets the foundation for expansion.
* Note: All pulls are now Advanced Pulls so the red Advanced Repull button will most likely be going away in the next release.
* Various layout tweaks and polishing in this release as well
* New content pull logic when curating. We've changed the first pull logic to be more like the advanced repull logic.
* Fix for some plugin conflicts with new UI change
* Fix for certain add to post button actions (they weren't properly putting content where your cursor was)
* Platform drop down fix
* Layout tweaking from feedback from Beta users
* Script loading fix (one of the scripts for CS was included wrong for the WordPress admin, we fixed that)

= 2.0.3 =
* Layout tweaking from feedback from Beta users
* Script loading fix (one of the scripts for CS was included wrong for the WordPress admin, we fixed that)

= 2.0.2 =
* Vine added as a social discovery when curating content. Now when you curate (pull) content if there are Vine updates you can curate those and embed
* Added quick shortcut for headline in on demand searches and Listening Engine - You'll see a H icon next to each headline, clicking that will place that headline in your Posts Title box.
* Added Go To Top shortcut. Now when you open up Curation Suite in the sidebar you'll see a new arrow above the close tab. Clicking this arrow will bring you to the top of any screen you're in within Curation Suite
* Quick Add added to all social media content when curating. Now you'll see a arrow pointing left on each social media update, clicking on that will add that social media update to your post where your cursor is located.
* Refactor and quick add added to videos. Now in the visual curation editor when a video is present you can also click on the left arrow to add a video right to your post.
* Video thumbnails can be now easily added to your post or set as a featured image
* Re-factored the curation pull logic drastically. This will improve the content it finds and also sets the foundation for expansion.
* Note: All pulls are now Advanced Pulls so the red Advanced Repull button will most likely be going away in the next release.
* Various layout tweaks and polishing in this release as well

= 2.0.1 =
* Search in your native language - Now all content on demand searches have language support
* Set language defaults in Curation Suite Admin screen - You can default Google searches, Bing search, YouTube and Twitter (defaults are set to US - English)
* Improved image discover logic (now if images can't be rendered they won't be shown)
* New content pull logic when curating. We've changed the first pull logic to be more like the advanced repull logic.
* Fix for some plugin conflicts with new UI change
* Fix for certain add to post button actions (they weren't properly putting content where your cursor was)

= 2.0.0 =
* Major release and major update
* New sidebar UI - now all Curaiton Suite actions are found in a new sidebar UI. From searching for content, the Listening Engine, and curating. It's all in a handy side bar.
* New functionality: content will now be added to your post box where you have your cursor -- no longer added to the end of your post.
* Quick Add to Post on content on demand searches added
* Add to Post and Add Featured added to all images when searching, etc.
* A new H will display next to headlines when searching. Clicking on the H will add that headline to the Title of your post.
* Twitter added as a search type. Now you can easily search Twitter to add Tweets to your content. Note: this feature requires you to create a Twitter APP, that is simple and easy to do.
* Social Actions - after you add a few tweets to you content you can then go to the social actions to mention people you've added
* Listening Engine: New option for Curate to Draft feture. You can now choose to have the curate to draft button either work as a mouse over or a mouse click.
* Yahoo! search removed - unfortunately due to changes in Yahoo! policies and some features we had to remove Yahoo! search.
* Various other stability and minor fixes (improved loading of scripts, shared assets now added to YBI plugin, etc).

= 1.49 =
* hotfix for Listening Engine that causes timeframe to not work under some instances
* hotfix for search in Curation Suite on demand search

= 1.48 =
* Tweaks for the click to post mouse over logic
* Advanced Listening Engine tweaks for timeframe
* various minor fixes for usability

= 1.47 =
* Important Hotfix - YouTube on demand searches have been updated to latest API and now works just as it did before.
* Beta New Feature. Featured Images - now you can easily select an image to be a featured image. When curating you'll see a new link over an image that says "Set Featured". Clicking on the link will set that image as a featured image.
* Beta New Feature for Listening Engine Click to Draft. Featured images also added to the Click to Draft feature in the Listening Engine. In order for that to happen you have to select the "Click to Draft Feature Image" in the settings on the reading page.
* Image logic - we improved the image finding logic when curating
* Advanced Repull Logic - we also improved on the advanced repull logic to be more intelligent
* Minor Listening Engine funcationality change - when ignoring or saving a piece of content it will disappear right away instead of a second or two. Originally this feature waited for the Listening Engine API to communicate back to your site (which it still does) but now the content is removed immediately.
* Timeframe drop down is added back for the Listening Engine

= 1.46 =
* New foundation for some new features soon to come
* update to reading page for Listening Engine changes
* Timeframe dropdown is removed from reading page (no longer needed as content that comes back will always be the latest)
* Additional tweaks and fixes to minor annoyances

= 1.45 =
* New feature! Now you can add multiple images to your post box.
* Listening Engine new features, check the Listening Engine change log
* Minor layout fix for shortcuts on Admin screen
* Minor updates based on WordPress 4.2 changes

= 1.44 =
* Fixed minor error in parsing content that was an issue for some users, sorry to those users that experienced this issue.
* Foundational items added for future updates to come
* Quick source logic fix for Listening Engine
* Shortcut sidebar added for Listening Engine (you'll see this on the right where you can use the shortcut 'to top' to go to the top of the page and 'ignore all' to ignore all content.)
* Changed domain blocking for Listening Engine
* Updated source name for Listening Engine content (now it just lists the domain name, this allows for more clarity on where content comes from and blocking).
* Click to draft functionality. This is a pretty awesome feature for the Listening Engine. You'll notice a new button at the top of a content item. When you mouse over the button you'll see you're categories, click on the category and
* Block options - now you have the option to permanently block a source, block for 2 days, or block for 7 days.

= 1.43 =
* Fixed conflict with plugin that had a similar method
* Saved content fix for Listening Engine
* Source icon now lays out correctly on long titles

= 1.42 =
* pagination fixes for the Platform Control in the Listening Engine

= 1.41 =
Curation Suite Additions:
* Improved logic in pulling
* fixes for Font Awesome

Listening Engine Additions:
* 12 Hour Time Frame – Added 12 hour sort time frame for searching
* OkToPost Sharing – Added OKToPost on Reading Page for sharing option
* Auto Search – Added option to search automatically (and setting) when a sorting value is changed. With this setting turned on when you change a topic, time frame, or sort drop down the search will automatically load. You can easily turn this on or off in the settings link and clicking the Load Search on Sort Change.
* Share Tracking – Now when you share something from the reading page using one of the share links you can choose for this content to be hidden from future results. You can always go back and remove this but this means that as you use the Listening Engine to share and engage you can ensure you’re always getting new content to discover and curate.
* Hide News Options – Now you can hid the news quick curation options in your reading page. You’ll find this in the settings link titled: Show Platform Display Options.

= 1.31 =
* You can now select the user level who has access to Curaton Suite - see admin screen
* You can now have Curation Suite functionality on pages, posts and custom post types. Please note that this is only for core functionality like curating and searching content. The CurateThis and AddLink shortcuts still go to the Curate Action screen and only work for standard Posts.
* Custom post types for shortcuts might be added at a later date.

= 1.30 =
* Listening platform additions for Quick Start Clients

= 1.20 =
* Major update for v1.20
* Changed add to post action buttons to simplfy and give more options. Now you can choose where to put your link, before, after, or headline.
* Merged all other meta boxes into one called "Curation Suite Conent & Actions" - this is where you will find the link buckets, sharing actions and the image credit actions (in addition to a new find content, see below)
* Added On demand search for finding content. You'll find this in the Content & Actions box under Find Content. You can now search using keywords on Google News/Blogs, Yahoo News, Bing News, YouTube, and Slideshare
* Added direct share in on demand searches so you can share directly content you're searching for. This can be toggled on and on per search.
* Added screenshot feature - now the first image you will see is a screenshot of the content you are curating. Use the "reload screenshot" shortcut if it doesn't load right away.
* Added Raw Add to Post button that you'll see just above the curated content staging area. This adds just what you have in the curated content to the post box.
* Added new default actions in the Curation Suite Options: Link Attribution default, blockquote default, headline default
* CurateThis now opens up in a new tab instead of a popup. Addlink Shortcut still opens up in a small popup.
* Many under the hood fixes that help with stability, finding content and such.
* Added easy copy for CoSchedule sharing. When selected additional share text will be displayed ready to be copied into the CoSchedule sharing app.

= 1.11 =
* Licensing issue fixes. Now all licensing issues that have been present are fixed!

= 1.1 =
* Image uploads feature added
* The social media module is now wrapped in the Post Actions module
* added the image credit module
* added a few more 3rd party sites for video curation (EX, TED).
* stability improvements

= 0.6 =
* Tons of changes

= 0.5 =
* Alpha version
* Includes the ability to load a link and auto summarizes
* Ability to select an thumbnail image for each story
* shortcuts for link text

== Upgrade Notice ==

= 0.5 =
Awesome new features!