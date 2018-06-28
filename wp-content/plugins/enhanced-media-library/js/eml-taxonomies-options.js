window.eml = window.eml || { l10n: {} };


( function( $, _ ) {

    _.extend( eml.l10n, wpuxss_eml_taxonomies_options_l10n_data );


    // Click on "Remove Taxonomy" button
    $( document ).on( 'click', 'li .wpuxss-eml-button-remove', function() {

        var target = $(this).parent();

        if ( target.hasClass( 'wpuxss-eml-clone-taxonomy' ) ) {

            target.hide( 300, function() {
                $(this).remove();
            });
        }
        else {

            emlConfirmDialog( eml.l10n.tax_deletion_confirm_title, eml.l10n.tax_deletion_confirm_text_p1+eml.l10n.tax_deletion_confirm_text_p2+eml.l10n.tax_deletion_confirm_text_p3+eml.l10n.tax_deletion_confirm_text_p4, eml.l10n.tax_deletion_yes, eml.l10n.cancel, 'button button-primary eml-warning-button' )
            .done( function() {

                target.hide( 300, function() {
                    $(this).remove();
                });
            })
            .fail(function() {
                return false;
            });
        }

        return false;
    });



    // Click on "Create New Taxonomy" button
    $(document).on( 'click', '.wpuxss-eml-button-create-taxonomy', function() {
        $('.wpuxss-eml-media-taxonomy-list').find('.wpuxss-eml-clone').clone().attr('class','wpuxss-eml-clone-taxonomy').appendTo('.wpuxss-eml-media-taxonomy-list').show(300);

        return false;
    });



    // Click on "Edit Taxonomy" button
    $(document).on( 'click', '.wpuxss-eml-button-edit', function() {

        $(this).parent().find('.wpuxss-eml-taxonomy-edit').toggle(300);

        $(this).html(function(i, html)
        {
            return html == eml.l10n.edit+' \u2193' ? eml.l10n.close+' \u2191' : eml.l10n.edit+' \u2193';
        });

        return false;
    });



    // on Taxonomy Name change (clone)
    $(document).on( 'blur', '.wpuxss-eml-clone-taxonomy .wpuxss-eml-taxonomy-name', function() {

        var taxname = $(this).val().toLowerCase(),
            taxonomy_clone_box = $(this).parents('.wpuxss-eml-clone-taxonomy'),
            slug = taxonomy_clone_box.find('.wpuxss-eml-slug').val();


        if ( '' === taxname ) {
            setTaxonomyID( '', taxonomy_clone_box );
            setTaxonomyName( '', taxonomy_clone_box );
            return;
        }

        taxname = taxname.replace(/[^a-z0-9_]/g, '');

        if ( '' !== taxname ) {
            taxname = generateTaxname( taxname );

            if ( 'year' == taxname ) {
                taxname = 'media_year';
            }
        }

        $(this).val( taxname );

        if ( '' === slug ) {
            taxonomy_clone_box.find('.wpuxss-eml-slug').val( taxname );
        }

        if ( validateTaxname( taxname ) ) {
            setTaxonomyID( taxname, taxonomy_clone_box );
            setTaxonomyName( taxname, taxonomy_clone_box );
            return;
        }

        setTaxonomyID( '', taxonomy_clone_box );
        setTaxonomyName( '', taxonomy_clone_box );

        emlAlertDialog( eml.l10n.tax_error_wrong_taxname_title, eml.l10n.tax_error_wrong_taxname, eml.l10n.okay, 'button button-primary' )
       .done( function() {
           return false;
       });
    });



    // on Taxonomy Slug change (both clone and non-clone)
    $(document).on( 'blur', '.wpuxss-eml-slug', function() {

        var slug = $(this).val().toLowerCase(),
            taxname = $(this).parents('.wpuxss-eml-clone-taxonomy').find('.wpuxss-eml-taxonomy-name').val();


        if ( '' === slug && '' === taxname ) {
            return;
        }

        slug = slug.replace(/[^a-z0-9_\-]/g, '');


        if ( validateSlug( slug ) ) {
            $(this).val( slug );
            return;
        }

        $(this).val( taxname );

        emlAlertDialog( eml.l10n.tax_error_wrong_slug_title, eml.l10n.tax_error_wrong_slug, eml.l10n.okay, 'button button-primary' )
       .done( function() {
           return false;
       });
    });



    // on Singular Name change (clone)
    $(document).on( 'blur', '.wpuxss-eml-clone-taxonomy .wpuxss-eml-singular_name', function() {

        var singular_name = $(this).val().replace(/(<([^>]+)>)/g,''),
            taxname,
            taxonomy_clone_box = $(this).closest('.wpuxss-eml-clone-taxonomy'),
            taxonomy_edit_box = $(this).parents('.wpuxss-eml-taxonomy-edit'),
            taxname_field = taxonomy_edit_box.find('.wpuxss-eml-taxonomy-name'),
            slug = taxonomy_edit_box.find('.wpuxss-eml-slug').val();


        $(this).val( singular_name );

        // check if generate a new taxname
        if ( '' === singular_name ) {
            setSingularLabels( '', taxonomy_edit_box );
            return;
        }

        setSingularLabels( singular_name, taxonomy_edit_box );

        taxname = generateTaxname( singular_name );
        taxname_field.val( taxname );

        if ( '' === slug ) {
            taxonomy_edit_box.find('.wpuxss-eml-slug').val( taxname );
        }

        if ( validateTaxname( taxname ) ) {
            setTaxonomyID( taxname, taxonomy_clone_box );
            setTaxonomyName( taxname, taxonomy_clone_box );
            return;
        }

        setTaxonomyID( '', taxonomy_clone_box );
        setTaxonomyName( '', taxonomy_clone_box );
    });



    // on Singular Name change (non-clone)
    $(document).on( 'blur', '.wpuxss-eml-taxonomy .wpuxss-eml-singular_name', function() {

        var singular_name = $(this).val().replace(/(<([^>]+)>)/g,''),
            edit_box = $(this).parents('.wpuxss-eml-taxonomy-edit');


        $(this).val( singular_name );
        setSingularLabels( singular_name, edit_box );
    });



    // on Plural Label change (clone)
    $(document).on( 'blur', '.wpuxss-eml-clone-taxonomy .wpuxss-eml-name, .wpuxss-eml-taxonomy .wpuxss-eml-name', function() {
        var plural_name = $(this).val().replace(/(<([^>]+)>)/g,''),
            edit_box = $(this).parents('.wpuxss-eml-taxonomy-edit'),
            main_tax_label = $(this).closest('.wpuxss-eml-clone-taxonomy').find('.wpuxss-eml-taxonomy-label span');


        $(this).val( plural_name );
        setPluralLabels( plural_name, edit_box, main_tax_label );
    });



    // on taxonomy form submit
    $('#wpuxss-eml-form-taxonomies').submit(function( event ) {

        var built_in = [ 'link_category', 'post_format' ],
            current_taxonomy,
            singular_name,
            plural_name,
            slug,
            submit_it = true,
            alert_title = eml.l10n.tax_error_empty_fileds_title,
            alert_text = '';


        $('.wpuxss-eml-clone-taxonomy, .wpuxss-eml-taxonomy').each( function( index ) {

            current_taxonomy = $(this).attr('id');
            singular_name = $('.wpuxss-eml-singular_name',this).val();
            plural_name = $('.wpuxss-eml-name',this).val();
            slug = $('.wpuxss-eml-slug',this).val();


            // no taxonomy name
            if ( ! current_taxonomy ) {
                submit_it = false;
                alert_text = '<p>' + eml.l10n.tax_error_empty_taxname + '</p><p>' + eml.l10n.tax_error_wrong_taxname + '</p>';
            }
            // if no slug just make it same as taxname
            else if ( ! slug ) {
                $('.wpuxss-eml-slug',this).val( current_taxonomy );
            }
            // no singular and plural names
            else if ( ! singular_name && ! plural_name ) {
                submit_it = false;
                alert_text = eml.l10n.tax_error_empty_both;
            }
            // no singular name
            else if ( ! singular_name ) {
                submit_it = false;
                alert_text = eml.l10n.tax_error_empty_singular;
            }
            // no plural name
            else if ( ! plural_name ) {
                submit_it = false;
                alert_text = eml.l10n.tax_error_empty_plural;
            }
            // duplicates existing taxonomy
            else if ( $('.wpuxss-eml-clone-taxonomy[id='+current_taxonomy+'], .wpuxss-eml-taxonomy[id='+current_taxonomy+'], .wpuxss-non-eml-taxonomy[id='+current_taxonomy+']').length > 1 || -1 !== $.inArray( current_taxonomy, built_in ) ) {
                submit_it = false;
                alert_title = eml.l10n.tax_error_duplicate_title;
                alert_text = eml.l10n.tax_error_duplicate_text;
            }
        });


        if ( ! submit_it ) {
            emlAlertDialog( alert_title, alert_text, eml.l10n.okay, 'button button-primary' )
            .done( function() {
                $('.wpuxss-eml-clone-taxonomy, .wpuxss-eml-taxonomy-name').focus();
                return false;
            });
        }

        return submit_it;
    });



    function validateTaxname( taxname ) {

        var re = new RegExp('^(?=.{3,32}$)[a-z][a-z0-9]*(?:_[a-z0-9]+)*$');


        if ( re.test( taxname ) )
            return true;

        return false;
    }



    function validateSlug( slug ) {

        var re = new RegExp('^[a-z][a-z0-9]*(?:[_\-][a-z0-9]+)*$');


        if ( re.test( slug ) )
            return true;

        return false;
    }



    function generateTaxname( label ) {

        var dictionary,
            taxonomy_name = label.toLowerCase().replace(/[^a-z0-9_\s]/g, '');


        // thanks to
        // https://github.com/borodean/jquery-translit
        // https://gist.github.com/richardsweeney/5317392
        // http://www.advancedcustomfields.com/
        // for the 'dictionary' code!
        dictionary = {
            'а': 'a',
            'б': 'b',
            'в': 'v',
            'г': 'g',
            'ґ': 'g',
            'д': 'd',
            'е': 'e',
            'є': 'ie',
            'ж': 'zh',
            'з': 'z',
            'и': 'y',
            'і': 'i',
            'ї': 'i',
            'й': 'i',
            'к': 'k',
            'л': 'l',
            'м': 'm',
            'н': 'n',
            'о': 'o',
            'п': 'p',
            'р': 'r',
            'с': 's',
            'т': 't',
            'у': 'u',
            'ф': 'f',
            'х': 'kh',
            'ц': 'tc',
            'ч': 'ch',
            'ш': 'sh',
            'щ': 'shch',
            'ь': '',
            'ю': 'iu',
            'я': 'ia',

            'ё': 'e',
            'ы': 'y',
            'ъ': '',
            'э': 'e',

            'ä': 'a',
            'æ': 'a',
            'å': 'a',
            'ö': 'o',
            'ø': 'o',
            'é': 'e',
            'ë': 'e',
            'ü': 'u',
            'ó': 'o',
            'ő': 'o',
            'ú': 'u',
            'é': 'e',
            'á': 'a',
            'ű': 'u',
            'í': 'i',
            ' ' : '_',
            '-' : '_',
            '\'' : '',
            '&' : '_'
        };

        $.each( dictionary, function(k, v) {

            var regex = new RegExp( k, 'g' );
            taxonomy_name = taxonomy_name.replace( regex, v );
        });

        return taxonomy_name;
    }



    function setTaxonomyID( id, taxonomy_clone_box ) {

        var built_in = [ 'link_category', 'post_format' ];


        taxonomy_clone_box.attr( 'id', id );

        if ( '' !== id && $('.wpuxss-eml-clone-taxonomy[id='+id+'], .wpuxss-eml-taxonomy[id='+id+'], .wpuxss-non-eml-taxonomy[id='+id+']').length > 1 || -1 !== $.inArray( id, built_in ) ) {

            taxonomy_clone_box.attr( 'id', '' );

            emlAlertDialog( eml.l10n.tax_error_duplicate_title, eml.l10n.tax_error_duplicate_text, eml.l10n.okay, 'button button-primary' )
            .done( function() {
                return false;
            });
        }
    }



    function setTaxonomyName( taxonomy_name, taxonomy_clone_box ) {

        var fields = {
                assigned : '.wpuxss-eml-assigned',
                eml_media : '.wpuxss-eml-eml_media',
                create_taxonomy : '.wpuxss-eml-create_taxonomy'
            },
            tax_fields = {
                labels : [ 'singular_name', 'name', 'menu_name', 'all_items', 'edit_item', 'view_item', 'update_item', 'add_new_item', 'new_item_name', 'parent_item', 'search_items' ],
                hierarchical : 'hierarchical',
                show_admin_column : 'show_admin_column',
                admin_filter : 'admin_filter',
                media_uploader_filter : 'media_uploader_filter',
                media_popup_taxonomy_edit : 'media_popup_taxonomy_edit',
                show_in_nav_menus : 'show_in_nav_menus',
                sort : 'sort',
                show_in_rest : 'show_in_rest',
                rewrite : [ 'slug', 'with_front' ]
            };


        $.each( fields, function( key, field ) {
            taxonomy_clone_box.find(field).attr('name','wpuxss_eml_taxonomies['+taxonomy_name+']['+key+']');
        });


        $.each( tax_fields, function( key, field ) {

            if ( key === field ) {
                taxonomy_clone_box.find('.wpuxss-eml-'+field).attr('name','wpuxss_eml_taxonomies['+taxonomy_name+']['+field+']');
            }
            else {
                $.each( field, function( i, field ) {
                    taxonomy_clone_box.find('.wpuxss-eml-'+field).attr('name','wpuxss_eml_taxonomies['+taxonomy_name+']['+key+']['+field+']');
                });
            }
        });
    }



    function setSingularLabels( singular_name, edit_box ) {

        var fields = {
                edit : '.wpuxss-eml-edit_item',
                view : '.wpuxss-eml-view_item',
                update : '.wpuxss-eml-update_item',
                add_new : '.wpuxss-eml-add_new_item',
                new : '.wpuxss-eml-new_item_name',
                parent : '.wpuxss-eml-parent_item'
            };

        if ( '' !== singular_name ) {

            $.each( fields, function( key, field ){
                edit_box.find(field).val( eml.l10n[key]+' '+singular_name );
            });

        }
        else {

            $.each( fields, function( key, field ){
                edit_box.find(field).val( '' );
            });
        }
    }



    function setPluralLabels( plural_name, edit_box, main_tax_label ) {

        var fields = {
                all : '.wpuxss-eml-all_items',
                search : '.wpuxss-eml-search_items'
            };

        if ( '' !== plural_name ) {

            edit_box.find('.wpuxss-eml-menu_name').val( plural_name );
            main_tax_label.text( plural_name );

            $.each( fields, function( key, field ){
                edit_box.find(field).val( eml.l10n[key]+' '+plural_name );
            });
        }
        else {

            edit_box.find('.wpuxss-eml-menu_name').val( '' );
            main_tax_label.text( eml.l10n.tax_new );

            $.each( fields, function( key, field ){
                edit_box.find(field).val( '' );
            });
        }
    }



    // synchronize parent terms to media items (PRO)
    $( document ).on( 'click', '.eml-button-synchronize-terms', function( event ) {

        var $el, post_type, taxonomy;


        emlConfirmDialog( eml.l10n.sync_warning_title, eml.l10n.sync_warning_text, eml.l10n.sync_warning_yes, eml.l10n.sync_warning_no, 'button button-primary' )
        .done( function() {

            $el = $( event.target );

            post_type = $el.attr( 'data-post-type' );
            taxonomy = $el.attr( 'data-taxonomy' );

            emlFullscreenSpinnerStart( eml.l10n.in_progress_sync_text );

            $.post( ajaxurl, {
                nonce: eml.l10n.bulk_edit_nonce,
                action: 'eml-synchronize-terms',
                post_type: post_type,
                taxonomy: taxonomy
            },function( response ) {
                emlFullscreenSpinnerStop();
    		});
        })
        .fail(function() {
            return;
        });
    });

})( jQuery, _ );
