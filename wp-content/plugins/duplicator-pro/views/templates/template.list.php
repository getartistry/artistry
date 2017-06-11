<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/views/inc.header.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/entities/class.package.template.entity.php');

$nonce_action = 'duppro-template-list';

$display_edit = false;

if (isset($_REQUEST['action']))
{
    check_admin_referer($nonce_action);
    
    // DUP_PRO_U::debug("action set ");
    $action = $_REQUEST['action'];

    switch ($action)
    {
        case 'add':
        case 'edit':
            $display_edit = true;
            break;

        case 'bulk-delete':
            $package_template_ids = $_REQUEST['selected_id'];

            foreach ($package_template_ids as $package_template_id)
            {
                DUP_PRO_LOG::trace("attempting to delete $package_template_id");
                DUP_PRO_Package_Template_Entity::delete_by_id($package_template_id);
            }

            break;

        case 'delete':

            $package_template_id = (int) $_REQUEST['package_template_id'];

            DUP_PRO_LOG::trace("attempting to delete $package_template_id");
            DUP_PRO_Package_Template_Entity::delete_by_id($package_template_id);
            break;

        default:

            break;
    }
}

$package_templates = DUP_PRO_Package_Template_Entity::get_all();
$package_template_count = count($package_templates);
?>

<style>
    /*Detail Tables */
    table.package-tbl td {height: 45px}
    table.package-tbl a.name {font-weight: bold}
    table.package-tbl input[type='checkbox'] {margin-left: 5px}
    table.package-tbl div.sub-menu {margin: 5px 0 0 2px; display: none}
    tr.package-detail {display: none;}
    tr.package-detail td {padding:2px 2px 2px 15px; margin:-5px 0 2px 0; height: 22px}
</style>

<form id="dup-package-form" action="<?php echo $templates_tab_url; ?>" method="post">
    <?php wp_nonce_field($nonce_action); ?>
    <input type="hidden" id="dup-package-form-action" name="action" value=""/>
    <input type="hidden" id="dup-package-selected-package-template" name="package_template_id" value="-1"/>

    <!-- ====================
    TOOL-BAR -->
    <table class="dpro-edit-toolbar">
        <tr>
            <td>
                <select id="bulk_action">
                    <option value="-1" selected="selected"><?php _e("Bulk Actions"); ?></option>
                    <option value="delete" title="Delete selected package(s)"><?php _e("Delete"); ?></option>
                </select>
                <input type="button" class="button action" value="<?php DUP_PRO_U::_e("Apply") ?>" onclick="DupPro.Template.BulkAction()">
				<a href="admin.php?page=duplicator-pro-tools&tab=data" class="button" title="<?php DUP_PRO_U::_e("Import/Export Data"); ?>"><i class="fa fa-download"></i></a>
            </td>
            <td>
                <span><i class="fa fa-files-o"></i> <?php DUP_PRO_U::_e("All Templates"); ?></span>
                <a href="<?php echo $edit_template_url; ?>" class="add-new-h2"><?php DUP_PRO_U::_e('Add New'); ?></a>
            </td>
        </tr>
    </table>	

    <!-- ====================
    LIST ALL SCHEDULES -->
    <table class="widefat package-tbl">
        <thead>
            <tr>
                <th style='width:10px;'><input type="checkbox" id="dpro-chk-all" title="Select all packages" onclick="DupPro.Template.SetDeleteAll(this)"></th>
                <th style='width:100%;'>Name</th>
                <!--th><?php DUP_PRO_U::_e('Filters') ?></th-->
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($package_templates as $package_template) :
                /* @var $package_template DUP_PRO_Package_Template_Entity */
                $i++;

                $schedules = DUP_PRO_Schedule_Entity::get_by_template_id($package_template->id);
                $schedule_count = count($schedules);
                ?>
                <tr class="package-row <?php echo ($i % 2) ? 'alternate' : ''; ?>">
                    <td>
                        <?php if ($package_template->is_default == false) : ?>
                            <input name="selected_id[]" type="checkbox" value="<?php echo $package_template->id ?>" class="item-chk" />
                        <?php else : ?>
                            <input type="checkbox" disabled />
                       <?php endif; ?>
                    </td>
                    <td>

                        <a href="javascript:void(0);" onclick="document.location.href = '<?php echo "$edit_template_url&package_template_id=$package_template->id"; ?>'" class="name" ><?php echo $package_template->name; ?></a>
                        <div class="sub-menu">
                            <a href="javascript:void(0);" onclick="document.location.href = '<?php echo "$edit_template_url&package_template_id=$package_template->id"; ?>'"><?php DUP_PRO_U::_e('Edit'); ?></a> 
                            <!--a href="javascript:void(0);" onclick="DupPro.Template.View('<?php echo $package_template->id; ?>');"><?php DUP_PRO_U::_e('Quick View'); ?></a-->
                            <?php if ($package_template->is_default == false) :?>
                                | <a href="javascript:void(0);" onclick="DupPro.Template.Delete(<?php echo "$package_template->id, $schedule_count"; ?>);"><?php DUP_PRO_U::_e('Delete'); ?></a>
							<?php endif; ?>
                        </div>                        
                    </td>
                    <!--td></td-->
                </tr>
                <!--tr id='<?php echo $i ?>' class='<?php echo ($i % 2) ? 'alternate' : ''; ?> package-detail'>
                    <td colspan="7">
                        <table style="line-height: 15px">
                            <tr>
                                <td><b>Notes:</b></td>
                                <td colspan="3"></td>
                            </tr>							
                            <tr>
                                <td><b>Directories:</b></td>
                                <td>
                                    E:/somepath/path1 <br/>
                                    E:/somepath/path2 <br/>
                                </td>
                            </tr>							
                            <tr>
                                <td><b>File Extensions:</b></td>
                                <td>ext1; ext2</td>
                            </tr>
                        </table>
                    </td>
                </tr-->
<?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="8" style="text-align:right; font-size:12px">						
                    <?php echo DUP_PRO_U::__('Total') . ': ' . $package_template_count; ?>
                </th>
            </tr>
        </tfoot>
    </table>
</form>

<script>
    jQuery(document).ready(function ($) {

        //Shows detail view
        DupPro.Template.View = function (id) {
            $('#' + id).toggle();
        }

        //Delets a single record
        DupPro.Template.Delete = function (id, schedule_count) {
            var message = "<?php DUP_PRO_U::_e('Delete the selected template?') ?>";

            if (schedule_count > 0)
            {
                message += "\r\n";
                message += "<?php DUP_PRO_U::_e('There currently are') ?>" + " ";
                message += schedule_count + " " + "<?php DUP_PRO_U::_e('schedule(s) using this template.'); ?>" + "  ";
                message += "<?php DUP_PRO_U::_e('All schedules using this template will be reassigned to the \"Default\" template.') ?>" + " ";
            }

            if (confirm(message))
            {
                jQuery("#dup-package-form-action").val('delete');
                jQuery("#dup-package-selected-package-template").val(id);
                jQuery("#dup-package-form").submit();
            }
        }        
        
        DupPro.Template.BulkAction = function () {
            var action = $('#bulk_action').val();

            var checked = ($('.item-chk:checked').length > 0);
            
            if(checked)
            {
                switch (action) {

                    case 'delete':

                        var message = "<?php DUP_PRO_U::_e('Delete the selected templates?\n\r') ?>";
                        message += "<?php DUP_PRO_U::_e('All schedules using this template will be reassigned to the \"Default\" Template.') ?>" + " ";
                        
                        if (confirm(message))
                        {                            
                            jQuery("#dup-package-form-action").val('bulk-delete');
                            jQuery("#dup-package-form").submit();
                        }
                        break;
                }
            }
        }

        //Sets all for deletion
        DupPro.Template.SetDeleteAll = function (chkbox) {
            $('.item-chk').each(function () {
                this.checked = chkbox.checked;
            });
        }

        //Name hover show menu
        $("tr.package-row").hover(
                function () {
                    $(this).find(".sub-menu").show();
                },
                function () {
                    $(this).find(".sub-menu").hide();
                }
        );
    });
</script>
