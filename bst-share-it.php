<?php
/*
Plugin Name: BST share iT
Plugin URI: http://www.bst-systemtechnik.de/wordpress-plugin-rechtskonforme-social-sharing-buttons-mit-bstshareit/
Description: BSTshareiT - Die Social-Sharing-Buttons die nicht nach Hause telefonieren und geltendes deutsches Recht beachten.
Version: 1.0.9
Author: BST Systemtechnik (Arnold Margolf)
Author URI: http://www.bst-systemtechnik.de/wordpress-plugin-rechtskonforme-social-sharing-buttons-mit-bstshareit/
Copyright 2015 by Arnold Margolf (amargolf@bst-systemtechnik.de)
*/

if (!defined('bst_share_it_PLUGIN_NAME')) define('bst_share_it_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
if (!defined('bst_share_it_PLUGIN_DIR')) define('bst_share_it_PLUGIN_DIR', WP_PLUGIN_DIR . '/bst-share-it');
if (!defined('bst_share_it_PLUGIN_URL')) define('bst_share_it_PLUGIN_URL', WP_PLUGIN_URL . '/' .bst_share_it_PLUGIN_NAME);
if (!defined('bst_share_it_VERSION')) define('bst_share_it_VERSION', 'v' . '1.0.5');
  
    
  
function bst_share_it_add_js_css(){
        //register scripts ----------------------------
		wp_register_script('bst_share_it_bst-js', plugins_url('js/bst.js', __FILE__ ), false,'1.0', 'all' );
		wp_register_script('bst_share_it_cp-js', plugins_url('tools/cp/js/colpick.js', __FILE__ ), false,'1.0', 'all' );
		wp_register_script('bst_share_it_fl-js', plugins_url('tools/fl/js/featherlight.min.js', __FILE__ ), false,'1.0', 'all' );
		
		//register stylesheets ------------------------
		wp_register_style('bst_share_it', plugins_url('css/bst_share_it.css', __FILE__ ), false,'1.0', 'all' );
		wp_register_style('bst_share_it_cp', plugins_url('tools/cp/css/colpick.css', __FILE__ ), false,'1.0', 'all' );
		wp_register_style('bst_share_it_fl', plugins_url('tools/fl/css/featherlight.min.css', __FILE__ ), false,'1.0', 'all' );
		
		// ---> enqueue the stuff ############################################################################
		// Scripts
		wp_enqueue_script('bst_share_it_bst-js');
		wp_enqueue_script('bst_share_it_cp-js');
		wp_enqueue_script('bst_share_it_fl-js');
		// Styles
		wp_enqueue_style('bst_share_it');
		wp_enqueue_style('bst_share_it_cp');
		wp_enqueue_style('bst_share_it_fl');
		}


function bst_share_it_add_js_css_admin(){
     
		// ---> register scripts ----------------------------
		wp_register_script('bst_share_it_cp-js', plugins_url('tools/cp/js/colpick.js', __FILE__ ), false,'1.0', 'all' );
		wp_register_script('bst_share_it_admin-js', plugins_url('js/admin.js', __FILE__ ), false,'1.0', 'all' );
		wp_register_script('bst_share_it_upload-js', plugins_url('js/upload.js', __FILE__ ), false,'1.0', 'all' );
		
		// ---> register stylesheets ------------------------
	    wp_register_style('bst_share_it_cp', plugins_url('tools/cp/css/colpick.css', __FILE__ ), false,'1.0', 'all' );
		wp_register_style('bst_share_it_admin-css', plugins_url('css/bst_share_it-admin.css', __FILE__ ), false,'1.0', 'all' );
		
		// ---> enqueue the stuff ############################################################################
		// Scripts
		wp_enqueue_script('bst_share_it_cp-js');
		wp_enqueue_script('bst_share_it_admin-js');
		wp_enqueue_script('bst_share_it_upload-js');
		
		wp_enqueue_style('bst_share_it_cp');
		wp_enqueue_style('bst_share_it_admin-css');
		
		// ---> enable the build in media handling
		wp_enqueue_media();
		}


// Load translations
// Load localization domain
//load_plugin_textdomain( 'bst_share_it', false, '/bst_share_it/languages/' );


function bst_share_it_plugin_setup () {
	
	load_plugin_textdomain('bst_share_it', false, 'bst-share-it/languages');
}
add_action('init','bst_share_it_plugin_setup');


//Wird beim Deaktivieren erledigt --------------------------
function bst_share_it_deactivate()
{
	//unregister_setting('bst_share_it-options-group', 'bst_share_it-options', $sanitize_callback );
	// delete_option( 'bst_share_it_options' ); 
echo "Plugin deaktiviert";
   }

register_deactivation_hook(__FILE__, 'bst_share_it_deactivate');


//Wird beim Aktivieren erledigt --------------------------
function bst_share_it_activate()
{
//print "AKTIVE";
}
register_activation_hook(__FILE__, 'bst_share_it_activate');
//--------------------------------------------------------


//Wird beim Deinstallieren erledigt ----------------------
function bst_share_it_uninstall() {
    delete_option( 'bst_share_it_options' );
	delete_option( 'bst_share_it_options_tab_1' );
	delete_option( 'bst_share_it_options_tab_2' );
	delete_option( 'widget_bst_share_it' );
	//global $wpdb;
	//$table_name = $wpdb->prefix . "xxx";
	//$sql = "DROP TABLE IF EXISTS ".$table_name;
	//$wpdb->query($sql);
}
register_uninstall_hook(__FILE__, 'bst_share_it_uninstall');
//--------------------------------------------------------



function bst_share_it_GetCurrentURLDir() {
  $url = 'http://' .$_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
  return $url;
}




if (is_admin()) 
	{
		add_action('admin_enqueue_scripts', "bst_share_it_add_js_css_admin"); 
		include_once('includes/bst_share_it_admin.php');
	}
	else 
	{
		add_action('wp_enqueue_scripts', "bst_share_it_add_js_css");
		include_once('includes/bst_share_it_widget.php');
	}
	include_once('includes/bst_share_it_widget.php');

?>