wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;

jQuery( document ).ready( function( $ ){

	// Initialize Filters
	wp.Modula.Filters = new wp.Modula.models['filters']({
		'filters' : wp.Modula.Settings.get( 'filters' ),
    });
    
    // Initialize Gallery Conditions 

    wp.Modula.Conditions = new modulaProGalleryConditions();

	// Bulk Edit
	wp.Modula.BulkEdit = new wp.Modula.bulkedit['model'];

	$('#modula-bulk-edit').click(function( evt ){
		evt.preventDefault();
		wp.Modula.BulkEdit.open();
	});

	if ( 'undefined' != typeof wp.Modula.Settings ) {
		var maxImageCount = $( '#modula-general tr[data-container="maxImagesCount"]' );
        var all_filter = $('#modula-filters tr[data-container="allFilterLabel"]');
        var collapsible_Text = $('#modula-filters tr[data-container="collapsibleActionText"]');

        wp.Modula.Settings.on( 'change:hideAllFilter', function( event, type ){
        	if( 1 == type){
                all_filter.hide();
			} else {
                all_filter.show();
			}
        });

        wp.Modula.Settings.on( 'change:enableCollapsibleFilters', function( event, type ){
            if( 0 == type){
                collapsible_Text.hide();
            } else {
                collapsible_Text.show();
            }
        });

        if ( wp.Modula.Settings.get('hideAllFilter') == 1 ) {
            all_filter.hide();
        }

        if ( wp.Modula.Settings.get('enableCollapsibleFilters') != 1 ) {
            collapsible_Text.hide();
        }

	}

    $(document).on( 'modula:sortChanged', wp.Modula.Items.changeOrder );
    // Initiate the sorting
    wp.Modula.sorting.init();

});