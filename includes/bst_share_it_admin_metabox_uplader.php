<?php 
$bst_share_it_caption="<img src='". plugins_url('../img/share-button-red-16.png', __FILE__ ) . "' style='float:left; margin: 4px 3px 0px 0px;'>Bild für BST Social Sharing";
$metaBox = array(
  'id'     => 'bst_share_it_metabox_picture',
  'title'    => $bst_share_it_caption,
  'page'     => 'page',
  'context'  => 'side',
  'priority'   => 'low',
  'fields' => array(
    array(
      'name'   => 'BST Sharing Buttons Bild',
      'desc'   => 'Wählen Sie das von den Sharing Buttons zu verwendente Bild. Wenn Sie kein Bild auswählen wird das aktuelle Beitragsbild genommen!',
      'id'  => 'bst_share_it_image',  //value is stored with this as key.
      'class' => 'image_upload_field',
      'type'   => 'media'
    )
  )
);


function bst_share_it_add_custom_meta_boxes() {
    global $metaBox;
	$metaBox['page']='post';
	
	// Define the custom attachment for posts
    //add_meta_box($metaBox
	 add_meta_box($metaBox['id'], $metaBox['title'], 'bst_share_it_createMetaBox', $metaBox['page'], $metaBox['context'], $metaBox['priority']);
     
    // Define the custom attachment for pages
    $metaBox['page']='page';
	//add_meta_box($metaBox
	 add_meta_box($metaBox['id'], $metaBox['title'], 'bst_share_it_createMetaBox',$metaBox['page'], $metaBox['context'], $metaBox['priority']);
} 

// end bst_share_it_add_custom_meta_boxes
add_action('add_meta_boxes', 'bst_share_it_add_custom_meta_boxes');
add_action('save_post', 'bst_share_it_saveMetaData', 10, 2);

//<img src='". get_bloginfo('wpurl'). "/wp-content/plugins/bst_share_it/share-button-red-16.png' style='float:left; margin: 0px 3px 0px 0px;'>
    
    /**
    * Create Metabox HTML.
    */
    function bst_share_it_createMetaBox($post) {
      global $metaBox;
      if (function_exists('wp_nonce_field')) {
        wp_nonce_field('bst_share_it_nonce_action','bst_share_it_nonce_field');
      }
     
      foreach ($metaBox['fields'] as $field) {
        echo '<div class="bst_share_it_PicMetaBox">';
        //get attachment id if it exists.
        $meta = get_post_meta($post->ID, $field['id'], true);
        switch ($field['type']) {
          case 'media':
    ?>
            
            <style>
			.attachment-bst_share_it_image_thumbnail
			{
				width:250px;
				height:100px;
			}
			</style>
            
            <p><?php echo $field['desc']; ?></p>
            <div class="bst_share_it_MetaImage">
    <?php 
            if ($meta) {
              echo wp_get_attachment_image( $meta, 'bst_share_it_image_thumbnail', true);
              $attachUrl = wp_get_attachment_url($meta);
              echo 
              '<p>URL: <a target="_blank" href="'.$attachUrl.'">'.$attachUrl.'</a></p>';
            }
    ?>    
            </div><!-- end .bst_share_it_MetaImage -->
            <p>
              <input type="hidden" 
                class="bst_share_it_image_metaValueField" 
                id="<?php echo $field['id']; ?>" 
                name="<?php echo $field['id']; ?>"
                value="<?php echo $meta; ?>" 
              /> 
              <input id="bst_share_it_image_upload_button"  type="button" value="Bild wählen" /> 
              <input id="bst_share_it_image_remove_button" type="button" value="Bild entfernen" />
            </p>
     
    <?php
          break;
        }
        echo '</div> <!-- end .bst_share_it_PicMetaBox -->';
      } //end foreach
    }//end function bst_share_it_createMetaBox
     
     
    function bst_share_it_saveMetaData($post_id, $post) {
     
      if ( empty($_POST)
        || !wp_verify_nonce($_POST['bst_share_it_nonce_field'],'bst_share_it_nonce_action')
        || $post->post_type == 'revision') {
        return;
      }
     
      global $metaBox;
      global $wpdb;
     
      foreach ($metaBox['fields'] as $field) {
        $value = $_POST[$field['id']];
     
        if ($field['type'] == 'media' && !is_numeric($value) ) {
          //Convert URL to Attachment ID.
          $value = $wpdb->get_var(
            "SELECT ID FROM $wpdb->posts 
             WHERE guid = '$value' 
             AND post_type='attachment' LIMIT 1");
        }
        update_post_meta($post_id, $field['id'], $value);
      }//end foreach
    }//end function bst_share_it_saveMetaData
     

 ?>