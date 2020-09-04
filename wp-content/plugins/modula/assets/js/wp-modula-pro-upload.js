wp.Modula = 'undefined' === typeof( wp.Modula ) ? {} : wp.Modula;
wp.Modula.upload = 'undefined' === typeof( wp.Modula.upload ) ? {} : wp.Modula.upload;

(function( $, modula ){

	var ModulaPROSelection = modula.upload['selection'].extend({
		add: function( models, options ) {
			/**
			 * call 'add' directly on the parent class
			 */
			return wp.media.model.Attachments.prototype.add.call( this, models, options );
		},
	});

	var uploadPRoHandler = modula.upload['uploadHandler'].extend({
		fileupload: function( up, file, info ){

			var modulaGalleryObject = this,
				response = JSON.parse( info.response );

			modulaGalleryObject.generateSingleImage( response['data'] );

		},

		generateSingleImage: function( attachment ){
			var data = { halign: 'center', valign: 'middle', link: '', target: '' }
				captionSource = modula.Settings.get( 'wp_field_caption' ),
				titleSource = modula.Settings.get( 'wp_field_title' );

			data['full']      = attachment['sizes']['full']['url'];
			if ( "undefined" != typeof attachment['sizes']['large'] ) {
				data['thumbnail'] = attachment['sizes']['large']['url'];
			}else{
				data['thumbnail'] = data['full'];
			}
			data['id']          = attachment['id'];
			data['alt']         = attachment['alt'];
			data['orientation'] = attachment['orientation'];
			data['date'] 		= attachment['date'];
			data['modified'] 	= attachment['modified'];
			data['original-title'] = attachment['title'];
			
			// Check from where to populate image title ( poate ar trebui sa pastrezi logica asta pentru frontend ca altfel nu am cum sa sortez dupa titlu )
			if ( 'none' == titleSource ) {
				data['title'] = attachment['title'];
			}else if ( 'title' == titleSource ) {
				data['title'] = attachment['title'];
			}else if ( 'description' == titleSource ) {
				data['title'] = attachment['description'];
			}

			// Check from where to populate image caption
			if ( 'none' == captionSource ) {
				data['description'] = attachment['title'];
			}else if ( 'title' == captionSource ) {
				data['description'] = attachment['title'];
			}else if ( 'caption' == captionSource ) {
				data['description'] = attachment['caption'];
			}else if ( 'description' == captionSource ) {
				data['description'] = attachment['description'];
			}

			var item = new modula.items['model']( data );
			item.addToCollection();
		}

	});

	modula.upload['selection']     = ModulaPROSelection;
	modula.upload['uploadHandler'] = uploadPRoHandler;

}( jQuery, wp.Modula ))