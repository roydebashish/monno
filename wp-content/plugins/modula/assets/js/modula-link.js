jQuery(document).ready(function($){
	$(document).on( 'click', '.modula-link', function( evt ){
		var images = $(this).data('images');

		evt.preventDefault();

		if ( 'undefined' != typeof $.fancybox ) {
			$.fancybox.open( images ,{
      			loop : true,
      			thumbs : {
        			autoStart : true
      			}
    		});
		}
	});
});