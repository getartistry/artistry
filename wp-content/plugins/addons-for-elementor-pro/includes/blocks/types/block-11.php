<?php

namespace LivemeshAddons\Blocks;

class LAE_Block_11 extends LAE_Block {

    function inner($posts, $settings) {

        $output = '';

        $post_count = 1;

        $num_of_columns = $settings['per_line1'];

        $block_layout = new LAE_Block_Layout();

        $column_class = lae_get_column_class(intval($num_of_columns));

        if (!empty($posts)) {

            foreach ($posts as $post) {

                switch ($num_of_columns) {
                    case 1:
                        $output .= $block_layout->open_column($column_class);

                        // big posts for posts
                        if ($post_count <= 1) {

                            $module2 = new \LivemeshAddons\Modules\LAE_Module_1($post, $settings);

                            $output .= $module2->render();

                        }
                        else {

                            $module6 = new \LivemeshAddons\Modules\LAE_Module_2($post, $settings);

                            $output .= $module6->render();
                        }

                        $post_count++;

                        break;

                    case 2:
                    case 3:

                        // big posts for posts
                        if ($post_count <= 1) {

                            $output .= $block_layout->open_row();

                            $output .= $block_layout->open_column($column_class);

                            $module2 = new \LivemeshAddons\Modules\LAE_Module_1($post, $settings);

                            $output .= $module2->render();

                            $output .= $block_layout->close_column($column_class);

                        }
                        else {

                            $output .= $block_layout->open_column($column_class);

                            $module6 = new \LivemeshAddons\Modules\LAE_Module_2($post, $settings);

                            $output .= $module6->render();
                        }

                        // Help start a 3rd column for 5th post onwards when in 3 column mode
                        if ($num_of_columns == 3 && $post_count == 5)
                            $output .= $block_layout->close_column($column_class);

                        $post_count++;

                        break;
                }


            };

            $output .= $block_layout->close_all_tags();

        };

        return $output;

    }

    function get_block_class() {

        return 'lae-block-posts lae-block-11';

    }
}