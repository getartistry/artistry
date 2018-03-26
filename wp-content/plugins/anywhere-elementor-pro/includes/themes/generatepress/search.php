<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package GeneratePress
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

get_header(); ?>

    <section id="primary" <?php generate_content_class(); ?>>
        <main id="main" <?php generate_main_class(); ?>>
            <?php do_action('generate_before_main_content'); ?>

                <?php echo do_action('ae_pro_search'); ?>

            <?php do_action('generate_after_main_content'); ?>
        </main><!-- #main -->
    </section><!-- #primary -->

<?php get_footer(); ?>