jQuery(document).ready(function($){

	jQuery( document ).on('click', '.ccre_logo_uploader', function() {
		
		var imgfield,showimgfield;
		imgfield = jQuery(this).prev('input').attr('id');
		showimgfield = jQuery(this).next('div').attr('id'); //show uploaded image
    	
		if(typeof wp == "undefined" || Ccre.new_media_ui != '1' ){// check for media uploader
				
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	    	
			window.original_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html) {
				
				if(imgfield)  {
					
					var mediaurl = jQuery('img',html).attr('src');
					jQuery('#'+imgfield).val(mediaurl);
					jQuery('#'+showimgfield).html('<img src="'+mediaurl+'" alt="Image" />');
					tb_remove();
					imgfield = '';
					
				} else {
					
					window.original_send_to_editor(html);
					
				}
			};
	    	return false;
			
		      
		} else {
			
			var file_frame;
			
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
			  return;
			}
	
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				multiple: false  // Set to true to allow multiple files to be selected
			});
	
			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};
	
		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
	
		        // Initialize the views in our view object.
		        view.set(views);
		    });
	
			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {
	
				// Get selected size from media uploader
				var selected_size = jQuery('.attachment-display-settings .size').val();
				
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					
					// Selected attachment url from media uploader
					var attachment_url = attachment.sizes[selected_size].url;
					
					if(index == 0){
						// place first attachment in field
						jQuery('#'+imgfield).val(attachment_url);
						jQuery('#'+showimgfield).html('<img src="'+attachment_url+'" alt="Image" />');
					} else{
						jQuery('#'+imgfield).val(attachment_url);
						jQuery('#'+showimgfield).html('<img src="'+attachment_url+'" alt="Image" />');
					}
				});
			});
	
			// Finally, open the modal
			file_frame.open();
			
		}
		
	});
	
	$('body.post-type-ccre-campaigns.edit-php .wp-heading-inline').after('<a href="https://ridime.com/plugin" class="page-title-action ccre_settings_btn" target="_blank">'+Ccre.documentation+'</a>');
	
	$('body.post-type-ccre-campaigns.edit-php .wp-heading-inline').after('<a href="edit.php?post_type=ccre-campaigns&page=ccre-settings" class="page-title-action ccre_settings_btn">'+Ccre.settings+'</a>');
	
	if( Ccre.show_success )
	{
		$('body.post-type-ccre-campaigns.edit-php .wp-header-end').after('<div class="updated ccre_success_msg" id="message"><p><strong>'+Ccre.success+'</strong></p></div>');
	}
	else
	{
		if( Ccre.error != '' )
		{
			$('body.post-type-ccre-campaigns.edit-php .wp-header-end').after('<div class="updated ccre_error_msg" id="message"><p><strong>'+Ccre.error+'</strong></p></div>');
		}
	}
	
	$(document).on( 'change', '#ccre_enable_min_amount', function(){
		
		if( this.checked )
		{
			$('.ccre_min_amount_wrap').fadeIn();
		}
		else
		{
			$('.ccre_min_amount_wrap').fadeOut();
		}
	});
	
	$(document).on( 'change', '#ccre_enable_products', function(){
		
		if( this.checked )
		{
			$('.ccre_products_wrap').fadeIn();
		}
		else
		{
			$('.ccre_products_wrap').fadeOut();
		}
	});
	
	if($('#ccre_products').length)
	{
		$('#ccre_products').select2({
			placeholder: "Type any keyword to search product",
			ajax: {
				url: Ccre.ajaxurl,
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'ccre_search' // AJAX action for admin-ajax.php
					};
				},
				processResults: function (data) {
					var options = [];
					
					if ( data ) {
						
						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1] } );
						});
					
					}
					return {
						results: options
					};
				}
			},
			minimumInputLength: 3,
		});
	}

	if($('#ccre_category').length)
	{
		$('#ccre_category').select2({
			placeholder: "Type any keyword to search category",
			ajax: {
				url: Ccre.ajaxurl,
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'ccre_search_category' // AJAX action for admin-ajax.php
					};
				},
				processResults: function (data) {
					var options = [];
					
					if ( data ) {
						
						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1] } );
						});
					
					}
					return {
						results: options
					};
				}
			},
			minimumInputLength: 3,
		});
	}
	$(document).on( 'click', '.ccre_refund', function(){
		
		var ccre_refund = $(this);
		
		ccre_refund.hide();
		
		ccre_refund.next().fadeIn();
		
		var data = { 
						action  : 'ccre_refund',
						uuid    : ccre_refund.attr('data-uuid'),
						camp_id : ccre_refund.attr('data-camp')
					};
    	
    	$.post( Ccre.ajaxurl, data, function( response ){
    		
			ccre_refund.next().hide();
		
			ccre_refund.closest('tr').find('.ccre_reserved').empty();
			ccre_refund.closest('tr').find('.ccre_status').html(Ccre.burned);
			
			ccre_refund.parent().html(Ccre.refunded);
			
    		if( response == 'success' )
    		{
    			alert(Ccre.refund_success);
    		}
    		else
    		{
    			alert(response);
    		}
    	});
	});
});