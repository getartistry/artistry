<ul id="tabs"  class='tabs'>
    <li><a tabid="601" class='subtheme current'>Overall box layout</a></li>
    <li><a tabid="602" class='subtheme'>Input field layout</a></li>
    <li><a tabid="603" class='subtheme'>Settings icon & dropdown</a></li>
    <li><a tabid="604" class='subtheme'>Magnifier & loading icon</a></li>
	<li><a tabid="612" class='subtheme'>Search text button</a></li>
    <li><a tabid="605" class='subtheme'>Isotopic Results</a></li>
    <li><a tabid="606" class='subtheme'>Isotopic Navigation</a></li>
    <li><a tabid="607" class='subtheme'>Vertical Results</a></li>
    <li><a tabid="608" class='subtheme'>Horizontal Results</a></li>
    <li><a tabid="609" class='subtheme'>Polaroid Results</a></li>
    <li><a tabid="610" class='subtheme'>Typography</a></li>
    <li><a tabid="611" class='subtheme'>Custom CSS</a></li>
</ul>
<div class='tabscontent'>

    <div tabid="601">
        <?php include(ASP_PATH."backend/tabs/instance/theme/overall_box.php"); ?>
    </div>
    <div tabid="602">
        <?php include(ASP_PATH."backend/tabs/instance/theme/input_field.php"); ?>
    </div>
    <div tabid="603">
        <?php include(ASP_PATH."backend/tabs/instance/theme/sett_dropdown.php"); ?>
    </div>
    <div tabid="604">
        <?php include(ASP_PATH."backend/tabs/instance/theme/magn_load.php"); ?>
    </div>
	<div tabid="612">
		<?php include(ASP_PATH."backend/tabs/instance/theme/search_text.php"); ?>
	</div>
    <div tabid="605">
        <?php include(ASP_PATH."backend/tabs/instance/theme/isotopic_res.php"); ?>
    </div>
    <div tabid="606">
        <?php include(ASP_PATH."backend/tabs/instance/theme/isotopic_nav.php"); ?>
    </div>
    <div tabid="607">
        <?php include(ASP_PATH."backend/tabs/instance/theme/vertical_res.php"); ?>
    </div>
    <div tabid="608">
        <?php include(ASP_PATH."backend/tabs/instance/theme/horizontal_res.php"); ?>
    </div>
    <div tabid="609">
        <?php include(ASP_PATH."backend/tabs/instance/theme/polaroid_res.php"); ?>
    </div>
    <div tabid="610">
        <?php include(ASP_PATH."backend/tabs/instance/theme/typography.php"); ?>
    </div>
    <div tabid="611">
        <?php include(ASP_PATH."backend/tabs/instance/theme/custom_css.php"); ?>
    </div> <!-- tab 18 -->

</div> <!-- .tabscontent -->

<?php if(ASP_DEBUG==1): ?>
    <textarea class='previewtextarea' style='display:block;width:600px;'>
    </textarea>
<?php endif; ?>

<script>
    jQuery(document).ready(function() {
        (function( $ ){
            $(".previewtextarea").click(function(){
                var skip = ['settingsimage_custom', 'magnifierimage_custom', 'search_text', 'res_z_index', 'sett_z_index'];
                var parent = $(this).parent().parent();
                var content = "";
                var v = "";
                $("input[isparam=1], select[isparam=1]", parent).each(function(){
                    var name = $(this).attr("name");
                    if ( skip.indexOf(name) > -1 )
                        return true;
                    var val = $(this).val().replace(/(\r\n|\n|\r)/gm,"");
                    content += '"'+name+'":"'+val+'",\n';
                });
                //$(this).val(content+v);

                $("select[name=resultstype]").each(function(){
                    var name = $(this).attr("name");
                    var val = $(this).val().replace(/(\r\n|\n|\r)/gm,"");
                    content += '"'+name+'":"'+val+'",\n';
                });
                $("input[name=showdescription]").each(function(){
                    var name = $(this).attr("name");
                    var val = $(this).val().replace(/(\r\n|\n|\r)/gm,"");
                    content += '"'+name+'":"'+val+'",\n';
                });

                content = content.trim();
                content = content.slice(0, - 1);
                $(this).val('"theme": {\n' + content + "\n}");
            });
        }(jQuery))
    });
</script>
<div class="item">
    <input name="reset_<?php echo $search['id']; ?>" class="asp_submit asp_submit_transparent asp_submit_reset" type="button" value="Restore defaults">
    <input name="submit_<?php echo $search['id']; ?>" type="submit" value="Save this search!" />
</div>