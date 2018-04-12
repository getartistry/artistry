<?php

namespace LivemeshAddons\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('LAE_Blocks_Init')):

    class LAE_Blocks_Init {

        public function __construct() {

            $this->setup_constants();

            $this->includes();

            $this->hooks();
        }

        private function setup_constants() {

            // Plugin Folder Path
            if (!defined('LAE_BLOCKS_DIR')) {
                define('LAE_BLOCKS_DIR', LAE_PLUGIN_DIR. 'includes/blocks/');
            }

        }

        private function includes() {

            require_once LAE_BLOCKS_DIR . 'block.php';
            require_once LAE_BLOCKS_DIR . 'block-functions.php';
            require_once LAE_BLOCKS_DIR . 'block-header.php';
            require_once LAE_BLOCKS_DIR . 'block-layout.php';
            require_once LAE_BLOCKS_DIR . 'blocks-manager.php';
            require_once LAE_BLOCKS_DIR . 'module.php';

            /* Block Headers */
            require_once LAE_BLOCKS_DIR . 'headers/block-header-1.php';
            require_once LAE_BLOCKS_DIR . 'headers/block-header-2.php';
            require_once LAE_BLOCKS_DIR . 'headers/block-header-3.php';
            require_once LAE_BLOCKS_DIR . 'headers/block-header-4.php';
            require_once LAE_BLOCKS_DIR . 'headers/block-header-5.php';
            require_once LAE_BLOCKS_DIR . 'headers/block-header-6.php';
            require_once LAE_BLOCKS_DIR . 'headers/block-header-7.php';

            /* Modules */
            require_once LAE_BLOCKS_DIR . 'modules/module-1.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-2.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-3.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-4.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-5.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-6.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-7.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-8.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-9.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-10.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-11.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-12.php';
            require_once LAE_BLOCKS_DIR . 'modules/module-13.php';

            /* Block Types */
            require_once LAE_BLOCKS_DIR . 'types/block-1.php';
            require_once LAE_BLOCKS_DIR . 'types/block-2.php';
            require_once LAE_BLOCKS_DIR . 'types/block-3.php';
            require_once LAE_BLOCKS_DIR . 'types/block-4.php';
            require_once LAE_BLOCKS_DIR . 'types/block-5.php';
            require_once LAE_BLOCKS_DIR . 'types/block-6.php';
            require_once LAE_BLOCKS_DIR . 'types/block-7.php';
            require_once LAE_BLOCKS_DIR . 'types/block-8.php';
            require_once LAE_BLOCKS_DIR . 'types/block-9.php';
            require_once LAE_BLOCKS_DIR . 'types/block-10.php';
            require_once LAE_BLOCKS_DIR . 'types/block-11.php';
            require_once LAE_BLOCKS_DIR . 'types/block-12.php';
            require_once LAE_BLOCKS_DIR . 'types/block-13.php';

            require_once LAE_BLOCKS_DIR . 'types/block-grid-1.php';
            require_once LAE_BLOCKS_DIR . 'types/block-grid-2.php';
            require_once LAE_BLOCKS_DIR . 'types/block-grid-3.php';
            require_once LAE_BLOCKS_DIR . 'types/block-grid-4.php';
            require_once LAE_BLOCKS_DIR . 'types/block-grid-5.php';
            require_once LAE_BLOCKS_DIR . 'types/block-grid-6.php';
        }

        private function hooks(){

        }

    }

endif;

new LAE_Blocks_Init();
