<?php
    // Post preview. Use within the loop.
    $defaults = [
        'wrap_in' => '',
    ];

    $categories = array_filter((array) get_the_terms(get_the_ID(), 'category'));

    $image = c27()->featured_image(get_the_ID(), 'large');

    if ( ! $image ) $image = c27()->get_setting('blog_default_post_image');

    if (is_array($image) && isset($image['sizes'])) {
        $image = $image['sizes']['large'];
    }
?>

<div class="<?php echo $data['wrap_in'] ? esc_attr( $data['wrap_in'] ) : '' ?>">
    <div class="single-blog-feed grid-item reveal">
        <div class="sbf-container">
            <div class="lf-head">
                <div class="lf-head-btn event-date">
                    <span class="e-month"><?php echo get_the_date('M') ?></span>
                    <span class="e-day"><?php echo get_the_date('d') ?></span>
                </div>
                <?php if (is_sticky()): ?>
                    <div class="lf-head-btn">
                        <i class="icon icon-pin-2"></i>
                    </div>
                <?php endif ?>
            </div>
            <div class="sbf-thumb">
                <a href="<?php the_permalink() ?>">
                    <div class="overlay"></div>
                    <?php if ($image): ?>
                        <div class="sbf-background" style="background-image: url('<?php echo esc_url( $image ) ?>')"></div>
                    <?php endif ?>
                </a>
            </div>
            <div class="sbf-title">
                <a href="<?php the_permalink() ?>" class="case27-secondary-text"><?php the_title() ?></a>
                <p><?php c27()->the_excerpt(91) ?></p>
            </div>

            <div class="listing-details">
                <ul class="c27-listing-preview-category-list">
                    <?php if ( ! is_wp_error( $categories ) && count( $categories ) ):
                        $category_count = count( $categories );

                        $first_category = array_shift($categories);
                        $first_ctg = new CASE27\Classes\Term( $first_category );
                        $category_names = array_map(function($category) {
                            return $category->name;
                        }, $categories);
                        $categories_string = join('<br>', $category_names);
                        ?>

                        <li>
                            <a href="<?php echo esc_url( $first_ctg->get_link() ) ?>">
                                <span class="cat-icon" style="background-color: <?php echo esc_attr( $first_ctg->get_color() ) ?>;">
                                    <?php echo $first_ctg->get_icon([ 'background' => false ]) ?>
                                </span>
                                <span class="category-name"><?php echo esc_html( $first_ctg->get_name() ) ?></span>
                            </a>
                        </li>

                        <?php if (count($categories)): ?>
                            <li data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo esc_attr( $categories_string ) ?>" data-html="true">
                                <div class="categories-dropdown dropdown c27-more-categories">
                                    <a href="#other-categories">
                                        <span class="cat-icon cat-more">+<?php echo $category_count - 1 ?></span>
                                    </a>
                                </div>
                            </li>
                        <?php endif ?>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
    </div>
</div>