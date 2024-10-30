<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
// *****************************************************************
// Vorarbeiten - Laden der Script- und CSS-Dateien -----------------
// *****************************************************************
$active_tab='';

// *****************************************************************
// Functions - 
// *****************************************************************
function bst_share_it_options_init() {
    register_setting(
        'bst_share_it_options_group_tab_1', // Optionsgruppe
        'bst_share_it_options_tab_1',  // Optionsname
		'bst_share_it_options_validate_tab_1'  // Callback zur Validierung
    );
	
	 register_setting(
		'bst_share_it_options_group_tab_2', // Optionsgruppe
        'bst_share_it_options_tab_2',  // Optionsname
		'bst_share_it_options_validate_tab_2'  // Callback zur Validierung
    );
	
	bst_share_it_checkop_tab_1();
	bst_share_it_checkop_tab_2();
}


/**
 * Creates the options
 */
function bst_share_it_checkop_tab_1() {
    //check if option is already present
    //option key is plugin_abbr_op, but can be anything unique
    if(!get_option('bst_share_it_options_tab_1')) {
        //not present, so add
        $op = array(
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
				
        );
        add_option('bst_share_it_options_tab_1', $op);
	}
	
	}
	
	
	function bst_share_it_checkop_tab_2() {
    //check if option is already present
    //option key is plugin_abbr_op, but can be anything unique
    if(!get_option('bst_share_it_options_tab_2')) {
        //not present, so add
        $op = array(
					'info_bgcolor' => '1d325f',
					'info_fontcolor' => 'e0e0e0',
					'buttons_bgcolor' => '',	
					'shadow' => '1',
					'buttons_style' => 'style1',
        );
        add_option('bst_share_it_options_tab_2', $op);
	}
	
	}


/**
 * Menüpunkt zu den Einstellungen im Dashboard hinzufügen
 */
function bst_share_it_options_menu() {
    add_options_page(
        "BSTshareiT",       // Seitentitel
        "<img src='" . plugins_url('../img/share-button-red-16.png', __FILE__ ) . "' style='float:left; margin: 0px 3px 0px 0px;'>BST share iT",       // Menütitel
        'manage_options',   // Berechtigung
        'bst_share_it-options',       // Slug
        "bst_share_it_options_page"      // Callback (Funktionsaufruf der Optionsseite) durch : function bst_share_it_options_page() 
    );
}

 
function bst_share_it_begruessung()
 {
 // Absendedatum und Absendeuhrzeit  
$Datum   = date("j.n.Y"); 
$Uhrzeit = date("H:i")." Uhr"; 

// Grußformel 
     
if(date("G") < 10) { 
$bst_share_it_begruessung = _e('Good morning', 'bst_share_it'); 
} 
elseif(date("G") <= 18 && date("G") >= 10) { 
$bst_share_it_begruessung = _e('Welcome', 'bst_share_it'); 
} 
elseif(date("G") >= 18) { 
$bst_share_it_begruessung = _e('Good evening', 'bst_share_it'); 
}
//return $Datum. " " . $Uhrzeit . " " .$bst_share_it_begruessung;
global $user_login;
return $bst_share_it_begruessung. " " . ucfirst($user_login);
}

/**
 * Die Optionsseite
 */


function bst_share_it_options_page() {
    ?>
    
<div class="wrap">
<div id="icon-options-general" class="icon32"></div>
<h2><?php echo bst_share_it_begruessung() ?>, <?php _e('welcome to the BST share iT Buttons', 'bst_share_it'); ?></h2><br />



<!-- ### The tab section ###################################################### -->
<?php
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'tab_1';
if(isset($_GET['tab'])) $active_tab = $_GET['tab'];
?>
<h2 class="nav-tab-wrapper">
<a href="?page=bst_share_it-options&amp;tab=tab_1" class="nav-tab <?php echo $active_tab == 'tab_1' ? 'nav-tab-active' : ''; ?>"><?php _e('General settings', 'bst_share_it'); ?></a>
<a href="?page=bst_share_it-options&amp;tab=tab_2" class="nav-tab <?php echo $active_tab == 'tab_2' ? 'nav-tab-active' : ''; ?>"><?php _e('Colors and styles', 'bst_share_it'); ?></a>
</h2>



<?php if($active_tab == 'tab_1') { ?>


<div id="poststuff" class="ui-sortable meta-box-sortables">
<div class="postbox">

	<div class="inside">

		<form method="post" action="options.php">
   		<?php
        		settings_fields('bst_share_it_options_group_tab_1');    // Optionsgruppe
        		$options = get_option('bst_share_it_options_tab_1');   // Optionsname
    	?>




        <div>
        <p><?php _e('Currently you have the following social buttons to share your content. By activating / deactivating the respective checkbox You can show or hide the buttons globally.', 'bst_share_it'); ?></ p>
        </div>


       <div class="flexbox-container"> <!-- Start Flexcontainer 1 -->
                
				<div> <!-- Column 1 -->
                        <div> <!-- Caption -->
                			<div style="display:inline-block; width:100px;line-height:20px;"><p><b><?php _e('Show button:', 'bst_share_it'); ?></b></p></div>
             			</div>
                        
                        <!-- FB Share -->
                        <div>
                        	<div style="display:inline-block; width:120px;line-height:20px;">Facebook Share:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[facebook]" type="checkbox" value="1" <?php checked('1', $options['facebook']); ?> /></div>
            			</div>
                        
                        <!-- FB Like -->
                        <div>
                			<div style="display:inline-block; width:120px;line-height:20px;">Facebook Like:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[facebook_like]" type="checkbox" value="1" <?php checked('1', $options['facebook_like']); ?> /></div>
            			</div>
                        
                        <!-- Twitter -->
                        <div>
                			<div style="display:inline-block; width:120px;line-height:20px;">Twitter:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[twitter]" type="checkbox" value="1" <?php checked('1', $options['twitter']); ?> /></div>
            			</div>
                        
                         <!-- G+ -->
                         <div>
                			<div style="display:inline-block; width:120px;line-height:20px;">Google+:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[gplus]" type="checkbox" value="1" <?php checked('1', $options['gplus']); ?> /></div>
            			</div>
                        
                        <!-- Pinterest -->
                        <div>
                			<div style="display:inline-block; width:120px;line-height:20px;">Pinterest:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[pin]" type="checkbox" value="1" <?php checked('1', $options['pin']); ?> /></div>
            			</div>
                        
                        <!-- In -->
 						<div>
                			<div style="display:inline-block; width:120px;line-height:20px;">LinkedIn:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[in]" type="checkbox" value="1" <?php checked('1', $options['in']); ?> /></div>
            			</div>
            
            			<!-- XING -->
            			<div>
                			<div style="display:inline-block; width:120px;line-height:20px;">XING:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[xing]" type="checkbox" value="1" <?php checked('1', $options['xing']); ?> /></div>
            			</div>
            
            			<!-- Stumble -->
             			<div>
                			<div style="display:inline-block; width:120px;line-height:20px;">Stumble:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[stumble]" type="checkbox" value="1" <?php checked('1', $options['stumble']); ?> /></div>
            			</div>
            
            			<!-- Mail -->
            			<div>
                			<div style="display:inline-block; width:120px;line-height:20px;">Mail:</div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[mail]" type="checkbox" value="1" <?php checked('1', $options['mail']); ?> /></div>
            			</div>
                        
                        <div>
                			<div style="display:inline-block; width:120px;line-height:20px;"></div>
            			</div>
                        
                        <div>
                			<div style="display:inline-block; width:120px;line-height:20px;"><b><?php _e('Show counter:', 'bst_share_it'); ?></b></div>
                 			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[share_counter]" type="checkbox" value="1" <?php checked('1', $options['share_counter']); ?> /></div>
            			</div>
                        
                
                </div> <!-- End column 1 -->
                
                
                
				<div>  <!-- Column 2 -->
                        <div>
                			<div style="display:inline-block; width:200px;line-height:20px;"><p><b><?php _e('Show buttons on:', 'bst_share_it'); ?></b></p></div>
           				</div>
            
            			<div>
                			<div style="display:inline-block; width:120px;line-height:20px;"><?php _e('Posts:', 'bst_share_it'); ?></div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[btn_onposts]" type="checkbox" value="1" <?php checked('1', $options['btn_onposts']); ?> /></div>
            			</div>
            			<div>
                			<div style="display:inline-block; width:120px;line-height:20px;"><?php _e('Pages:', 'bst_share_it'); ?></div>
                			<div style="display:inline-block;"><input name="bst_share_it_options_tab_1[btn_onpages]" type="checkbox" value="1" <?php checked('1', $options['btn_onpages']); ?> /></div>
            			</div>
            
            			<div>
                			<div style="display:inline-block; width:200px;line-height:20px;"><p><b><?php _e('Button space to content:', 'bst_share_it'); ?></b></p></div>
            			</div>
            
             			<div>
                			<div style="display:inline-block; width:120px;line-height:20px;"><?php _e('Pixel before:', 'bst_share_it'); ?></div>
                			<div style="display:inline-block; width:30px;"><input id="px_before" type="text" size="3" name="bst_share_it_options_tab_1[px_before]" value="<?php  echo $options['px_before']; ?>" /></div>
            			</div>
                        
            			<div>
                			<div style="display:inline-block; width:120px;line-height:20px;"><?php _e('Pixel after:', 'bst_share_it'); ?></div>
                			<div style="display:inline-block; width:30px;"><input id="px_after" type="text" size="3" name="bst_share_it_options_tab_1[px_after]" value="<?php  echo $options['px_after']; ?>" /></div>
            			</div>
                </div> <!-- End column 2 -->
		
        </div> <!-- End flexcontainer 1 -->
            	<div style="display:inline-block; width:390px;height:30px;"></div>
                <div>
                	<div style="display:inline-block; width:100%;line-height:20px;"><b><?php _e('Fallback to global picture if no post or sharing picture is set:', 'bst_share_it'); ?></b></div>
             	</div>
             
            	<div>
                	<div style="display:inline-block; width:390px;line-height:20px;">
                    <input id="upload_image" type="url" size="50" name="bst_share_it_options_tab_1[global_picture]" value="<?php  echo $options['global_picture']; ?>" readonly />
                    </div>
                    <div style="display:inline-block;width:100px;"><input id="upload_image_button" type="button" value="<?php _e('Picture', 'bst_share_it'); ?>" /></div>
             	</div> 
            	
                <div style="display:inline-block; width:390px;line-height:20px;"></div>
    			<div style="display:inline-block; width:100%;line-height:30px;"><p><b>Twitter Card:</b></p></div>
                
                <div>
                	<div style="display:inline-block; width:200px;"><?php _e('Include twitter card section:', 'bst_share_it'); ?></div>
                	<div style="display:inline-block;"><input id="twitter_toggle" title ="Setze diesen Haken nicht, wenn ein SEO-Tool, &#10oder ein anderes Plugin die og:metatags bereits einfügt." name="bst_share_it_options_tab_1[twitter_card]" type="checkbox" value="1" <?php checked('1', $options['twitter_card']); ?> /></div>
            	</div>
                
                 <div class = "twitter_card" style="display:none; width:100%;height:20px;"></div>
                 <div class = "twitter_card" style="display:none; width:100%;line-height:20px;">
                 		<div style="display:inline-block; width:135px;"><?php _e('Twitter site:', 'bst_share_it'); ?></div>
                		<div style="display:inline-block; width:350px;"><input id="twitter_site" type="text" size="45" name="bst_share_it_options_tab_1[twitter_site]" value="<?php  echo $options['twitter_site']; ?>" /></div>
                </div>
                
                 <div class = "twitter_card" style="display:none; width:100%;line-height:20px;">
                 		<div style="display:inline-block; width:135px;"><?php _e('Twitter creator:', 'bst_share_it'); ?></div>
                		<div style="display:inline-block; width:350px;"><input id="twitter_creator" type="text" size="45" name="bst_share_it_options_tab_1[twitter_creator]" value="<?php  echo $options['twitter_creator']; ?>" /></div>
                </div>		
                
                
                <div style="display:inline-block; width:390px;line-height:20px;"></div>
    			<div style="display:inline-block; width:100%;line-height:30px;"><p><b>Facebook Opengraph:</b></p></div>
    
                <div>
                	<div style="display:inline-block; width:200px;"><?php _e('Include opengraph section:', 'bst_share_it'); ?></div>
                	<div style="display:inline-block;"><input id="fb_toggle" title ="Setze diesen Haken nicht, wenn ein SEO-Tool, &#10oder ein anderes Plugin die og:metatags bereits einfügt." name="bst_share_it_options_tab_1[fb_opengraph]" type="checkbox" value="1" <?php checked('1', $options['fb_opengraph']); ?> /></div>
            	</div>

                 <div class = "fb_opengraph" style="display:none; width:100%;height:20px;"></div>	
                
                 <div class = "fb_opengraph" style="display:none; width:100%;line-height:20px;">
                 		<div style="display:inline-block; width:135px;"><?php _e('Facebook admin:', 'bst_share_it'); ?></div>
                		<div style="display:inline-block; width:350px;"><input id="fb_admin" type="text" size="45" name="bst_share_it_options_tab_1[fb_admin]" value="<?php  echo $options['fb_admin']; ?>" /></div>
                </div>
                
                <div class = "fb_opengraph" style="display:none; width:100%;line-height:20px;">
                 		<div style="display:inline-block; width:135px;"><?php _e('Facebook app id:', 'bst_share_it'); ?></div>
                		<div style="display:inline-block; width:350px;"><input id="fb_app_id" type="text" size="45" name="bst_share_it_options_tab_1[fb_app_id]" value="<?php  echo $options['fb_app_id']; ?>" /></div>
                </div>

                
                <div class = "fb_opengraph" style="display:none; width:100%;line-height:20px;">
                 		<div style="display:inline-block; width:135px;"><?php _e('Facebook author:', 'bst_share_it'); ?></div>
                		<div style="display:inline-block; width:350px;"><input id="fb_article_author" type="text" size="45" name="bst_share_it_options_tab_1[fb_article_author]" value="<?php  echo $options['fb_article_author']; ?>" /></div>
                </div>
                
                <div class = "fb_opengraph" style="display:none; width:100%;line-height:20px;">
                 		<div style="display:inline-block; width:135px;"><?php _e('Facebook publisher:', 'bst_share_it'); ?></div>
                		<div style="display:inline-block; width:350px;"><input id="fb_article_publisher" type="text" size="45" name="bst_share_it_options_tab_1[fb_article_publisher]" value="<?php  echo $options['fb_article_publisher']; ?>" /></div>
                </div>
               

   
</div> <!-- End inside -->
</div>
</div>
<?php } if($active_tab == 'tab_2') { ?>

<div id="poststuff" class="ui-sortable meta-box-sortables">
<div class="postbox">
<div class="inside">

	<form method="post" action="options.php">
   	<?php
        settings_fields('bst_share_it_options_group_tab_2');    // Optionsgruppe
        $options = get_option('bst_share_it_options_tab_2');   // Optionsname
    ?>

       	<div class="flexbox-container"> <!-- Start Flexcontainer 1 -->
        	<!-- Column 1 -->
	  		<div style="width:350px;>
             	<div>
                	<div style="display:inline-block; width:200px;line-height:20px;"><p><b><?php _e('Info field:', 'bst_share_it'); ?></b></p></div>
             	</div>
         		<div>
                	<div style="display:inline-block; width:200px;line-height:20px;margin-bottom:5px;"><?php _e('Background color:', 'bst_share_it'); ?></div>
                    <div style="display:inline-block; width:10px;line-height:20px;">#</div>
                	<div style="display:inline-block; width:200px;"><input name="bst_share_it_options_tab_2[info_bgcolor]" type="text" maxlength="6" id="picker" style="border-right:25px solid #<?php echo $options['info_bgcolor'];?>;" value="<?php echo $options['info_bgcolor']; ?>" /></div>
            	</div>
            	<div id="picker"></div>
          		
                <div>
                	<div style="display:inline-block; width:200px;line-height:20px;margin-bottom:5px;"><?php _e('Infofield font color:', 'bst_share_it'); ?></div>
                	<div style="display:inline-block; width:10px;line-height:20px;">#</div>
                	<div style="display:inline-block; width:200px;"><input name="bst_share_it_options_tab_2[info_fontcolor]" type="text" maxlength="6" id="picker_font" style="border-right:25px solid #<?php echo $options['info_fontcolor'];?>;" value="<?php echo $options['info_fontcolor']; ?>" /></div>
            	</div>
            	<div id="picker_font"></div>

        		<div>
                	<div style="display:inline-block; width:200px;height:20px;"></div>
             	</div>
                  
                <div>
                	<div style="display:inline-block; width:200px;line-height:20px;"><p><b><?php _e('Button Container:', 'bst_share_it'); ?></b></p></div>
             	</div>
  
          
                <div>
                	<div style="display:inline-block; width:200px;line-height:20px;margin-bottom:5px;"><?php _e('Buttons background color:', 'bst_share_it'); ?></div>
                	<div style="display:inline-block; width:10px;line-height:20px;">#</div>
                	<div style="display:inline-block; width:200px;"><input name="bst_share_it_options_tab_2[buttons_bgcolor]" type="text" maxlength="6" id="picker_bgbuttons" style="border-right:25px solid #<?php echo $options['buttons_bgcolor'];?>;"   value="<?php echo $options['buttons_bgcolor']; ?>" /></div>
            	</div>
            	<div id="picker_bgbuttons"></div>
                <div>
                	<div style="display:inline-block; width:200px;line-height:20px;"><?php _e('Shadow:', 'bst_share_it'); ?></div>
                	<div style="display:inline-block;"><input name="bst_share_it_options_tab_2[shadow]" type="checkbox" value="1" <?php checked('1', $options['shadow']); ?> /></div>
            	</div>
                 <div>
                	<div style="display:inline-block; width:200px;height:20px;"></div>
             	</div>
                <div>
                	<div style="display:inline-block; width:200px;line-height:20px;"><p><b><?php _e('Buttons at choice:', 'bst_share_it'); ?></b></p></div>
             	</div>
                
                <div>
                	<div style="display:inline-block; width:200px;line-height:20px;margin-bottom:5px;"><?php _e('Button style:', 'bst_share_it'); ?></div>
                	<div style="display:inline-block; width:200px;">
                    
                    <select name="bst_share_it_options_tab_2[buttons_style]">
    						<option value="simple1" <?php if ( $options['buttons_style'] == 'simple1' ) echo 'selected="selected"'; ?>><?php _e('The flat one', 'bst_share_it'); ?></option>
                            <option value="simple2" <?php if ( $options['buttons_style'] == 'simple2' ) echo 'selected="selected"'; ?>><?php _e('The simple flat one', 'bst_share_it'); ?></option>
    						<option value="style1" <?php if ( $options['buttons_style'] == 'style1' ) echo 'selected="selected"'; ?>>Style 1</option>
    						<option value="style2" <?php if ( $options['buttons_style'] == 'style2' ) echo 'selected="selected"'; ?>>Style 2</option>
                            <option value="style3" <?php if ( $options['buttons_style'] == 'style3' ) echo 'selected="selected"'; ?>>Style 3</option>
					</select>
                    
                    
                    
                    
</div>
            	</div>
                
      		
           </div> 
     
           
			<div>  <!-- Column 2 -->

        	</div> <!-- End column 2 -->
            
            
            
		
        </div> <!-- End flexcontainer 1 -->


</div>  <!-- End inside -->
</div>
</div>
<?php } if($active_tab == 'tab_3') { ?>
    
    

<?php } ?>
        <?php
            submit_button();
        ?>
        </form>

            <!--
            <div>
                <div style="display:inline-block; width:100px;">Text</div>
                <div style="display:inline-block;"><input type="text" name="bst_share_it-options[blabla]" value="<?php //echo $options['blabla']; ?>" /></div>
            </div>

			-->
             
         
<script type="text/javascript" charset="utf-8">
  	jQuery(document).ready(function($) {

		$('#picker').colpick({
			layout:'hex',
			submit:1,
			colorScheme:'light',
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				if(!bySetColor) $(el).val(hex);
			},
			onSubmit:function(hsb,hex,rgb,el) {
				$('#picker').colpickHide();
			}
		});
		
		
		
		$('#picker_font').colpick({
			layout:'hex',
			submit:1,
			colorScheme:'light',
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				if(!bySetColor) $(el).val(hex);
			},
			onSubmit:function(hsb,hex,rgb,el) {
				$('#picker_font').colpickHide();
			}
		});
		
		
			$('#picker_bgbuttons').colpick({
			layout:'hex',
			submit:1,
			colorScheme:'light',
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				if(!bySetColor) $(el).val(hex);
			},
			onSubmit:function(hsb,hex,rgb,el) {
				$('#picker_bgbuttons').colpickHide();
			}
		});
		
		}).keyup(function(){
		jQuery(this).colpickSetColor(this.value);
	});
 </script>


    
    
     <div>
     <hr />
     <p><?php _e('More information on this plugin you get', 'bst_share_it'); ?> <a href="http://www.bst-systemtechnik.de" target ="_blank" title="BST Social Buttons"><?php _e('here', 'bst_share_it'); ?></a> 
        
        
        
        </p>
        
        </div>
    
    
    
    
    </div>
    

    <?php
	
}


/**
 * Einstellungen validieren
 *
 * @param array $input
 * @return array $output
 */
function bst_share_it_options_validate_tab_1($input) {
    // Socual Buttons Global anzgeeigen [Ja] oder [Nein]
	// Facebook Share

   
    $output['facebook'] = ($input['facebook'] == 1 ? 1 : 0);
	// Facebook Like
    $output['facebook_like'] = ($input['facebook_like'] == 1 ? 1 : 0);
	// Twitter
    $output['twitter'] = ($input['twitter'] == 1 ? 1 : 0);
	// G+
    $output['gplus'] = ($input['gplus'] == 1 ? 1 : 0);
	// Pinterest
    $output['pin'] = ($input['pin'] == 1 ? 1 : 0);
	// LinkedIn
    $output['in'] = ($input['in'] == 1 ? 1 : 0);
	// XING
    $output['xing'] = ($input['xing'] == 1 ? 1 : 0);
	// Stumble
    $output['stumble'] = ($input['stumble'] == 1 ? 1 : 0);
	// mail
    $output['mail'] = ($input['mail'] == 1 ? 1 : 0);
	
	// Sektion wo sollen Buttons gezeigt werdebn
	// btn_onposts (in Artikeln)
    $output['btn_onposts'] = ($input['btn_onposts'] == 1 ? 1 : 0);
	
	// btn_onpages (in Seiten)
    $output['btn_onpages'] = ($input['btn_onpages'] == 1 ? 1 : 0);
	
	// Zeige Sharing Zähler
    $output['share_counter'] = ($input['share_counter'] == 1 ? 1 : 0);
	
	// Abstand zu den Buttons
	if (!$input['px_before']) {$output['px_before']='';} else {
	$safe_px_before = intval($input['px_before']);
		//Error handling
		$sub = 'px_before';
    	$err_message = __('Invalid input [ Pixel before ] - Allowed are values between 0 and 100', 'bst_share_it');
	
	if  ($safe_px_before == 0) {
		$safe_px_before = ''; add_settings_error($sub,'settings_updated',$err_message,'error');} 
	else 
		{if (($safe_px_before <= 0) || ($safe_px_before > 100) ) {$safe_px_before = ''; add_settings_error($sub,'settings_updated',$err_message,'error');}
	}
	$output['px_before']=$safe_px_before;}
	
	if (!$input['px_after']) {$output['px_after']='';} else {
	$safe_px_after = intval($input['px_after']);
		//Error handling
		$sub = 'px_after';
    	$err_message = __('Invalid input [ Pixel after ] - Allowed are values between 0 and 100', 'bst_share_it');
	if  ($safe_px_after==0) {
		$safe_px_after = ''; add_settings_error($sub,'settings_updated',$err_message,'error');} 
	else 
		{if (($safe_px_after <= 0) || ($safe_px_after > 100) ) {$safe_px_after= ''; add_settings_error($sub,'settings_updated',$err_message,'error');}
	}
	$output['px_after'] = $safe_px_after;}
	

	
	// Das globale Bildobjekt
	// only pictures from the media / inputfield ist readonly
	$output['global_picture'] = $input['global_picture'];
	
	// Twitter Card
	$output['twitter_card'] = ($input['twitter_card'] == 1 ? 1 : 0);
	
	//twitter_site -------------------------
	$safe_twitter_site = $input['twitter_site'];
		//Error handling
		$sub = 'twitter_site';
    	$err_message = __('Invalid input [ Twitter site ] - Allowed are letters a-z and @ with a max length of 50', 'bst_share_it');
	if  (!$safe_twitter_site ) {
		$safe_twitter_site = '';} 
	else {
		if (!preg_match("#^[a-zA-Z \@]+$#", $safe_twitter_site) || strlen( $safe_twitter_site ) > 50) {$safe_twitter_site= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
	}
	$output['twitter_site']=$safe_twitter_site; 
	
	//twitter_creator -------------------------
	$safe_twitter_creator = $input['twitter_creator'];
		//Error handling
		$sub = 'twitter_creator';
    	$err_message = __('Invalid input [ Twitter creator ] - Allowed are letters a-z and @ with a max length of 50', 'bst_share_it');
	if  (!$safe_twitter_creator ) {
		$safe_twitter_creator = '';} 
	else {
		if (!preg_match("#^[a-zA-Z \@]+$#", $safe_twitter_creator) || strlen( $safe_twitter_creator ) > 50) {$safe_twitter_creator= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
	}
	$output['twitter_creator']=$safe_twitter_creator; 
	
	// Facbook Opengraphp
	$output['fb_opengraph'] = ($input['fb_opengraph'] == 1 ? 1 : 0); 
	
    //fb_admin -------------------------
	$safe_fb_admin = $input['fb_admin'];
		//Error handling
		$sub = 'fb_admin';
    	$err_message = __('Invalid input [ Facebook admin ] - Allowed are numbers between 0 and 9 with max length 20', 'bst_share_it');
	if  (!$safe_fb_admin ) {
		$safe_fb_admin = '';} 
	else {
		if (!preg_match("#^[0-9]+$#", $safe_fb_admin) || strlen( $safe_fb_admin ) > 20) {$safe_fb_admin= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
		
	}
	$output['fb_admin'] =$safe_fb_admin;
	
	 //fb_app_id-------------------------
	$safe_fb_app_id = $input['fb_app_id'];
		//Error handling
		$sub = 'fb_app_id';
    	$err_message = __('Invalid input [ Facebook app id ] - Allowed are numbers between 0 and 9 with max length 20', 'bst_share_it');

	if  (!$safe_fb_app_id ) {
		$safe_fb_app_id = '';} 
	else {
		if (!preg_match("#^[0-9]+$#", $safe_fb_app_id) || strlen( $safe_fb_app_id ) > 20) {$safe_fb_app_id= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
	}
	$output['fb_app_id'] =$safe_fb_app_id;
	
	
	//fb_article_author ----------------------------------
	$safe_fb_article_author = $input['fb_article_author'];
		//Error handling
		$sub = 'fb_article_author';
    	$err_message = __('Invalid input [ Facebook author ] - Allowed are "a-z", "A-Z", "0-9" and ". : /"', 'bst_share_it');

	if  (!$safe_fb_article_author ) {
		$safe_fb_article_author = '';} 
	else {
		if (!preg_match("#^[a-zA-Z0-9 \.\:\/]+$#", $safe_fb_article_author) || strlen( $safe_fb_article_author ) > 100) {$safe_fb_article_author= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
		
	}
	$output['fb_article_author'] = $safe_fb_article_author;
	
	//fb_article_publisher -------------------------------------
	$safe_fb_article_publisher = $input['fb_article_publisher'];
		//Error handling
		$sub = 'fb_article_publisher';
    	$err_message = __('Invalid input [ Facebook publisher ] - Allowed are "a-z", "A-Z", "0-9" and ". : /"', 'bst_share_it');

	if  (!$safe_fb_article_publisher ) {
		$safe_fb_article_publisher = '';} 
	else {
		if (!preg_match("#^[a-zA-Z0-9 \.\:\/]+$#", $safe_fb_article_publisher) || strlen( $safe_fb_article_publisher ) > 100) {$safe_fb_article_publisher= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
	}
	$output['fb_article_publisher'] = $safe_fb_article_publisher;

    return $output;
}


function bst_share_it_options_validate_tab_2($input) {
    // Socual Buttons Global anzgeeigen [Ja] oder [Nein]
	// Facebook Share

    $output['shadow'] = ($input['shadow'] == 1 ? 1 : 0);
    
	// Das Textfeld für Backgroundcolor Info Bereich
    // info_bgcolor---------------------------------
	$safe_info_bgcolor = $input['info_bgcolor'];
		//Error handling
		$sub = 'info_bgcolor';
    	$err_message = __('Invalid input [ Info backcolor ] - Allowed are "a-f", "A-F" and "0-9"', 'bst_share_it');

	if  (!$safe_info_bgcolor ) {
		$safe_info_bgcolor = '';} 
	else {
		if (!preg_match("#^[a-fA-F0-9 \.\:\/]+$#",$safe_info_bgcolor) || strlen( $safe_info_bgcolor) > 6) {$safe_info_bgcolor= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
	}
	$output['info_bgcolor'] = $safe_info_bgcolor;
	
	// Das Textfeld für Fontcolor Info Bereich.
    // info_bgcolor---------------------------------
	$safe_info_fontcolor = $input['info_fontcolor'];
		//Error handling
		$sub = 'info_fontcolor';
    	$err_message = __('Invalid input [ Info fontcolor ] - Allowed are "a-f", "A-F" and "0-9"', 'bst_share_it');

	if  (!$safe_info_fontcolor ) {
		$safe_info_fontcolor = '';} 
	else {
		if (!preg_match("#^[0-9a-zA-Z]+$#", $safe_info_fontcolor) ||  strlen($safe_info_fontcolor) > 6) {$safe_info_fontcolor= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
	}
	$output['info_fontcolor'] = $safe_info_fontcolor;
	
	// Das Textfeld für Hintergrundfarbe der Buttons
	// buttons_bgcolor---------------------------------
	$safe_buttons_bgcolor = $input['buttons_bgcolor'];
			//Error handling
		$sub = 'buttons_bgcolor';
    	$err_message = __('Invalid input [ Buttons backcolor ] - Allowed are "a-f", "A-F" and "0-9"', 'bst_share_it');

	if  (!$safe_buttons_bgcolor) {
		$safe_buttons_bgcolor= '';} 
	else {
		if (!preg_match("#^[0-9a-zA-Z]+$#", $safe_buttons_bgcolor) || strlen($safe_buttons_bgcolor) > 6) {$safe_buttons_bgcolor= '';add_settings_error($sub,'settings_updated',$err_message,'error');}
	
	}
	$output['buttons_bgcolor'] = $safe_buttons_bgcolor;
	
	$output['buttons_style'] = $input['buttons_style'];
    return $output;
}




	






/** ----------------------------
 *  Optionen initioalisieren ---
 */ 
add_action('admin_init', 'bst_share_it_options_init');   // Optionen initialisieren

/** -----------------------------------------
 * Menüpunkt zu Einstellungen hinzufügen ----
 */
add_action('admin_menu', 'bst_share_it_options_menu');   // Menü hinzufügen


/** -------------------------------------------------------
 * Installiere Uploader Metabox in Artikeln und Seiten ----
 */
include_once('bst_share_it_admin_metabox_uplader.php');
include_once('bst_share_it_admin_metabox_buttons.php');


?>