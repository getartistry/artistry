<?php 
 $showTutorial = $_GET['tutorials'];
 $isCTPluginInstalled = isYBIPluginActive("Curation Traffic Plugin");
 $installSuccess = $_GET['success'];
?>
<div class="wrap">
    <div class="products_header main_heading" style="background: url(<?php echo plugins_url('youbrandinc_products/i/you-brand-guys-32.png');?>); background-repeat: no-repeat; background-position: 0px 7px; min-height: 40px;">
		 <h2><?php echo _e('You Brand, Inc. Products'); ?></h2>
    </div>
<?php if($installSuccess != ''): ?>
<div id="message" class="updated"><h1>Successful Installation</h1>
<p>You should now see new menu items.<strong> Your next step is to <a href="<?php echo get_admin_url() ?>admin.php?page=youbrandinc-license">activate your license</a>.</strong></p>
</div>
<?php endif; ?>
    <div style="clear: both;" class="inner">
        <div class="products_left">
            <div class="products_support_section">
                <h3>Special Offers</h3>
                <p><a href="https://members.youbrandinc.com/special-offers/" target="_blank">See Your Special Upgrade Offers</a></p>


                <h3>Where to Get Support</h3>
                <p>You can visit <a href="https://youbrandinc.zendesk.com/home" target="_blank">FAQs and Knowledge Base</a> (login to access forums and FAQs).</p>
<p class="support_request_w" style="text-align: left;"><a href="<?php echo YBI_SUPPORT_URL; ?>" target="_blank" class="green_button green_button_support"><i class="fa fa-users fa-lg"></i>&nbsp;&nbsp;Create Support Request</a></p>
                
                
                <h3>Members Area:</h3>
                <p>Login to the <a href="https://members.youbrandinc.com" target="_blank">Members Area</a>.</p>
                <ul>
                    <li><a href="https://members.youbrandinc.com/dashboard/curation-suite/" target="_blank" title="">Curation Suite</a></li>
                    <li><a href="https://members.youbrandinc.com/dashboard/curation-traffic-plugin/" target="_blank" title="">Curation Traffic Plugin</a></li>
                    <li><a href="https://members.youbrandinc.com/dashboard/ultimate-call-to-action-plugin/" target="_blank" title="">Ultimate Call to Action</a></li>
                    <li><a href="https://members.youbrandinc.com/dashboard/curation-mastery-training/" target="_blank" title="">Curation Mastery Training</a></li>
                    <!--<li><a href="" target="_blank" title=""></a></li>-->
                </ul>
            </div>
        </div><!--products_left-->
            <div class="products_right">
            <?php if($showTutorial == '') : ?>
            <div class="latest_blog_posts">
                <h3>Curation Suite News</h3>
                <?php getFeedYBI("http://curationsuite.com/category/product-news/feed/", 5); ?>
                <h3>Latest You Brand, Inc. Blog Posts</h3>
                <?php getFeedYBI("http://youbrandinc.com/feed/", 10); ?>
            </div>
            <?php endif; ?>
            </div><!--products_right-->
    </div><!--inner-->    
    <div style="clear: both;" class="inner">
<?php if($showTutorial != ''): ?>
    <div id="ybi-accordian">
    	<?php if($isCTPluginInstalled) : ?>
	    <h3>Overview of Cuation Traffic & CurateThis&#8482; Common Problems</h3>
        <div>
            <p>The CurateThis&#8482; Bookmarklet is used by 1000's of people just like you across multiple news sites, blogs, and social networks. From time to time you might encounter problems in this section we cover
            the most common issues and how to fix them.</p>
            <p>If the CurateThis&#8482; bookmarklet window is coming up but does not seem to pull in all the information there is typically 3 main causes:</p>
            <ol>
                <li>The site you are curating is unusual (in an iframe, hosting the content or images on different servers that are set up in an unusual manner)</li>
                <li>The bookmarklet itself is having a glitch that can be fixed by our support staff</li>
                <li>You security settings are restricting it and can be fixed by the instructions below</li>
            </ol>
        </div>
	    <h3>CurateThis Bookmarklet Not Pulling Info or Pictures?</h3>
        <div class="ybi_accordian_row">
			<p>In most cases, especially if the bookmarklet is not finding images or not able to add images or text to the post by clicking the "add to post" button within the bookmarklet, 
            then your security settings on your server are restricting the bookmarklet.</p> 
            <p>To fix these errrs see turning on allow_url settings below</p>
        </div>
	    <h3>Turning on Your allow_url_* Settings</h3>
        <div class="ybi_accordian_row">
            <p>The solution most likely is a setting in your <strong>php.ini</strong> (not wordpress but Apache or other server software you're using) file that needs to be turned on that 
            allows PHP to query external urls.</p>
		    <h4>Step 1 - Backup and Open your PHP.ini File</h4>
	            <p>Open your php.ini file on your server. Or you should FTP to your server and download your php.ini file.</p>
            <h4>Step 2 - Find and Edit the allow_url_fopen line</h4>
                <p>Use a search or ctrl-f (find) in your php.ini file to search for: <strong>allow_url_fopen</strong></p>
                <p>Find the line that says "allow_url_fopen" and change the setting from Off to On, as shown below:</p>
                <p><code>allow_url_fopen = On</code></p>
			<h4>Step 3 - Upload and Copy Over Your Servers php.ini file</h4>
				<p>Now you'll want to copy your php.ini file to your server copying over the one you just downloaded.</p>
            <h4>Step 4 - Test CurateThis&#8482; bookmarklet</h4>
    	        <p>Test the CurateThis&#8482; bookmarklet. At this time everything should work, if so your done!</p>
            <h4>Possible Step 5 - Turn on Your allow_url_include</h4>
                <p>If what you've done above doesn't work then adding or changing your allow_url_include to ON should fix any issues.</p>
                <p>Use a search or ctrl-f (find) in your php.ini file to search for: <strong>allow_url_include</strong></p>
                <p>Find the line that says "allow_url_include" and change the setting from Off to On, as shown below:</p>
                <p><code>allow_url_include = On</code></p>
                <p>Note: If the allow_url_include isn't found in your php.ini file then add the line to the end of your php.ini file.</p>
			<h4>Troublshooting</h4>                
                <p><strong>Added note:</strong> For some hosts you might have to wait a minute or restart your server. Doing this is beyond the scope of this help section but searching your hosts support forum should provide the answer.</p>
            
                <p>Here's a reference file: 
                <a href="http://us3.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen" target="_blank">http://us3.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen</a></p>
                <p>The Headline and the Link are passed as parameters in the URL that's why they show up with no problem. We use a HTML parser that replicates a browser to parse content, find the images, twitter users etc</p>
                <p>Please include FTP and hosting  access information in your support request.</p> 
        </div>
        <?php endif; //isCTPluginInstalled ?>
	    <h3>Recommended FTP Client</h3>
        <div>
			<p>Most of the time you will want to use an FTP client to remotely access the files on your server. </p>
			<p>Many hosting companies will provide a file manager within their c-panel as well but we prefer using a free FTP client like Filezilla: </p>
			<p><a href="https://filezilla-project.org/" target="_blank">https://filezilla-project.org/</a></p>
        </div>
        <h3>Backing-up and Editing Files within Your server. </h3>
        <div>
            <p>For activating php 5.3 and Ioncube, most host require you to edit your php.ini and .htaccess files on your server. </p>
            <p>You will usually find the php.ini file and .htaccess file within the root directory for your domain within the public_html directory. </p>
            <p>Make sure to save a copy of your original php.ini and .htaccess files to your computer before making any changes.</p>
            <p>Once you have found the php.ini or .htaccess file, right click and select view/edit to open the file.</p>
            <p>If you are using Windows it should open up within Notepad or Textedit if you are using Mac. Or you can also use a more advanced program like Dreamweaver.  </p>
            <p>When you are editing either the php.info or .htaccess files, add the lines to the end of the documents. </p>
            <p>Or if you are trying to find a specific setting, Click ctrl + f to search the advanced server information page.</p> 
            <p>If you are having troubles opening up the file, copy the file locally and then open that new local file with notepad</p>
            <p>Make any necessary edits and copy the file back on to your server ensuring to "overwrite" the existing file so the changes can take effect. </p>
        </div>
        
    </div>
    <?php endif; //$showTutorial != '' ?>
    </div><!--inner-->    

</div><!--wrap-->