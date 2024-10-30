<?php 
if (isset($_POST["url"]) && isset($_POST["fb_like_button_type"]))

{
   $url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
   $button_type =  filter_var($_POST["fb_like_button_type"], FILTER_SANITIZE_STRING);
   $fb_button_code='';
  
   $fb_button_code = bst_share_it_build_fb_like_iframe($button_type, $url);
   $fb_array = array(
    	"fb_button_code" => $fb_button_code,
      "share_url"  => $url,
	);
	//---------------------------------------------------------------------------------------------------
    $json = json_encode($fb_array);
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
		<div class="fb-like" data-href="' . urlencode($url) . '" data-layout="' . $button_type . '" data-action="like" data-show-faces="false" data-share="false"></div></div>';
		return $fb_like;
};
	

function bst_share_it_build_fb_like_iframe ($button_type, $url) {
	
		$fb_like='<iframe src="http://www.facebook.com/plugins/like.php?href=' . urlencode($url) . '&amp;
					layout='. $button_type . '&amp;
					show_faces=false&amp;
					width=100&amp;
					action=like&amp;
					font=helvetica&amp;
					colorscheme=light&amp;
					height="25" scrolling="no" frameborder="0" 
					style="border:none; overflow:hidden; width:130px; height:25px;">
				</iframe>';
		return $fb_like;
};
?>
