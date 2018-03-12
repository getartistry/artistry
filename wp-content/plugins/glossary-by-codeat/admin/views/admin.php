<?php

/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
?>
<div class="wrap glossary-settings">

    <h2><?php 
_e( 'Glossary General Settings', GT_TEXTDOMAIN );
?></h2>

    <div class="postbox settings-tab">
        <div class="inside">
            <a href="<?php 
echo  add_query_arg( 'gl_count_terms', true ) ;
?>" class="button button-primary" style="float:right"><?php 
_e( 'Update Terms Count', GT_TEXTDOMAIN );
?></a>
            <div class="gl-labels">
                <strong><?php 
_e( 'Single Terms:', GT_TEXTDOMAIN );
?> <span><?php 
echo  gl_get_terms_count() ;
?></span></strong> &#124;
                <strong><?php 
_e( 'Additional Terms:', GT_TEXTDOMAIN );
?> <span><?php 
echo  gl_get_related_terms_count() ;
?></span></strong> &#124;
                <strong><?php 
_e( 'Total Glossary Terms:', GT_TEXTDOMAIN );
?> <span><?php 
echo  gl_get_terms_count() + gl_get_related_terms_count() ;
?></span></strong>
            </div>
            <small><?php 
_e( 'The glossary terms amount count is scheduled once a day. Use this button if you need to manually calculate it.', GT_TEXTDOMAIN );
?></small>
        </div>
    </div>

    <div id="tabs" class="settings-tab">
        <ul>
            <li><a href="#tabs-settings"><?php 
_e( 'Settings', GT_TEXTDOMAIN );
?></a></li>
			<?php 
?>
            <li><a href="#tabs-impexp"><?php 
_e( 'Import/Export', GT_TEXTDOMAIN );
?></a></li>
        </ul>
        <div id="tabs-settings" class="wrap">
			<?php 
$pro = ' <span class="gl-pro-label">' . __( 'This feature is available only for PRO users.', GT_TEXTDOMAIN ) . '</span>';
/* translators: The placeholder will be replace by a url */
$doc = __( '<a href="%s" target="_blank">Not sure? check out Glossary\'s documentation</a>', GT_TEXTDOMAIN );
$cmb = new_cmb2_box( array(
    'id'         => GT_SETTINGS . '_options',
    'hookup'     => false,
    'show_on'    => array(
    'key'   => 'options-page',
    'value' => array( GT_TEXTDOMAIN ),
),
    'show_names' => true,
) );
$cmb->add_field( array(
    'name' => __( 'Settings for Post Types', GT_TEXTDOMAIN ),
    'id'   => 'title_post_types',
    'type' => 'title',
) );
$cmb->add_field( array(
    'name' => __( 'Enable in:', GT_TEXTDOMAIN ),
    'id'   => 'posttypes',
    'type' => 'multicheck_posttype',
) );
$where_enable = array(
    'home'         => __( 'Home', GT_TEXTDOMAIN ),
    'category'     => __( 'Category archive', GT_TEXTDOMAIN ),
    'tag'          => __( 'Tag archive', GT_TEXTDOMAIN ),
    'arc_glossary' => __( 'Glossary Archive', GT_TEXTDOMAIN ),
    'tax_glossary' => __( 'Glossary Taxonomy', GT_TEXTDOMAIN ),
);
$cmb->add_field( array(
    'name'    => __( 'Enable also in following archives:', GT_TEXTDOMAIN ),
    'id'      => 'is',
    'type'    => 'multicheck',
    'options' => $where_enable,
) );
$cmb->add_field( array(
    'name' => __( 'Alphabetical order in Glossary Archives', GT_TEXTDOMAIN ),
    'id'   => 'order_terms',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name'    => __( 'Glossary Terms Slug', GT_TEXTDOMAIN ),
    'desc'    => __( 'Terms and Categories cannot have the same custom slug.', GT_TEXTDOMAIN ),
    'id'      => 'slug',
    'type'    => 'text',
    'default' => 'glossary',
) );
$cmb->add_field( array(
    'name'    => __( 'Glossary Category Slug', GT_TEXTDOMAIN ),
    'desc'    => __( 'Terms and Categories cannot have the same custom base slug.', GT_TEXTDOMAIN ),
    'id'      => 'slug-cat',
    'type'    => 'text',
    'default' => 'glossary-cat',
) );
$cmb->add_field( array(
    'name' => __( 'Disable Archive in the frontend for Glossary Terms', GT_TEXTDOMAIN ),
    'desc' => __( 'Don\'t forget to flush the permalinks in the General Settings.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'https://codeat.co/glossary/docs/disable-archives-frontend/' ),
    'id'   => 'archive',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name' => __( 'Disable Archive in the frontend for Glossary Categories', GT_TEXTDOMAIN ),
    'id'   => 'tax_archive',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name' => __( 'Remove "Archive/Category" prefix from meta titles in Archive/Category pages', GT_TEXTDOMAIN ),
    'desc' => sprintf( $doc, 'https://codeat.co/glossary/docs/remove-archive-category-prefix-meta-titles/' ),
    'id'   => 'remove_archive_label',
    'type' => 'checkbox',
) );
$temp = array(
    'name' => __( 'Add total number of terms in the meta title of the page', GT_TEXTDOMAIN ),
    'desc' => sprintf( $doc, 'https://codeat.co/glossary/docs/add-total-number-terms-meta-title-page/' ),
    'id'   => 'number_archive_title',
    'type' => 'checkbox',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Behaviour', GT_TEXTDOMAIN ),
    'id'   => 'title_behaviour',
    'type' => 'title',
) );
$temp = array(
    'name' => __( 'Link only the first occurrence of the same key term', GT_TEXTDOMAIN ),
    'desc' => __( 'Prevents duplicating links and tooltips for all key terms that point to the same definition.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'https://codeat.co/glossary/docs/link-first-occurrence-key-term/' ) . $pro,
    'id'   => 'first_occurrence',
    'type' => 'checkbox',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$temp = array(
    'name' => __( 'Link only the first occurrence of all the term keys', GT_TEXTDOMAIN ),
    'desc' => __( 'Prevent duplicate links and tooltips for the same term, even if has more than one key, in a single post.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'https://codeat.co/glossary/docs/link-first-occurence-term-keys/' ) . $pro,
    'id'   => 'first_all_occurrence',
    'type' => 'checkbox',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Add icon to external link', GT_TEXTDOMAIN ),
    'desc' => __( 'Add a css class with an icon to external link', GT_TEXTDOMAIN ),
    'id'   => 'external_icon',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name' => __( 'Force Glossary terms to be included within WordPress search results', GT_TEXTDOMAIN ),
    'desc' => __( 'Choose this option if you don\'t see your terms while searching for them in WordPress.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'https://codeat.co/glossary/docs/force-glossary-terms-included-within-wordpress-search-results/' ),
    'id'   => 'search',
    'type' => 'checkbox',
) );
$temp = array(
    'name' => __( 'Match case-sensitive terms', GT_TEXTDOMAIN ),
    'id'   => 'case_sensitive',
    'type' => 'checkbox',
    'desc' => $pro,
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$temp = array(
    'name' => __( 'Prevent term link to appear in the same term page.', GT_TEXTDOMAIN ),
    'desc' => __( 'Choose this option to avoid redundancy.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'https://codeat.co/glossary/docs/prevent-term-link-appear-term-page/' ) . $pro,
    'id'   => 'match_same_page',
    'type' => 'checkbox',
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Settings for Tooltip', GT_TEXTDOMAIN ),
    'id'   => 'title_tooltip',
    'type' => 'title',
) );
$mode = array(
    'link'         => __( 'Only Link', GT_TEXTDOMAIN ),
    'link-tooltip' => __( 'Link and Tooltip', GT_TEXTDOMAIN ),
);
$cmb->add_field( array(
    'name'    => __( 'Enable tooltips on terms', GT_TEXTDOMAIN ),
    'desc'    => __( 'Tooltip will popup on hover', GT_TEXTDOMAIN ),
    'id'      => 'tooltip',
    'type'    => 'select',
    'options' => $mode,
) );
$themes = apply_filters( 'glossary_themes_dropdown', array(
    'classic' => 'Classic',
    'box'     => 'Box',
    'line'    => 'Line',
) );
$cmb->add_field( array(
    'name'    => __( 'Tooltip style', GT_TEXTDOMAIN ),
    'desc'    => __( 'The featured image will only show with the Classic and all the PRO themes.<br>', GT_TEXTDOMAIN ) . sprintf( $doc, 'https://codeat.co/glossary/docs/tooltip-styles/' ),
    'id'      => 'tooltip_style',
    'type'    => 'select',
    'options' => $themes,
) );
$cmb->add_field( array(
    'name' => __( 'Enable image in tooltips', GT_TEXTDOMAIN ),
    'id'   => 't_image',
    'type' => 'checkbox',
) );
$temp = array(
    'name' => __( 'Remove "more" link in tooltips', GT_TEXTDOMAIN ),
    'id'   => 'more_link',
    'type' => 'checkbox',
    'desc' => $pro,
);
if ( !empty($pro) ) {
    $temp['attributes'] = array(
        'readonly' => 'readonly',
        'disabled' => 'disabled',
    );
}
$cmb->add_field( $temp );
$cmb->add_field( array(
    'name' => __( 'Excerpt', GT_TEXTDOMAIN ),
    'id'   => 'title_excerpt_limit',
    'type' => 'title',
) );
$cmb->add_field( array(
    'name' => __( 'Limit the excerpt by words', GT_TEXTDOMAIN ),
    'desc' => __( 'As opposed to characters', GT_TEXTDOMAIN ),
    'id'   => 'excerpt_words',
    'type' => 'checkbox',
) );
$cmb->add_field( array(
    'name'    => __( 'Excerpt length in characters or words', GT_TEXTDOMAIN ),
    'desc'    => __( 'Refers to selection above', GT_TEXTDOMAIN ),
    'id'      => 'excerpt_limit',
    'type'    => 'text_number',
    'default' => '60',
) );
cmb2_metabox_form( GT_SETTINGS . '_options', GT_SETTINGS . '-settings' );
?>
        </div>
		<?php 
?>
        <div id="tabs-impexp" class="metabox-holder">
            <div class="postbox">
                <h3 class="hndle"><span><?php 
_e( 'Export Settings', GT_TEXTDOMAIN );
?></span>
                    <p class="cmb2-metabox-description"><?php 
_e( 'Here you can Import/Export Glossary\'s settings from/to other WordPress installations. For more details head over to our documentation', GT_TEXTDOMAIN );
?></p>
                </h3>
                <div class="inside">
                    <p><?php 
_e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', GT_TEXTDOMAIN );
?></p>
                    <form method="post">
                        <p><input type="hidden" name="g_action" value="export_settings" /></p>
                        <p>
							<?php 
wp_nonce_field( 'g_export_nonce', 'g_export_nonce' );
?>
							<?php 
submit_button(
    __( 'Export', GT_TEXTDOMAIN ),
    'secondary',
    'submit',
    false
);
?>
                        </p>
                    </form>
                </div>
            </div>

            <div class="postbox">
                <h3 class="hndle"><span><?php 
_e( 'Import Settings', GT_TEXTDOMAIN );
?></span></h3>
                <div class="inside">
                    <p><?php 
_e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', GT_TEXTDOMAIN );
?></p>
                    <form method="post" enctype="multipart/form-data">
                        <p>
                            <input type="file" name="g_import_file"/>
                        </p>
                        <p>
                            <input type="hidden" name="g_action" value="import_settings" />
							<?php 
wp_nonce_field( 'g_import_nonce', 'g_import_nonce' );
?>
							<?php 
submit_button(
    __( 'Import', GT_TEXTDOMAIN ),
    'secondary',
    'submit',
    false
);
?>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php 
?>
</div>
