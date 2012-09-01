   function showUrlInDialog(url, title = 'Dialog') {
	   var tag = $("<div></div>");
	   $.ajax({
		     url: url,
		     success: function(data) {
		       tag.html(data).dialog({
		    	   modal: true,
		    	   height: 400,
		           width: 600,
		           title: title
		       }).dialog('open');
		     }
		   });

        return false;
    }
