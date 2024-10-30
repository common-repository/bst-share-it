jQuery(document).ready(function($){
 
 
    var custom_uploader;
 
 
    $('#upload_image_button').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Bild auswahlen oder vom PC hochladen',
            button: {
                text: 'Bild auswählen'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').prop('readonly',false);
            $('#upload_image').val(attachment.url);
            $('#upload_image').prop('readonly',true);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
    
    
    
    
    
    
   
     
       $('#bst_share_it_image_remove_button').click(function(e) {
          $(this).closest('p').prev('.bst_share_it_MetaImage').html('');   
          $(this).prev().prev().val('');
          return false;
        });
     
        
    $('#bst_share_it_image_upload_button').click(function(e) {
 
   
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Bild auswahlen oder vom PC hochladen',
            button: {
                text: 'Bild auswählen'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
           
            $('.bst_share_it_image_metaValueField').val(attachment.url);
            
            $('.bst_share_it_MetaImage').html('<p>URL: '+ attachment.url + '</p>');  
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
        
 
});