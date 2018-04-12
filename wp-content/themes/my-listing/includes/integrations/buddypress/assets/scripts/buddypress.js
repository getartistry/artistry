// Single Listings.
if (document.getElementById('c27-bp-listings-wrapper')) {
var CASE27_BuddyPress_Listings = new Vue({
    el: '#c27-bp-listings-wrapper',

    data: {
        authid: null,
        listings: {
            html: '',
            pagination: '',
            page: 0,
            per_page: 9,
            show: true,
            listing_type: '',
            loaded: false,
            loading: false,
            count: 0,
        },
    },

    created: function() {
        var self = this;

        self.authid = jQuery('#case27-author-id').val();
    },

    mounted: function () {
        this.$nextTick(function () {
            this.getListings();
            this.jQueryReady();
        }.bind(this));
    },

    methods: {
        getListings: lodash.debounce(function() {
                this._getListings();
            }, 100),

        _getListings: function(related_listings_id) {
            this.listings.show = false;
            this.listings.loading = true;

            this.$http.post(CASE27.ajax_url, {
                auth_id: this.authid,
                page: this.listings.page,
                per_page: this.listings.per_page,
                listing_type: this.listings.listing_type,
            }, {params: {action: 'get_listings_by_author', security: CASE27.ajax_nonce}, emulateJSON: true}).then(function(response) {
                // console.log(response.body);
                this.listings.show = true;
                this.listings.loading = false;
                this.listings.loaded = true;
                this.listings.count = parseInt(response.body.found_posts, 10);
                this.listings.html = response.body.html;
                this.listings.pagination = response.body.pagination;


                setTimeout(function() {
                    if ( typeof jQuery('.c27-bp-listings-grid').data('isotope') !== 'undefined' ) {
                        jQuery('.c27-bp-listings-grid').isotope('destroy');
                    }

                    if ( this.listings.count ) {
                        jQuery('.c27-bp-listings-grid').isotope({
                            itemSelector: '.grid-item',
                        });
                    }

                    jQuery('.lf-background-carousel').owlCarousel({
                        margin:20,
                        items:1,
                        loop: true,
                    });

                    jQuery('[data-toggle="tooltip"]').tooltip({
                        trigger: 'hover',
                    });

                    window.c27_trigger_reveal();
                }.bind(this), 10);
            }.bind(this));
        },

        jQueryReady: function() {
            var self = this;

            jQuery(document).ready(function() {
                jQuery('body').on('click', '.c27-bp-listings-pagination a', function(e) {
                    e.preventDefault();
                    self.listings.page = (parseInt(jQuery(this).data('page'), 10) - 1);
                    self.getListings();
                });
            });
        },
    },
});
}