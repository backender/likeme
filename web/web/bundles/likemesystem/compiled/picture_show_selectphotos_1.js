$(document).ready(function() {
	//id(ul id),width,height(element height),row(elements in row)        
	$('#fcbklist').photoselector({ width: '540', height: '160', row: '4' });
	 //add to selected items function

	
	$.each($('#fcbklist').children("li"), function(i, obj){
		 obj = $(obj);
		 if (obj.attr('class') == "selected") {
			 item = obj.children(".fcbklist_item");
			 $('#fcbklist').photoselector('addToSelected', item);
			 item.toggleClass("itemselected");
			 item.parents("li").toggleClass("liselected");
		 }
	 }); 	
});    


