<?php

namespace LivemeshAddons\Modules;

class LAE_Module_3 extends LAE_Module {

    function render() {
        ob_start();
        ?>

        <div class="lae-module-3 lae-small-thumb <?php echo $this->get_module_classes(); ?>">

            <?php echo $this->get_thumbnail('medium'); ?>

            <div class="lae-entry-details">

                <?php echo $this->get_title(); ?>

                <div class="lae-module-meta">
                    <?php echo $this->get_date(); ?>
                </div>

            </div>

        </div>

        <?php return ob_get_clean();
    }
}