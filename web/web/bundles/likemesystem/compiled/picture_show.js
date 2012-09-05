/*
 fcbkListSelection 1.10
 - Jquery version required: 1.2.x, 1.3.x, 1.4.x
 
 Changelog:
 - 1.1: added preselected items
 - 1.0: project started
 */
/* Coded by: emposha <admin@emposha.com> */
/* Copyright: Emposha.com <http://www.emposha.com/> - Distributed under MIT - Keep this message! */
/*
 * elem - ul element id or object
 * width - width of ul
 * height - height of each element
 * row - number of items in row
 */



;(function ( $, window, document, undefined ) {

    // define your widget under a namespace of your choice
    //  with additional parameters e.g. 
    // $.widget( "namespace.widgetname", (optional) - an 
    // existing widget prototype to inherit from, an object 
    // literal to become the widget's prototype ); 

    $.widget( "likeme.photoselector" , {

        //Options to be used as defaults
        options: {
        	width: null,
        	height: null,
        	row: null,
        	// Define how many pictures can be selected
            maxSelect: 3
        },

        //Setup widget (eg. element creation, apply theming
        // , bind events etc.)
        _create: function () {
        	var self = this,  
        	o = self.options,  
        	elem = self.element;
            //main
            if (typeof(elem) != 'object') 
                elem = $(elem);
            elem.css("width", o.width + "px");
            
            this.createTabs(elem, o.width);
            this.wrapElements(elem, o.width, o.height, o.row);
           
         // not required without tabs
         //    this.bindEventsOnTabs(elem);
            this.bindEventsOnItems(elem);
        },
        
        // Get Content of Tabs
        getContent: function(elem, tab){
            switch (tab) {
	            case "all":
	                elem.children("li").show();
	                break;
	                
	            case "selected":
	                elem.children("li:not([addedid])").hide();
	                elem.children("li[addedid]").show();
	                break;
	                
	            case "unselected":
	                elem.children("li[addedid]").hide();
	                elem.children("li:not([addedid])").show();
	                break;
            }
        },

        hiddenCheck: function(obj, elem){
            switch (this.curTab()) {
	            case "all":
	                elem.children("li").show();
	                break;
	                
	            case "selected":
	                elem.children("li:not([addedid])").hide();
	                elem.children("li[addedid]").show();
	                break;
	                
	            case "unselected":
	                elem.children("li[addedid]").hide();
	                elem.children("li:not([addedid])").show();
	                break;
	        }
        },
            
        addToSelected: function(obj){
            if (obj.hasClass("itemselected")) {
            	//deselect item
                $("#view_selected_count").text(parseInt($("#view_selected_count").text(), 10) - 1);
                obj.parents("li").removeAttr("addedid");
                this.removeValue(obj, this.element);
            }
            else {
            	//select item
                $("#view_selected_count").text(parseInt($("#view_selected_count").text(), 10) + 1);
                obj.parents("li").attr("addedid", "tester");
                this.addValue(obj, this.element);
            }
          // not required without tabs
          //  this.hiddenCheck(obj, this.element);
        },
        
        //bind onmouseover && click event on item
        bindEventsOnItems: function(elem){
        	var self = this;
        	var maxSelect = self.options.maxSelect;
            $.each(elem.children("li").children(".fcbklist_item"), function(i, obj){
                obj = $(obj);
                if (obj.children("input[checked]").length != 0) {
                	self.addToSelected(obj);
                    obj.toggleClass("itemselected");
                    obj.parents("li").toggleClass("liselected");
                }
                obj.click(function(){
                	//limit the maximal select
                	if (parseInt($("#view_selected_count").text(), 10) >  maxSelect - 1 && !obj.hasClass("itemselected")) {
                	}
                	else {
                		self.addToSelected(obj);
                		obj.toggleClass("itemselected");
                		obj.parents("li").toggleClass("liselected");
                	}
                });
                obj.mouseover(function(){
                    obj.addClass("itemover");
                });
                obj.mouseout(function(){
                    $(".itemover").removeClass("itemover");
                });
            });
        },
        
        //bind onclick event on filters
        bindEventsOnTabs: function(elem){
        	var self = this;
            $.each($("#selections li"), function(i, obj){
                obj = $(obj);
                obj.click(function(){
                    $(".view_on").removeClass("view_on");
                    obj.addClass("view_on");
                    self.getContent(elem, obj.attr("id").replace("view_", ""));
                });
            });
        },
                
        //create control without tabs
        createTabs: function(elem, width){
            var html = '<div id="filters" style="width:' + (parseInt(width, 10) + 2) + 'px;">' +
            '<ul class="selections" id="selections"><li id="view_selected" class="">' +
            'Ausgew&auml;hlt (<strong id="view_selected_count">0</strong>)</li>' +
            '</ul>' +
            '<div class="clearer"></div></div>';
            elem.before(html);
        },

        //wrap elements with div
        wrapElements: function(elem, width, height, row){
            elem.children("li").wrapInner('<div class="fcbklist_item"></div>');
            $(".fcbklist_item").css("height", height + "px");
            var newwidth = Math.ceil((parseInt(width, 10)) / parseInt(row, 10)) - 15;
            $(".fcbklist_item").css("width", newwidth + "px");
        },
        
        addValue: function(obj, elem, value){
            //create input
            var inputid = elem.attr('id') + "_values";
            if ($("#" + inputid).length == 0) {
                var input = document.createElement('input');
                $(input).attr({
                    'type': 'hidden',
                    'name': inputid,
                    'id': inputid,
                    'value': ""
                });
                elem.after(input);
            }
            else {
                var input = $("#" + inputid);
            }
            var randid = "rand_" + this.randomId();
            if (!value) {
                value = obj.find("[type=hidden]").val();
                obj.find("[type=hidden]").attr("randid", randid);
            }
            var jsdata = new this.data(randid, value);
            var stored = this.jsToString(jsdata, $(input).val());
            $(input).val(stored);
            return input;
        },
        
        jsToString: function(jsdata, json){
            var string = "{";
            $.each(jsdata, function(i, item){
                if (i) {
                    string += "\"" + i + "\":\"" + item + "\",";
                }
            });
            try {
                eval("json = " + json + ";");
                $.each(json, function(i, item){
                    if (i && item) {
                        string += "\"" + i + "\":\"" + item + "\",";
                    }
                });
            } 
            catch (e) {            
            }
            //remove last ,
            string = string.substr(0, (string.length - 1));
            string += "}"
            return string;
        },

        data: function(id, value){
            try {
                eval("this." + id + " = value;");
            } 
            catch (e) {            
            }
        },
            
        removeValue: function(obj, elem){
            var randid = obj.find("[type=hidden]").attr("randid");
            var inputid = elem.attr('id') + "_values";
            if ($("#" + inputid).length != 0) {
                try {
                    eval("json = " + $("#" + inputid).val() + ";");
                    var string = "{";
                    $.each(json, function(i, item){
                        if (i && item && i != randid) {
                            string += "\"" + i + "\":\"" + item + "\",";
                        }
                    });
                    //remove last ,
                    if (string.length > 2) {
                        string = string.substr(0, (string.length - 1));
                        string += "}"
                    }
                    else {
                        string = "";
                    }
                    $("#" + inputid).val(string);
                } 
                catch (e) {                
                }
            }
        },
            
        randomId: function(){
            var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
            var string_length = 32;
            var randomstring = '';
            for (var i = 0; i < string_length; i++) {
                var rnum = Math.floor(Math.random() * chars.length);
                randomstring += chars.substring(rnum, rnum + 1);
            }
            return randomstring;
        },
            
        curTab: function(){
            return $(".view_on").attr("id").replace("view_", "");
        }
        	

//        // Destroy an instantiated plugin and clean up 
//        // modifications the widget has made to the DOM
//        destroy: function () {
//
//            // this.element.removeStuff();
//            // For UI 1.8, destroy must be invoked from the 
//            // base widget
//          //  $.Widget.prototype.destroy.call(this);
//            // For UI 1.9, define _destroy instead and don't 
//            // worry about 
//            // calling the base widget
//        }

    });

})( jQuery, window, document );



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


