<?php

namespace LivemeshAddons\Modules;

class LAE_Module_13 extends LAE_Module {

    function render() {
        ob_start();
        ?>

        <article
                class="lae-module-13 <?php echo $this->get_module_classes(); ?> <?php echo join(' ', get_post_class('', $this->post_ID)); ?>">

            <?php if ($thumbnail_exists = has_post_thumbnail($this->post_ID)): ?>

                <div class="lae-module-image">

                    <div class="lae-module-thumb">

                        <?php echo $this->get_media(); ?>

                        <?php echo $this->get_lightbox(); ?>

                    </div>

                    <div class="lae-module-image-info">

                        <div class="lae-module-entry-info">

                            <?php echo $this->get_media_title(); ?>

                            <?php echo $this->get_media_taxonomy(); ?>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

            <div class="lae-module-entry-text">

                <?php echo $this->get_title(); ?>

                <div class="lae-module-meta">
                    <?php echo $this->get_author(); ?>
                    <?php echo $this->get_date(); ?>
                    <?php echo $this->get_comments(); ?>
                    <?php echo $this->get_taxonomies_info(); ?>
                </div>

                <div class="lae-excerpt">
                    <?php echo $this->get_excerpt(); ?>
                </div>

                <?php echo $this->get_read_more_link(); ?>

            </div>

        </article><!-- .hentry -->

        <?php return ob_get_clean();
    }
}