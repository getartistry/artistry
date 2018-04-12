<?php

namespace LivemeshAddons\Modules;

class LAE_Module_12 extends LAE_Module {

    function render() {
        ob_start();
        ?>

        <article
                class="lae-module-12 <?php echo $this->get_module_classes(); ?> <?php echo join(' ', get_post_class('', $this->post_ID)); ?>">

            <?php if ($thumbnail_exists = has_post_thumbnail($this->post_ID)): ?>

                <div class="lae-module-image">

                    <div class="lae-module-thumb">

                        <?php echo $this->get_media(); ?>

                        <?php echo $this->get_lightbox(); ?>

                    </div>

                    <div class="lae-module-image-info">

                        <div class="lae-module-entry-info">

                            <?php echo $this->get_title(); ?>

                            <?php echo $this->get_taxonomies_info(); ?>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </article><!-- .hentry -->

        <?php return ob_get_clean();
    }
}