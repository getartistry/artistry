<?php
    $data = c27()->merge_options([
            'skin' => 'transparent',
            'ids' => '',
            'align' => 'center',
        ], $data);

    $category_ids = (array) explode( ',', (string) $data['ids'] );

    $categories = (array) get_terms([
        'taxonomy' => 'job_listing_category',
        'hide_empty' => false,
        'include' => array_filter( array_map( 'absint', $category_ids ) ) ? : [-1],
        'orderby' => 'include',
        ]);

    if ( is_wp_error( $categories ) ) {
        return false;
    }
 ?>

 <div class="<?php echo esc_attr( 'text-' . $data['align'] ) ?>">
    <div class="featured-categories <?php echo esc_attr( $data['skin'] ) ?>" style="display: inline-block;">
        <ul>
            <?php foreach ( $categories as $category):
                $term = new CASE27\Classes\Term( $category );
                ?>

                <li class="reveal text-center">
                    <a href="<?php echo esc_url( $term->get_link() ) ?>">
                        <div class="slc-icon">
							<?php echo $term->get_icon([ 'background' => false, 'color' => false ]); ?>
                        </div>
                        <div class="slc-info">
                            <p><?php echo esc_html( $term->get_name() ) ?></p>
                        </div>
                    </a>
                </li>

            <?php endforeach ?>
        </ul>
    </div>
</div>