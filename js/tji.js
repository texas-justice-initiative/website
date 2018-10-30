jQuery(document).ready(function($) {
	
	//Define initial variables
	var taglineHeight = document.querySelector(".tji-about-brief").offsetTop;
	var storyStart = document.querySelector(".story-sections").offsetTop;
	var panelClosed = $(".nav-panel").hasClass("closed");
	var navPanel = $(".nav-panel");

	//Setup scroll functions for homepage	
	$(window).scroll(function() {
		var height = $(window).scrollTop();

		//Toggle header
		/*
		if ( height > taglineHeight ) {
			$("header").addClass("fixed");
			$(".custom-logo-link img").css("opacity", "1");
			$(".main-navigation li").css("float","left");
			$(".main-navigation li").css("margin-top","35px");
		} else {
			$("header").removeClass("fixed");
			$(".custom-logo-link img").css("opacity", "0");
			$(".main-navigation li").css("float","none");
			$(".main-navigation li").css("margin-top","0");
		}
		*/		
		
		//Display or show nav panel
		if ( height > storyStart - 100) {
			$(".story-wrapper").addClass("story-mode");
			$(".nav-panel").addClass("story-mode");
			$(".story-sections").addClass("story-mode");
		} else {
			$(".story-wrapper").removeClass("story-mode");
			$(".nav-panel").removeClass("story-mode");
			$(".story-sections").removeClass("story-mode");
			$(".viz-link").removeClass("active");
			$(".nav-link").removeClass("active");
		}
	});
	
	//Nav panel toggle switch
	$(".panel-toggle a").click(function(e) {
		e.preventDefault();
		
		panelClosed = $(".nav-panel").hasClass("closed");
		
		if (panelClosed) {
			navPanel.removeClass("closed");
			$(".panel-toggle a").html("&rarr;");
			$(".story-sections").removeClass("closed");
		} else {
			navPanel.addClass("closed");
			$(".panel-toggle a").html("&larr;");
			$(".story-sections").addClass("closed");
		}
	});
	
	//Highlight nav links when section is active
	$(".story-section").mouseenter(function() {
		var section = $(this).attr("id");
		var link = $("[href=#" + section + "]").parent();
		
		$(".viz-link").removeClass("active");
		$("[href=#" + section + "]").addClass("active");
		link.addClass("active");		
	});
	
	$(".viz-link").click(function() {
		$(".nav-link").removeClass("active");
		$(".viz-link").removeClass("active");
		$(this).addClass("active");
		$(this).parent().addClass("active");
	});
	
});