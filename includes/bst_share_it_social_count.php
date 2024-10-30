<?php 

 if (isset($_POST["url"]) && isset($_POST["show_facebook_like"]) && isset($_POST["fb_like_button_type"]))
{

   include_once 'bst_share_it_social_get_shares.php';
	
   $url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
   $show_facebook_like = filter_var($_POST["show_facebook_like"], FILTER_SANITIZE_NUMBER_INT);
   $fb_like_button_type = filter_var($_POST["fb_like_button_type"], FILTER_SANITIZE_STRING);
   $counter = filter_var($_POST["counter"], FILTER_SANITIZE_NUMBER_INT);

   $sum_shares_array = bst_share_it_get_shares($url);
   
   $fb_button_code='';
   if ($show_facebook_like==1){
	   $fb_button_code = bst_share_it_build_fb_like($fb_like_button_type, $url);
   };
   $options_array = array(
    	"fb_button_code" => $fb_button_code,
		"share_url"  => $url,
	);
	//---------------------------------------------------------------------------------------------------
   $share_array = array_merge($sum_shares_array, $options_array);

   $json = json_encode($share_array);

echo $json;

}		
	
	
function bst_share_it_build_fb_like_js ($button_type,$url) {
	
$fb_like='<div id="fb-container"><div id="fb-root"></div>
		  <script>(function(d, s, id) {
  			var js, fjs = d.getElementsByTagName(s)[0];
  			if (d.getElementById(id)) return;
  			js = d.createElement(s); js.id = id;
  			js.src = "//connect.facebook.net/de_DE/sdk.js#xfbml=1&version=v2.3";
  			fjs.parentNode.insertBefore(js, fjs);
			}(document, "script", "facebook-jssdk"));
        </script>
		<div class="fb-like" data-href="' . $url . '" data-layout="' . $button_type . '" data-action="like" data-show-faces="false" data-share="false"></div></div>';
		return $fb_like;
};
	

function bst_share_it_build_fb_like ($button_type,$url) {
	
$fb_like='<iframe src="http://www.facebook.com/plugins/like.php?href=' . $url . '&amp;
			layout=' . $button_type . '&amp;
			show_faces=false&amp;
			width=100&amp;
			action=like&amp;
			font=helvetica&amp;
			colorscheme=light&amp;
			height=25" scrolling="no" 
			frameborder="0" style="border:none; overflow:hidden; width:130px; height:25px;">
		</iframe>';
	
		return $fb_like;
};



	
?>
