<?php

namespace LivemeshAddons\Modules;

class LAE_Module_10 extends LAE_Module {

    function render() {
        ob_start();
        ?>

        <div class="lae-module-10 lae-small-thumb <?php echo $this->get_module_classes(); ?>">

            <div class="lae-entry-details">

                <?php echo $this->get_taxonomies_info(); ?>

                <?php echo $this->get_title(); ?>

                <div class="lae-module-meta">
                    <?php echo $this->get_author(); ?>
                    <?php echo $this->get_date(); ?>
                    <?php echo $this->get_comments(); ?>
                    <?php echo $this->get_taxonomies_info(); ?>
                </div>

            </div>

            <?php echo $this->get_thumbnail(); ?>

            <div class="lae-excerpt">
                <?php echo $this->get_excerpt(); ?>
            </div>

            <div class="lae-read-more">
                <a href="<?php the_permalink($this->post_ID); ?>"><?php echo esc_html__('Read more', 'livemesh-el-addons'); ?></a>
            </div>

        </div>

        <?php return ob_get_clean();
    }
}