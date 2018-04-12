/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Declare vars
	var api = wp.customize;

	api( 'osf_opening_icon', function( value ) {
		value.bind( function( newval ) {
			var $icon = $( '#footer-bar .osf-left li.osf-btn a > span' );

			if ( $icon.length ) {
				$icon.removeClass();
				$icon.addClass( newval );
			}
		} );
	} );

	api( 'osf_footer_opacity', function( value ) {
		value.bind( function( to ) {
			$( '.osf-footer .site-footer' ).css( 'opacity', to );
		} );
	} );

	api( 'osf_text', function( value ) {
		value.bind( function( newval ) {
			$( '#footer-bar .osf-text' ).text( newval );
		} );
	} );

	api( 'osf_footer_bar_background', function( value ) {
		value.bind( function( to ) {
			$( '#footer-bar' ).css( 'background-color', to );
		} );
	} );

	api( 'osf_opening_btn_background', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_opening_btn_background' );
			if ( to ) {
				var style = '<style class="customizer-osf_opening_btn_background">#footer-bar .osf-left li.osf-btn a{background-color: ' + to + ';}</style>';
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

	api( 'osf_opening_btn_hover_background', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_opening_btn_hover_background' );
			if ( to ) {
				var style = '<style class="customizer-osf_opening_btn_hover_background">#footer-bar .osf-left li.osf-btn a:hover{background-color: ' + to + ';}</style>';
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

	api( 'osf_opening_btn_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_opening_btn_color' );
			if ( to ) {
				var style = '<style class="customizer-osf_opening_btn_color">#footer-bar .osf-left li.osf-btn a{color: ' + to + ';}</style>';
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

	api( 'osf_opening_btn_hover_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_opening_btn_hover_color' );
			if ( to ) {
				var style = '<style class="customizer-osf_opening_btn_hover_color">#footer-bar .osf-left li.osf-btn a:hover{color: ' + to + ';}</style>';
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

	api( 'osf_menu_items_background', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_menu_items_background' );
			if ( to ) {
				var style = '<style class="customizer-osf_menu_items_background">#footer-bar .osf-left li.menu-item a{background-color: ' + to + ';}</style>';
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

	api( 'osf_menu_items_hover_background', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_menu_items_hover_background' );
			if ( to ) {
				var style = '<style class="customizer-osf_menu_items_hover_background">#footer-bar .osf-left li.menu-item a:hover{background-color: ' + to + ';}</style>';
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

	api( 'osf_menu_items_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_menu_items_color' );
			if ( to ) {
				var style = '<style class="customizer-osf_menu_items_color">#footer-bar .osf-left li.menu-item a{color: ' + to + ';}</style>';
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

	api( 'osf_menu_items_hover_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_menu_items_hover_color' );
			if ( to ) {
				var style = '<style class="customizer-osf_menu_items_hover_color">#footer-bar .osf-left li.menu-item a:hover{color: ' + to + ';}</style>';
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

	api( 'osf_text_color', function( value ) {
		value.bind( function( to ) {
			$( '#footer-bar .osf-text' ).css( 'color', to );
		} );
	} );

	api( 'osf_scroll_top_background', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_scroll_top_background' );
			if ( to ) {
				var style = '<style class="customizer-osf_scroll_top_background">#footer-bar .osf-right li #scroll-top{background-color: ' + to + ';}</style>';
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

	api( 'osf_scroll_top_hover_background', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_scroll_top_hover_background' );
			if ( to ) {
				var style = '<style class="customizer-osf_scroll_top_hover_background">#footer-bar .osf-right li #scroll-top:hover{background-color: ' + to + ';}</style>';
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

	api( 'osf_scroll_top_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_scroll_top_color' );
			if ( to ) {
				var style = '<style class="customizer-osf_scroll_top_color">#footer-bar .osf-right li #scroll-top{color: ' + to + ';}</style>';
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

	api( 'osf_scroll_top_hover_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osf_scroll_top_hover_color' );
			if ( to ) {
				var style = '<style class="customizer-osf_scroll_top_hover_color">#footer-bar .osf-right li #scroll-top:hover{color: ' + to + ';}</style>';
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

	// Font family
    api( 'osf_footer_bar_typo_font_family', function(value) {
        value.bind( function( to ) {
            if ( to ) {
                var idfirst     = ( to.trim().toLowerCase().replace( ' ', '-' ), 'customizer-osf_footer_bar_typo_font_family' );
                var font        = to.replace( ' ', '%20' );
                    font        = font.replace( ',', '%2C' );
                    font        = osf_sticky_footer.googleFontsUrl + '/css?family=' + to + ':' + osf_sticky_footer.googleFontsWeight;

                if ( $( '#' + idfirst ).length ) {
                    $( '#' + idfirst ).attr( 'href', font );
                } else {
                    $( 'head' ).append( '<link id="' + idfirst + '" rel="stylesheet" type="text/css" href="' + font + '">' );
                }
            }
            var $child = $( '.customizer-osf_footer_bar_typo_font_family' );
            if ( to ) {
                var style = '<style class="customizer-osf_footer_bar_typo_font_family">#footer-bar .osf-left li.menu-item a, #footer-bar .osf-text{font-family: ' + to + ';}</style>';
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

    // Font size
    api('osf_footer_bar_typo_font_size', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#footer-bar .osf-left li.menu-item a, #footer-bar .osf-text' ).css( 'font-size', newval );
            }
        });
    });

    // Font weight
    api('osf_footer_bar_typo_font_weight', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#footer-bar .osf-left li.menu-item a, #footer-bar .osf-text' ).css( 'font-weight', newval );
            }
        });
    });

    // Font style
    api('osf_footer_bar_typo_font_style', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#footer-bar .osf-left li.menu-item a, #footer-bar .osf-text' ).css( 'font-style', newval );
            }
        });
    });

    // Text transform
    api('osf_footer_bar_typo_transform', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#footer-bar .osf-left li.menu-item a, #footer-bar .osf-text' ).css( 'text-transform', newval );
            }
        });
    });

    // Line height
    api('osf_footer_bar_typo_line_height', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#footer-bar .osf-left li.menu-item a, #footer-bar .osf-text' ).css( 'line-height', newval );
            }
        });
    });

    // Letter spacing
    api('osf_footer_bar_typo_spacing', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                $( '#footer-bar .osf-left li.menu-item a, #footer-bar .osf-text' ).css( 'letter-spacing', newval );
            }
        });
    });
	
} )( jQuery );
