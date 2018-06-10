// Call WordPress Media Uploader
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#chart_url').val(image_url);
            $('.viz-chart').attr('src',image_url);
            $('#remove-btn').css('display', 'inline-block');
        });
    });
    
    if ( $('#chart_url').val() == '' ) {
	    $('#remove-btn').css('display', 'none');
    }
    
    $('#remove-btn').click(function(e) {
	    e.preventDefault();
		$('#chart_urls').val('');
		$('.viz-chart').attr('src','');
    });
});