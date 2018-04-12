<?php

namespace LivemeshAddons\Blocks\Headers;

class LAE_Block_Header_4 extends LAE_Block_Header {

    function get_block_taxonomy_filter() {

        $output = '';

        $block_filter_terms = $this->get_block_filter_terms();

        if (empty($block_filter_terms))
            return '';

        $output .= '<div class="lae-block-filter">';

        $output .= '<div class="lae-block-filter-dropdown">';

        $output .= '<div class="lae-block-filter-more"><span>' . __('All' , 'livemesh-el-addons') . '</span><i class="lae-icon-arrow-right3"></i></div>';

        $output .= '<ul class="lae-block-filter-dropdown-list">';

        $output .= '<li class="lae-block-filter-item lae-active"><a class="lae-block-filter-link" data-term-id="" data-taxonomy="" href="#">' . esc_html__('All', 'livemesh-el-addons') . '</a>';

        foreach ($block_filter_terms as $block_filter_term) {

            $output .= '<li class="lae-block-filter-item"><a class="lae-block-filter-link" data-term-id="' . $block_filter_term->term_id . '" data-taxonomy="' . $block_filter_term->taxonomy . '" href="#">' . $block_filter_term->name . '</a>';

        }

        $output .= '</ul>';

        $output .= '</div><!-- .lae-block-filter-dropdown -->';

        $output .= '</div><!-- .lae-block-filter -->';

        return $output;

    }

    function get_block_header_class() {

        return 'lae-block-header-4';

    }
}