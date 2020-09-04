function modula_pro_get_url_parameters(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || false
}

function modula_pro_enable_lightbox( data ){

    if ( 'undefined' == typeof wpModulaProHelper.lightboxes[data.options.lightbox] ) { return; }

    var currentLightbox = wpModulaProHelper.lightboxes[data.options.lightbox];
    if ('magnific' == data.options.lightbox && 'function' == typeof jQuery.fn['magnificPopup']) {
        currentLightbox['options'].delegate = "a.tile-inner[data-cyclefilter='show']";
        currentLightbox['options'].callbacks = {
            beforeOpen: function() {
                jQuery(document).trigger('modula_magnific_lightbox_before_open', [event, data, this]);
            },
            elementParse: function(item) {
                jQuery(document).trigger('modula_magnific_lightbox_elementparse', [event, data, this, item]);
            },
            change: function() {
                jQuery(document).trigger('modula_magnific_lightbox_change', [event, data, this]);
            },
            resize: function() {
                jQuery(document).trigger('modula_magnific_lightbox_resize', [event, data, this]);
            },
            open: function () {
                jQuery(document).trigger('modula_magnific_lightbox_open', [event, data, this]);
            },
            beforeClose: function() {
                jQuery(document).trigger('modula_magnific_lightbox_before_close', [event, data, this]);
            },
            close: function() {
                jQuery(document).trigger('modula_magnific_lightbox_close', [event, data, this]);
            },
            afterClose: function() {
                jQuery(document).trigger('modula_magnific_lightbox_after_close', [event, data, this]);
            },
            imageLoadComplete: function () {
                jQuery(document).trigger('modula_magnific_lightbox_image_load_complete', [event, data, this]);
            }
        };
        jQuery( data.element ).magnificPopup( currentLightbox['options'] );
    } else if ('prettyphoto' == data.options.lightbox && 'function' == typeof jQuery.fn['prettyPhoto']) {
        // Callbacks
        currentLightbox['options']['changepicturecallback'] = function() {
            jQuery(document).trigger('modula_prettyphoto_lightbox_change', [ data, this ]);
        };
        currentLightbox['options']['callback'] = function() {
            jQuery(document).trigger('modula_prettyphoto_lightbox_close', [ data, this ]);
        };

        jQuery(data.element).find('a.tile-inner[data-cyclefilter="show"]').prettyPhoto(currentLightbox['options']);
    } else if ('fancybox' == data.options.lightbox && 'function' == typeof jQuery.fn['fancybox']) {
        // Callbacks
        currentLightbox['options']['beforeLoad'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_before_load', [ data, this ]);
        };
        currentLightbox['options']['afterLoad'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_after_load', [ data, this ]);
        };
        currentLightbox['options']['beforeShow'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_before_show', [ data, this ]);
        };
        currentLightbox['options']['afterShow'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_after_show', [ data, this ]);
        };
        currentLightbox['options']['beforeClose'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_before_close', [ data, this ]);
        };
        currentLightbox['options']['afterClose'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_after_close', [ data, this ]);
        };
        currentLightbox['options']['onInit'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_on_init', [ data, this ]);
        };
        currentLightbox['options']['onActivate'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_on_activate', [ data, this ]);
        };
        currentLightbox['options']['onDeactivate'] = function() {
            jQuery(document).trigger('modula_fancybox_lightbox_on_deactivate', [ data, this ]);
        };

        jQuery(data.element).find('a.tile-inner[data-cyclefilter="show"]').fancybox(currentLightbox['options']);
    } else if ('swipebox' == data.options.lightbox && 'function' == typeof jQuery.fn['swipebox']) {
        // Callbacks
        currentLightbox['options']['beforeOpen'] = function() {
            jQuery(document).trigger('modula_swipebox_lightbox_before_open', [ data, this ]);
        };
        currentLightbox['options']['afterOpen'] = function() {
            jQuery(document).trigger('modula_swipebox_lightbox_after_open', [ data, this ]);
        };
        currentLightbox['options']['afterClose'] = function() {
            jQuery(document).trigger('modula_swipebox_lightbox_after_close', [ data, this ]);
        };
        currentLightbox['options']['nextSlide'] = function() {
            setTimeout( function(){ jQuery(document).trigger('modula_swipebox_lightbox_next_slide', [ data, this ]) }, 500);
        };
        currentLightbox['options']['prevSlide'] = function() {
            setTimeout( function(){ jQuery(document).trigger('modula_swipebox_lightbox_prev_slide', [ data, this ]) },500);
        };

        jQuery(data.element).find('a.tile-inner[data-cyclefilter="show"]').swipebox(currentLightbox['options']);
    } else if ('lightgallery' == data.options.lightbox && 'function' == typeof jQuery.fn['lightGallery']) {
        if ( typeof jQuery(data.element).data('lightGallery') != 'undefined' ) {
            jQuery(data.element).data('lightGallery').destroy(true);
        }
        currentLightbox['options'].selector = "a.tile-inner[data-cyclefilter='show']";
        jQuery(data.element).lightGallery(currentLightbox['options']);
    }

}

jQuery(document).on( 'modula_api_after_init', function (event, data) {
    var currentLightbox, filters, hiddenItems;


    hiddenItems = data.$element.find( '.hidden-items a' );

    // Check if we have filters and initialize filters.
    filters = jQuery(data.element).find('.filters > ul.menu__list > li > a');

    if ( filters.length > 0 ) {
        var filterClick = data.options.filterClick;

        // Check url to see if we have a filter
        var urlFilter = modula_pro_get_url_parameters('jtg-filter');

        if ( urlFilter ) {
            var currentFilter = 'jtg-filter-' + urlFilter;

            // Show filtered items
            data.$items.find('a').attr('data-cyclefilter', 'show');
            data.$items.show();

            // hidden items
            hiddenItems.attr('data-cyclefilter', 'show');

            // If we need to show all, don't hide anything
            if ('all' != urlFilter) {
                data.$items.not("." + currentFilter).hide().addClass('jtg-hidden');
                data.$items.not("." + currentFilter).find('a').attr('data-cyclefilter', 'hide');

                // Hidden Items
                hiddenItems.not("." + currentFilter).attr('data-cyclefilter', 'hide');

                jQuery( '.filters .menu__item--current' ).removeClass( 'menu__item--current' );
                data.$element.find("a[data-filter='" + urlFilter + "']").parent().addClass( 'menu__item--current' );

                data.reset();

            }

        }


        //options for default active filter
        if ( 'All' != data.options.defaultActiveFilter && 'all' != data.options.defaultActiveFilter &&  '' != data.options.defaultActiveFilter && ! urlFilter ) {
            var defaultFilterItems = jQuery( data.element).find('.filters a[data-filter="' + data.options.defaultActiveFilter + '"]' );

            if ( defaultFilterItems.length > 0 ) {

                // Process filters
                filters.parent().removeClass('menu__item--current');
                filters.filter( '[data-filter="' + data.options.defaultActiveFilter + '"]' ).parent().addClass('menu__item--current');
                data.$items.hide().addClass('jtg-hidden');
                data.$items.filter( '.jtg-filter-' + data.options.defaultActiveFilter ).show().removeClass('jtg-hidden');
                data.$items.filter( '.jtg-filter-' + data.options.defaultActiveFilter ).find('a').attr('data-cyclefilter', 'show');

                // hidden items
                hiddenItems.attr('data-cyclefilter', 'hide');
                hiddenItems.filter( '.jtg-filter-' + data.options.defaultActiveFilter ).attr('data-cyclefilter', 'show');

            }

            data.reset();

        }

        data.$element.on( 'click', '.filters a', function (e) {

            if ('0' == filterClick) {
                e.preventDefault();
            }else{
                return true;
            }

            if ( jQuery(this).parent().hasClass( "menu__item--current" ) ) {
                return;
            }

            var currentFilter = jQuery(this).data('filter');
            data.$element.find( '.filters .menu__item--current' ).removeClass( 'menu__item--current' );
            data.$element.find(".filters a[data-filter='" + jQuery(this).attr('data-filter') + "']").parent().addClass( 'menu__item--current' );

            var filter = 'jtg-filter-' + currentFilter;
            if (filter) {
                data.$items.show().removeClass('jtg-hidden');
                data.$items.find('a').attr('data-cyclefilter', 'show');
                data.$items.not("." + filter).hide().addClass('jtg-hidden');
                data.$items.not("." + filter).find('a').attr('data-cyclefilter', 'hide');

                // hidden items
                hiddenItems.attr('data-cyclefilter', 'show');
                hiddenItems.not("." + filter).attr('data-cyclefilter', 'hide');
            } else {
                data.$items.find('a').attr('data-cyclefilter', 'show');
                data.$items.show().removeClass('jtg-hidden');

                // hidden items
                hiddenItems.attr('data-cyclefilter', 'show');
            }

            //reinitialize lightboxes
            modula_pro_enable_lightbox( data );
            data.reset();
        });
    }

    // initialize lightboxes
    modula_pro_enable_lightbox( data );


    data.$element.find('.filter-by-wrapper').click(function () {

        var wrapper = jQuery(this);
        if (data.$element.find('.filters').hasClass('active')) {
            data.$element.find('.filters').hide(600).removeClass('active');
            wrapper.removeClass('opened');
        } else {
            data.$element.find('.filters').show(600).addClass('active');
            wrapper.addClass('opened');
        }
    });

    // reset data on window load, otherwise doesn't find element and gives error
    // jQuery(window).load(function () {
    //     data.reset();
    // });

});