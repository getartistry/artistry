<?php

namespace Wpae\App\Service;


class CategoriesService
{
    public function getTaxonomyHierarchy($parent = 0)
    {
        $terms = \get_categories(
            array(
                'taxonomy'     => 'product_cat',
                'parent' => $parent,
                'hide_empty' => false
            )
        );

        $children = array();

        foreach ($terms as $term) {

            $item = array(
                'id' => $term->term_id,
                'title' => $term->name,
                'children' => $this->getTaxonomyHierarchy($term->term_id)
            );
            $children[] = $item;
        }

        return $children;
    }
}