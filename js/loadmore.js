jQuery(function($){
	var canBeLoaded = true, // this param allows to initiate the AJAX call only if necessary
	    bottomOffset = 1000; // the distance (in px) from the page bottom when you want to load more posts
 
	$(window).scroll(function(){
		var data = {
			'action': 'loadmore',
			'query': tji_loadmore_params.posts,
			'page' : tji_loadmore_params.current_page
		};
		if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == true ){
			$.ajax({
				url : tji_loadmore_params.ajaxurl,
				data:data,
				type:'POST',
				beforeSend: function( xhr ){
					// you can also add your own preloader here
					// you see, the AJAX call is in process, we shouldn't run it again until complete
					canBeLoaded = false; 
				},
				success:function(data){
					if( data ) {
						//$('#main').find('article:last-of-type').after( data ); // where to insert posts
						$('.story-sections').find('.story-section:last-of-type').after( data ); // where to insert posts
						canBeLoaded = true; // the ajax is completed, now we can run it again
						tji_loadmore_params.current_page++;
					}
				}
			});
		}
	});

	$(".viz-link").click(function(){
		
		var button = $(this);
		var storyId = button.attr("href").substring(1);
		if ($("#" + storyId).length != 0) {
			console.log("Already loaded");
		} else {
			console.log("Not loaded yet");
		}
		console.log(storyId);
		
		
		var data = {
			'action': 'loadmore',
			'query': 'post_name=' + storyId + '&post_type=tji_viz',
		};
		
		$.ajax({
			url : tji_loadmore_params.ajaxurl,
			data:data,
			type:'POST',
			beforeSend: function( xhr ){
				// you can also add your own preloader here
				// you see, the AJAX call is in process, we shouldn't run it again until complete
				canBeLoaded = false; 
			},
			success:function(data){
				if( data ) {
					//$('#main').find('article:last-of-type').after( data ); // where to insert posts
					$('.story-sections').find('.story-section:last-of-type').after( data ); // where to insert posts
					canBeLoaded = true; // the ajax is completed, now we can run it again
					tji_loadmore_params.current_page++;
				}
			}
		});
	});
});