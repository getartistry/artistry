<?php

namespace LivemeshAddons\Blocks;

class LAE_Block_Grid_5 extends LAE_Block {

    function inner($posts, $settings) {

        $output = '';

        $post_count = 1;

        $num_of_columns = $settings['per_line'];

        $block_layout = new LAE_Block_Layout();

        $column_class = lae_get_column_class(intval($num_of_columns));

        if (!empty($posts)) {

            foreach ($posts as $post) {

                if ($num_of_columns == 1) {

                    $output .= $block_layout->open_column($column_class);

                    $module2 = new \LivemeshAddons\Modules\LAE_Module_12($post, $settings);

                    $output .= $module2->render();

                    $post_count++;

                }
                else {

                    $output .= $block_layout->open_column($column_class);

                    $module2 = new \LivemeshAddons\Modules\LAE_Module_12($post, $settings);

                    $output .= $module2->render();

                    $output .= $block_layout->close_column($column_class);

                    $post_count++;
                }

            };

            $output .= $block_layout->close_all_tags();

        };

        return $output;

    }

    function get_block_class() {

        return 'lae-block-grid lae-block-grid-5 lae-gapless-grid';

    }
}