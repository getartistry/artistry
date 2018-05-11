<?php
$sugg_select_arr = array(
	'google' => 'Google keywords',
    'google_places' => 'Google Places API',
	'statistics' => 'Statistics database',
	'tags' => 'Post tags',
	'titles' => 'Post titles'
);
$taxonomies_arr = get_taxonomies(array('public' => true, '_builtin' => false), 'names', 'and');
foreach($taxonomies_arr as $taxx) {
	$sugg_select_arr['xtax_'.$taxx] = '[taxonomy] ' . $taxx;
}
?>
<ul id="subtabs"  class='tabs'>
    <li><a tabid="501" class='subtheme current'>Autocomplete</a></li>
    <li><a tabid="502" class='subtheme'>Keyword suggestions</a></li>
    <li><a tabid="503" class='subtheme'>Suggested search keywords</a></li>
</ul>
<div class='tabscontent'>
    <div tabid="501">
        <fieldset>
            <legend>Autocomplete</legend>
            <p class="infoMsg">
                Autocomplete feature will try to help the user finish what is being typed into the search box.
            </p>
            <?php include(ASP_PATH."backend/tabs/instance/suggest/autocomplete.php"); ?>
        </fieldset>
    </div>
    <div tabid="502">
        <fieldset>
            <legend>Keyword suggestions</legend>
            <p class="infoMsg">
                Keyword suggestions appear when no results match the keyword.
            </p>
            <?php include(ASP_PATH."backend/tabs/instance/suggest/keywords.php"); ?>
        </fieldset>
    </div>
    <div tabid="503">
        <fieldset>
            <legend>Suggested search keywords</legend>
            <?php include(ASP_PATH."backend/tabs/instance/suggest/suggestions.php"); ?>
        </fieldset>
    </div>
</div>
<div class="item">
    <input name="reset_<?php echo $search['id']; ?>" class="asp_submit asp_submit_transparent asp_submit_reset" type="button" value="Restore defaults">
    <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save all tabs!" />
</div>