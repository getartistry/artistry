/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Declare vars
	var api = wp.customize;

	api('osh_no_shadow', function( value ) {
		value.bind( function( newval ) {
			if ( newval ) {
				$( '.is-sticky #site-header' ).addClass( 'no-shadow' );
			} else {
				$( '.is-sticky #site-header' ).removeClass( 'no-shadow' );
			}
		});
	});

	api( 'osh_shrink_header_height', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_shrink_header_height' );
			if ( to ) {
				var style = '<style class="customizer-osh_shrink_header_height">.is-sticky #site-header.shrink-header #site-logo #site-logo-inner, .is-sticky #site-header.shrink-header #oceanwp-social-menu .social-menu-inner, .is-sticky #site-header.shrink-header.full_screen-header .menu-bar-inner { height: ' + to + 'px; }.is-sticky #site-header.shrink-header #site-navigation-wrap .dropdown-menu > li > a, .is-sticky #site-header.shrink-header #oceanwp-mobile-menu-icon a{line-height: ' + to + 'px;}</style>';
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

	api( 'osh_header_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_top_padding">body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-top: ' + to + 'px; }</style>';
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

	api( 'osh_header_right_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_right_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_right_padding">body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-right: ' + to + 'px; }</style>';
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

	api( 'osh_header_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_bottom_padding">body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-bottom: ' + to + 'px; }</style>';
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

	api( 'osh_header_left_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_left_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_left_padding">body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-left: ' + to + 'px; }</style>';
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

	api( 'osh_header_tablet_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_tablet_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_tablet_top_padding">@media (max-width: 768px){body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-top: ' + to + 'px; }}</style>';
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

	api( 'osh_header_tablet_right_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_tablet_right_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_tablet_right_padding">@media (max-width: 768px){body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-right: ' + to + 'px; }}</style>';
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

	api( 'osh_header_tablet_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_tablet_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_tablet_bottom_padding">@media (max-width: 768px){body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-bottom: ' + to + 'px; }}</style>';
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

	api( 'osh_header_tablet_left_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_tablet_left_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_tablet_left_padding">@media (max-width: 768px){body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-left: ' + to + 'px; }}</style>';
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

	api( 'osh_header_mobile_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_mobile_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_mobile_top_padding">@media (max-width: 480px){body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-top: ' + to + 'px; }}</style>';
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

	api( 'osh_header_mobile_right_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_mobile_right_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_mobile_right_padding">@media (max-width: 480px){body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-right: ' + to + 'px; }}</style>';
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

	api( 'osh_header_mobile_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_mobile_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_mobile_bottom_padding">@media (max-width: 480px){body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-bottom: ' + to + 'px; }}</style>';
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

	api( 'osh_header_mobile_left_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_header_mobile_left_padding' );
			if ( to ) {
				var style = '<style class="customizer-osh_header_mobile_left_padding">@media (max-width: 480px){body .is-sticky #site-header.fixed-scroll #site-header-inner { padding-left: ' + to + 'px; }}</style>';
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

	api( 'osh_fixed_header_height', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_fixed_header_height' );
			if ( to ) {
				var style = '<style class="customizer-osh_fixed_header_height">.is-sticky #site-header.oceanwp-fixed-sticky-header #site-logo #site-logo-inner,.is-sticky #site-header.oceanwp-fixed-sticky-header #oceanwp-social-menu .social-menu-inner { height: ' + to + 'px; }.is-sticky #site-header.oceanwp-fixed-sticky-header #site-navigation-wrap .dropdown-menu > li > a,.is-sticky #site-header.oceanwp-fixed-sticky-header #oceanwp-mobile-menu-icon a{line-height: ' + to + 'px;}</style>';
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

	api( 'osh_sticky_header_opacity', function( value ) {
		value.bind( function( to ) {
			$( '.is-sticky #site-header,.oceanwp-sticky-top-bar-holder.is-sticky #top-bar-wrap,.is-sticky .header-top' ).css( 'opacity', to );
		} );
	} );

	api( 'osh_shrink_header_logo_height', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_shrink_header_logo_height' );
			if ( to ) {
				var style = '<style class="customizer-osh_shrink_header_logo_height">.is-sticky .shrink-header #site-logo #site-logo-inner a img, .is-sticky #site-header.shrink-header.center-header #site-navigation .middle-site-logo a img{max-height: ' + to + 'px !important;}</style>';
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

	api( 'osh_background_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_background_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_background_color">.is-sticky #site-header,.is-sticky #searchform-header-replace{background-color: ' + to + '!important;}</style>';
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

	api( 'osh_links_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_links_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_links_color">.is-sticky #site-navigation-wrap .dropdown-menu > li > a,.is-sticky #oceanwp-mobile-menu-icon a,.is-sticky #searchform-header-replace-close{color: ' + to + ';}</style>';
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

	api( 'osh_links_hover_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_links_hover_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_links_hover_color">.is-sticky #site-navigation-wrap .dropdown-menu > li > a:hover,.is-sticky #oceanwp-mobile-menu-icon a:hover,.is-sticky #searchform-header-replace-close:hover{color: ' + to + ';}</style>';
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

	api( 'osh_links_active_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_links_active_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_links_active_color">.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-item > a > span,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-parent > a > span,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-item > a:hover > span,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-parent > a:hover > span{color: ' + to + ';}</style>';
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

	api( 'osh_links_bg_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_links_bg_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_links_bg_color">.is-sticky #site-navigation-wrap .dropdown-menu > li > a{background-color: ' + to + ';}</style>';
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

	api( 'osh_links_hover_bg_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_links_hover_bg_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_links_hover_bg_color">.is-sticky #site-navigation-wrap .dropdown-menu > li > a:hover,.is-sticky #site-navigation-wrap .dropdown-menu > li.sfHover > a{background-color: ' + to + ';}</style>';
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

	api( 'osh_links_active_bg_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_links_active_bg_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_links_active_bg_color">.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-item > a > span,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-parent > a > span,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-item > a:hover > span,.is-sticky #site-navigation-wrap .dropdown-menu > .current-menu-parent > a:hover > span{background-color: ' + to + ';}</style>';
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

	api( 'osh_menu_social_links_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_menu_social_links_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_menu_social_links_color">.is-sticky #oceanwp-social-menu ul li a,.is-sticky #site-header.full_screen-header #oceanwp-social-menu.simple-social ul li a{color: ' + to + ';}</style>';
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

	api( 'osh_menu_social_hover_links_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-osh_menu_social_hover_links_color' );
			if ( to ) {
				var style = '<style class="customizer-osh_menu_social_hover_links_color">.is-sticky #oceanwp-social-menu ul li a:hover,.is-sticky #site-header.full_screen-header #oceanwp-social-menu.simple-social ul li a:hover{color: ' + to + ';}</style>';
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
