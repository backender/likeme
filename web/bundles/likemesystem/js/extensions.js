$(document).ready(function() {
		$('.likepic, .likepic-sm').each(function(i) {
			var self = $(this);
			self.poshytip({
					className: 'tip-darkgray',
					bgImageFrameSize: 8,
					showOn: 'none',
					alignTo: 'target',
					alignX: 'right',
					alignY: 'center',
					offsetX: 7,
					content: function(updateCallback) {
								var cropx,cropy,cropw,croph;
								
								// Create container div for poshytip
								var container = $('<div/>')
									.addClass('content');

								// Create a header div
								var headerdiv = $('<div/>')
									.attr("style","margin-top: -3px; margin-right: -14px; float: right;")
									.appendTo(container);
							
								// Create a close button
								var button = $('<button/>')
									.attr("id", "exitButton")
									.attr("style","background: none repeat scroll 0% 0%; border: none;")
									.width('36px')
									.height('13px')
									.button({
										text: false,
										icons: {
											primary: "ui-icon-closethick"
										}
									})
									.click(function(){
										self.poshytip('hide'); 
									})
									.appendTo(headerdiv);

								// Create a content div
								var content = $('<div/>')
									.attr("style","padding-top: 17px;")
									.appendTo(container);	
								
								// Get selected image
								var image = $('<img/>')
									.attr("src", self.find('img').attr("org"))
									.appendTo(content)
									.Jcrop({
							        	aspectRatio: 1,
							        	minSize: [100, 100],
							        	boxWidth: 200, 
							        	boxHeight: 200,
							        	bgColor: 'none',
							        	onSelect: updateCoords
							        });
							       
								function updateCoords(c) {
									cropx = c.x;
									cropy = c.y;
									cropw = c.w;
									croph = c.h;
								};
								
								// Create a footer div
								var footerdiv = $('<div/>')
									.attr("style","margin-bottom: -3px; margin-right: -14px;")
									.appendTo(container);
								
								// Create a close button
								var cropbutton = $('<button/>')
									.attr("id", "cropButton")
									.attr("style","background: none repeat scroll 0% 0%; border: none;")
									.button({
										label: "Speichern",
										icons: {
											primary: "ui-icon-disk"
										}
									})
									.click(function(){
										$('body').css('cursor','wait');
										$.ajax({
											  type: "POST",
											  url: Routing.generate('crop_pictures'),
											  data: { 
												  url: self.find('img').attr("org"),
												  thumburl: self.find('img').attr("src"),
												  x: cropx, 
												  y: cropy,
												  w: cropw, 
												  h: croph
											  }
											}).done(function( msg ) {
											  alert( msg );
											  window.location.reload();
											});
									})
									.appendTo(footerdiv);
									
								return container;
					}
			});
			var link = Routing.generate('crop_pictures', { "url": self.find('img').attr("src") });
			self.click(function(){
				$('.likepic, .likepic-sm').each(function(i) {
					$(this).poshytip('hide'); 
				});
				self.poshytip('show'); 
			});	
			
		});

});   

function showUrlInDialog(url, title = 'Dialog') {
	   var tag = $("<div></div>");
	   $.ajax({
		     url: url,
		     success: function(data) {
		       tag.html(data);
		     }
		   });

        return false;
    }
