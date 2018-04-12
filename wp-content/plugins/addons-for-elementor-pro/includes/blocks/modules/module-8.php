<?php

namespace LivemeshAddons\Modules;

class LAE_Module_8 extends LAE_Module {

    function render() {
        ob_start();
        ?>

        <div class="lae-module-8 lae-small-thumb <?php echo $this->get_module_classes(); ?>">

            <?php echo $this->get_thumbnail(); ?>

            <div class="lae-entry-details">

                <?php echo $this->get_title();?>

                <div class="lae-module-meta">
                    <?php echo $this->get_author();?>
                    <?php echo $this->get_date();?>
                    <?php echo $this->get_comments();?>
                </div>

                <div class="lae-excerpt">
                    <?php echo $this->get_excerpt();?>
                </div>

                <div class="lae-read-more">
                    <a class="lae-button" href="<?php the_permalink($this->post_ID);?>"><?php echo esc_html__('Read more', 'livemesh-el-addons');?></a>
                </div>

            </div>

        </div>

        <?php return ob_get_clean();
    }
}