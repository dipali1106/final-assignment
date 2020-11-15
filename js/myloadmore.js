
jQuery(document).ready(function($)
{ // use jQuery code inside this to avoid "$ is not defined" error
	$('.my_loadmore').click(function(){

		var button = $(this),
		    data = {
			'action': 'loadmore',
			'query': mi_loadmore_params.posts, // that's how we get params from wp_localize_script() function
			'page' : mi_loadmore_params.current_page
		};
 
		$.ajax({ // you can also use $.post here
			url : mi_loadmore_params.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				//window.alert("inside beforesend");
				button.text('Loading....'); // change the button text, you can also add a preloader image
				
			},
			success : function( data ){
				//console.log(1);
				if( data ) { 
					button.text( 'More posts' ).prev().after(data); // insert new posts
					//console.log(data);
					mi_loadmore_params.current_page++;
					if ( mi_loadmore_params.current_page == mi_loadmore_params.max_page ) 
						button.remove(); // if last page, remove the button
 
					// you can also fire the "post-load" event here if you use a plugin that requires it
					//$( document.body ).trigger( 'post-load' );
				} else {
					button.remove(); // if no data, remove the button as well
				}
			}
		});
	});
});