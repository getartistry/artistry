// Sticky header
var $j 					= jQuery.noConflict(),
	$window 			= $j( window ),
	$windowTop 			= $window.scrollTop(),
	$previousScroll 	= 0;

// On page load
$window.on( 'load', function() {
	// Wrap top bar height
	oceanWrapTopBarHeight();
	// Wrap header
	oceanWrapHeight();
	// Logo height for sticky shrink style
	oceanLogoHeight();
	// Vertical header transparent
	oceanAddVerticalHeaderSticky();
} );

// On scroll
$window.scroll( function() {

    if ( $window.scrollTop() != $windowTop ) {
        $windowTop = $window.scrollTop();

    	// Sticky top bar
    	oceanStickyTopBar();

    	// Sticky header
    	oceanAddSticky();

    	// Sticky effects
    	oceanStickyEffects();

    	// Vertical header transparent
    	oceanAddVerticalHeaderSticky();
    }

} );

// On resize
$window.resize( function() {

	/**
	 * Update sticky top bar
	 */
	oceanUpdateStickyTopBar();

	/**
	 * Update sticky header
	 */
	oceanUpdateSticky();

} );

// On orientation change
$window.on( 'orientationchange' , function() {

	/**
	 * Update sticky top bar
	 */
	oceanUpdateStickyTopBar();

	/**
	 * Update sticky header
	 */
	oceanUpdateSticky();

} );

/* ==============================================
SITE HEADER
============================================== */
function oceanSiteHeader() {
	"use strict"

	// Var
	var $siteHeader;

	// If manual sticky
	if ( 'manual' == oceanwpLocalize.stickyChoose ) {
		$siteHeader = $j( '.owp-sticky' );
	} else {
		$siteHeader = $j( '#site-header' );
	}

	// If top header style
	if ( $j( '#site-header' ).hasClass( 'top-header' ) ) {
		$siteHeader = $j( '#site-header .header-top' );
	}

	// If medium header style
	if ( $j( '#site-header' ).hasClass( 'medium-header' )
		&& $j( '.bottom-header-wrap' ).hasClass( 'fixed-scroll' ) ) {
		$siteHeader = $j( '.bottom-header-wrap' );
	}

    // Return
    return $siteHeader;

}

/* ==============================================
HEADER OFFSET
============================================== */
function oceanStickyOffset() {
	"use strict"

    // Vars
    var $offset 		= 0,
	    $adminBar 		= $j( '#wpadminbar' ),
	    $stickyTopBar 	= $j( '#top-bar-wrap' );

    // Offset adminbar
    if ( $adminBar.length
    	&& $window.width() > 600 ) {
		$offset = $offset + $adminBar.outerHeight();
	}

    // Offset sticky topbar
    if ( true == oceanwpLocalize.hasStickyTopBar ) {
		$offset = $offset + $stickyTopBar.outerHeight();
	}

	// If vertical header style
	if ( $j( '#site-header' ).hasClass( 'vertical-header' ) ) {
		$offset = $offset;
	}

    // Return
    return $offset;

}

/* ==============================================
TOP BAR OFFSET
============================================== */
function oceanTopBarOffset() {
	"use strict"

    // Vars
    var $offset 		= 0,
	    $adminBar 		= $j( '#wpadminbar' ),
	    $topBarWrap 	= $j( '#top-bar-sticky-wrapper' );

    // Admin bar offset
    if ( $adminBar.length
    	&& $window.width() > 600 ) {
		$offset = $offset + $adminBar.outerHeight();
	}

    // Return
    return $offset;

}

/* ==============================================
WRAP TOP BAR STICKY HEIGHT
============================================== */
function oceanWrapTopBarHeight() {
	"use strict"

	// Return if no topbar sticky
	if ( false == oceanwpLocalize.hasStickyTopBar ) {
		return;
	}

	// Vars
	var $topBarWrap,
		$topBar 		= $j( '#top-bar-wrap' ),
		$topBarHeight 	= $topBar.outerHeight();

	// Add wrap
    $topBarWrap 	= $j( '<div id="top-bar-sticky-wrapper" class="oceanwp-sticky-top-bar-holder"></div>' );
    $topBar.wrapAll( $topBarWrap );
    $topBarWrap     = $j( '#top-bar-sticky-wrapper' );

    // Add wrap height
    $topBarWrap.css( 'height', $topBarHeight );

}

/* ==============================================
STICKY TOP BAR
============================================== */
function oceanStickyTopBar() {
	"use strict"

	// Top bar wrap
	var $topBar = $j( '#top-bar-wrap' );

	// Return if no topbar sticky
	if ( ! $topBar.length
		|| false == oceanwpLocalize.hasStickyTopBar
		|| ( $window.width() <= 960
			&& true != oceanwpLocalize.hasStickyMobile ) ) {
		return;
	}

	// Vars
	var $topBarWrap     = $j( '#top-bar-sticky-wrapper' ),
    	$adminBar 		= $j( '#wpadminbar' ),
    	$offset 		= oceanTopBarOffset(),
    	$position;

	// Position
	if ( $topBarWrap.length ) {
		$position = $topBarWrap.offset().top - $offset;
	}

    // When scrolling
    if ( $windowTop >= $position && 0 !== $windowTop ) {

		// Add sticky wrap class
		$topBarWrap.addClass( 'is-sticky' );

		// Add CSS
		$topBar.css( {
			top 	: $offset,
			width 	: $topBarWrap.width()
		} );

    } else {

		// Remove sticky wrap class
		$topBarWrap.removeClass( 'is-sticky' );

		// Remove CSS
		$topBar.css( {
			top 	: '',
			width 	: ''
		} );

    }

}

/* ==============================================
UPDATE STICKY TOP BAR
============================================== */
function oceanUpdateStickyTopBar() {
	"use strict"

	// Return if no topbar sticky
	if ( false == oceanwpLocalize.hasStickyTopBar ) {
		return;
	}

	// Vars
	var $topBarWrap 	= $j( '#top-bar-sticky-wrapper' ),
		$topBarHeight 	= $j( '#top-bar-wrap' ).outerHeight(),
		$topBar 		= $j( '#top-bar-wrap' ),
		$offset 		= oceanTopBarOffset();

	// Update header height
	$topBarWrap.css( 'height', $topBarHeight );

	// Update CSS
	if ( 0 !== $windowTop ) {
		$topBar.css( {
			top 	: $offset,
			width 	: $topBarWrap.width()
		} );
	}

}

/* ==============================================
WRAP STICKY HEIGHT
============================================== */
function oceanWrapHeight() {
	"use strict"

	// Vars
	var $siteHeader = oceanSiteHeader(),
		$siteHeaderHeight,
		$headerWrap;

	// Header height
	$siteHeaderHeight = $siteHeader.outerHeight();

	// Add wrap
    $headerWrap = $j( '<div id="site-header-sticky-wrapper" class="oceanwp-sticky-header-holder"></div>' );
    $siteHeader.wrapAll( $headerWrap );
    $headerWrap = $j( '#site-header-sticky-wrapper' );

	// Add wrap height
	if ( ! $j( '#site-header' ).hasClass( 'vertical-header' ) ) {
		$headerWrap.css( 'height', $siteHeaderHeight );
	}

}

/* ==============================================
LOGO HEIGHT
============================================== */
function oceanLogoHeight() {
	"use strict"

	// Site logo
	var $siteLogo   	= $j( '#site-logo img' ),
		$siteLogoIMG 	= $j( '#site-logo .custom-logo' ),
		$headerWrap 	= $j( '#site-header-sticky-wrapper' ),
		$siteHeader 	= $j( '#site-header' );

	// If center header style
	if ( $j( '#site-header' ).hasClass( 'center-header' ) ) {
		$siteLogo   	= $j( '.middle-site-logo img' );
		$siteLogoIMG 	= $j( '.middle-site-logo .custom-logo' );
	}

	// Return if not shrink style and on some header styles
	if ( 'shrink' != oceanwpLocalize.stickyStyle
		|| ! $siteLogo.length
		|| ! $siteHeader.hasClass( 'fixed-scroll' )
		|| ! $headerWrap.length
		|| ( $window.width() <= 960
			&& true != oceanwpLocalize.hasStickyMobile )
		|| 'manual' == oceanwpLocalize.stickyChoose
		|| $j( '#site-header' ).hasClass( 'top-header' )
		|| ( $j( '#site-header' ).hasClass( 'medium-header' )
			&& $j( '.bottom-header-wrap' ).hasClass( 'fixed-scroll' ) )
		|| $j( '#site-header' ).hasClass( 'vertical-header' ) ) {
		return;
	}

	// Get logo image if mobile logo
	var $mobileLogo = $j( '#site-logo .responsive-logo' );
	if ( $j( '#site-logo' ).hasClass( 'has-responsive-logo' )
		&& $mobileLogo.is( ':visible' ) ) {
		$siteLogoIMG = $mobileLogo;
	}

	// Vars
	var $logoHeight 		= $siteLogoIMG.height(),
		$shrinkLogoHeight 	= parseInt( oceanwpLocalize.shrinkLogoHeight ),
		$shrinkLogoHeight 	= $shrinkLogoHeight ? $shrinkLogoHeight : 30,
		$position;

	// Position
	$position = $headerWrap.offset().top - oceanStickyOffset();

	// On scroll
	$window.scroll( function() {

	    // When scrolling
	    if ( $windowTop >= $position && 0 !== $windowTop ) {

	    	$siteLogo.css( {
				'max-height' : $shrinkLogoHeight
			} );

	    } else {

	    	$siteLogo.css( {
				'max-height' : $logoHeight
			} );

	    }

	} );

}

/* ==============================================
ADD STICKY HEADER
============================================== */
function oceanAddSticky() {
	"use strict"

	// Header wrapper
	var $headerWrap = $j( '#site-header-sticky-wrapper' );

	// Return if no header wrapper or if disabled on mobile
	if ( ! $headerWrap.length
		|| ! $j( '#site-header' ).hasClass( 'fixed-scroll' )
		|| ( $window.width() <= 960
			&& true != oceanwpLocalize.hasStickyMobile )
		|| $j( '#site-header' ).hasClass( 'vertical-header' ) ) {
		return;
	}

	// Vars
	var $siteHeader 		= oceanSiteHeader(),
		$header 			= $j( '#site-header' ),
		$position,
		$positionTwo,
		$slidePosition;

	// Position
	$position 		= $headerWrap.offset().top - oceanStickyOffset();
	$slidePosition 	= $position;
	$positionTwo 	= $position + $headerWrap.outerHeight();

	// If slide effect
	if ( 'slide' == oceanwpLocalize.stickyEffect
		&& ! $j( '#site-header' ).hasClass( 'vertical-header' ) ) {
		$position 	= $positionTwo;
	}

    // When scrolling
    if ( $windowTop >= $position && 0 !== $windowTop ) {

		// Add sticky wrap class
		$headerWrap.addClass( 'is-sticky' );

		// Add CSS
		$siteHeader.css( {
			top 	: oceanStickyOffset(),
			width 	: $headerWrap.width()
		} );

		// If slide effect
		if ( 'slide' == oceanwpLocalize.stickyEffect
			&& ! $j( '#site-header' ).hasClass( 'vertical-header' ) ) {
			$header.addClass( 'show' );
		}

    } else {

    	// If is not slide effect
    	if ( 'slide' != oceanwpLocalize.stickyEffect ) {

	    	// Remove sticky wrap class
			$headerWrap.removeClass( 'is-sticky' );

			// Remove CSS
			$siteHeader.css( {
				top 	: '',
				width 	: ''
			} );

		}

    }

    // If slide effect
	if ( 'slide' == oceanwpLocalize.stickyEffect
		&& ! $j( '#site-header' ).hasClass( 'vertical-header' ) ) {

		// Remove sticky class when window top
		if ( $windowTop <= $slidePosition ) {
			
			// Remove sticky wrap class
			$headerWrap.removeClass( 'is-sticky' );

			// Remove CSS
			$siteHeader.css( {
				top 	: '',
				width 	: ''
			} );

			// Remove slide effect class
			$header.removeClass( 'show' );

		}
	    
	}

}

/* ==============================================
ADD STICKY HEADER FOR VERTCIAL HEADER STYLE
============================================== */
function oceanAddVerticalHeaderSticky() {
	"use strict"

	// Return if is not vertical header style and transparent
	if ( ! $j( '#site-header.vertical-header' ).hasClass( 'is-transparent' ) ) {
		return;
	}

	// Header wrapper
	var $headerWrap = $j( '#site-header-sticky-wrapper' );

	// Return if no header wrapper
	if ( ! $headerWrap.length ) {
		return;
	}

	// Position
	var $position = $headerWrap.offset().top;

    // When scrolling
    if ( $windowTop >= $position && 0 !== $windowTop ) {

		// Add sticky wrap class
		$headerWrap.addClass( 'is-sticky' );

    } else {

    	// Remove sticky wrap class
		$headerWrap.removeClass( 'is-sticky' );

    }

}

/* ==============================================
UPDATE STICKY HEADER
============================================== */
function oceanUpdateSticky() {
	"use strict"

	// Return if is vertical header style
	if ( $j( '#site-header' ).hasClass( 'vertical-header' ) ) {
		return;
	}

	// Vars
	var $headerWrap 	= $j( '#site-header-sticky-wrapper' ),
		$siteHeader 	= oceanSiteHeader(),
		$headerHeight 	= $siteHeader.outerHeight();

	// Update header height
	$headerWrap.css( 'height', $headerHeight );

	// Update CSS
	if ( 0 !== $windowTop ) {
		$siteHeader.css( {
			top 	: oceanStickyOffset(),
			width 	: $headerWrap.width()
		} );
	}

}

/* ==============================================
STICKY UP EFFECT
============================================== */
function oceanStickyEffects() {
	"use strict"

	// Return if is vertical header style 
	if ( $j( '#site-header' ).hasClass( 'vertical-header' ) ) {
		return;
	}

	// Header wrapper
	var $headerWrap = $j( '#site-header-sticky-wrapper' ),
		$siteHeader = $j( '#site-header' );

	// Return if no header wrapper
	if ( ! $headerWrap.length ) {
		return;
	}

	// If show up effect
	if ( 'up' == oceanwpLocalize.stickyEffect ) {
		
		// Vars
		var $position 		= $headerWrap.offset().top + $headerWrap.outerHeight(),
			$currentScroll 	= $j( document ).scrollTop();

	    if ( $currentScroll >= $previousScroll && $currentScroll >= $position ) {
	        $siteHeader.removeClass( 'header-down' ).addClass( 'header-up' );
	    } else {
	        $siteHeader.removeClass( 'header-up' ).addClass( 'header-down' );
	    }
	    
	    $previousScroll = $currentScroll;

    }

}