<?php

namespace LivemeshAddons\Blocks;

class LAE_Block_13 extends LAE_Block {

    function inner($posts, $settings) {

        $output = '';

        $post_count = 1;

        $num_of_columns = 1;

        $block_layout = new LAE_Block_Layout();

        $column_class = lae_get_column_class(intval($num_of_columns));

        if (!empty($posts)) {

            foreach ($posts as $post) {

                    $output .= $block_layout->open_column($column_class);

                    $module6 = new \LivemeshAddons\Modules\LAE_Module_10($post, $settings);

                    $output .= $module6->render();

                    $post_count++;

            };

            $output .= $block_layout->close_all_tags();

        };

        return $output;

    }

    function get_block_class() {

        return 'lae-block-posts lae-block-13';

    }
}