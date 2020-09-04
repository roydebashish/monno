wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;

var modulaProGalleryConditions = Backbone.Model.extend({ 

    initialize: function( args ){

		var rows = jQuery('.modula-settings-container tr[data-container]');
		var tabs = jQuery('.modula-tabs .modula-tab');
		this.set( 'rows', rows );
		this.set( 'tabs', tabs );
        
		this.initEvents();
		this.initValues();

    },
    
    initEvents: function(){

        this.listenTo(wp.Modula.Settings, 'change:cursor', this.changeCustomCursor );
        this.listenTo(wp.Modula.Settings, 'change:uploadCursor', this.changeUploadCursor );
     },

     initValues: function(){
         this.changeCustomCursor( false, wp.Modula.Settings.get( 'cursor' ) );
         this.changeUploadCursor( false, wp.Modula.Settings.get( 'uploadCursor') );
     },
     
     changeCustomCursor: function( settings, value) {
        var cursorBox = jQuery( '.modula-effects-preview > div' );
        var rows = this.get( 'rows' );
        if ( 'custom' != value ) {
            rows.filter( '[data-container="uploadCursor"]' ).hide();
            cursorBox.css('cursor', wp.Modula.Settings.get( 'cursor' ) );
        }else {
            rows.filter( '[data-container="uploadCursor"]' ).show();
            var imageSource;
            if(jQuery("#modula_cursor_preview")[0]) {
                imageSource = jQuery("#modula_cursor_preview")[0].src;
            
            cursorBox.css('cursor', 'url(' + imageSource + '), auto' );
            }
        }

     },

     changeUploadCursor: function( settings, value ) {
         cursorBox = jQuery( '.modula-effects-preview > div' );
         customCursorValue =  wp.Modula.Settings.get( 'cursor' );
         if ( 0 != value  && 'custom' == customCursorValue ) {
            var imageSource = jQuery("#modula_cursor_preview")[0].src;
            cursorBox.css('cursor', 'url(' + imageSource + '), auto' );
         }else {
             cursorBox.css( 'cursor', wp.Modula.Settings.get( 'cursor' ) );
         }
     }

})