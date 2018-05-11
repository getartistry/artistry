<ul id="subtabs"  class='tabs'>
    <li><a tabid="301" class='subtheme current'>General</a></li>
    <li><a tabid="310" class='subtheme'>Generic filters</a></li>
    <li><a tabid="311" class='subtheme'>Content type</a></li>
    <li><a tabid="308" class='subtheme'>Post Types</a></li>
    <li><a tabid="309" class='subtheme'>Date filters</a></li>
    <li><a tabid="307" class='subtheme'>Categories & Taxonomy Terms</a></li>
    <li><a tabid="306" class='subtheme'>Post Tags</a></li>
    <li><a tabid="303" class='subtheme'>Custom Fields</a></li>
    <li><a tabid="304" class='subtheme'>Advanced</a></li>
</ul>
<div class='tabscontent'>
    <div tabid="301">

            <?php include(ASP_PATH."backend/tabs/instance/frontend/general.php"); ?>

    </div>
    <div tabid="310">

        <?php include(ASP_PATH."backend/tabs/instance/frontend/generic.php"); ?>

    </div>
    <div tabid="311">

        <?php include(ASP_PATH."backend/tabs/instance/frontend/content_type.php"); ?>

    </div>
    <div tabid="308">

        <?php include(ASP_PATH."backend/tabs/instance/frontend/post_and_cpt.php"); ?>

    </div>
    <div tabid="309">

        <?php include(ASP_PATH."backend/tabs/instance/frontend/date.php"); ?>

    </div>
    <div tabid="303">

            <?php include(ASP_PATH."backend/tabs/instance/frontend/custom_fields.php"); ?>

    </div>
    <div tabid="304">

            <?php include(ASP_PATH."backend/tabs/instance/frontend/advanced.php"); ?>

    </div>
    <div tabid="306">

        <?php include(ASP_PATH."backend/tabs/instance/frontend/post_tags.php"); ?>

    </div>
    <div tabid="307">

        <?php include(ASP_PATH."backend/tabs/instance/frontend/taxonomy_terms.php"); ?>

    </div>
</div>
<div class="item">
    <input name="reset_<?php echo $search['id']; ?>" class="asp_submit asp_submit_transparent asp_submit_reset" type="button" value="Restore defaults">
    <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
</div>