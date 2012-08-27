$(document).ready(function() {
	//id(ul id),width,height(element height),row(elements in row)        
//	fcbklist = new fcbkListSelection("#fcbklist","540","150","4");
	$('#fcbklist').photoselector({ width: '540', height: '150', row: '4' });
	   //add to selected items function

	
//	$.each($('#fcbklist').children("li"), function(i, obj){
//		 obj = $(obj);
//		 if (obj.attr('class') == "selected") {
//			 var selobj = obj.children(".fcbklist_item");
//			 addToSelected(selobj);
//
//			 selobj.toggleClass("itemselected");
//			 selobj.parents("li").toggleClass("liselected");
//		 }
//	 });
});    


