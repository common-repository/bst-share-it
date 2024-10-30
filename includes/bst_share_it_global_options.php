<?php

bst_share_it_check_options_tab_1();
bst_share_it_check_options_tab_2();

function bst_share_it_check_options_tab_1() {
    //check if option is already present
    //option key is plugin_abbr_op, but can be anything unique

    if(!get_option('bst_share_it_options_tab_1')) {
        //not present, so add
        $op = array(
          			'facebook' => '1',
					'facebook_like' => '0',
  					'twitter' => '1',
					'gplus' => '1',
					'pin' => '1',
					'in' => '1',
					'xing' => '1',
					'stumble' => '1',
					'mail' => '1',
					'fb_opengraph' => '1',
					'fb_admin' => '',
					'fb_app_id' => '',
					'fb_article_author' => '',
					'fb_article_publisher' => '',
					'global_picture' => '',
					'btn_onposts' => '1',
					'btn_onpages' => '1',
					'px_before' => '',
					'px_after' => '',
					'share_counter' => '1',
					'twitter_card' => '',
					'twitter_site' => '',
					'twitter_creator' => '',
        );
        add_option('bst_share_it_options_tab_1', $op);
	}
	
	}
	
	function bst_share_it_check_options_tab_2() {
    //check if option is already present
    //option key is plugin_abbr_op, but can be anything unique
    if(!get_option('bst_share_it_options_tab_2')) {
        //not present, so add
        $op = array(
					'info_bgcolor' => 'cccccc',
					'info_fontcolor' => '000000',
					'buttons_bgcolor' => '',	
					'shadow' => '1',
					'buttons_style' => 'style1',
        );
        add_option('bst_share_it_options_tab_2', $op);
	}
	
	}
	

?>