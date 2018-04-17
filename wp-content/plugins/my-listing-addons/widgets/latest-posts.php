<?php
/*
 * Widget to display the latest blog posts.
 */

class CASE27_Widgets_Latest_Posts extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            false,
            esc_html__( '27 > Latest Posts', 'my-listing' )
        );
    }

    /**
     * Front-end display of widget.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }


        wp_reset_postdata();

        $query = new WP_Query(array(
            'posts_per_page' => 3,
            'post_type' => 'post',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'ignore_sticky_posts' => true,
        ));

        if ($query->have_posts()): ?>
            <div class="blog-feed">
                <ul>

                    <?php while($query->have_posts()): $query->the_post() ?>
                        <?php $image = c27()->featured_image(get_the_ID(), 'thumbnail') ?>
                        <?php $terms = c27()->get_terms(get_the_ID(), 'category') ?>

                        <li class="blogArticle">
                            <a href="<?php the_permalink() ?>">
                                <div class="blogPic" style="background-image: url('<?php echo esc_url( $image ) ?>')"></div>
                            </a>
                            <div class="blogTitle">
                                <a href="<?php the_permalink() ?>"><h5><?php the_title() ?></h5></a>
                                <?php if ($terms): ?>
                                    <h6><?php _e('Posted in', 'my-listing') ?>
                                        <?php foreach ($terms as $term): ?>
                                            <a href="<?php echo esc_url( $term['link'] ) ?>"><span><?php echo esc_attr( $term['name'] ) ?></span></a>
                                        <?php endforeach ?>
                                    </h6>
                                <?php endif ?>
                            </div>
                        </li>
                    <?php endwhile ?>

                </ul>
            </div>
        <?php endif;

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'my-listing' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'my-listing' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }
}

register_widget('CASE27_Widgets_Latest_Posts');