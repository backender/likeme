$(document).ready(function() {
		$('.likepic-sm').each(function(i) {
			var self = $(this);
			self.poshytip({
					className: 'tip-twitter',
					showTimeout: 1,
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
									.addClass('crop_content');
							
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
							        	boxWidth: 400, 
							        	boxHeight: 400,
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
									.appendTo(container);
								
								// Create a close button
								var cropbutton = $('<div/>')
									.attr("style","margin-bottom: -3px; margin-right: -14px;")
									.append("<div id='cropButton'><a href='#'>Speichern</a></div>")
									.click(function(){
										 $("#loading").css({"visibility":"visible"});
										 $.ajax({
											  type: "POST",
											  url: Routing.generate('crop_pictures'),
											  data: { 
												  url: self.find('img').attr("org"),
												  x: cropx, 
												  y: cropy,
												  w: cropw, 
												  h: croph
											  }
										})
										.done(function( msg ) {
											  if (msg == 1) {
												  var timestamp = new Date().getTime();
												  // Update small picture
												  $(self).find('img').attr('src', self.find('img').attr("src") + '?' + timestamp); 
												  // Update big picture
												  $('.likepic').find('img').attr('src', $(self).find('img').attr('src'));
												  $("#loading").css({"visibility":"hidden"});
												  $(self).poshytip('hide');
											  } else {
												  alert( msg );
											  }
										});
									})
									.appendTo(footerdiv);
	
							    if (image.height() > 0) {
							    	return container;
							    } else {
							    	image.load(function() {
							    		updateCallback(container);
							    	});
							    }								
									 	
								return 'Loading image...';
					}
			});
			
			$('.likepic-crop').click(function(event){
				$('.likepic-sm').each(function(i) {
					$(this).poshytip('hide'); 
				});
				$(this).parent().poshytip('show'); 
			});	
			
			$('.likepic-sm').click(function(){
				$('.likepic').find('img').attr('src', $(this).find('img').attr('src'));
			});	
			
		});

});   

function showUrlInDialog(url, dialogtitle, loadingtext) {
		if (typeof title == 'undefined' ) {
			title = '';
		}
	   $("#loading-text").text(loadingtext);
	   $("#loading").css({"visibility":"visible"});
	   $('#dialogdiv').remove();
	   var tag = $("<div/>")
	   	.attr("id", "dialogdiv");
	   $.ajax({
		     url: url,
		     success: function(data) {
		       $('body').append(tag);
		       tag.append(data)
		       	.dialog({
		       		modal: true,
		       		width: 582,
		       		height: 645,
		       		title: dialogtitle
		       }).dialog('open');
		     }
		   }).done(function() {
			   $("#loading").css({"visibility":"hidden"});
			   $("#loading-text").text("Speichern...");
		   });

        return false;
}
