<?php
/**
 * Single listing cover buttons template.
 *
 * @since 1.6.0
 */

// Possible cover button styles.
$button_styles = [
    'primary' => 'button-primary',
    'secondary' => 'button-secondary',
    'outline' => 'button-outlined',
    'plain' => 'button-plain',
    'none' => 'button-plain',
];
?>

<div class="profile-cover-content reveal">
    <div class="container">
        <div class="cover-buttons">
            <ul v-pre>
                <?php if ( $layout['buttons'] ): ?>
                    <?php foreach ( $layout['buttons'] as $button ):
                        if ( ! isset( $button['id'] ) ) {
                            $button['id'] = sprintf( 'cover-button--%s', uniqid() );
                        }

                        if ( ! isset( $button['classes'] ) ) {
                            $button['classes'] = [];
                        }

                        if ( ! empty( $button['style'] ) && isset( $button_styles[ $button['style'] ] ) ) {
                            $button['classes'][] = $button_styles[ $button['style'] ];
                        } else {
                            $button['classes'][] = 'button-outlined';
                        }

                        if ( ! empty( $button['icon'] ) && isset( $button['label'] ) ) {
                            $button['label'] = sprintf(
                                '%s<span class="button-label">%s</span>',
                                c27()->get_icon_markup( $button['icon'] ),
                                $button['label']
                            );
                        }


                        $button_template_path = sprintf( 'partials/single/buttons/%s.php', $button['action'] );

                        if ( $button_template = locate_template( $button_template_path ) ):
                            require $button_template;
                        elseif ( has_action( sprintf( 'case27\listing\cover\buttons\%s', $button['action'] ) ) ):
                            do_action( "case27\listing\cover\buttons\\{$button['action']}", $button, $listing );
                        endif;
                        ?>
                    <?php endforeach ?>
                <?php endif ?>

                <li class="dropdown">
                    <a href="#" class="buttons button-outlined medium show-dropdown c27-listing-actions" type="button" id="more-actions" data-toggle="dropdown">
                        <i class="mi more_vert"></i>
                    </a>
                    <ul class="i-dropdown share-options dropdown-menu" aria-labelledby="more-actions">
                        <?php
                        if ( job_manager_user_can_edit_job( $listing->get_id() ) && function_exists( 'wc_get_account_endpoint_url' ) ) :
                            $endpoint = wc_get_account_endpoint_url( 'my-listings' );
                            $edit_link = add_query_arg([
                                'action' => 'edit',
                                'job_id' => $listing->get_id(),
                                ], $endpoint);
                            ?>
                            <li><a href="<?php echo esc_url( $edit_link ) ?>"><?php _e( 'Edit Listing', 'my-listing' ) ?></a></li>
                        <?php endif ?>
                        <li><a href="#" data-toggle="modal" data-target="#report-listing-modal"><?php _e( 'Report this Listing', 'my-listing' ) ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>