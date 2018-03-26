var $j = jQuery.noConflict();

$j( document ).on( 'ready', function() {
	"use strict";
	// Masonry
	op_portfolioMasonry();
    // Isotope
    op_portfolioIsotope();
    // Lightbox
    op_portfolioLightbox();
} );

// Run on orientation change
$j( window ).on( 'orientationchange', function() {
	"use strict";
	// Masonry
	op_portfolioMasonry();
	// Isotope
	op_portfolioIsotope();
} );

/* ==============================================
MASONRY
============================================== */
function op_portfolioMasonry() {
	"use strict"

	// Make sure scripts are loaded
    if ( undefined == $j.fn.imagesLoaded || undefined == $j.fn.isotope ) {
        return;
    }

    // Loop through items
    $j( '.portfolio-entries.masonry-grid .portfolio-wrap' ).each( function() {

        // Var
        var $wrap = $j( this );

        // Run only once images have been loaded
        $wrap.imagesLoaded( function() {

            // Create the isotope layout
            var $grid = $wrap.isotope( {
                itemSelector       : '.portfolio-entry',
                transformsEnabled  : true,
                isOriginLeft       : oceanwpLocalize.isRTL ? false : true,
                transitionDuration : '0.4s',
                layoutMode         : 'masonry'
            } );

        } );

    } );

}

/* ==============================================
ISOTOPE
============================================== */
function op_portfolioIsotope() {
	"use strict"

	// Make sure scripts are loaded
    if ( undefined == $j.fn.imagesLoaded || undefined == $j.fn.isotope ) {
        return;
    }

    // Loop through items
    $j( '.portfolio-entries.isotope-grid .portfolio-wrap' ).each( function() {

        // Var
        var $wrap = $j( this );

        // Run only once images have been loaded
        $wrap.imagesLoaded( function() {

            // Create the isotope layout
            var $grid = $wrap.isotope( {
                itemSelector       : '.portfolio-entry',
                transformsEnabled  : true,
                isOriginLeft       : oceanwpLocalize.isRTL ? false : true,
                transitionDuration : '0.4s',
                layoutMode         : $wrap.data( 'layout' ) ? $wrap.data( 'layout' ) : 'masonry'
            } );

            // Filter links
            var $filter = $wrap.prev( 'ul.portfolio-filters' );
            if ( $filter.length ) {

                var $filterLinks = $filter.find( 'a' );

                $filterLinks.click( function() {

                    $grid.isotope( {
                        filter : $j( this ).attr( 'data-filter' )
                    } );

                    $j( this ).parents( 'ul' ).find( 'li' ).removeClass( 'active' );
                    $j( this ).parent( 'li' ).addClass( 'active' );

                    return false;

                } );

            }

        } );

    } );

}

/* ==============================================
LIGHTBOX
============================================== */
function op_portfolioLightbox() {
    "use strict"

    // Make sure lightbox script is enabled
    if ( $j( 'body' ).hasClass( 'no-lightbox' )
        || $j( '.portfolio-entries' ).hasClass( 'no-lightbox' ) ) {
        return;
    }

    $j( '.portfolio-entries' ).magnificPopup( {
        delegate: '.portfolio-lightbox',
        type: 'image',
        mainClass: 'mfp-with-zoom',
        gallery: {
            enabled: true
        },
    } );

}