jQuery(document).ready(function($) 
{
   

 //entferne $info_text
        var itotal=0;
    
        itotal=$( ".bst_share_it_container" ).width()-20;
        if (itotal <= 500) {$(".bst_share_it_container").find("div.header_box2").text('Share it and like it');}; 
     
 

    var show_facebook_like = $('#show_facebook_like').attr("name"); //Facebook Like Button anzeigen ja oder nein
    var fb_like_button_type = $('#type_facebook_like').attr("name"); //Facebook Like Button anzeigen ja oder nein

    var share_counter=0;
    share_counter = $('#flagg_share_counter').val();
    var count_url = $('#count_url').val();
    var update_url = $('#update_url').val();
    var post_id = $('#post_id').val();
    var stat = $('#dt_start').val();
    var flagg_shadow = $('#flagg_shadow').val();
    var dt_status_page = $('#dt_status_page').val();
    var surl= $(location).attr("href");
    surl = surl.replace('#1', '');
    var set_flagg="1";
    var iwidth= $('#iwidth').val();
    var style =$('#style').val();

    $("dt").click(function(event){ // trigger
         event.preventDefault();  //verhindert #1 in url
        
         var flagg_button_build=$('#flagg_button_build').attr("name");
    
         $(this).parent("div").parent("div").parent("dl").find("dd").slideToggle("fast"); // blendet beim Klick auf "dt" die nächste "dd" ein. 
         $(this).find("a").toggleClass("bst_share_it-button_open bst_share_it-button_closed"); // wechselt beim Klick auf "dt" die Klasse des enthaltenen a-Tags von "closed" zu "open". 
         
         if(flagg_shadow=1) 
            {$(this).parent("div").parent("div").parent("dl").find("div#fs").toggleClass("flex-divcontainer_footer_closed flex-divcontainer_footer_open_without_shadow");}
         else
            {$(this).parent("div").parent("div").parent("dl").find("div#fs").toggleClass("flex-divcontainer_footer_closed flex-divcontainer_footer_open");}

         // toggle farbe sum_shares
         $(this).parent("div").parent("div").find("#flex-divcontainer-rechts").children("div").toggleClass("social_counter_header_red social_counter_header_green"); // wechselt beim Klick auf "dt" die Klasse des enthaltenen a-Tags von "closed" zu "open". 
	  
	 });
	 
   $("dd").hide();
   
   
   $("#enable_fb_like").click(function(){ // enable fb like

        var like_url = $('#like_url').val();
        var fb_like_button_type = $('#type_facebook_like').attr("name");
        //alert (fb_like_button_type);
        var url= $(location).attr("href");
        url = url.replace('#1', '');
        $.post(like_url, {url:url, fb_like_button_type:"button_count"},  function(data) {
            var json=$.parseJSON(data);
            $("#facebook_like").html(json.fb_button_code);
        });
        return false;
   
  });
  
   $("#enable_fb_like_widget").click(function(){ // enable fb like

        var like_url = $('#like_url').val();
        var fb_like_button_type = $('#type_facebook_like').attr("name");
        //alert (fb_like_button_type);
        var url= $(location).attr("href");
        url = url.replace('#1', '');
        $.post(like_url, {url:url, fb_like_button_type:"button_count"},  function(data) {
            var json=$.parseJSON(data);
            $("#facebook_like_widget").html(json.fb_button_code);
        });
        return false;
   
  });
  
  
window.onresize = doResize;
function doResize() {
     
    var itotal=0;
    var itotal_widget=0;
    itotal=$( ".bst_share_it_container" ).width()-20;
    itotal_widget=$(".bst_share_it_container_widget" ).width()-12;
    

    if((style=='simple1') || (style=='simple2')) 
      {

        var button_in_line = 0;
        var button_in_line_widget=0;
        
        if (itotal <= 300) {button_in_line=2} 
        
        else if ((itotal >300) && (itotal <=500)) {button_in_line=3}
        
        else if ((itotal >500) && (itotal <=600)) {button_in_line=4}
        
        else if ((itotal >600) && (itotal <=700)) {button_in_line=5}
        
        else if ((itotal >700) && (itotal <=800)) {button_in_line=6}
        
        else if (itotal > 800) {button_in_line = 8};
        
        //entferne $info_text
        if (itotal <= 500) {$(".bst_share_it_container").find("div.header_box2").text('Share it and like it');}; 
        
        var button_width= Math.floor(itotal/button_in_line)-button_in_line;
        
        if (itotal_widget<=300) {button_in_line_widget = 2};
        var button_widget_width= Math.floor(itotal_widget/button_in_line_widget)-button_in_line_widget;
        
        
      // alert(button_width);
      //alert( button_widget_width);
       // alert(button_in_line);
       
        $("#facebook_sw").width(button_width);
        $("#twitter_sw").width(button_width);
        $("#gplus_sw").width(button_width);
        $("#pin_sw").width(button_width);
        $("#in_sw").width(button_width);
        $("#xing_sw").width(button_width);
        $("#stumble_sw").width(button_width);
        $("#mail_sw").width(button_width);
        
        $("#facebook_sw_widget").width(button_widget_width);
        $("#twitter_sw_widget").width(button_widget_width);
        $("#gplus_sw_widget").width(button_widget_width);
        $("#pin_sw_widget").width(button_widget_width);
        $("#in_sw_widget").width(button_widget_width);
        $("#xing_sw_widget").width(button_widget_width);
        $("#stumble_sw_widget").width(button_widget_width);
        $("#mail_sw_widget").width(button_widget_width);
        
        var button_container_width = button_in_line * 5 + button_in_line * button_width;  
        $("#flex-divcontainer-inner").width(button_container_width);
        $(".info_flat").width(button_container_width);
        $(".info_flat_widget").width(itotal_widget);
        
       


        $(".bst_share_it_container").find('.social_counter').css('margin-left',button_width-25);
        $(".bst_share_it_container").find('.social_counter').css('margin-top',6);
        $(".bst_share_it_container").find('.social_counter').css('margin-left',button_width-25);
        $(".bst_share_it_container").find('.social_counter').css('margin-top',6);
        
        if ( $(".bst_share_it_container_widget").length) {$(".bst_share_it_container_widget").find('.social_counter').css('margin-left',button_widget_width-25);};
        if ( $(".bst_share_it_container_widget").length) {$(".bst_share_it_container_widget").find('.social_counter').css('margin-top',6);};
        if ( $(".bst_share_it_container_widget").length) {$(".bst_share_it_container_widget").find('.social_counter').css('margin-left',button_widget_width-25);};
        if ( $(".bst_share_it_container_widget").length) {$(".bst_share_it_container_widget").find('.social_counter').css('margin-top',6);};
        
        
        if (button_width<=80) {$("span.sw_link").hide();} else {$("span.sw_link").show();};
        if (button_widget_width<=80) { $("span.sw_link_widget").hide();} else {$("span.sw_link_widget").show();};
         
            
        
        $( "#spacer_before_widget" ).remove();
        $( "#spacer_a_widget" ).remove();
        

      }
    
   else
      {
          var button_anzahl=Math.floor(itotal/iwidth);
         
          if(button_anzahl>8) {button_anzahl=8};  
          var button_container_width = button_anzahl * 5 + button_anzahl * iwidth;  
          //$("#flex-divcontainer-inner").width(button_container_width);
      };
}
  
  
   //Clickfunktion init für flex-item
   $('.flex-item').click(function() {      
      window.location = $(this).find("a").attr("href");
   
      var toolTip = $(this).find("a").attr("title");
      $("#show").html ( toolTip );
      
      $('#show').html($('#' + $(this).attr('aria-describedby')).children().html());
      
      return false;  
   });
   
   //Tooltip zeigen
   $('.flex-item').hover( function() {
        var toolTip = $(this).find("a").attr("title");
        $('.flex-item').attr("title", toolTip);
   });
   
   

$(window).trigger('resize');


});

  
function SocialPopUp(Dateiname,PopUpBreite,PopUpHoehe)
{
   
    //alert(Dateiname);
    sbreite = screen.availWidth;
    shoehe = screen.availHeight;
    x = (sbreite-PopUpBreite)/2;
    y = (shoehe-PopUpHoehe)/2;
    Eigenschaften="left="+x+",top="+y+",screenX="+x+",screenY="+y+",width="+PopUpBreite+",height="+PopUpHoehe+",menubar=no,toolbar=no";
    
    var fenster=window.open(Dateiname,"Titel",Eigenschaften);
    fenster.focus();

(function($) {


    var timer = setInterval(function() {   
       if(fenster.closed) {  
            clearInterval(timer);  
           
           // alert('popup is closed'); 
            
          var show_facebook_like = $('#show_facebook_like').attr("name"); //Facebook Like Button anzeigen ja oder nein
          var fb_like_button_type = $('#type_facebook_like').attr("name"); //Facebook Like Button anzeigen ja oder nein

          var share_counter=0;
          share_counter = $('#share_counter').val();
          var post_id = $('#post_id').val();
          var surl= $(location).attr("href");
          surl = surl.replace('#1', '');
          var count_url = $('#count_url').val();
          var update_url = $('#update_url').val();
          
          //alert(count_url);
            
          $.post(count_url, {url:surl, fb_like_button_type:fb_like_button_type, show_facebook_like:show_facebook_like, counter:share_counter},  function(data) {
                            var json = $.parseJSON( data );
                               
                            //alert(surl);   
                                                                                 
                            $("#fb_counter span").text(json.fb);
                            $("#twitter_counter span").text(json.twitter);
                            $("#gplus_counter span").text(json.gplus);
                            $("#pin_counter span").text(json.pin);
                            $("#in_counter span").text(json.in);
                            $("#xing_counter span").text(json.xing);
                            $("#stumble_counter span").text(json.stumble);
                            
                            //alert(update_url); 
                            $.post(update_url, {post_id:post_id, share_url:surl}); // End $.post()
                            $("#social_counter_sum_shares span").text(json.sum_shares);
                       
                       }); // End $.post()
             
             
        }  
    }, 1000); 

})(jQuery);

}



  
