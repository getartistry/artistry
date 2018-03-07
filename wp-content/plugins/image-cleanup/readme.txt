=== Image Cleanup ===
Contributors: Opicron
Donate link: http://www.opicron.eu/wp/
Author: Robbert Langezaal
Author URI: http://www.opicron.eu/wp/
Plugin Name: Image Cleanup
Plugin URI: http://www.opicron.eu/wp/
Tags: image, clean, cleanup
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 1.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Image Cleanup will index unreferenced images. These can be leftovers from cropping and scaling images. Stop them cluttering your content folder!

== Description ==

Did you ever scale or crop uploaded images in wordpress? Did you ever try various image generating plugins? Did you ever change or add various media image sizes? If yes, chances are that your content folder is full of unused and unreferenced images. These image leftovers will remain cluttering your content folder until the end of days. 

Image Cleanup will index these (and many more) unreferenced images. Unreferenced images are not indexed in the wordpress database and also not used by posts, scripts or backup restore meta data. Please note that images which have valid database metadata but are not used in posts are not indexed at this time. This is beyond the scope of this plugin.

Image Cleanup will also find images without meta data and images with incorrect meta data.

* Save space taken up by unreferenced/backup images!
* Fix incorrect metadata (sizes)
* Bulk repair/delete functionality
* Pagination for extremely large result sets
* Logs tabs easily visible for reviewing!
* Detailed information in log tabs!
* Full restore functionality!
* Especially useful for developers!
* Written as class to avoid any conflicts!
* Highly configurable to avoid memory and/or timeouts on some setups

_On my one month old website with 50 files (and 250 attachment size variants) this plugin found 95 unreferenced images which I could remove without any conflicts._

_There has been a reported case where this plugin indexed and removed over 23.000 images on a website. See forum/review for details_

= Image Cleanup workflow details =

- Retrieves all attachment images from the database (including size variants);
- Finds all images in the wp-content folder;
- Subtracts the attachment (and size variants) from physical found images;

The above will result in an list of invalid images which are not referenced by the database

- Posts will be searched for images and found images will be subtracted from the invalid files (if any);
- Scripts in wp-content will be searched for images and they will be subtracted from the invalid files (if any);
- Backup images (for restoring images after scale/crop) will be subtracted from the invalid images;
- Found unreferenced files will be checked against the meta data to see if the database meta entry still exist;

This results in various image lists in which the images can be reviewed, repaired or removed.

= After indexing = 

After indexing you are able to (temporary) move the images to a backup folder to check the site without the images in the wp-content folder. You could also check the logs in the ../wp-content/uploads/image-cleanup/ID/logs folder for full information about the index.

If any problems are found you can revert the move of images by restoring them to their original locations.

When no problems arrise after moving the images to another location it is possible to permanently remove the images.

= Future planned additions =

* Index unused valid images 
* Index missing image sizes (and option to generate missing images)
* Index missing images which are used in posts

== Installation ==

1. Upload Image Cleanup to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the administration page: Tools > Image Cleanup
4. Initiate index images
5. Check log tabs for detailed information
6. Choose to move files to temp location
7. Restore or delete files from temp location
8. Clutter free content folder! ;)
9. After step 5. you can also repair/delete specific meta data (re-indexing required after changes)

== Frequently Asked Questions ==

= Will Image Cleanup delete, move or rename images? =

When indexing the images Image Cleanup will _not_ delete, move or rename any image. 

Images will only be moved when you choose to. Only after the move of images an permanently delete option will be available.

= Can I revert back to previous state? =

Yes you can! Full restore functionality is present.

= What if the index is not finishing? =

It might take a while for the plugin to index all the files. Please give it enough time to run! 

If the index is returning an error this will be clearly visible. An bold red text will appear with a link to the debug log. Please send me this and the other logs.

If your image base is huge (50.000 or more images) you might want to exclude some parts of the image paths and work your way from year to year.

For example: exclude all folders which have the text '/2010/', '/2011/' etc. Do not remove the '/' slashes because without it will also remove filesnames containing '2010'. Scan only one year by excluding all the others.

Then, after the index is done and you removed the images you are able to index other years of the content folder.

= Did you get any help? =

Yes, I would like to say thank you to: Kelly Rosal and Tali Walt for letting me test and debug the plugin on their websites. Their websites were very though to run a full index through and because of that I had to optimize the plugin a lot!

== Screenshots ==

1. Image Cleanup Index Images (screenshot-01.png)
2. Overview Admin Table (screenshot-02.png)
3. File Admin Table (screenshot-03.png)

== Changelog ==

= 1.9.2 =
- Fixed error minimum version on PHP 7.0
- Fixed Strict Standards warnings
- Compatibility with 4.7
- Fixed many notices of undefined variable
- Validate and sanitize user data

= 1.9.1 =
- Fixed error where meta data could not be removed, thanks Squill1959

= 1.9.0 =
- WARNING: All current index information is removed for this upgrade!
- Changed all file reads into memory efficient loads
- Better memory management e.g. unsetting unused array vars
- Saves category counts into variable for faster browsing
- Skip image paths now also skip the attachment metadata with same paths
- Fixed incorrect meta size after re-index when removing backup metadata
- Improved and fixed some layout glitches
- Improved retaining index position of moved files after re-index
- Fixed some warnings when logs are read but not created
- Added reset plugin option (devs only)

= 1.8.2 =
- Fixed error on invalid metadata

= 1.8.1 =
- Added check if meta image path was set

= 1.8 =
- Fixed potential errors where getimagesize was not checked for results
- Cleaned up parts of code
- Fixed url of log files in general tab
- Cleaned up wordpress ThickBox css

= 1.7.8 =
- Added quickview of image by clicking on filename

= 1.7.7 = 
- Adjusted options save method
- Added setting to choose how many posts should be processed each step

= 1.7.6 =
- Fixed general actions not showing up
- Fixed posts not being scanned
- Broke up post scanning in multiple ajax calls

= 1.7.5 =
- Fixed UTF8 basename bug (PHP bug)
- Improved comparision functions

= 1.7.4 =
- Added additional debug information
- Fixed debug log link not working

= 1.7.3 =
- Fixed error handling when image dimensions could not be read

= 1.7.2 =
- Small bugfix which prevented ajax loops on some platforms

= 1.7.1 =
- Small bugfix to check if meta size is available

= 1.7.0 =
- Added upgrade code to avoid errors in older versions
- Fixed a lot of warnings when E_NOTICE has been turned on
- Fixed issue that logs would be always be saved to 'uploads'

= 1.6.2 =
- Improved memory usage. 
- Fixed a lot of fatal memory errors by avoiding loading file lists to memory
- Exceptions are checked against the full file and path instead of against the path only
- Fixed an error where getimagesize was called without first checking if the file existed
- Improved code to find attachments from invalid files
- Temporaly removed option to remove meta from valid meta data, will be returned in next version

= 1.6.1 =
- Optimized code for major speed improvements
- Optimized array indexes 
- A lot of minor fixes and improvements
- Improved image table loading significantly
- Added option to increase decrease number of images which are handled each step

= 1.6.0 = 
- Optimized wp_query by retrieving only ID instead of full attachment data
- Fixed jQuery Ajax Setup interfering with other Ajax calls outside plugin

= 1.5.9 =
- Added code to remove old indexes as we now work with only one index
- Updated CSS to avoid wrapping in some columns

= 1.5.8 =
- Fixed call by reference error in PHP 5.4+
- Move/Restore and Delete file functionality now also possible by bulk or single entry

= 1.5.7 =
- Added option box to skip images with part of string

= 1.5.6 =
- More skipping code when the index is too large. This is very usefull for initial bulk repair/delete

= 1.5.5 =
- Added pagination
- Skipping search for images in scripts/plugins when the array is too large

= 1.5.4 =
- Changed function to find image in files due to memory problems on some websites

= 1.5.3 = 
- Split up Ajax calls to avoid timeouts

= 1.5.2 =
- Many small improvements

= 1.5.1 =
- Removed searching for image filenames/paths in themes and plugins

= 1.5 = 
- Reduced memory footprint which caused timeouts on some systems

= 1.4 = 
- Fixed handling of unknown errors
- Fixed error when image had no sizes in meta data

= 1.3 =
- Fixed loading message not appearing in IE and Chrome

= 1.2 = 
- Fixed warning on main screen (thank you Taliwalt ;)

= 1.1 = 
- Increased information in debug.json log

= 1.0 =
- Bulk repair/delete added
- Code cleanup
- Small improvements

= 0.9 =
- Fixed issue where repairing 'full' sized metadata didnt work

= 0.8 =
- Restore functionality was broken, fixed

= 0.7 =
- Small updates

= 0.6 =
- Logs renamed

= 0.5 = 
- Small bugfix

= 0.4 =
- Major overhaul
- Logs improved
- Log tabs added for reviewing
- More detailed indexing and results

= 0.3 =
- Small bugfix concerning log location and removal

= 0.2 =
- Added full logging

= 0.1 =
- Initial release
