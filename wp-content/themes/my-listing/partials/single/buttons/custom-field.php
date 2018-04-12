<?php
/**
 * Template to display cover buttons containing a custom field.
 *
 * @since 1.6.0
 */

if ( ! ( $value = $listing->get_field( $button['custom_field'] ) ) ) {
    return false;
}

$value = apply_filters( 'case27\listing\cover\field\\' . $button['custom_field'], $value, $button, $listing );

if ( is_array( $value ) ) {
    $value = join( ', ', $value );
}

// Save this value globally, so it can be retrieved by shortcodes
// that could be present in the button's value.
$GLOBALS['c27_active_shortcode_content'] = $value;

// Replace [[field]] placeholder with the actual field value.
$content = str_replace( '[[field]]', $value, do_shortcode( $button['label'] ) );

if ( has_shortcode( $button['label'], '27-format') ) {
    $button['classes'][] = 'formatted';

    preg_match('/\[27-format.*type="(?<format_type>[^"]+)"/', $button['label'], $matches);

    if ( ! empty( $matches['format_type'] ) ) {
        $button['classes'][] = $matches['format_type'];
    }
}
?>

<li>
    <?php if ( trim( $value ) && trim( $content ) ): ?>
        <div class="buttons medium <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?>">
            <?php echo $content ?>
        </div>
    <?php endif ?>
</li>