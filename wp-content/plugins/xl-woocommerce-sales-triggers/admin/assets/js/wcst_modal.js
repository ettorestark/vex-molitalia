/*
 * Thickbox 3.1 - One Box To Rule Them All.
 * By Cody Lindley (http://www.codylindley.com)
 * Copyright (c) 2007 cody lindley
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
*/

if ( typeof wcst_modal_pathToImage != 'string' ) {
	var wcst_modal_pathToImage = (typeof wcstmodal10n !== "undefined")?wcstmodal10n.loadingAnimation:"";
}

/*!!!!!!!!!!!!!!!!! edit below this line at your own risk !!!!!!!!!!!!!!!!!!!!!!!*/

//on page load call wcst_modal_init
jQuery(document).ready(function(){
	wcst_modal_init('a.wcstmodal, area.wcstmodal, input.wcstmodal');//pass where to apply wcstmodal
	imgLoader = new Image();// preload image
	imgLoader.src = wcst_modal_pathToImage;
});

/*
 * Add wcstmodal to href & area elements that have a class of .wcstmodal.
 * Remove the loading indicator when content in an iframe has loaded.
 */
function wcst_modal_init(domChunk){
	jQuery( 'body' )
		.on( 'click', domChunk, wcst_modal_click )
		.on( 'wcstmodal:iframe:loaded', function() {
			jQuery( '#WCST_MB_window' ).removeClass( 'wcstmodal-loading' );
		});
}

function wcst_modal_click(){
	var t = this.title || this.name || null;
	var a = this.href || this.alt;
	var g = this.rel || false;
	wcst_modal_show(t,a,g);
	this.blur();
	return false;
}

function wcst_modal_show(caption, url, imageGroup) {//function called when the user clicks on a wcstmodal link

	var $closeBtn;

	try {
		if (typeof document.body.style.maxHeight === "undefined") {//if IE 6
			jQuery("body","html").css({height: "100%", width: "100%"});
			jQuery("html").css("overflow","hidden");
			if (document.getElementById("WCST_MB_HideSelect") === null) {//iframe to hide select elements in ie6
				jQuery("body").append("<iframe id='WCST_MB_HideSelect'>"+wcstmodal10n.noiframes+"</iframe><div id='WCST_MB_overlay'></div><div id='WCST_MB_window' class='wcstmodal-loading'></div>");
				jQuery("#WCST_MB_overlay").click(wcst_modal_remove);
			}
		}else{//all others
			if(document.getElementById("WCST_MB_overlay") === null){
				jQuery("body").append("<div id='WCST_MB_overlay'></div><div id='WCST_MB_window' class='wcstmodal-loading'></div>");
				jQuery("#WCST_MB_overlay").click(wcst_modal_remove);
				jQuery( 'body' ).addClass( 'modal-open' );
			}
		}

		if(wcst_modal_detectMacXFF()){
			jQuery("#WCST_MB_overlay").addClass("WCST_MB_overlayMacFFBGHack");//use png overlay so hide flash
		}else{
			jQuery("#WCST_MB_overlay").addClass("WCST_MB_overlayBG");//use background and opacity
		}

		if(caption===null){caption="";}
		jQuery("body").append("<div id='WCST_MB_load'><img src='"+imgLoader.src+"' width='208' /></div>");//add loader to the page
		jQuery('#WCST_MB_load').show();//show loader

		var baseURL;
	   if(url.indexOf("?")!==-1){ //ff there is a query string involved
			baseURL = url.substr(0, url.indexOf("?"));
	   }else{
	   		baseURL = url;
	   }

	   var urlString = /\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;
	   var urlType = baseURL.toLowerCase().match(urlString);

		if(urlType == '.jpg' || urlType == '.jpeg' || urlType == '.png' || urlType == '.gif' || urlType == '.bmp'){//code to show images

			WCST_MB_PrevCaption = "";
			WCST_MB_PrevURL = "";
			WCST_MB_PrevHTML = "";
			WCST_MB_NextCaption = "";
			WCST_MB_NextURL = "";
			WCST_MB_NextHTML = "";
			WCST_MB_imageCount = "";
			WCST_MB_FoundURL = false;
			if(imageGroup){
				WCST_MB_TempArray = jQuery("a[rel="+imageGroup+"]").get();
				for (WCST_MB_Counter = 0; ((WCST_MB_Counter < WCST_MB_TempArray.length) && (WCST_MB_NextHTML === "")); WCST_MB_Counter++) {
					var urlTypeTemp = WCST_MB_TempArray[WCST_MB_Counter].href.toLowerCase().match(urlString);
						if (!(WCST_MB_TempArray[WCST_MB_Counter].href == url)) {
							if (WCST_MB_FoundURL) {
								WCST_MB_NextCaption = WCST_MB_TempArray[WCST_MB_Counter].title;
								WCST_MB_NextURL = WCST_MB_TempArray[WCST_MB_Counter].href;
								WCST_MB_NextHTML = "<span id='WCST_MB_next'>&nbsp;&nbsp;<a href='#'>"+wcstmodal10n.next+"</a></span>";
							} else {
								WCST_MB_PrevCaption = WCST_MB_TempArray[WCST_MB_Counter].title;
								WCST_MB_PrevURL = WCST_MB_TempArray[WCST_MB_Counter].href;
								WCST_MB_PrevHTML = "<span id='WCST_MB_prev'>&nbsp;&nbsp;<a href='#'>"+wcstmodal10n.prev+"</a></span>";
							}
						} else {
							WCST_MB_FoundURL = true;
							WCST_MB_imageCount = wcstmodal10n.image + ' ' + (WCST_MB_Counter + 1) + ' ' + wcstmodal10n.of + ' ' + (WCST_MB_TempArray.length);
						}
				}
			}

			imgPreloader = new Image();
			imgPreloader.onload = function(){
			imgPreloader.onload = null;

			// Resizing large images - original by Christian Montoya edited by me.
			var pagesize = wcst_modal_getPageSize();
			var x = pagesize[0] - 150;
			var y = pagesize[1] - 150;
			var imageWidth = imgPreloader.width;
			var imageHeight = imgPreloader.height;
			if (imageWidth > x) {
				imageHeight = imageHeight * (x / imageWidth);
				imageWidth = x;
				if (imageHeight > y) {
					imageWidth = imageWidth * (y / imageHeight);
					imageHeight = y;
				}
			} else if (imageHeight > y) {
				imageWidth = imageWidth * (y / imageHeight);
				imageHeight = y;
				if (imageWidth > x) {
					imageHeight = imageHeight * (x / imageWidth);
					imageWidth = x;
				}
			}
			// End Resizing

			WCST_MB_WIDTH = imageWidth + 30;
			WCST_MB_HEIGHT = imageHeight + 60;
			jQuery("#WCST_MB_window").append("<a href='' id='WCST_MB_ImageOff'><span class='screen-reader-text'>"+wcstmodal10n.close+"</span><img id='WCST_MB_Image' src='"+url+"' width='"+imageWidth+"' height='"+imageHeight+"' alt='"+caption+"'/></a>" + "<div id='WCST_MB_caption'>"+caption+"<div id='WCST_MB_secondLine'>" + WCST_MB_imageCount + WCST_MB_PrevHTML + WCST_MB_NextHTML + "</div></div><div id='WCST_MB_closeWindow'><button type='button' id='WCST_MB_closeWindowButton'><span class='screen-reader-text'>"+wcstmodal10n.close+"</span><span class='wcst_modal_close_btn'></span></button></div>");

			jQuery("#WCST_MB_closeWindowButton").click(wcst_modal_remove);

			if (!(WCST_MB_PrevHTML === "")) {
				function goPrev(){
					if(jQuery(document).unbind("click",goPrev)){jQuery(document).unbind("click",goPrev);}
					jQuery("#WCST_MB_window").remove();
					jQuery("body").append("<div id='WCST_MB_window'></div>");
					wcst_modal_show(WCST_MB_PrevCaption, WCST_MB_PrevURL, imageGroup);
					return false;
				}
				jQuery("#WCST_MB_prev").click(goPrev);
			}

			if (!(WCST_MB_NextHTML === "")) {
				function goNext(){
					jQuery("#WCST_MB_window").remove();
					jQuery("body").append("<div id='WCST_MB_window'></div>");
					wcst_modal_show(WCST_MB_NextCaption, WCST_MB_NextURL, imageGroup);
					return false;
				}
				jQuery("#WCST_MB_next").click(goNext);

			}

			jQuery(document).bind('keydown.wcstmodal', function(e){
				if ( e.which == 27 ){ // close
					wcst_modal_remove();

				} else if ( e.which == 190 ){ // display previous image
					if(!(WCST_MB_NextHTML == "")){
						jQuery(document).unbind('wcstmodal');
						goNext();
					}
				} else if ( e.which == 188 ){ // display next image
					if(!(WCST_MB_PrevHTML == "")){
						jQuery(document).unbind('wcstmodal');
						goPrev();
					}
				}
				return false;
			});

			wcst_modal_position();
			jQuery("#WCST_MB_load").remove();
			jQuery("#WCST_MB_ImageOff").click(wcst_modal_remove);
			jQuery("#WCST_MB_window").css({'visibility':'visible'}); //for safari using css instead of show
			};

			imgPreloader.src = url;
		}else{//code to show html

			var queryString = url.replace(/^[^\?]+\??/,'');
			var params = wcst_modal_parseQuery( queryString );

			WCST_MB_WIDTH = (params['width']*1) + 30 || 630; //defaults to 630 if no parameters were added to URL
			WCST_MB_HEIGHT = (params['height']*1) + 40 || 440; //defaults to 440 if no parameters were added to URL
			ajaxContentW = WCST_MB_WIDTH - 30;
			ajaxContentH = WCST_MB_HEIGHT - 45;

			if(url.indexOf('WCST_MB_iframe') != -1){// either iframe or ajax window
					urlNoQuery = url.split('WCST_MB_');
					jQuery("#WCST_MB_iframeContent").remove();
					if(params['modal'] != "true"){//iframe no modal
						jQuery("#WCST_MB_window").append("<div id='WCST_MB_title'><div id='WCST_MB_ajaxWindowTitle'>"+caption+"</div><div id='WCST_MB_closeAjaxWindow'><button type='button' id='WCST_MB_closeWindowButton'><span class='screen-reader-text'>"+wcstmodal10n.close+"</span><span class='wcst_modal_close_btn'></span></button></div></div><iframe frameborder='0' hspace='0' allowtransparency='true' src='"+urlNoQuery[0]+"' id='WCST_MB_iframeContent' name='WCST_MB_iframeContent"+Math.round(Math.random()*1000)+"' onload='wcst_modal_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;' >"+wcstmodal10n.noiframes+"</iframe>");
					}else{//iframe modal
					jQuery("#WCST_MB_overlay").unbind();
						jQuery("#WCST_MB_window").append("<iframe frameborder='0' hspace='0' allowtransparency='true' src='"+urlNoQuery[0]+"' id='WCST_MB_iframeContent' name='WCST_MB_iframeContent"+Math.round(Math.random()*1000)+"' onload='wcst_modal_showIframe()' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;'>"+wcstmodal10n.noiframes+"</iframe>");
					}
			}else{// not an iframe, ajax
					if(jQuery("#WCST_MB_window").css("visibility") != "visible"){
						if(params['modal'] != "true"){//ajax no modal
						jQuery("#WCST_MB_window").append("<div id='WCST_MB_title'><div id='WCST_MB_ajaxWindowTitle'>"+caption+"</div><div id='WCST_MB_closeAjaxWindow'><a href='#' id='WCST_MB_closeWindowButton'><div class='wcst_modal_close_btn'></div></a></div></div><div id='WCST_MB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px'></div>");
						}else{//ajax modal
						jQuery("#WCST_MB_overlay").unbind();
						jQuery("#WCST_MB_window").append("<div id='WCST_MB_ajaxContent' class='WCST_MB_modal' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>");
						}
					}else{//this means the window is already up, we are just loading new content via ajax
						jQuery("#WCST_MB_ajaxContent")[0].style.width = ajaxContentW +"px";
						jQuery("#WCST_MB_ajaxContent")[0].style.height = ajaxContentH +"px";
						jQuery("#WCST_MB_ajaxContent")[0].scrollTop = 0;
						jQuery("#WCST_MB_ajaxWindowTitle").html(caption);
					}
			}

			jQuery("#WCST_MB_closeWindowButton").click(wcst_modal_remove);

				if(url.indexOf('WCST_MB_inline') != -1){
					jQuery("#WCST_MB_ajaxContent").append(jQuery('#' + params['inlineId']).children());
					jQuery("#WCST_MB_window").bind('wcst_modal_unload', function () {
						jQuery('#' + params['inlineId']).append( jQuery("#WCST_MB_ajaxContent").children() ); // move elements back when you're finished
					});
					wcst_modal_position();
					jQuery("#WCST_MB_load").remove();
					jQuery("#WCST_MB_window").css({'visibility':'visible'});
				}else if(url.indexOf('WCST_MB_iframe') != -1){
					wcst_modal_position();
					jQuery("#WCST_MB_load").remove();
					jQuery("#WCST_MB_window").css({'visibility':'visible'});
				}else{
					var load_url = url;
					load_url += -1 === url.indexOf('?') ? '?' : '&';
					jQuery("#WCST_MB_ajaxContent").load(load_url += "random=" + (new Date().getTime()),function(){//to do a post change this load method
						wcst_modal_position();
						jQuery("#WCST_MB_load").remove();
						wcst_modal_init("#WCST_MB_ajaxContent a.wcstmodal");
						jQuery("#WCST_MB_window").css({'visibility':'visible'});
					});
				}

		}

		if(!params['modal']){
			jQuery(document).bind('keydown.wcstmodal', function(e){
				if ( e.which == 27 ){ // close
					wcst_modal_remove();
					return false;
				}
			});
		}

		$closeBtn = jQuery( '#WCST_MB_closeWindowButton' );
		/*
		 * If the native Close button icon is visible, move focus on the button
		 * (e.g. in the Network Admin Themes screen).
		 * In other admin screens is hidden and replaced by a different icon.
		 */
		if ( $closeBtn.find( '.wcst_modal_close_btn' ).is( ':visible' ) ) {
			$closeBtn.focus();
		}
                    
              
                if(jQuery("#WCST_MB_ajaxContent").innerHeight() > window.innerHeight){
                    jQuery("#WCST_MB_ajaxContent").height( (window.innerHeight * 90) / 100 );
                }

	} catch(e) {
		//nothing here
	}
}

//helper functions below
function wcst_modal_showIframe(){
	jQuery("#WCST_MB_load").remove();
	jQuery("#WCST_MB_window").css({'visibility':'visible'}).trigger( 'wcstmodal:iframe:loaded' );
}

function wcst_modal_remove() {
 	jQuery("#WCST_MB_imageOff").unbind("click");
	jQuery("#WCST_MB_closeWindowButton").unbind("click");
	jQuery( '#WCST_MB_window' ).fadeOut( 'fast', function() {
		jQuery( '#WCST_MB_window, #WCST_MB_overlay, #WCST_MB_HideSelect' ).trigger( 'wcst_modal_unload' ).unbind().remove();
		jQuery( 'body' ).trigger( 'wcstmodal:removed' );
	});
	jQuery( 'body' ).removeClass( 'modal-open' );
	jQuery("#WCST_MB_load").remove();
	if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
		jQuery("body","html").css({height: "auto", width: "auto"});
		jQuery("html").css("overflow","");
	}
	jQuery(document).unbind('.wcstmodal');
	return false;
}

function wcst_modal_position() {
var isIE6 = typeof document.body.style.maxHeight === "undefined";
jQuery("#WCST_MB_window").css({marginLeft: '-' + parseInt((WCST_MB_WIDTH / 2),10) + 'px', width: WCST_MB_WIDTH + 'px'});
	if ( ! isIE6 ) { // take away IE6
		jQuery("#WCST_MB_window").css({marginTop: '-' + parseInt((WCST_MB_HEIGHT / 2),10) + 'px'});
	}
}

function wcst_modal_parseQuery ( query ) {
   var Params = {};
   if ( ! query ) {return Params;}// return empty object
   var Pairs = query.split(/[;&]/);
   for ( var i = 0; i < Pairs.length; i++ ) {
      var KeyVal = Pairs[i].split('=');
      if ( ! KeyVal || KeyVal.length != 2 ) {continue;}
      var key = unescape( KeyVal[0] );
      var val = unescape( KeyVal[1] );
      val = val.replace(/\+/g, ' ');
      Params[key] = val;
   }
   return Params;
}

function wcst_modal_getPageSize(){
	var de = document.documentElement;
	var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
	var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	arrayPageSize = [w,h];
	return arrayPageSize;
}

function wcst_modal_detectMacXFF() {
  var userAgent = navigator.userAgent.toLowerCase();
  if (userAgent.indexOf('mac') != -1 && userAgent.indexOf('firefox')!=-1) {
    return true;
  }
}
