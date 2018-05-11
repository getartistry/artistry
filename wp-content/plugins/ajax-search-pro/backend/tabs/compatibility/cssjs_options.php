<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("js_source", "Javascript source", array(
            'selects'   => wd_asp()->o['asp_compatibility_def']['js_source_def'],
            'value'     => $com_options['js_source']
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
    <ul style="float:right;text-align:left;width:50%;">
        <li><b>Non minified</b> - Optimal Compatibility, Medium space</li>
        <li><b>Minified</b> - Optimal Compatibility, Low space (recommended)</li>
        <li><b>Non minified Scoped</b> - High Compatibility, High space</li>
        <li><b>Minified Scoped</b> - High Compatibility, Medium space</li>
    </ul>
    <div class="clear"></div>
    </p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("js_init", "Javascript init method", array(
            'selects'=>array(
                array('option'=>'Dynamic (default)', 'value'=>'dynamic'),
                array('option'=>'Blocking', 'value'=>'blocking')
            ),
            'value'=>$com_options['js_init']
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
        Try to choose <strong>Blocking</strong> if the search bar is not responding to anything.
    </p>
</div>
<div class="item">
    <p class='infoMsg'>You can turn some of these off, if you are not using them.</p>
    <?php $o = new wpdreamsYesNo("js_retain_popstate", "Remember search phrase and options when using the Browser Back button?",
        $com_options['js_retain_popstate']
    ); ?>
    <p class='descMsg'>Whenever the user clicks on a live search result, and decides to navigate back, the search will re-trigger and reset the previous options.</p>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("js_fix_duplicates", "Try fixing DOM duplicates of the search bar if they exist?",
        $com_options['js_fix_duplicates']
    ); ?>
    <p class='descMsg'>Some menu or widgets scripts tend to <strong>clone</strong> the search bar completely for Mobile viewports, causing a malfunctioning search bar with no event handlers. When this is active, the plugin script will try to fix that, if possible.</p>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("detect_ajax", "Try to re-initialize if the page was loaded via ajax?",
        $com_options['detect_ajax']
    ); ?>
    <p class='descMsg'>Will try to re-initialize the plugin in case an AJAX page loader is used, like Polylang language switcher etc..</p>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("load_in_footer", "Load scripts in footer?",
        $com_options['load_in_footer']
    ); ?>
    <p class='descMsg'>Will load the scripts in the footer for better performance.</p>
</div>
<div class="item">
    <?php
    $o = new wpdreamsCustomSelect("css_compatibility_level", "CSS compatibility level", array(
            'selects'=>array(
                array('option'=>'Optimal (recommended)', 'value'=>'low'),
                array('option'=>'Medium', 'value'=>'medium'),
                array('option'=>'Maximum', 'value'=>'maximum')
            ),
            'value'=>$com_options['css_compatibility_level']
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">
    <ul style="float:right;text-align:left;width:50%;">
        <li><b>Optimal</b> - Good compabibility, smallest size</li>
        <li><b>Medium</b> - Better compatibility, bigger size</li>
        <li><b>Maximum</b> - High compatibility, very big size</li>
    </ul>
    <div class="clear"></div>
    </p>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("css_minify", "Minify the generated CSS?",
        $com_options['css_minify']
    ); ?>
    <p class='descMsg'>
        When enabled, the generated stylesheet files will be minified before saving. Can save ~10% CSS file size.
    </p>
</div>
<div class="item">
    <p class='infoMsg'>Set to yes if you are experiencing issues with the <b>search styling</b>, or if the styles are <b>not saving</b>!</p>
    <?php $o = new wpdreamsYesNo("forceinlinestyles", "Force inline styles?",
        $com_options['forceinlinestyles']
    ); ?>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("css_async_load", "Load CSS files conditionally? (asnychronous, <b>experimental!</b>)",
        $com_options['css_async_load']
    ); ?>
    <p class='descMsg'>
        Will save every search instance CSS file separately and load them with Javascript on the document load event.
        Only loads them if it finds the search instance on the page. Huge performance saver, however it might not work
        so test it seriously! Check the <a target="_blank" href="https://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/performance/visual_performance.html#3-css-compatibility-and-loading">Visual Performance</a> section of the documentation for more info.
    </p>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("load_google_fonts", "Load the <strong>google fonts</strong> used in the search options?",
        $com_options['load_google_fonts']
    ); ?>
    <p class='descMsg'>When <strong>turned off</strong>, the google fonts <strong>will not be loaded</strong> via this plugin at all.<br>Useful if you already have them loaded, to avoid mutliple loading times.</p>
</div>
<div class="item">
    <p class='infoMsg'>This might speed up the search, but also can cause incompatibility issues with other plugins.</p>
    <?php $o = new wpdreamsYesNo("usecustomajaxhandler", "Use the custom ajax handler?",
        $com_options['usecustomajaxhandler']
    ); ?>
</div>
<div class="item">
    <?php $o = new wpdreamsYesNo("old_browser_compatibility", "Display the default search box on old browsers? (IE<=8)",
        $com_options['old_browser_compatibility']
    ); ?>
</div>