<?php
function bst_share_it_get_shares($url){
   
    //if ($counter==1){
   	$fb = bst_share_it_get_fb($url);
   	$twitter = bst_share_it_get_tweets($url);
   	$gplus = bst_share_it_get_plusones($url);
   	$pin= bst_share_it_get_pins($url);
   	$in= bst_share_it_get_ins($url);
   	$xing= bst_share_it_get_xings($url);
   	$stumble= bst_share_it_get_stumbles($url);
	//Summe der Shares
	$sum_shares=$fb+$twitter+$gplus+$pin+$in+$xing+$stumble;

   	$share_array = array(
    	"fb" => $fb,
    	"twitter" => $twitter,
    	"gplus" => $gplus,
    	"pin" => $pin,
    	"in" => $in,
    	"xing" => $xing,
		"stumble" => $stumble,
		"mail" => 0,
		"sum_shares" => $sum_shares,
		"time" => time(),
 	);
   
   	//$json = json_encode($share_array);

//echo $json;

	return $share_array;

}		
	

			
function bst_share_it_get_tweets($url) {
 
 
 //	$tw_url = "https://api.twitter.com/1.1/search/tweets.json?q=" . $url;
 //  $json_string = file_get_contents($tw_url);
 // $json = json_decode($json_string, true);
 // return intval( $json['count'] );


   
   return 3;
}
 
function bst_share_it_get_fb($url) {
 	
 	$fb_url = "http://api.facebook.com/restserver.php?format=json&method=links.getStats&urls=".urlencode($url);
    $json = json_decode(file_get_contents($fb_url),true);

    $shares = $json[0]['share_count'];
	$likes= $json[0]['like_count'];
    return $shares;
 
    //return intval( $json['share_count'] );
 }
 
function bst_share_it_get_plusones($url) {
 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        $curl_results = curl_exec ($curl);
        curl_close ($curl);

        $json = json_decode($curl_results, true);

        return intval( $json[0]['result']['metadata']['globalCounts']['count'] );
}

function bst_share_it_get_pins($url) {
    
    $pin_url ='http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $url;
    $json_string = file_get_contents($pin_url);
    $json_string = preg_replace("/[^(]*\((.*)\)/", "$1", file_get_contents($pin_url));
    $json = json_decode($json_string, true);
 
    return intval( $json['count'] );
  
}

function bst_share_it_get_ins($url) {
 
    $json_string = file_get_contents('http://www.linkedin.com/countserv/count/share?url=' . $url . '&format=json');
    $json = json_decode($json_string, true);
 
   return intval( $json['count'] );
}

function bst_share_it_get_stumbles($url) {
 
    $dataUrl= 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $url;
	$html_string = file_get_contents($dataUrl);
	$ss=bst_share_it_get_sshares($html_string);
	
    return $ss;
}


function bst_share_it_get_xings($url)
   {
   
   $dataUrl='https://www.xing-share.com/app/share?op=get_share_button;counter=top;url=' . $url;
   $html_string = file_get_contents($dataUrl);
   $xs= bst_share_it_get_xshares($html_string);
   return  $xs;
   }
    

// Hilfsfunction um Xing Sahres auszulesen -----	
function bst_share_it_get_xshares ($SORURCE){
		$pos1_temp = strpos($SORURCE, '<span class="xing-count top">');
		if ($pos1_temp == false) {
			return 0;
		}
		$pos1=strpos($SORURCE, '>', $pos1_temp)+1;
		$pos2 = strpos($SORURCE, '<', $pos1);
		return intval( trim(substr($SORURCE, $pos1,  $pos2-$pos1)));
	}
	
	
// Hilfsfunction um stumbles auszulesen -----	
function bst_share_it_get_sshares ($SORURCE){
		$pos1_temp = strpos($SORURCE, 'views');
		if ($pos1_temp == false) {
			return 0;
		}
		$pos1=strpos($SORURCE, ':', $pos1_temp)+1;
		$pos2 = strpos($SORURCE, ',', $pos1);
		return intval( trim(substr($SORURCE, $pos1,  $pos2-$pos1)));
	}
	
?>