<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

    if (isset($_POST["post_id"]) && isset($_POST["share_url"]))
      {

		//$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
		//define('WP_USE_THEMES', false);
		//require($parse_uri[0] . 'wp-blog-header.php');
		include_once 'bst_share_it_social_get_shares.php';
		
		//bst_alert("TEST") ;
	
        $post_id = filter_var($_POST["post_id"], FILTER_SANITIZE_NUMBER_INT);
        $share_url = filter_var($_POST["share_url"], FILTER_SANITIZE_URL);
	
        $sum_shares_array = bst_share_it_get_shares($share_url);
        $sum_shares_array["time"] = time(); //neuen Zeitstempel setzen
        $sum_shares_json = json_encode($sum_shares_array);

        add_post_meta( $post_id, 'bst_share_it_sum_shares', $sum_shares_json, true ) || update_post_meta( $post_id, 'bst_share_it_sum_shares', $sum_shares_json );
        return;
      } 

	//Globale Variablen initialisieren------------------------------------------------------------------
	if(!get_option('bst_share_it_options_tab_1')) {
        //not present, so add
        $options_tab_1 = array(
          'facebook' => '1',
					'facebook_like' => '1',
  				'twitter' => '1',
					'gplus' => '1',
					'pin' => '1',
					'in' => '1',
					'xing' => '1',
					'stumble' => '1',
					'mail' => '1',
					'fb_opengraph' => '1',
					'fb_admin' => '',
					'fb_article_author' => '',
					'fb_article_publisher' => '',
					'fb_app_id' => '',
					'btn_onposts' => '1',
					'btn_onpages' => '1',
					'px_before' => '',
					'px_after' => '',
					'global_picture' => '',
					'share_counter' => '1',
					'twitter_card' => '',
					'twitter_site' => '',
					'twitter_creator' => '',
					
        );} else {$options_tab_1 = get_option('bst_share_it_options_tab_1');}; //Globale Einstellungen des Plugins laden }
	
	if(!get_option('bst_share_it_options_tab_2')) {
        //not present, so add
        $options_tab_2 =  array(
					'info_bgcolor' => '1d325f',
					'info_fontcolor' => 'e0e0e0',
					'buttons_bgcolor' => '',	
					'shadow' => '1',
					'buttons_style' => 'style1',
					
        );} else {$options_tab_2 = get_option('bst_share_it_options_tab_2');}; //Globale Einstellungen des Plugins laden }
	
	//zusaätzliche Optionen ----------------------------------------------------------------------------

	$options_array = array(
    "build_css" => '0',
		"description"  => '',
		"description_twitter" => '',
		"sharing_image" => '',
		"post_id" => 0,
	);
	//---------------------------------------------------------------------------------------------------
	
	$bst_array = array_merge($options_tab_1, $options_tab_2, $options_array);
	$GLOBALS["bst_share_it_array"] = $bst_array;
	$GLOBALS["bst_share_it_array"]["build_css"]='0';

// *****************************************************************
// Vorarbeiten - Ersetzen des og:image -----------------------------
// *****************************************************************		
	 	
	add_action('get_header', 'add_bst_share_it_start');
	add_action('wp_head', 'add_bst_share_it_end_flush',1000);
	
	if ($GLOBALS["bst_share_it_array"]['fb_opengraph']==1) {add_action('wp_head', 'bst_share_it_add_action_og_metatags', 1001);}
	
	function add_bst_share_it_start() {
		
		$GLOBALS["bst_share_it_array"]["post_id"]=get_the_ID();
		
		//Farben mit # versehen
		If ($GLOBALS["bst_share_it_array"]["buttons_bgcolor"] !='') {$GLOBALS["bst_share_it_array"]["buttons_bgcolor"]='#'.$GLOBALS["bst_share_it_array"]["buttons_bgcolor"];}
		If ($GLOBALS["bst_share_it_array"]["info_bgcolor"] !='') {$GLOBALS["bst_share_it_array"]["info_bgcolor"]='#'.$GLOBALS["bst_share_it_array"]["info_bgcolor"];}
		If ($GLOBALS["bst_share_it_array"]["info_fontcolor"] !='') {$GLOBALS["bst_share_it_array"]["info_fontcolor"]='#'.$GLOBALS["bst_share_it_array"]["info_fontcolor"];}
	    bst_share_it_PUT_Sharing_Image_to_GLOBALs(); // Sharing zur weiteren Verwendung in Globale Vaiablen laden      -
		
		
		
		if ($GLOBALS["bst_share_it_array"]['twitter_card']==1) // Twittercard wird von bst_share_it-buttons Plugin gesetzt!!!    - 
			{
			ob_start('bst_share_it_twitter_card_delete'); // löschen der Twittercard von anderem Plugin    -                   
			} 

		
		
		
		if ($GLOBALS["bst_share_it_array"]['fb_opengraph']==1) // opengraphtags werden vom bst_share_it-buttons Plugin gesetzt!!!    - 
			{
			ob_start('bst_share_it_og_metatags_delete'); // löschen der der og tags von anderem Plugin    -                   
			} 
		else // og:tags werden von anderem plugin geliefert, lediglich das og:immage wird ausgetauscht!!! -
			{ 
			ob_start('bst_share_it_og_image_setzen');
			}
	}
	
	function add_bst_share_it_end_flush() {
    	ob_end_flush();
	}
	
	// Hilfsfunction um die ID von einer gegebenen Bild URL zu bekommen -----	
	function bst_share_it_get_attachment_id_from_src ($image_src) {

		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;
	}
	
	function bst_alert($msg) 
    {
        echo '<script type="text/javascript">alert("' . $msg . '"); </script>';
    }
	
	

	// Alte Twitter Card wird aus dem Html Code entferent ----
	function bst_share_it_twitter_card_delete($output) {
				
		// Entfernen eventueller alter Twitter Card Tags ---
		$output = bst_share_it_DELETE_og('<meta name="twitter:card', $output); 
		$output = bst_share_it_DELETE_og('<meta name="twitter:description"', $output); 
		$output = bst_share_it_DELETE_og('<meta name="twitter:title"', $output); 
		$output = bst_share_it_DELETE_og('<meta name="twitter:site"', $output); 
		$output = bst_share_it_DELETE_og('<meta name="twitter:domain"', $output); 
		$output = bst_share_it_DELETE_og('<meta name="twitter:image:src"', $output); 
		$output = bst_share_it_DELETE_og('<meta name="twitter:creator"', $output); 
		$output = bst_share_it_DELETE_og('<meta name="twitter:image:width"', $output);
		$output = bst_share_it_DELETE_og('<meta name="twitter:image:height"', $output);      
		
	return $output;
	
	}	
	
	
	// Opengraphtags werden mit dieser Hilfsfunktion entfernt ----
	function bst_share_it_og_metatags_delete($output) {
	
		//if (defined('WPSEO_VERSION')) { // Spielerei um die Yoast Werbung zu entfernen!!! ----
		//	$output = str_ireplace('<!-- This site is optimized with the Yoast WordPress SEO plugin v' . WPSEO_VERSION . ' - https://yoast.com/wordpress/plugins/seo/ -->', '', $output);
        //	$output = str_ireplace('<!-- / Yoast WordPress SEO plugin. -->', '', $output);
		//}
		
		// Entfernen eventueller alter og:Tags ---
		$output = bst_share_it_DELETE_og('<meta property="og:locale', $output); 
		$output = bst_share_it_DELETE_og('<meta property="og:type"', $output); 
		$output = bst_share_it_DELETE_og('<meta property="og:title"', $output); 
		$output = bst_share_it_DELETE_og('<meta property="og:description"', $output); 
		$output = bst_share_it_DELETE_og('<meta property="og:url"', $output); 
		$output = bst_share_it_DELETE_og('<meta property="og:site_name"', $output); 
		$output = bst_share_it_DELETE_og('<meta property="article:publisher"', $output); 
		$output = bst_share_it_DELETE_og('<meta property="article:author"', $output);
		$output = bst_share_it_DELETE_og('<meta property="fb:admins"', $output);      
		$output = bst_share_it_DELETE_og('<meta property="og:image"', $output); 
		$output = bst_share_it_DELETE_og('<meta property="fb:app_id"', $output);
		$output = bst_share_it_DELETE_og('<meta property="og:updated_time"', $output);
		
		// Metatag Description sowie Twitter Sharingtext zur weiteren Verwendung in globales Array laden  -
		// -- > $GLOBALS["bst_share_it_array"]["description_twitter"]                                                           -
		// -- > $GLOBALS["bst_share_it_array"]["description"]    
		bst_share_it_PUT_metatag_Description_to_GLOBALs ($output); // Variable $output kommt von 'get_header'          -
	
	//echo '<meta property="og:image" content="' . $GLOBALS["bst_share_it_array"]["sharing_image"] . '" />' . "\n";	
	return $output;
	
	}	
	
	// Hier wird der og:image Tag bearbeitet! og:immage wird nie wieder leer gelassen :-D ----
	function bst_share_it_og_image_setzen($output) {
    
		if (defined('WPSEO_VERSION')) { // Spielerei um die Yoast Werbung zu entfernen!!! ----
			
			//$output = str_ireplace('<!-- This site is optimized with the Yoast WordPress SEO plugin v' . WPSEO_VERSION . ' - https://yoast.com/wordpress/plugins/seo/ -->', '', $output);
        	//$output = str_ireplace('<!-- / Yoast WordPress SEO plugin. -->', '', $output);
		}
		
		// Metatag Description sowie Twitter Sharingtext zur weiteren Verwendung in globales Array laden  -
		// -- > $GLOBALS["bst_share_it_array"]["description_twitter"]                                                           -
		// -- > $GLOBALS["bst_share_it_array"]["description"]                                                                   -
		bst_share_it_PUT_metatag_Description_to_GLOBALs ($output); // Variable $output kommt von 'get_header'          -
		
		//Prüfen og og:image gessetzt ---
		$pos1_temp = strpos($output, 'og:image'); // erstes Auftreten von og:image
		$pos1 = strpos($output, '=', $pos1_temp) + 1;
		$pos2 = strpos($output, '/>',$pos1);

		if (($pos1_temp) and ($pos2)) // Wenn og:image gesetzt dann austauschen durch Sharing Image
		{
			$output=substr_replace($output,'"' . $GLOBALS["bst_share_it_array"]["sharing_image"] . '" ',$pos1, $pos2-$pos1);
		}
		else // Wenn kein og:image tag vorhanden dann hinzufügen des Sharing Image
		{
			if ($image_id > 0) { 
				add_action( 'wp_head', 'bst_share_it_add_action_og_image', 1);
			}
		}

	return $output;
    }
	
	// Hilfsfunction um alte og:tags zu enferenen -----	
	function bst_share_it_DELETE_og($og_tag, $output)
	{
		//$pos1_temp = strpos($output, 'og:image'); // erstes Auftreten von og:image
		$pos1 = strpos($output, $og_tag); // erstes Auftreten von og:image
		$pos2 = strpos($output, '/>', $pos1) + 3;

		if (($pos1) and ($pos2)) // Wenn og:image gesetzt dann austauschen durch Sharing Image
		{
			$output=substr_replace($output,'', $pos1, $pos2-$pos1);
		}
	return $output;
	}
	
	//Hilfsfunktion für Kontrastschrift
	function bst_share_it_getContrastYIQ($hexcolor){
	$hexcolor=str_ireplace('#','',$hexcolor);
	$r = hexdec(substr($hexcolor,0,2));
	$g = hexdec(substr($hexcolor,2,2));
	$b = hexdec(substr($hexcolor,4,2));
	$yiq = (($r*299)+($g*587)+($b*114))/1000;
	return ($yiq >= 128) ? 'black' : 'white';
}
	
	
	// Hilfsfunction um Description zu Globalen Array hinzuzufügen hinzufügen -----	
	function bst_share_it_PUT_metatag_Description_to_GLOBALs ($SORURCE){
	
			//Auslesen der Sharing Description ---
		$pos1_temp = strpos($SORURCE, 'og:description'); // Auslesen des Opengraph Tags Description
		
		if ($pos1_temp == false) {
			$pos1_temp = strpos($SORURCE, '"description"'); // Auslesen des Metatags Descriptieon
			if ($pos1_temp == false) {
				$title_r = get_the_title();
				$GLOBALS["bst_share_it_array"]["description"]=substr($title_r, 0,  140);
				
					//Festlegen der Sharing Description für Twitter (Ein Link benötigt aktuell 22 Zeichen, dh es bleiben 121 Zeichen (+1 Leerzeichen) für die DESC ---
					if (strlen($GLOBALS["bst_share_it_array"]["description"]) > 121){
					$GLOBALS["bst_share_it_array"]["description_twitter"]=substr($GLOBALS["bst_share_it_array"]["description"], 0,  110) . "...";
					}
					else
					{
					$GLOBALS["bst_share_it_array"]["description_twitter"]=$GLOBALS["bst_share_it_array"]["description"];
					}
				return;
				};
		}
		
		$pos1 = strpos($SORURCE, '=', $pos1_temp)+1;
		$pos2 = strpos($SORURCE, '/>',$pos1);
		$GLOBALS["bst_share_it_array"]["description"] = trim(substr($SORURCE, $pos1,  $pos2-$pos1));
		$GLOBALS["bst_share_it_array"]["description"] = str_replace('"', '',$GLOBALS["bst_share_it_array"]["description"]);
		
		//Festlegen der Sharing Description für Twitter (Ein Link benötigt aktuell 22 Zeichen, dh es bleiben 121 Zeichen (+1 Leerzeichen) für die DESC ---
		if (strlen($GLOBALS["bst_share_it_array"]["description"]) > 121){
			$GLOBALS["bst_share_it_array"]["description_twitter"]=substr($GLOBALS["bst_share_it_array"]["description"], 0,  110) . "...";
		}
		else
		{
			$GLOBALS["bst_share_it_array"]["description_twitter"]=$GLOBALS["bst_share_it_array"]["description"];
		}
	
	}
	
		
	// Social Sharing in Globale Variable laden  ---
	function bst_share_it_PUT_Sharing_Image_to_GLOBALs(){
		
		$id= $GLOBALS["bst_share_it_array"]["post_id"]; // Post ID
		$image_id = get_post_meta( $id, 'bst_share_it_image', true );
		
		
		if ($image_id == false) // kein Social Sharing Image gesetzt also Artikelbild prüfen 
		{
			$image_id = get_post_thumbnail_id();
			
			if ($image_id == false) // kein Social Sharing Image gesetzt also globales Scharingbild nehmen 
			{
				$image_id=bst_share_it_get_attachment_id_from_src($GLOBALS["bst_share_it_array"]['global_picture']);
			}	
		}
	
		
		$GLOBALS["bst_share_it_array"]["image_id"]=$image_id;
		$img_atts = wp_get_attachment_image_src( $image_id, 'large');
		$sharing_image = $img_atts[0];
		//do_alert($sharing_image);
		$GLOBALS["bst_share_it_array"]["sharing_image"] = $sharing_image;
		
	}
	
	// Hilfsfunction um nur og:image im Header hinzufügen -----	
	function bst_share_it_add_action_og_image() {			
	
			echo '<meta property="og:image" content="' . $GLOBALS["bst_share_it_array"]["sharing_image"] . '" />' . "\n";
	}
		
	
		// Hilfsfunction um og:metagags im Header hinzufügen -----	
	function bst_share_it_add_action_og_metatags() {			
		
		
		
		global $autor;
		//Seitentyp bestimmen ---
		if (is_singular('post'))
			{
			$styp = 'article';
			}
		else
			{
			$styp = 'website';
			}
		
		if(!is_page()) {
		$autor = get_the_author_meta('display_name');
		}
		
		$url=get_permalink();
		$seitename=get_bloginfo();
			
		//echo  $sharing_image;
		echo '' . "\n";
		echo '<!-- Facebook opengraph tags generated by BST share iT plugin ' . bst_share_it_VERSION . ' - http://www.bst-systemtechnik.de/ -->' . "\n";
		echo '<meta property="og:locale" content="' .  get_locale() . '" />' . "\n";
		echo '<meta property="og:type" content="' .  $styp . '" />' . "\n";
		echo '<meta property="og:title" content="' . get_the_title() . '" />' . "\n";
		echo '<meta property="og:description" content="' . $GLOBALS["bst_share_it_array"]["description"] . '" />' . "\n";
		echo '<meta property="og:url" content="' . $url . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . $seitename . '" />' . "\n";
		echo '<meta property="og:image" content="' . $GLOBALS["bst_share_it_array"]["sharing_image"] . '" />' . "\n";
		if ($GLOBALS["bst_share_it_array"]['fb_article_author']) {echo '<meta property="article:author" content="' . $GLOBALS["bst_share_it_array"]['fb_article_author'] . '" />' . "\n";};
		if ($GLOBALS["bst_share_it_array"]['fb_article_publisher']) {echo '<meta property="article:publisher" content="' . $GLOBALS["bst_share_it_array"]['fb_article_publisher'] . '" />' . "\n";};
		if ($GLOBALS["bst_share_it_array"]['fb_app_id']) {echo '<meta property="fb:app_id" content="' . $GLOBALS["bst_share_it_array"]['fb_app_id'] . '" />' . "\n";};
		if ($GLOBALS["bst_share_it_array"]['fb_admin']) {echo '<meta property="fb:admins" content="' . $GLOBALS["bst_share_it_array"]['fb_admin'] . '" />' . "\n";};
		echo '<!-- End of opengraph tags generated by BST share iT plugin -->' . "\n\n";
		
		// Begin Twittercard
		if ($GLOBALS["bst_share_it_array"]['twitter_card']==1)	{
			echo '<!-- Twitter card generated by BST share iT plugin ' . bst_share_it_VERSION . ' - http://www.bst-systemtechnik.de/ -->' . "\n";
			echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
			echo '<meta name="twitter:url" content="' . $url . '" />' . "\n";
			echo '<meta name="twitter:title" content="' . get_the_title() . '" />' . "\n";
			echo '<meta name="twitter:description" content="' . $GLOBALS["bst_share_it_array"]["description"] . '" />' . "\n";
			echo '<meta name="twitter:site" content="' . $GLOBALS["bst_share_it_array"]["twitter_site"] . '" />' . "\n";
			echo '<meta name="twitter:creator" content="' . $GLOBALS["bst_share_it_array"]["twitter_creator"] . '" />' . "\n";
			if ($GLOBALS["bst_share_it_array"]["sharing_image"]) {
				
				//Wird nur eingefügt wenn getimagesize() keinen Fehler auslöst 
				if ($twi_size = @getimagesize($GLOBALS["bst_share_it_array"]["sharing_image"])) {
    				//$twi_size = getimagesize($GLOBALS["bst_share_it_array"]["sharing_image"]);
					$twi_width = $twi_size[0];
					$twi_height = $twi_size[1];
					echo '<meta name="twitter:image:src" content="' . $GLOBALS["bst_share_it_array"]["sharing_image"] . '" />' . "\n";
					echo '<meta name="twitter:image:width" content="' . $twi_width . '" />' . "\n";
					echo '<meta name="twitter:image:height" content="' . $twi_height . '" />' . "\n";
				}
			}
			echo '<!-- End of Twitter card generated by BST share iT plugin -->' . "\n\n";
		} // End Twittercard
		
	}

// *****************************************************************
// Darstellen des Widget -------------------------------------------
// *****************************************************************		

class bst_share_it extends WP_Widget {
	

	// constructor -------------------------------------------------
	function bst_share_it () {
		/* ... */
		 parent::WP_Widget(false, $name = 'BST share iT');
	}

// *****************************************************************
// AUSGABE IM BACKEND ----------------------------------------------
// *****************************************************************
	function form($instance) {
		
		/* ... */
		// Check values
		if($instance) {
     	$title = esc_attr($instance['title']);
     	$facebook = esc_attr($instance['facebook']);
			$facebook_like = esc_attr($instance['facebook_like']);
			$twitter = esc_attr($instance['twitter']);
			$gplus = esc_attr($instance['gplus']);
			$pin = esc_attr($instance['pin']);
			$in = esc_attr($instance['in']);
			$xing = esc_attr($instance['xing']);
			$stumble = esc_attr($instance['stumble']);
			$mail = esc_attr($instance['mail']);
			$dt_status = esc_attr($instance['dt_status']);
		    
		} else {
     		//Defaultwerte eintragen bei neuem Widget
			$title = __('To recommend', 'bst_share_it');
     	$facebook = '1';
			$facebook_like = '0';
			$twitter = '1';
			$gplus = '1';
			$pin = '1';
			$in = '1';
			$xing = '1';
			$stumble = '0';
			$mail = '1';
			$dt_status = '0';
     		//$textarea = '';
			?>
			 <div>
                <div style="display:inline-block; width:100%;line-height:20px;margin-top:10px;"><?php _e('The widget has been added. Please save your settings before closing.','bst_share_it'); ?>
                </div>
			</div>	
            <?php
		}
		
		
  
	
	
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bst_share_it'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
        	 <!-- Facebook --->
        	 <?php if ($GLOBALS["bst_share_it_array"]['facebook'] == 1)	
				{ 
			 ?>        
        	<div>
                <div style="display:inline-block; width:100px;line-height:20px;">Facebook Share:</div>
                <div style="display:inline-block;"><input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="checkbox" value="1" <?php checked('1', $facebook); ?> /></div>
        	</div>				
			<?php };?>
            
             <!-- Facebook Like --->
        	 <?php if ($GLOBALS["bst_share_it_array"]['facebook_like'] == 1)	
				{ 
			 ?>        
        	<div>
                <div style="display:inline-block; width:100px;line-height:20px;">Facebook Like:</div>
                <div style="display:inline-block;"><input class="widefat" id="<?php echo $this->get_field_id('facebook_like'); ?>" name="<?php echo $this->get_field_name('facebook_like'); ?>" type="checkbox" value="1" <?php checked('1', $facebook_like); ?> /></div>
        	</div>				
			<?php };?>
         	
            <!-- Twitter --->
             <?php if ($GLOBALS["bst_share_it_array"]['twitter'] == 1)	
				{ 
			 ?>        
 			<div>
                <div style="display:inline-block; width:100px;line-height:20px;">Twitter:</div>
                <div style="display:inline-block;"><input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="checkbox" value="1" <?php checked('1', $twitter); ?> /></div>
        	</div>				
			<?php };?>
        	
            <!-- Google+ --->
             <?php if ($GLOBALS["bst_share_it_array"]['gplus'] == 1)	
				{ 
			 ?>        
        	<div>
                <div style="display:inline-block; width:100px;line-height:20px;">Google+:</div>
                <div style="display:inline-block;"><input class="widefat" id="<?php echo $this->get_field_id('gplus'); ?>" name="<?php echo $this->get_field_name('gplus'); ?>" type="checkbox" value="1" <?php checked('1', $gplus); ?> /></div>
        	</div>				
			<?php };?>
            
            <!-- Pinterest --->
             <?php if ($GLOBALS["bst_share_it_array"]['pin'] == 1)	
				{ 
			 ?>        
        	<div>
                <div style="display:inline-block; width:100px;line-height:20px;">Pinterest:</div>
                <div style="display:inline-block;"><input class="widefat" id="<?php echo $this->get_field_id('pin'); ?>" name="<?php echo $this->get_field_name('pin'); ?>" type="checkbox" value="1" <?php checked('1', $pin); ?> /></div>
        	</div>			
			<?php };?>
        
 			<!-- LinkedIn --->
            <?php if ($GLOBALS["bst_share_it_array"]['in'] == 1)	
				{ 
			?>        
        	<div>
                <div style="display:inline-block; width:100px;line-height:20px;">LinkedIn:</div>
                <div style="display:inline-block;"><input class="widefat" id="<?php echo $this->get_field_id('in'); ?>" name="<?php echo $this->get_field_name('in'); ?>" type="checkbox" value="1" <?php checked('1', $in); ?> /></div>
        	</div>		
			<?php };?>
        
			<!-- XING --->
            <?php if ($GLOBALS["bst_share_it_array"]['xing'] == 1)	
				{ 
			?>        
        	<div>
                <div style="display:inline-block; width:100px;line-height:20px;">XING:</div>
                <div style="display:inline-block;"><input class="widefat" id="<?php echo $this->get_field_id('xing'); ?>" name="<?php echo $this->get_field_name('xing'); ?>" type="checkbox" value="1" <?php checked('1', $xing); ?> /></div>
        	</div>		
			<?php };?>
            
            <!-- Stumble --->
            <?php if ($GLOBALS["bst_share_it_array"]['stumble'] == 1)	
				{ 
			?>        
        	<div>
                <div style="display:inline-block; width:100px;line-height:20px;">Stumble:</div>
                <div style="display:inline-block;"><input class="widefat" id="<?php echo $this->get_field_id('stumble'); ?>" name="<?php echo $this->get_field_name('stumble'); ?>" type="checkbox" value="1" <?php checked('1', $xing); ?> /></div>
        	</div>		
			<?php };?>

        	<!-- Mail --->
            <?php if ($GLOBALS["bst_share_it_array"]['mail'] == 1)	
				{ 
			?>        
        	<div>
                <div style="display:inline-block; width:100px; line-height:20px;">Mail:</div>
                <div style="display:inline-block; width:50px;"><input class="widefat" id="<?php echo $this->get_field_id('mail'); ?>" name="<?php echo $this->get_field_name('mail'); ?>" type="checkbox" value="1" <?php checked('1', $mail); ?> /></div>
        	</div>		
			<?php };?>
            <hr>
            <!-- Button Status beim öffnen --->
        	<div>
		        <div style="display:inline-block; width:100px;line-height:20px;"><?php _e('Button status:', 'bst_share_it'); ?></div>
                <div style="display:inline-block; width:20px"><input class="widefat" id="<?php echo $this->get_field_id('dt_status'); ?>" name="<?php echo $this->get_field_name('dt_status'); ?>" type="checkbox" value="1" <?php checked('1', $dt_status); ?> /></div>
                 <div style="display:inline-block; line-height:20px;"><?php _e('Share buttons visible on opening the website!', 'bst_share_it'); ?></div>
        	</div>		

 <br />
        <!-- 
		<p>
			<label for="<?php //echo $this->get_field_id('text'); ?>"><?php //_e('Text:', 'bst_share_it'); ?></label>
			<input class="widefat" id="<?php //echo $this->get_field_id('text'); ?>" name="<?php //echo $this->get_field_name('text'); ?>" type="text" value="<?php //echo $text; ?>" />
		</p>

		<p>
			<label for="<?php //echo $this->get_field_id('textarea'); ?>"><?php //_e('Textarea:', 'bst_share_it'); ?></label>
			<textarea class="widefat" id="<?php //echo $this->get_field_id('textarea'); ?>" name="<?php //echo $this->get_field_name('textarea'); ?>"><?php //echo $textarea; ?></textarea>
		</p>
        -->
<?php
	}

	// widget update -------------------------------------------------
	function update($new_instance, $old_instance) {
		/* ... */
	  // Fields
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['facebook'] = strip_tags($new_instance['facebook']);
		$instance['facebook_like'] = strip_tags($new_instance['facebook_like']);
		$instance['twitter'] = strip_tags($new_instance['twitter']);
		$instance['gplus'] = strip_tags($new_instance['gplus']);
		$instance['pin'] = strip_tags($new_instance['pin']);
		$instance['in'] = strip_tags($new_instance['in']);
		$instance['xing'] = strip_tags($new_instance['xing']);
		$instance['stumble'] = strip_tags($new_instance['stumble']);
		$instance['mail'] = strip_tags($new_instance['mail']);
		$instance['dt_status'] = strip_tags($new_instance['dt_status']);
      	//$instance['textarea'] = strip_tags($new_instance['textarea']);
     	return $instance;
	}

// *****************************************************************
// AUSGABE IM FRONTEND ---------------------------------------------
// *****************************************************************

	function widget($args, $instance) {
		/* ... */
		extract( $args );
   	// these are the widget options
   	$title = apply_filters('widget_title', $instance['title']);
   	$facebook = $instance['facebook'];
		$facebook_like = $instance['facebook_like'];
		$twitter = $instance['twitter'];
		$gplus = $instance['gplus'];
		$pin = $instance['pin'];
		$in = $instance['in'];
		$xing = $instance['xing'];
		$stumble = $instance['stumble'];
		$mail = $instance['mail'];
		$dt_status =  $instance['dt_status'];
   		//$textarea = $instance['textarea'];
   		echo $before_widget;
   		
	    // Check if title is set
   		if ( $title ) {
      		echo $before_title . $title . $after_title;
			echo "<div class='bst_share_it_spacer-title-top'></div>";
			}
   		else
			{
		echo "<div class='bst_share_it_spacer-top'></div>";
		}
		
  		
		//echo $GLOBALS["bst_share_it_array"]["description"]; //Test der globalen Variable
		//echo $GLOBALS["bst_share_it_array"]["image_id"];
		
	//	echo bst_share_it_get_tweets(get_permalink()) . '  tweets';
		
		$output = str_ireplace('bst_share_it_container', 'bst_share_it_container_widget', bst_share_it_build_bttns_snippet('widget'));
		$output = str_ireplace('_sw', '_sw_widget', $output);
		$output = str_ireplace('spacer_before', 'spacer_before_widget', $output);
		$output = str_ireplace('spacer_after', 'spacer_after_widget', $output);
		$output = str_ireplace('sw_link', 'sw_link_widget', $output);

		echo $output;
		
		//echo bst_share_it_build_bttns_snippet('widget');
		 
		 // ---> Öffnungsstatus des Buttoncontainers ( 1 oder 0 )
		echo '<input type="hidden" id="dt_start" value="' . $dt_status . '" />';
		
   		echo "<div class='clear'></div>";
		
   		echo $after_widget;
	}
}

// *****************************************************************
// Finalisierung - Widget Registrierung bei WP ---------------------
// *****************************************************************
	add_action('widgets_init', create_function('', 'return register_widget("bst_share_it");'));

// *****************************************************************
// AUSGABE Buttons im Content --------------------------------------
// *****************************************************************

//Globale Einstellungen um das Anzeigen der Buttons in Posts und Pages zu regeln
//----> $GLOBALS["bst_share_it_array"]["btn_onposts"] : 1 oder 0
//----> $GLOBALS["bst_share_it_array"]["btn_onpages"] : 1 oder 0

//Seitenbezogene Einstellungen um das Anzeigen der Buttons in Posts und Pages zu regeln
//bst_share_it_radio ----> buttons_no
//bst_share_it_radio ----> buttons_top
//bst_share_it_radio ----> buttons_bottom


// show Buttons in Content
function bst_share_it_buttons_display_content($content) {
		
		$show_buttons = bst_share_it_get_custom_field_in_frontend('bst_share_it_radio');
		if ($show_buttons == '') {$show_buttons='buttons_bottom';}; //falls noch kein Wert gesetzt
		
		if (($show_buttons == 'buttons_top') or ($show_buttons == 'buttons_bottom')) 
		{
			
			if (is_page()) // Buttons auf Seite ?
			{
				if ($GLOBALS["bst_share_it_array"]["btn_onpages"]=='1')
				{
					
					//do_alert("TEST");
					//STARTE build Buttoncontaine -------------------------
					$contet_snippet=bst_share_it_build_bttns_snippet("page");
					 remove_filter( current_filter(), __FUNCTION__ );
					if( $show_buttons =='buttons_bottom')
					{
						$content = $content . $contet_snippet;
					}
					else // buttons_top
					{
						$content = $contet_snippet . $content;
					};
		
				};
			};
			
			
			if (is_singular('post'))  // Buttons in Artikel ?
			{
		
				if ($GLOBALS["bst_share_it_array"]["btn_onposts"]=='1')
				{
					 remove_filter( current_filter(), __FUNCTION__ );
					//STARTE build Buttoncontainer -------------------------
					$contet_snippet=bst_share_it_build_bttns_snippet("page");
					if( $show_buttons =='buttons_bottom')
					{
						$content = $content . $contet_snippet;
					}
					else // buttons_top
					{
						$content = $contet_snippet . $content;
					};
				};
		
			};
			
		
		};
		
		return $content;
		
}; // Ende bst_share_it_buttons_display_content() --------------------------------------------------------------
	
/**
* ###############################################################################################################
* Funktion zum Erstellen des Button Snippets
*
* $ausgabe 'widget' oder 'page' - Vorgabe ob das Snippet in ein Widget oder in den Content eingefügt werden soll.
* @return string html code
* ###############################################################################################################
*/
function bst_share_it_build_bttns_snippet ($ausgabe) {
	
		if (is_home() or is_front_page() or is_paged()) {return;}
		
		$post_id=get_the_ID();
		//if ($GLOBALS["bst_share_it_array"]["post_id"] != $post_id) {return;}
		
		$permalink = get_permalink();
		
		//html für Facebook Like Button generieren
		$fb_button_code=bst_share_it_build_fb_like_grey($ausgabe);
		
		//Shares der Netzwerke in ein array laden
		$sum_shares_array = bst_share_it_bst_share_it_get_shares ($post_id, $permalink);
		

		if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
				$iwidth=110;
				$iheight=28;
				$buttons_max_width='100%';
				$border_width=1;
				
			}
		else 
			{
				$img_url = bst_share_it_PLUGIN_URL . '/img/';
				$image_size_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/facebook.png';

				//calculate image --------------------------
				$image_size = getimagesize($image_size_url);
				$iwidth = $image_size[0];
				$iheight = $image_size[1];
				$border_width=1;
			}
			
	
		if ($ausgabe == 'widget') {
			$class_info_flat='info_flat_widget';
			$info_text = '';
			if ($iwidth < 48)
			{
				$buttons_max_width = $iwidth * 3 + 15 .'px';	
			}	
			elseif ($iwidth >= 48 and $iwidth < 96) 
			
			{
				$buttons_max_width = $iwidth * 2 + 10 .'px';	
			}
			
			elseif ($iwidth >= 96)	
			
			{
				$buttons_max_width=$iwidth . 'px';
			}
				 
			
		} elseif ($ausgabe == 'page') {
			$class_info_flat='info_flat';
			$info_text = __('If you like the website, recommend it gladly.', 'bst_share_it') ;
			if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {$buttons_max_width='100%';} else {$buttons_max_width = 575 . 'px';}
			//$buttons_max_width='100%';	
		}
		
		
		//Hintergrundfarbe des Buttoncontainers sowie Schatten des Containers festlegen
		//do_alert($GLOBALS["bst_share_it_array"]["buttons_bgcolor"]);
				//Schatten Einstellungen in Style packen
		if ($GLOBALS["bst_share_it_array"]["shadow"]==1){
		 	$add_shadow = '-webkit-box-shadow: 0 8px 6px -6px black !important;-moz-box-shadow: 0 8px 6px -6px black !important;box-shadow: 0 8px 6px -6px black !important;';
		} else {
			$add_shadow = '';
		}

		
        if ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2') {
			
			$buttons_bg_style = 'style="background:'. $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . '; padding-bottom: 0px; padding-top: 0px; margin-bottom: 0px;border:none;"';
			//$buttons_bg_style='style="margin: 0px auto;"';
		
		} else  {
		
		
		
			if ($GLOBALS["bst_share_it_array"]["buttons_bgcolor"]==''){
				$buttons_bg_style = '';
				$buttonscontainer_bordercolor='#E4E4E4';
				$buttons_bg_style = 'style="
					color:#000000;
					border-top: 0px solid ' . $buttonscontainer_bordercolor . ';
					border-left: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';
					border-right: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';
					border-bottom: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';' . $add_shadow . '"';	
			} else {
				$buttons_bg_style = 'style="text-align:right; padding-right:10px; padding-bottom:5px; font-size:10px; 
					color:' . bst_share_it_getContrastYIQ($GLOBALS["bst_share_it_array"]["buttons_bgcolor"]) . ';
					background:' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';
					border-top: 0px solid ' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';
					border-left: '.$border_width.'px solid ' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';
					border-right: '.$border_width.'px solid ' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';
					border-bottom: '.$border_width.'px solid ' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';' . $add_shadow . '"';
			}
		
		} 

		
		
		//Abstand vor den Buttons in Pixeln
		if ($GLOBALS["bst_share_it_array"]["px_before"]==''){
			$spacer_div_before = '';	
		} else {
			$spacer_div_before = '<div id="spacer_before" style="height:' . $GLOBALS["bst_share_it_array"]["px_before"] . 'px;"></div>';	
		}
		
		//Abstand nach den Buttons in Pixeln
		if ($GLOBALS["bst_share_it_array"]["px_after"]==''){
			$spacer_div_after = '';	
		} else {
			$spacer_div_after = '<div id="spacer_after" style="height:' . $GLOBALS["bst_share_it_array"]["px_after"] . 'px;"></div>';	
		}
		
		//Platzierung des Counter Feldes
		if ($GLOBALS["bst_share_it_array"]["buttons_style"]=='style1'){
			$sc_style_addition='Style="margin-left:105px; margin-top:6px;"';
		} elseif ($GLOBALS["bst_share_it_array"]["buttons_style"]=='style2') {
			$sc_style_addition='Style="margin-left:30px; margin-top:-8px;"';
		} elseif ($GLOBALS["bst_share_it_array"]["buttons_style"]=='style3') {
			$sc_style_addition='Style="margin-left:33px; margin-top:-4px;"';
		}
		elseif (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
			$sc_style_addition='Style="background:none !important;border: 0px solid #000 !important;font-size:12px !important;"';
		}
		
		$dt_status_page=0;
		
		// *****************************************************************
		// Beginne mit dem Bau des Buttontainers ---------------------------
		// *****************************************************************
		$contet_snippet=$spacer_div_before;
		
		// ---> Variablen für Zugriff von jQuery Scripts
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1)
		{
			$button_type='button_count';
		}
		else
		{
			$button_type='button';	
		};
		$contet_snippet.='<input type="hidden" id="like_url" value="' . plugins_url('bst_share_it_build_fb_like.php', __FILE__ ) . '" />';
		$contet_snippet.='<input type="hidden" id="count_url" value="' . plugins_url('bst_share_it_social_count.php', __FILE__ ) . '" />';
		$contet_snippet.='<input type="hidden" id="update_url" value="' . plugins_url('bst_share_it_update_db.php', __FILE__ ) . '" />';
		$contet_snippet.='<input type="hidden" id="post_id" value="' . $post_id . '" />';
		$contet_snippet.='<input type="hidden" id="show_facebook_like" name="' . $GLOBALS["bst_share_it_array"]["facebook_like"] . '" />';
		$contet_snippet.='<input type="hidden" id="type_facebook_like" name="' . $button_type . '" />';
		$contet_snippet.='<input type="hidden" id="flagg_button_build" name = "OFF" value="60" />';
		$contet_snippet.='<input type="hidden" id="flagg_shadow" value="' . $GLOBALS["bst_share_it_array"]["shadow"] . '" />';
		$contet_snippet.='<input type="hidden" id="flagg_share_counter" value="' . $GLOBALS["bst_share_it_array"]["share_counter"] . '" />';
		$contet_snippet.='<input type="hidden" id="dt_start" value="' . $dt_status_page . '" />'; //Öffnungsstatus des Buttoncontainers ( 1 oder 0 )
		$contet_snippet.='<input type="hidden" id="iwidth" value="' . $iwidth . '" />';
		$contet_snippet.='<input type="hidden" id="style" value="' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '" />'; //Öffnungsstatus des Buttoncontainers ( 1 oder 0 )
		
		

		
		
		
		if ($GLOBALS["bst_share_it_array"]["buttons_style"]=='simple2') 
			{$info_snippet='<div class="' . $class_info_flat . '"><div class="info_flat_text">
			
			<a href="#" data-featherlight="' . plugins_url('../info/info_bst_share_it.html', __FILE__ ) . '" title="BST share iT&#10;Datenschutzkonformes Teilen">
											<img class="info_img" style="padding-top:3px;" src="' . plugins_url('../img/bstshareit_logo_64.png', __FILE__ ) . '" />
			</a>
			
			
			</div><div class="info_flat_clear"></div></div>';}
			else
			{$info_snippet='';}


		$contet_snippet.='<div class = "bst_share_it_container">';
		$contet_snippet.= $info_snippet;
		
		
			
		if ($GLOBALS["bst_share_it_array"]["buttons_style"]!='simple2') {
		
				$contet_snippet.='<dl>					
								<div class= "flex-divcontainer_header" style="background:' . $GLOBALS["bst_share_it_array"]["info_bgcolor"] . ';">
										<div id="flex-divcontainer-links">
											<dt>
												<div>
													<a href="" id="bst_share_it-button" class="bst_share_it-button_closed"></a>
												</div>
											</dt>
										</div>			
										<div id="flex-divcontainer-rechts"> 
											<div id="social_counter_sum_shares" class="social_counter_header_red"><span>'. $sum_shares_array["sum_shares"] . '</span></div>
										</div>
										<div id="flex-divcontainer-mitte">
											<div class="header_box1"></div>
											<div class="header_box2" style = "color:'. $GLOBALS["bst_share_it_array"]["info_fontcolor"] . ';">' . $info_text . '</div>
										</div>
								</div>
          						<div id="fs" class= "flex-divcontainer_footer_closed">
								
								
										<a href="#" data-featherlight="' . plugins_url('../info/info_bst_share_it.html', __FILE__ ) . '" title="BST share iT&#10;Datenschutzkonformes Teilen">
											<img class="info_img" style="padding-top:5px;" src="' . plugins_url('../img/bstshareit_logo_64.png', __FILE__ ) . '" />
										</a>
								</div>
						  <dd class = "bst_share_it-button">'; 
		}
		
		//$s= substr($buttons_bg_style, 0, -1);
				
		$contet_snippet.= '<div class="flex-divcontainer" ' . $buttons_bg_style . '>';
		
							
		if (($GLOBALS["bst_share_it_array"]["buttons_style"] !='simple1') and ($GLOBALS["bst_share_it_array"]["buttons_style"] !='simple2')) {$contet_snippet.= '<div id="flex-divcontainer-inner" style="margin: 0px auto;max-width:'.$buttons_max_width.';border: 0px solid silver;">';}

		$contet_snippet.= '<ul class="flex-container wrap">'; // <--- Start Flex Elements

		// build Facebook --->
		// --- simple one start 
		if  (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
		$image_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/facebook.png';
		$btn_image = '<img src="'.$image_url.'" />';
		$btn_text= __('SHARE', 'bst_share_it');} else {$btn_image='';$btn_text=''; }
		// --- simple one end
		 
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1) { $sc_div= '<div id="fb_counter" class="social_counter" '. $sc_style_addition . '><span>' . $sum_shares_array["fb"] . '</span></div>'; } else { $sc_div= ''; } ;
		if ($GLOBALS["bst_share_it_array"]["facebook"] == 1) { 
	    $contet_snippet.='<li class="flex-item">
								<div id="facebook_sw" class="sw_facebook">
								' . $sc_div . '
									<a href="javaScript:SocialPopUp(' . "'" . 'https://www.facebook.com/sharer/sharer.php?u='  . $permalink .  '&display=popup&ref=plugin' . "'" . ', ' . "'" . '640' . "'" . ', ' . "'" . '480' . "'" . ')" rel="nofollow" title="Klicken und auf Facebook teilen">'. $btn_image .'<span class="sw_link" >'. $btn_text .'</span></a>
  								</div>
  						  </li>';
		};
		
		// build Twitter --->
		// --- simple one start 
		if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
		$image_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/twitter.png';
		$btn_image = '<img src="'.$image_url.'" />';
		$btn_text= __('TWEET', 'bst_share_it');} else {$btn_image='';$btn_text=''; }
		// --- simple one end
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1) { $sc_div= '<div id="twitter_counter" class="social_counter" '. $sc_style_addition . '><span>' . $sum_shares_array["twitter"] . '</span></div>'; $sc_div= ''; } else { $sc_div= ''; } ;
		if ($GLOBALS["bst_share_it_array"]["twitter"] == 1) { 
	    $contet_snippet.='<li class="flex-item">
  								<div id="twitter_sw" class="sw_twitter">
								' . $sc_div . '
									<a href="javaScript:SocialPopUp(' . "'" . 'http://twitter.com/share?text=' .$GLOBALS["bst_share_it_array"]["description_twitter"] . '&url='  . $permalink .  '' . "'" . ', ' . "'" . '640' . "'" . ', ' . "'" . '480' . "'" . ')" rel="nofollow" title="Klicken und Twittern">'. $btn_image .'<span class="sw_link" >'. $btn_text .'</span></a>									
	    						</div>  
  							</li>';
		};
		
		// build Google Plus --->
		// --- simple one start 
		if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
		$image_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/gplus.png';
		$btn_image = '<img src="'.$image_url.'" />';
		$btn_text= __('+1', 'bst_share_it');} else {$btn_image='';$btn_text=''; }
		// --- simple one end
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1) { $sc_div= '<div id="gplus_counter" class="social_counter" '. $sc_style_addition . '><span>' . $sum_shares_array["gplus"] . '</span></div>'; } else { $sc_div= ''; } ;
		if ($GLOBALS["bst_share_it_array"]["gplus"] == 1) { 
	    $contet_snippet.='<li class="flex-item">
  								<div id="gplus_sw" class="sw_gplus">
								' . $sc_div . '
									<a href="javaScript:SocialPopUp(' . "'" . 'https://plus.google.com/share?url='  . $permalink .  '' . "'" . ', ' . "'" . '640' . "'" . ', ' . "'" . '480' . "'" . ')" rel="nofollow" title="Klicken und auf Google+ teilen">'. $btn_image .'<span class="sw_link" >'. $btn_text .'</span></a>
								</div> 
  						</li>';
		};
		
		// build Pinterest --->
		// --- simple one start 
		if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
		$image_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/pin.png';
		$btn_image = '<img src="'.$image_url.'" />';
		$btn_text= __('PIN', 'bst_share_it');} else {$btn_image='';$btn_text=''; }
		// --- simple one end
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1) { $sc_div= '<div id="pin_counter" class="social_counter" '. $sc_style_addition . '><span>' . $sum_shares_array["pin"] . '</span></div>'; } else { $sc_div= ''; } ;
		if ($GLOBALS["bst_share_it_array"]["pin"] == 1) { 
	    $contet_snippet.='<li class="flex-item">
  								<div id="pin_sw" class="sw_pin">
								' . $sc_div . '
									<a href="javaScript:SocialPopUp(' . "'" . 'https://de.pinterest.com/pin/create/link/?url='  . $permalink .  '&description=' . $GLOBALS["bst_share_it_array"]["description"] . '&media=' . $GLOBALS["bst_share_it_array"]["sharing_image"] . '' . "'" . ', ' . "'" . '640' . "'" . ', ' . "'" . '480' . "'" . ')" rel="nofollow" title="Klicken und auf Pinterest teilen">'. $btn_image .'<span class="sw_link" >'. $btn_text .'</span></a>
								</div>
  						</li>';
		};
		
		// build linkedIn --->
		// --- simple one start 
		if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
		$image_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/in.png';
		$btn_image = '<img src="'.$image_url.'" />';
		$btn_text= __('SHARE', 'bst_share_it');} else {$btn_image='';$btn_text=''; }
		// --- simple one end
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1) { $sc_div= '<div id="in_counter" class="social_counter" '. $sc_style_addition . '><span>' . $sum_shares_array["in"] . '</span></div>'; } else { $sc_div= ''; } ;
		if ($GLOBALS["bst_share_it_array"]["in"] == 1) { 
	    $contet_snippet.='<li class="flex-item">
  								<div id="in_sw" class="sw_in">
								' . $sc_div . '
									<a href="javaScript:SocialPopUp(' . "'" . 'http://www.linkedin.com/shareArticle?mini=true&url='  . $permalink .  '&title=' 
									. get_the_title() . '&summary=' . $GLOBALS["bst_share_it_array"]["description"] . '&source='  . $permalink .  '' . "'" . ', ' . "'" . '640' . "'" . ', ' . "'" . '480' . "'" . ')" rel="nofollow" title="Klicken und auf LinkedIn teilen">'. $btn_image .'<span class="sw_link" >'. $btn_text .'</span></a>
								</div>	
  						</li>';
		};
		
		// build Xing --->
		// --- simple one start 
		if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
		$image_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/xing.png';
		$btn_image = '<img src="'.$image_url.'" />';
		$btn_text= __('XING', 'bst_share_it');} else {$btn_image='';$btn_text=''; }
		// --- simple one end
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1) { $sc_div= '<div id="xing_counter" class="social_counter" '. $sc_style_addition . '><span>' . $sum_shares_array["xing"] . '</span></div>'; } else { $sc_div= ''; } ;
		if ($GLOBALS["bst_share_it_array"]["xing"] == 1) { 
	    $contet_snippet.='<li class="flex-item">
  								<div id="xing_sw" class="sw_xing">
  								' . $sc_div . '
									<a href="javaScript:SocialPopUp(' . "'" . 'https://www.xing.com/spi/shares/new?url='  . urlencode($permalink) .  '' . "'" . ', ' . "'" . '640' . "'" . ', ' . "'" . '480' . "'" . ')" rel="nofollow" title="Klicken und auf XING teilen">'. $btn_image .'<span class="sw_link" >'. $btn_text .'</span></a>
								</div>	
  						</li>';
		};
		
		// build Stumble --->
		// --- simple one start 
		if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
		$image_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/stumble.png';
		$btn_image = '<img src="'.$image_url.'" />';
		$btn_text= __('STUMB', 'bst_share_it');} else {$btn_image='';$btn_text=''; }
		// --- simple one end
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1) { $sc_div= '<div id="stumble_counter" class="social_counter" '. $sc_style_addition . '><span>' . $sum_shares_array["stumble"] . '</span></div>'; } else { $sc_div= ''; } ;
		if ($GLOBALS["bst_share_it_array"]["stumble"] == 1) { 
	    $contet_snippet.='<li class="flex-item">
  								<div id="stumble_sw" class="sw_stumble">
  								' . $sc_div . '
									<a href="javaScript:SocialPopUp(' . "'" . 'http://www.stumbleupon.com/submit?url='  . $permalink .  '&title=' 
									. get_the_title() . '' . "'" . ', ' . "'" . '640' . "'" . ', ' . "'" . '480' . "'" . ')" rel="nofollow" title="Klicken und auf Stumble teilen">'. $btn_image .'<span class="sw_link" >'.$btn_text .'</span></a>
								</div>	
  						</li>';
		};
		
		// build Mail --->
		// --- simple one start 
		if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')) {
		$image_url =  bst_share_it_PLUGIN_URL . '/img/' . $GLOBALS["bst_share_it_array"]["buttons_style"] . '/mail.png';
		$btn_image = '<img src="'.$image_url.'" />';
		$btn_text= __('MAIL', 'bst_share_it');} else {$btn_image='';$btn_text=''; }
		// --- simple one end
		if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1) { $sc_div= '<div id="mail_counter" class="social_counter_idle" '. $sc_style_addition . '><span>' . $sum_shares_array["mail"] . '</span></div>'; } else { $sc_div= ''; } ;
		$sc_div=''; // Counter bei mail nicht anzeigen
		if ($GLOBALS["bst_share_it_array"]["mail"] == 1) { 
	    $contet_snippet.='<li class="flex-item">
  								<div id="mail_sw" class="sw_mail">
								' . $sc_div . '
									<a href="mailto:?subject='  . get_the_title() .  '&body=Hallo,%0A %0Aich habe gerade etwas interessantes gefunden. %0AHier ist der Link dazu:  '  . $permalink .  '%0A %0A" rel="nofollow" title="Klicken und per Mail versenden">'. $btn_image . '<span class="sw_link" >'.$btn_text .'</span></a>
								</div>	
  						</li>';
		};
		$contet_snippet.= '</ul>';
		
		// build Facebook Like  --->
		
		
		if ($GLOBALS["bst_share_it_array"]["facebook_like"] == 1) {
		
        if ($ausgabe == 'widget') {$facebook_like ="facebook_like_widget";} else {$facebook_like ="facebook_like";};
		
		
		
		$contet_snippet.= '<div id="' . $facebook_like . '" style="text-align:left; padding-left:5px;padding-top:5px;padding-bottom:5px;background:' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';">'. 
							$fb_button_code . '</div>';
		};
						
		if ($GLOBALS["bst_share_it_array"]["buttons_style"] !='simple1') {$contet_snippet.='</div>';}
		if ($GLOBALS["bst_share_it_array"]["buttons_style"]!='simple2') {$contet_snippet.='</div></dd></dl>';}
		$contet_snippet.='</div>'; // END class bst_share_it !
		
		$contet_snippet.=$spacer_div_after;
		
		 // ---> Baue CSS für Buttons ---------------------------
		$contet_snippet.=bst_share_it_build_button_css($GLOBALS["bst_share_it_array"]["buttons_style"], $border_width, $iwidth,	$iheight);
	
		$contet_snippet = str_replace(array("\r\n", "\r", "\n"), '', $contet_snippet); //Umbrüche Leerzeichen entferenen
		$contet_snippet = preg_replace('#\s+#', ' ', $contet_snippet);  //doppelte Leerzeichen entferenen
		
		return $contet_snippet;
}
	

//Funktion zum abhohlen der Shares aus DB bzw. von den Netzwerken
function bst_share_it_bst_share_it_get_shares ($post_id, $permalink) {
		
		$sum_shares_jason = get_post_meta($post_id, 'bst_share_it_sum_shares', true ); //Shares im json String aus DB 

		if ($sum_shares_jason == '') // Noch kein Datenbankeintrag der Shares zum Post vorhanden
		{
			include_once 'bst_share_it_social_get_shares.php';
			$sum_shares_array = bst_share_it_get_shares($permalink);
			$sum_shares_array["time"] = time();
			//var_dump($sum_shares_array);
			
			//Erstelle json string ---------------------------
			$sum_shares_json = json_encode($sum_shares_array);
			//------------------------------------------------
			add_post_meta($post_id, 'bst_share_it_sum_shares', $sum_shares_json, true ) || update_post_meta( $post_id, 'bst_share_it_sum_shares', $sum_shares_json );
		}
		else //Anzahl der Shares bereits in Datenabank gepuffert
		{
			$sum_shares_array = json_decode($sum_shares_jason, true);
			//Zeit nach der die Shares erneut von den Netzwerken abgerufen wird (60 = 1 Minute, 3600 = 1 Stunde)
			if ($sum_shares_array["time"] <= time()-60) {
				//print("ABGELAUFEN");
				include_once 'bst_share_it_social_get_shares.php';
				$sum_shares_array = bst_share_it_get_shares($permalink);
				$sum_shares_array["time"] = time(); //neuen Zeitstempel setzen
				//print $sum_shares_json;
				
				//Erstelle json string ---------------------------
				$sum_shares_json = json_encode($sum_shares_array);
				//------------------------------------------------
				add_post_meta($post_id, 'bst_share_it_sum_shares', $sum_shares_json, true ) || update_post_meta( $post_id, 'bst_share_it_sum_shares', $sum_shares_json );
			}	
		};

		return $sum_shares_array;
}

//function to return a custom field value.
function bst_share_it_get_custom_field_in_frontend( $value ) {
	global $post;

    $custom_field = get_post_meta( $post->ID, $value, true );
    if ( !empty( $custom_field ) )
	    return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );
    return false;
}


function bst_share_it_build_button_css ($style, $border_width, $iwidth,	$iheight ) {

	  if ($GLOBALS["bst_share_it_array"]["build_css"]=='1') {
        return '';
		};

    $img_url = bst_share_it_PLUGIN_URL . '/img/';
		$image_size_url =  bst_share_it_PLUGIN_URL . '/img/' . $style . '/facebook.png';

	
		if ($GLOBALS["bst_share_it_array"]["shadow"]==1){
			$add_shadow = '-webkit-box-shadow: 0 8px 6px -6px black !important;-moz-box-shadow: 0 8px 6px -6px black !important;box-shadow: 0 8px 6px -6px black !important;';
		} else {
			$add_shadow = '';
		}
		//if ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') {$add_shadow = '';}
		
		
		if ($GLOBALS["bst_share_it_array"]["buttons_bgcolor"]==''){
			$buttonscontainer_bordercolor='#E4E4E4';
		} else {
			$buttonscontainer_bordercolor=$GLOBALS["bst_share_it_array"]["buttons_bgcolor"];
		}
				
			$button_css ='<style id="inline_btn_css" type="text/css" >';
			
			$button_css .='.bst_share_it_container {width:100%;}';
			$button_css .='.bst_share_it_container_widget {width:100%;margin: 0px auto;}';
			
			
			$button_css .='.flex-divcontainer_footer_open_without_shadow {
				border-bottom:0px solid white !important;
				height:16px;
				text-align:right;
				padding-right:10px;
				padding-bottom:5px;
				font-size:10px;
				color:' . bst_share_it_getContrastYIQ($buttonscontainer_bordercolor) . ';
				background:' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';
				border-top: 0px solid ' . $buttonscontainer_bordercolor . ';
				border-left: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';
				border-right: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';
				border-bottom: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';}';
			
				
			$button_css .='.flex-divcontainer_footer_open {
				border-bottom:0px solid white !important;
				height:16px;
				text-align:right;
				padding-right:10px;
				padding-bottom:5px;
				font-size:10px;
				color:' . bst_share_it_getContrastYIQ($buttonscontainer_bordercolor) . ';
				background:' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';
				border-top: 0px solid ' . $buttonscontainer_bordercolor . ';
				border-left: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';
				border-right: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';
				border-bottom: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';'. $add_shadow . '}';
				
			$button_css .='.flex-divcontainer_footer_closed {
				height:15px;
				text-align:right;
				padding-right:10px;
				padding-bottom:5px;
				font-size:10px;
				-moz-border-radius-bottomleft: 6px;
				-webkit-border-bottom-left-radius: 6px;
				border-bottom-left-radius: 6px;
				-moz-border-radius-bottomright: 6px;
				-webkit-border-bottom-right-radius: 6px;
				border-bottom-right-radius: 6px;
				color:' . bst_share_it_getContrastYIQ($buttonscontainer_bordercolor) . ';
				background:' . $GLOBALS["bst_share_it_array"]["buttons_bgcolor"] . ';
				border-top: 0px solid ' . $buttonscontainer_bordercolor . ';
				border-left: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';
				border-right: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';
				border-bottom: '.$border_width.'px solid ' . $buttonscontainer_bordercolor . ';'. $add_shadow . '}';
			
			
			if (($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple1') or ($GLOBALS["bst_share_it_array"]["buttons_style"] =='simple2')){
				
				//Facebook Like Button
				$button_css .= 'div.sw_facebook {width:' . $iwidth . 'px;height:' . $iheight . 'px;background:#4168A2;border:none;margin:0 auto; line-height:' . $iheight .'px;}';	
				$button_css .= 'div.sw_facebook:hover {background:#4876BA;}';
				
				//Twitter Button
				$button_css .= 'div.sw_twitter {width:' . $iwidth . 'px;height:' . $iheight . 'px;background:#34A3CF;border:none;margin:0 auto; line-height:' . $iheight .'px;}';	
				$button_css .= 'div.sw_twitter:hover {background:#4DB2EC;}';
				
				//gplus Button
				$button_css .= 'div.sw_gplus {width:' . $iwidth . 'px;height:' . $iheight . 'px;background:#C54D35;border:none;margin:0 auto; line-height:' . $iheight .'px;}';	
				$button_css .= 'div.sw_gplus:hover {background:#E25A46;}';
				
				//pin Button
				$button_css .= 'div.sw_pin {width:' . $iwidth . 'px;height:' . $iheight . 'px;background:#AE3937;border:none;margin:0 auto; line-height:' . $iheight .'px;}';	
				$button_css .= 'div.sw_pin:hover {background:#D22C33;}';
				
				//in Button
				$button_css .= 'div.sw_in {width:' . $iwidth . 'px;height:' . $iheight . 'px;background:#2C8ABC;border:none;margin:0 auto; line-height:' . $iheight .'px;}';	
				$button_css .= 'div.sw_in:hover {background:#529CC2;}';
				
				//xing Button
				$button_css .= 'div.sw_xing {width:' . $iwidth . 'px;height:' . $iheight . 'px;background:#00706B;border:none;margin:0 auto; line-height:' . $iheight .'px;}';	
				$button_css .= 'div.sw_xing:hover {background:#00807A;}';
				
				//sw_stumble Button
				$button_css .= 'div.sw_stumble {width:' . $iwidth . 'px;height:' . $iheight . 'px;background:#E05E36;border:none;margin:0 auto; line-height:' . $iheight .'px;}';	
				$button_css .= 'div.sw_stumble:hover {background:#F56539;}';
				
				//mail Button
				$button_css .= 'div.sw_mail {width:' . $iwidth . 'px;height:' . $iheight . 'px;background:#54819E;border:none;margin:0 auto; line-height:' . $iheight .'px;}';	
				$button_css .= 'div.sw_mail:hover {background:#3D7CD3;}';

				
				
			} else {
			$button_css .= 'div.sw_facebook_like {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url . $style . '/facebook_like_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_facebook_like:hover {background: url("'. $img_url .  $style . '/facebook_like.png");}';
			//Facebook Button
			$button_css .= 'div.sw_facebook {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url . $style . '/facebook_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_facebook:hover {background: url("'. $img_url .  $style . '/facebook.png");}';
			//Twitter Button
			$button_css .= 'div.sw_twitter {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url . $style . '/twitter_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_twitter:hover {background: url("'. $img_url . $style . '/twitter.png");}';
			//gplus Button
			$button_css .= 'div.sw_gplus {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url .  $style . '/gplus_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_gplus:hover {background: url("'. $img_url .  $style . '/gplus.png");}';
			//pin Button
			$button_css .= 'div.sw_pin {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url .  $style . '/pin_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_pin:hover {background: url("'. $img_url .  $style . '/pin.png");}';
			//in Button
			$button_css .= 'div.sw_in {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url .  $style . '/in_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_in:hover {background: url("'. $img_url .  $style . '/in.png");}';
			//xing Button
			$button_css .= 'div.sw_xing {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url .  $style . '/xing_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_xing:hover {background: url("'. $img_url .  $style . '/xing.png");}';
			//Stumble Button
			$button_css .= 'div.sw_stumble {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url .  $style . '/stumble_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_stumble:hover {background: url("'. $img_url .  $style . '/stumble.png");}';
			//mail Button
			$button_css .= 'div.sw_mail {width:' . $iwidth . 'px;height:' . $iheight . 'px;background: url("'. $img_url .  $style . '/mail_grey.png");border:none;margin:0 auto;}';	
			$button_css .= 'div.sw_mail:hover {background: url("'. $img_url .  $style . '/mail.png");}';
			
			}
			
			$button_css .= 'div.sw_facebook_like a,
							div.sw_facebook a span,
							div.sw_twitter a span,
							div.sw_gplus a span,
							div.sw_pin a span,
							div.sw_in a span,
							div.sw_xing a span,
							div.sw_stumble a span,
							div.sw_mail a span {
							display:block;
							height:100%;
							color:#ffffff;
							font-family: Arial,sans-serif !important;
    					font-size:11px;
    					font-style:normal !important;
							font-weight:lighter !important;     
							float:left;
							}';
							
			$button_css .= 'div.sw_facebook_like img,
							div.sw_facebook img,
							div.sw_twitter img,
							div.sw_gplus img,
							div.sw_pin img,
							div.sw_in img,
							div.sw_xing img,
							div.sw_stumble img,
							div.sw_mail img {
							float:left;
							margin-top:6px;
							margin-left: 8px;
							margin-right: 10px;
							}';
							
							

			$button_css .='</style>';
		
	//	$GLOBALS["bst_share_it_array"]["build_css"]='1';	
		return $button_css;
}

function bst_share_it_build_fb_like_ ($permalink) {

if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1)
{
$button_type='button_count';
}
else
{
$button_type='button';	
};
	
$fb_like='<iframe src="http://www.facebook.com/plugins/like.php?href=' . urlencode($permalink) . '&amp;
			layout=' . $button_type . '&amp;
			show_faces=false&amp;
			width=100&amp;
			action=like&amp;
			font=helvetica&amp;
			colorscheme=light&amp;
			height="25" scrolling="no" 
			frameborder="0" style="border:none; overflow:hidden; width:130px; height:25px;">
		</iframe>';
		
		
		return $fb_like;
}


function bst_share_it_build_fb_like ($permalink) {

if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1)
{
$button_type='button_count';
}
else
{
$button_type='button';	
};
	
$fb_like='<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($permalink) .'&amp;layout='.$button_type.'&amp;show_faces=false&amp;width=100&amp;action=like&amp;font=helvetica&amp;colorscheme=light&amp;height=25" scrolling="no"frameborder="0" style="border:none; overflow:hidden; width:130px; height:25px;"></iframe>';
		return $fb_like;
}


function bst_share_it_build_fb_like_grey ($ausgabe) {
	
	if ($ausgabe == 'widget') {$enable_fb_like = 'enable_fb_like_widget';} else {$enable_fb_like = 'enable_fb_like';};
	
	
	if ($GLOBALS["bst_share_it_array"]["share_counter"] == 1)
		{
			$button_type='button_count';
		}
		else
		{
			$button_type='button';	
	};
	
	
	$permalink = get_permalink();
	//$fb_button_code=bst_share_it_build_fb_like($permalink);
	$img_url = bst_share_it_PLUGIN_URL . '/img/';
	
	$fb_like='<a id="' . $enable_fb_like . '" href="#" ><img src="'.$img_url.'fb_active_de.png" title = "'.__('Click here to activate the like button', 'bst_share_it').'" alt="' .__('Like Button without ringing home', 'bst_share_it').'"></a>';
	
	return $fb_like;
}
	
// *****************************************************************
// ausgabe der Buttons im Frontend ------------ --------------------
// *****************************************************************

add_action('the_content', 'bst_share_it_buttons_display_content');
//add_action('wp_footer', 'bst_share_it_buttons_display_content');

?>