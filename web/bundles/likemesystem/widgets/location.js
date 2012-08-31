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
					url: Routing.generate('location_get', { "input": request.term }),
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
			url: Routing.generate('location_by_id', { "id": id }),
			dataType: "json",
			data: {
			
			},
			success: function( data ) {
				$.map( data, function( item ) {
					$("#show_location").val(item.postalcode + " " + item.placename + ", " + item.statecode);
				});
			}
		});
	}
	
});

$('#likeme_user_profile_location').locationcomplete(); 	


})( jQuery, window, document );
