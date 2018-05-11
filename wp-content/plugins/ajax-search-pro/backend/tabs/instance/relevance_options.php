<ul id="subtabs"  class='tabs'>
    <li><a tabid="801" class='subtheme current'>Regular Engine</a></li>
    <li><a tabid="802" class='subtheme'>Index Table Engine</a></li>
</ul>
<div class='tabscontent'>
    <div class='item'>
        <p class='infoMsg'>
            Every result gets a relevance value based on the weight numbers set below. The weight is the measure of
            importance.<br/>
            You can change this ordering on the general options tab. (<strong>Results ordering</strong> option)
        </p>
    </div>

    <div tabid="801">

        <?php include(ASP_PATH."backend/tabs/instance/relevance/regular.php"); ?>

    </div>
    <div tabid="802">

        <?php include(ASP_PATH."backend/tabs/instance/relevance/index_table.php"); ?>

    </div>
</div>
<div class="item">
    <input name="reset_<?php echo $search['id']; ?>" class="asp_submit asp_submit_transparent asp_submit_reset" type="button" value="Restore defaults">
    <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
</div>

