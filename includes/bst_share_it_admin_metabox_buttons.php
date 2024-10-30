<?php
/**
 * function to return a custom field value.
 */
function bst_share_it_get_custom_field( $value ) {
	global $post;

    $custom_field = get_post_meta( $post->ID, $value, true );
    if ( !empty( $custom_field ) )
       
	      {  return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );}
	      
	      else
	      
	      { 
            return 'buttons_bottom'; //default
	      
	      }


 }


/**
 * Register the Meta box
 */
function bst_share_it_add_custom_meta_box() {
	$titletext =  __('Show or hide BST Social Buttons', 'bst_share_it');
	$title="<img src='". plugins_url('../img/share-button-red-16.png', __FILE__ ) . "' style='float:left; margin: 3px 3px 0px 0px;'>" . $titletext;
	
	add_meta_box( 'bst_share_it_metabox_buttonplace', $title, 'bst_share_it_metabox_buttonplace_output', 'post', 'normal', 'high' );
	add_meta_box( 'bst_share_it_metabox_buttonplace', $title, 'bst_share_it_metabox_buttonplace_output', 'page', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'bst_share_it_add_custom_meta_box' );


/**
 * Output the Meta box
 */
function bst_share_it_metabox_buttonplace_output( $post ) {
	// create a nonce field
	wp_nonce_field( 'my_bst_share_it_metabox_buttonplace_nonce', 'bst_share_it_metabox_buttonplace_nonce' ); ?>
	
<!-- 
	<p>
		<label for="bst_share_it_textfield"><?php //_e( 'Textfield', 'wpshed' ); ?>:</label>
		<input type="text" name="bst_share_it_textfield" id="bst_share_it_textfield" value="<?php //echo bst_share_it_get_custom_field( 'bst_share_it_textfield' ); ?>" size="50" />
    </p>
	
	<p>
		<label for="bst_share_it_textarea"><?php //_e( 'Textarea', 'wpshed' ); ?>:</label><br />
		<textarea name="bst_share_it_textarea" id="bst_share_it_textarea" cols="60" rows="4"><?php //echo bst_share_it_get_custom_field( 'bst_share_it_textarea' ); ?></textarea>
    </p>
    
 -->
       <p>
  
    <div class="bst_share_it-row-content">
        <p>
        <label for="bst_share_it-radio-one">
            <input type="radio" name="bst_share_it_radio" id="meta-radio-one" value="buttons_no" <?php if (bst_share_it_get_custom_field( 'bst_share_it_radio' ) == 'buttons_no') echo "checked=1";?>>
            <?php _e('Hide buttons', 'bst_share_it'); ?>
        </label>
        </p>
         <p>
        <label for="bst_share_it-radio-two">
  			<input type="radio" name="bst_share_it_radio" id="meta-radio-two" value="buttons_top" <?php if (bst_share_it_get_custom_field( 'bst_share_it_radio' ) == 'buttons_top') echo "checked=1";?>>
            <?php _e('Show buttons on top of post', 'bst_share_it'); ?>       
        </label>
        </p>
        <p>
        <label for="bst_share_it-radio-three">
  			<input type="radio" name="bst_share_it_radio" id="meta-radio-three" value="buttons_bottom" <?php if (bst_share_it_get_custom_field( 'bst_share_it_radio' ) == 'buttons_bottom') echo "checked=1";?>>
            <?php _e('Show Buttons on bottom of post', 'bst_share_it');?>        
        </label>
		</p>
    </div>
</p>


    
	<?php
}



/**
 * Save the Meta box values
 */
function bst_share_it_metabox_buttonplace_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['bst_share_it_metabox_buttonplace_nonce'] ) || !wp_verify_nonce( $_POST['bst_share_it_metabox_buttonplace_nonce'], 'my_bst_share_it_metabox_buttonplace_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post', get_the_id() ) ) return;

	// Checks for input and saves if needed
	if( isset( $_POST[ 'bst_share_it_radio' ] ) ) {
    	update_post_meta( $post_id, 'bst_share_it_radio', esc_attr($_POST[ 'bst_share_it_radio' ] ));
		
	
	}
}
add_action( 'save_post', 'bst_share_it_metabox_buttonplace_save' );


// Place the metabox in the post edit page below the editor before other metaboxes (like the Excerpt)
// add_meta_box( 'bst_share_it_metabox_buttonplace', __( 'Metabox Example', 'wpshed' ), 'bst_share_it_metabox_buttonplace_output', 'post', 'normal', 'high' );
// Place the metabox in the post edit page below the editor at the end of other metaboxes
// add_meta_box( 'bst_share_it_metabox_buttonplace', __( 'Metabox Example', 'wpshed' ), 'bst_share_it_metabox_buttonplace_output', 'post', 'normal', '' );
// Place the metabox in the post edit page in the right column before other metaboxes (like the Publish)
// add_meta_box( 'bst_share_it_metabox_buttonplace', __( 'Metabox Example', 'wpshed' ), 'bst_share_it_metabox_buttonplace_output', 'post', 'side', 'high' );
// Place the metabox in the post edit page in the right column at the end of other metaboxes
// add_meta_box( 'bst_share_it_metabox_buttonplace', __( 'Metabox Example', 'wpshed' ), 'bst_share_it_metabox_buttonplace_output', 'post', 'side', '' );

?>
