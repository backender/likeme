;(function ( $, window, document, undefined ) {


$.widget( "likeme.locationcomplete",  {
	
	_create: function() {
		
		var self = this,
			select = this.element.hide(),
			selected = select.children( ":selected" ), value = selected.val() ? selected.text() : "";
			
			//set name when id is already set
			this.selectitem(self.element.val());
			
			var input = this.input = $( "<input>" )
							.attr("id", "show_location")
			                .insertAfter( select )
			                .val( value )
			                .autocomplete({
			  
			source: function( request, response ) {
				$.ajax({
					/*TODO: Get dynamic link */
					url: "http://likeme.ch/~marc/likeme/web/app_dev.php/location/get/"+ request.term,
					dataType: "json",
					data: {
					
					},
					success: function( data ) {
						response( $.map( data, function( item ) {
							return {
								label: item.postalcode + " " + item.placename + ", " + item.statecode,
								value: item.postalcode + " " + item.placename + ", " + item.statecode,
								id: item.id
							}
						}));
					}
				});
			},
			select: function( event, ui ) {
					self.element.val(ui.item.id);
				
			}
	 
		} )
			 
	},
	selectitem: function( id ) {
		var self = this;
		$.ajax({
			/*TODO: Get dynamic link */
			url: "http://likeme.ch/~marc/likeme/web/app_dev.php/location/id/"+ id,
			dataType: "json",
			data: {
			
			},
			success: function( data ) {
				$.map( data, function( item ) {
						//self.element.val(item.postalcode + " " + item.placename + ", " + item.statecode);
						$("#show_location").val(item.postalcode + " " + item.placename + ", " + item.statecode);
						/*label: item.postalcode + " " + item.placename + ", " + item.statecode,
						value: item.postalcode + " " + item.placename + ", " + item.statecode,
						id: item.id*/
				});
			}
		});
	}
	
});

$('#likeme_user_profile_location').locationcomplete(); 	


})( jQuery, window, document );
