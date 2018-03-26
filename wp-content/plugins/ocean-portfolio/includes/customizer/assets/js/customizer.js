/**
 * Update Customizer settings live.
 *
 * @version 1.0.0
 */

( function( $ ) {

	// Declare vars
	var api                = wp.customize,
        filterPosition     = [
            'full',
            'left',
            'center',
            'right'
        ];

    // Filter Position
    api('op_portfolio_filter_position', function( value ) {
        value.bind( function( newval ) {
            var filter = $( '.portfolio-entries .portfolio-filters' );
            if ( filter.length ) {
                $.each( filterPosition, function( i, v ) {
                    filter.removeClass( 'filter-pos-' + v );
                });
                filter.addClass( 'filter-pos-' + newval );
            }
        });
    });

    // Filter bar margin
    api('op_portfolio_filter_margin', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters' ).css( 'margin', newval );
            }
        });
    });

    // Filter bar links padding
    api('op_portfolio_filter_links_padding', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters li a' ).css( 'padding', newval );
            }
        });
    });

    // Filter bar links margin
    api('op_portfolio_filter_links_margin', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters li' ).css( 'margin', newval );
            }
        });
    });

    // Filter links background
    api( 'op_portfolio_filter_links_bg', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_filter_links_bg' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_filter_links_bg">.portfolio-entries .portfolio-filters li a { background-color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Filter links color
    api( 'op_portfolio_filter_links_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_filter_links_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_filter_links_color">.portfolio-entries .portfolio-filters li a { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Filter active links background
    api( 'op_portfolio_filter_active_link_bg', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_filter_active_link_bg' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_filter_active_link_bg">.portfolio-entries .portfolio-filters li.active a { background-color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Filter active links color
    api( 'op_portfolio_filter_active_link_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_filter_active_link_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_filter_active_link_color">.portfolio-entries .portfolio-filters li.active a { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Filter hover links background
    api( 'op_portfolio_filter_hover_links_bg', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_filter_hover_links_bg' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_filter_hover_links_bg">.portfolio-entries .portfolio-filters li a:hover { background-color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Filter hover links color
    api( 'op_portfolio_filter_hover_links_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_filter_hover_links_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_filter_hover_links_color">.portfolio-entries .portfolio-filters li a:hover { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Overlay color
    api( 'op_portfolio_img_overlay_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_img_overlay_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_img_overlay_color">.portfolio-entries .portfolio-entry-thumbnail .overlay { background-color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Overlay icons width
    api('op_portfolio_img_overlay_icons_width', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a' ).css( 'width', newval );
            }
        });
    });

    // Overlay icons height
    api('op_portfolio_img_overlay_icons_height', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a' ).css( 'height', newval );
            }
        });
    });

    // Overlay icons size
    api('op_portfolio_img_overlay_icons_size', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a' ).css( 'font-size', newval + 'px' );
            }
        });
    });

    // Overlay icons background
    api( 'op_portfolio_img_overlay_icons_bg', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_img_overlay_icons_bg' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_img_overlay_icons_bg">.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a { background-color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Overlay icons hover background
    api( 'op_portfolio_img_overlay_icons_hover_bg', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_img_overlay_icons_hover_bg' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_img_overlay_icons_hover_bg">.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a:hover { background-color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Overlay icons color
    api( 'op_portfolio_img_overlay_icons_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_img_overlay_icons_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_img_overlay_icons_color">.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Overlay icons hover color
    api( 'op_portfolio_img_overlay_icons_hover_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_img_overlay_icons_hover_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_img_overlay_icons_hover_color">.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a:hover { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Overlay icons border radius
    api('op_portfolio_img_overlay_icons_border_radius', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a' ).css( 'border-radius', newval );
            }
        });
    });

    // Overlay icons border width
    api('op_portfolio_img_overlay_icons_border_width', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a' ).css( 'border-width', newval );
            }
        });
    });

    // Overlay icons border style
    api('op_portfolio_img_overlay_icons_border_style', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a' ).css( 'border-style', newval );
            }
        });
    });

    // Overlay icons border color
    api( 'op_portfolio_img_overlay_icons_border_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_img_overlay_icons_border_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_img_overlay_icons_border_color">.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a { border-color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Overlay icons hover border color
    api( 'op_portfolio_img_overlay_icons_hover_border_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_img_overlay_icons_hover_border_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_img_overlay_icons_hover_border_color">.portfolio-entries .portfolio-entry-thumbnail .portfolio-overlay-icons li a:hover { border-color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Item margin
    api('op_portfolio_item_margin', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries' ).css( 'margin', '0 -' + newval );
                $( '.portfolio-entries .portfolio-entry' ).css( 'padding', newval );
            }
        });
    });

    // Item padding
    api('op_portfolio_item_padding', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry .portfolio-entry-inner' ).css( 'padding', newval );
            }
        });
    });

    // Item border radius
    api('op_portfolio_item_border_radius', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry .portfolio-entry-inner' ).css( 'overflow', 'hidden' );
                $( '.portfolio-entries .portfolio-entry .portfolio-entry-inner' ).css( 'border-radius', newval );
            }
        });
    });

    // Item border width
    api('op_portfolio_item_border_width', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry .portfolio-entry-inner' ).css( 'border-width', newval );
            }
        });
    });

    // Item border style
    api('op_portfolio_item_border_style', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry .portfolio-entry-inner' ).css( 'border-style', newval );
            }
        });
    });

    // Item border color
    api('op_portfolio_item_border_color', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry .portfolio-entry-inner' ).css( 'border-color', newval );
            }
        });
    });

    // Item background color
    api('op_portfolio_item_bg', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry .portfolio-entry-inner' ).css( 'background-color', newval );
            }
        });
    });

    // Item outside content padding
    api('op_portfolio_outside_content_padding', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-content' ).css( 'padding', newval );
            }
        });
    });

    // Item outside content padding
    api('op_portfolio_outside_content_bg', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-thumbnail .triangle-wrap' ).css( 'border-bottom-color', newval );
                $( '.portfolio-entries .portfolio-content' ).css( 'background-color', newval );
            }
        });
    });

    // Title color
    api( 'op_portfolio_title_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_title_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_title_color">.portfolio-entries .portfolio-entry-title a, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .portfolio-entry-title a { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Hover title color
    api( 'op_portfolio_title_hover_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_title_hover_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_title_hover_color">.portfolio-entries .portfolio-entry-title a:hover, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .portfolio-entry-title a:hover { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Category color
    api( 'op_portfolio_category_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_category_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_category_color">.portfolio-entries .categories, .portfolio-entries .categories a, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories a { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Hover category color
    api( 'op_portfolio_category_hover_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_category_hover_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_category_hover_color">.portfolio-entries .categories a:hover, .portfolio-entries .portfolio-entry-thumbnail .portfolio-inside-content .categories a:hover { color: ' + to + '; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Filter font family
    api( 'op_portfolio_filter_typo_font_family', function(value) {
        value.bind( function( to ) {
            if ( to ) {
                var idfirst     = ( to.trim().toLowerCase().replace( ' ', '-' ), 'customizer-op_portfolio_filter_typo_font_family' );
                var font        = to.replace( ' ', '%20' );
                    font        = font.replace( ',', '%2C' );
                    font        = op_portfolio.googleFontsUrl + '/css?family=' + to + ':' + op_portfolio.googleFontsWeight;

                if ( $( '#' + idfirst ).length ) {
                    $( '#' + idfirst ).attr( 'href', font );
                } else {
                    $( 'head' ).append( '<link id="' + idfirst + '" rel="stylesheet" type="text/css" href="' + font + '">' );
                }
            }
            var $child = $( '.customizer-op_portfolio_filter_typo_font_family' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_filter_typo_font_family">.portfolio-entries .portfolio-filters li a{font-family: ' + to + ';}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Filter font size
    api('op_portfolio_filter_typo_font_size', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters li a' ).css( 'font-size', newval );
            }
        });
    });

    // Filter font weight
    api('op_portfolio_filter_typo_font_weight', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters li a' ).css( 'font-weight', newval );
            }
        });
    });

    // Filter font style
    api('op_portfolio_filter_typo_font_style', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters li a' ).css( 'font-style', newval );
            }
        });
    });

    // Filter text transform
    api('op_portfolio_filter_typo_transform', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters li a' ).css( 'text-transform', newval );
            }
        });
    });

    // Filter line height
    api('op_portfolio_filter_typo_line_height', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters li a' ).css( 'line-height', newval );
            }
        });
    });

    // Filter letter spacing
    api('op_portfolio_filter_typo_spacing', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-filters li a' ).css( 'letter-spacing', newval );
            }
        });
    });

    // Title font family
    api( 'op_portfolio_title_typo_font_family', function(value) {
        value.bind( function( to ) {
            if ( to ) {
                var idfirst     = ( to.trim().toLowerCase().replace( ' ', '-' ), 'customizer-op_portfolio_title_typo_font_family' );
                var font        = to.replace( ' ', '%20' );
                    font        = font.replace( ',', '%2C' );
                    font        = op_portfolio.googleFontsUrl + '/css?family=' + to + ':' + op_portfolio.googleFontsWeight;

                if ( $( '#' + idfirst ).length ) {
                    $( '#' + idfirst ).attr( 'href', font );
                } else {
                    $( 'head' ).append( '<link id="' + idfirst + '" rel="stylesheet" type="text/css" href="' + font + '">' );
                }
            }
            var $child = $( '.customizer-op_portfolio_title_typo_font_family' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_title_typo_font_family">.portfolio-entries .portfolio-entry-title{font-family: ' + to + ';}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Title font size
    api('op_portfolio_title_typo_font_size', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-title' ).css( 'font-size', newval );
            }
        });
    });

    // Title font weight
    api('op_portfolio_title_typo_font_weight', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-title' ).css( 'font-weight', newval );
            }
        });
    });

    // Title font style
    api('op_portfolio_title_typo_font_style', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-title' ).css( 'font-style', newval );
            }
        });
    });

    // Title text transform
    api('op_portfolio_title_typo_transform', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-title' ).css( 'text-transform', newval );
            }
        });
    });

    // Title line height
    api('op_portfolio_title_typo_line_height', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-title' ).css( 'line-height', newval );
            }
        });
    });

    // Title letter spacing
    api('op_portfolio_title_typo_spacing', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .portfolio-entry-title' ).css( 'letter-spacing', newval );
            }
        });
    });

    // Category font family
    api( 'op_portfolio_category_typo_font_family', function(value) {
        value.bind( function( to ) {
            if ( to ) {
                var idfirst     = ( to.trim().toLowerCase().replace( ' ', '-' ), 'customizer-op_portfolio_category_typo_font_family' );
                var font        = to.replace( ' ', '%20' );
                    font        = font.replace( ',', '%2C' );
                    font        = op_portfolio.googleFontsUrl + '/css?family=' + to + ':' + op_portfolio.googleFontsWeight;

                if ( $( '#' + idfirst ).length ) {
                    $( '#' + idfirst ).attr( 'href', font );
                } else {
                    $( 'head' ).append( '<link id="' + idfirst + '" rel="stylesheet" type="text/css" href="' + font + '">' );
                }
            }
            var $child = $( '.customizer-op_portfolio_category_typo_font_family' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_category_typo_font_family">.portfolio-entries .categories{font-family: ' + to + ';}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Category font size
    api('op_portfolio_category_typo_font_size', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .categories' ).css( 'font-size', newval );
            }
        });
    });

    // Category font weight
    api('op_portfolio_category_typo_font_weight', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .categories' ).css( 'font-weight', newval );
            }
        });
    });

    // Category font style
    api('op_portfolio_category_typo_font_style', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .categories' ).css( 'font-style', newval );
            }
        });
    });

    // Category text transform
    api('op_portfolio_category_typo_transform', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .categories' ).css( 'text-transform', newval );
            }
        });
    });

    // Category line height
    api('op_portfolio_category_typo_line_height', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .categories' ).css( 'line-height', newval );
            }
        });
    });

    // Category letter spacing
    api('op_portfolio_category_typo_spacing', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '.portfolio-entries .categories' ).css( 'letter-spacing', newval );
            }
        });
    });

    // Tablet item margin
    api( 'op_portfolio_tablet_item_margin', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_item_margin' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_item_margin">@media (max-width: 1023px) {.portfolio-entries { margin: 0 -' + to + '; }.portfolio-entries .portfolio-entry { padding: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet item padding
    api( 'op_portfolio_tablet_item_padding', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_item_padding' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_item_padding">@media (max-width: 1023px) {.portfolio-entries .portfolio-entry { padding: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet item border radius
    api( 'op_portfolio_tablet_item_border_radius', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_item_border_radius' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_item_border_radius">@media (max-width: 1023px) {.portfolio-entries .portfolio-entry .portfolio-entry-inner { overflow: hidden; }.portfolio-entries .portfolio-entry .portfolio-entry-inner { border-radius: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet item border width
    api( 'op_portfolio_tablet_item_border_width', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_item_border_width' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_item_border_width">@media (max-width: 1023px) {.portfolio-entries .portfolio-entry .portfolio-entry-inner { border-width: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet filter font size
    api( 'op_portfolio_tablet_filter_typo_font_size', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_filter_typo_font_size' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_filter_typo_font_size">@media (max-width: 1023px) {.portfolio-entries .portfolio-filters li a { font-size: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet filter text transform
    api( 'op_portfolio_tablet_filter_typo_transform', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_filter_typo_transform' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_filter_typo_transform">@media (max-width: 1023px) {.portfolio-entries .portfolio-filters li a { text-transform: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet filter line height
    api( 'op_portfolio_tablet_filter_typo_line_height', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_filter_typo_line_height' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_filter_typo_line_height">@media (max-width: 1023px) {.portfolio-entries .portfolio-filters li a { line-height: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet filter letter spacing
    api( 'op_portfolio_tablet_filter_typo_spacing', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_filter_typo_spacing' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_filter_typo_spacing">@media (max-width: 1023px) {.portfolio-entries .portfolio-filters li a { letter-spacing: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet title font size
    api( 'op_portfolio_tablet_title_typo_font_size', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_title_typo_font_size' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_title_typo_font_size">@media (max-width: 1023px) {.portfolio-entries .portfolio-entry-title { font-size: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet title text transform
    api( 'op_portfolio_tablet_title_typo_transform', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_title_typo_transform' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_title_typo_transform">@media (max-width: 1023px) {.portfolio-entries .portfolio-entry-title { text-transform: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet title line height
    api( 'op_portfolio_tablet_title_typo_line_height', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_title_typo_line_height' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_title_typo_line_height">@media (max-width: 1023px) {.portfolio-entries .portfolio-entry-title { line-height: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet title letter spacing
    api( 'op_portfolio_tablet_title_typo_spacing', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_title_typo_spacing' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_title_typo_spacing">@media (max-width: 1023px) {.portfolio-entries .portfolio-entry-title { letter-spacing: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet category font size
    api( 'op_portfolio_tablet_category_typo_font_size', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_category_typo_font_size' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_category_typo_font_size">@media (max-width: 1023px) {.portfolio-entries .categories { font-size: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet category text transform
    api( 'op_portfolio_tablet_category_typo_transform', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_category_typo_transform' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_category_typo_transform">@media (max-width: 1023px) {.portfolio-entries .categories { text-transform: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet category line height
    api( 'op_portfolio_tablet_category_typo_line_height', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_category_typo_line_height' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_category_typo_line_height">@media (max-width: 1023px) {.portfolio-entries .categories { line-height: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Tablet category letter spacing
    api( 'op_portfolio_tablet_category_typo_spacing', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_tablet_category_typo_spacing' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_tablet_category_typo_spacing">@media (max-width: 1023px) {.portfolio-entries .categories { letter-spacing: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile item margin
    api( 'op_portfolio_mobile_item_margin', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_item_margin' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_item_margin">@media (max-width: 767px) {.portfolio-entries { margin: 0 -' + to + '; }.portfolio-entries .portfolio-entry { padding: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile item padding
    api( 'op_portfolio_mobile_item_padding', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_item_padding' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_item_padding">@media (max-width: 767px) {.portfolio-entries .portfolio-entry { padding: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile item border radius
    api( 'op_portfolio_mobile_item_border_radius', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_item_border_radius' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_item_border_radius">@media (max-width: 767px) {.portfolio-entries .portfolio-entry .portfolio-entry-inner { overflow: hidden; }.portfolio-entries .portfolio-entry .portfolio-entry-inner { border-radius: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile item border width
    api( 'op_portfolio_mobile_item_border_width', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_item_border_width' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_item_border_width">@media (max-width: 767px) {.portfolio-entries .portfolio-entry .portfolio-entry-inner { border-width: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile filter font size
    api( 'op_portfolio_mobile_filter_typo_font_size', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_filter_typo_font_size' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_filter_typo_font_size">@media (max-width: 767px) {.portfolio-entries .portfolio-filters li a { font-size: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile filter text transform
    api( 'op_portfolio_mobile_filter_typo_transform', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_filter_typo_transform' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_filter_typo_transform">@media (max-width: 767px) {.portfolio-entries .portfolio-filters li a { text-transform: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile filter line height
    api( 'op_portfolio_mobile_filter_typo_line_height', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_filter_typo_line_height' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_filter_typo_line_height">@media (max-width: 767px) {.portfolio-entries .portfolio-filters li a { line-height: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile filter letter spacing
    api( 'op_portfolio_mobile_filter_typo_spacing', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_filter_typo_spacing' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_filter_typo_spacing">@media (max-width: 767px) {.portfolio-entries .portfolio-filters li a { letter-spacing: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile title font size
    api( 'op_portfolio_mobile_title_typo_font_size', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_title_typo_font_size' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_title_typo_font_size">@media (max-width: 767px) {.portfolio-entries .portfolio-entry-title { font-size: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile title text transform
    api( 'op_portfolio_mobile_title_typo_transform', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_title_typo_transform' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_title_typo_transform">@media (max-width: 767px) {.portfolio-entries .portfolio-entry-title { text-transform: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile title line height
    api( 'op_portfolio_mobile_title_typo_line_height', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_title_typo_line_height' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_title_typo_line_height">@media (max-width: 767px) {.portfolio-entries .portfolio-entry-title { line-height: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile title letter spacing
    api( 'op_portfolio_mobile_title_typo_spacing', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_title_typo_spacing' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_title_typo_spacing">@media (max-width: 767px) {.portfolio-entries .portfolio-entry-title { letter-spacing: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile category font size
    api( 'op_portfolio_mobile_category_typo_font_size', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_category_typo_font_size' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_category_typo_font_size">@media (max-width: 767px) {.portfolio-entries .categories { font-size: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile category text transform
    api( 'op_portfolio_mobile_category_typo_transform', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_category_typo_transform' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_category_typo_transform">@media (max-width: 767px) {.portfolio-entries .categories { text-transform: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile category line height
    api( 'op_portfolio_mobile_category_typo_line_height', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_category_typo_line_height' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_category_typo_line_height">@media (max-width: 767px) {.portfolio-entries .categories { line-height: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Mobile category letter spacing
    api( 'op_portfolio_mobile_category_typo_spacing', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_mobile_category_typo_spacing' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_mobile_category_typo_spacing">@media (max-width: 767px) {.portfolio-entries .categories { letter-spacing: ' + to + '; }}</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Page header image positon
	api('op_portfolio_single_title_bg_image_position', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
            	$( '.single-ocean_portfolio .page-header' ).css( 'background-position', newval );
            }
            if ( 'initial' == newval ) {
            	$( '.single-ocean_portfolio .page-header' ).css( 'background-position', 'top center' );
            }
        });
    });

    // Page header image attachment
    api('op_portfolio_single_title_bg_image_attachment', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
            	$( '.single-ocean_portfolio .page-header' ).css( 'background-attachment', newval );
            }
        });
    });

    // Page header image repeat
    api('op_portfolio_single_title_bg_image_repeat', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
            	$( '.single-ocean_portfolio .page-header' ).css( 'background-repeat', newval );
            }
            if ( 'initial' == newval ) {
            	$( '.single-ocean_portfolio .page-header' ).css( 'background-repeat', 'no-repeat' );
            }
        });
    });

    // Page header image size
    api('op_portfolio_single_title_bg_image_size', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
            	$( '.single-ocean_portfolio .page-header' ).css( 'background-size', newval );
            }
            if ( 'initial' == newval ) {
            	$( '.single-ocean_portfolio .page-header' ).css( 'background-size', 'cover' );
            }
        });
    });

    // Page header height
    api( 'op_portfolio_single_title_bg_image_height', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_single_title_bg_image_height' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_single_title_bg_image_height">.single-ocean_portfolio .page-header{height: ' + to + 'px; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Page header height
    api( 'op_portfolio_single_title_bg_image_height', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_single_title_bg_image_height' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_single_title_bg_image_height">.single-ocean_portfolio .page-header{height: ' + to + 'px; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Page header overlay
    api( 'op_portfolio_single_title_bg_image_overlay_opacity', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_single_title_bg_image_overlay_opacity' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_single_title_bg_image_overlay_opacity">.single-ocean_portfolio .background-image-page-header-overlay { opacity: ' + to + '!important; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Page header overlay color
    api( 'op_portfolio_single_title_bg_image_overlay_color', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_single_title_bg_image_overlay_color' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_single_title_bg_image_overlay_color">.single-ocean_portfolio .background-image-page-header-overlay { background-color: ' + to + '!important; }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        });
    });

    // Both Sidebars layout single item content width
    api( 'op_portfolio_single_both_sidebars_content_width', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_single_both_sidebars_content_width' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_single_both_sidebars_content_width">@media only screen and (min-width: 960px){ body.single-ocean_portfolio.content-both-sidebars .content-area { width: ' + to + '%; } body.single-ocean_portfolio.content-both-sidebars.scs-style .widget-area.sidebar-secondary, body.single-ocean_portfolio.content-both-sidebars.ssc-style .widget-area {left: -' + to + '%;} }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        } );
    } );

    // Both Sidebars layout single item sidebars width
    api( 'op_portfolio_single_both_sidebars_sidebars_width', function( value ) {
        value.bind( function( to ) {
            var $child = $( '.customizer-op_portfolio_single_both_sidebars_sidebars_width' );
            if ( to ) {
                var style = '<style class="customizer-op_portfolio_single_both_sidebars_sidebars_width">@media only screen and (min-width: 960px){ body.single-ocean_portfolio.content-both-sidebars .widget-area{width:' + to + '%;} body.single-ocean_portfolio.content-both-sidebars.scs-style .content-area{left:' + to + '%;} body.single-ocean_portfolio.content-both-sidebars.ssc-style .content-area{left:'+ to * 2 +'%;} }</style>';
                if ( $child.length ) {
                    $child.replaceWith( style );
                } else {
                    $( 'head' ).append( style );
                }
            } else {
                $child.remove();
            }
        } );
    } );

} )( jQuery );