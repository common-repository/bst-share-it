<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
    

if (isset($_POST["post_id"]) && isset($_POST["share_url"]))
{

   	
	$scriptPath = dirname(__FILE__);
	$path = realpath($scriptPath . '/./');
	$path_array=explode('wp-content', $path);
	$filepath = $path_array[0];
	
	require ($path . '/bst_share_it_social_get_shares.php');
	define('WP_USE_THEMES', false);
	require(''.$filepath.'/wp-blog-header.php');

	//$post_id='3613';
    //$share_url='http://www.bst-systemtechnik.de/softwareentwicklung/individuelle-softwareentwicklung-giessen-wetzlar-marburg-hessen/';
    
	$post_id = filter_var($_POST["post_id"], FILTER_SANITIZE_NUMBER_INT);
    $share_url =  filter_var($_POST["share_url"], FILTER_SANITIZE_URL);
	 
	$sum_shares_array = bst_share_it_get_shares($share_url);
	 
	 
	$sum_shares_array["time"] = time(); //neuen Zeitstempel setzen
	$sum_shares_json = json_encode($sum_shares_array);

    add_post_meta( $post_id, 'bst_share_it_sum_shares', $sum_shares_json, true ) || update_post_meta( $post_id, 'bst_share_it_sum_shares', $sum_shares_json );
	
    
 

}


?>