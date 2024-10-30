jQuery(document).ready(function($) 
{
$('.fb_opengraph').hide();  
var stat = $('#fb_toggle').attr('checked');



if (stat) { 
           $('.fb_opengraph').show();          
        } else {
           $('.fb_opengraph').hide();  
        }


$('#fb_toggle').change(function(){
        var checked = $(this).attr('checked');
      
        if (checked) { 
           $('.fb_opengraph').show();          
        } else {
           $('.fb_opengraph').hide();  
        }
    });
    
    


$('.twitter_card').hide();  
var stat_twitter = $('#twitter_toggle').attr('checked');



if (stat_twitter) { 
           $('.twitter_card').show();          
        } else {
           $('.twitter_card').hide();  
        }


$('#twitter_toggle').change(function(){
        var checked_twitter = $(this).attr('checked');
      
        if (checked_twitter) { 
           $('.twitter_card').show();          
        } else {
           $('.twitter_card').hide();  
        }
    });

















      
         
});

