<?php

namespace LivemeshAddons\Modules;

class LAE_Module_2 extends LAE_Module {

    function render() {
        ob_start();
        ?>

        <div class="lae-module-2 lae-small-thumb <?php echo $this->get_module_classes(); ?>">

            <div class="lae-entry-details">

                <?php echo $this->get_title(); ?>

                <div class="lae-module-meta">
                    <?php echo $this->get_author();?>
                    <?php echo $this->get_date();?>
                    <?php echo $this->get_comments();?>
                </div>

            </div>

        </div>

        <?php return ob_get_clean();
    }
}