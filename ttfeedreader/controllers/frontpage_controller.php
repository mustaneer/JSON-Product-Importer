<?php

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/**
 * FrontpageController
 *
 * @author Hassan Ali
 */
 if(!class_exists("FrontpageController")){
class FrontpageController{
	public $feedreader_page_id = 0;
	public $page_on_front = 0;
	public $page_on_front_old = 0;
	public $show_on_front = "";
	public $show_on_front_old = "";
	/**
	 * Create New Page
	 *
	 * @return void;
	 */
	public static function createPage(){
		global $wp_rewrite;
		$new_page = array(
			'post_title' => 'Products Home',
			'post_content' => '',
			'post_status' => 'publish',
			'post_type' => 'page',
		);
		 
		$feedreader_page_id = wp_insert_post($new_page);
		update_option('feedreader_page_id',$feedreader_page_id);
		
		// setup newpage as frontpage
		self::activateFrontPage($feedreader_page_id);
		
		//changing the permalink
		$permalink_structure = "/%postname%/";
		update_option('permalink_structure', $permalink_structure);
		$permalink_structure = sanitize_option( 'permalink_structure', $permalink_structure );
		$wp_rewrite->set_permalink_structure( $permalink_structure );
		
		unset($new_page);
		unset($permalink_structure);
	}
	
	/**
	 * Create New Table
	 *
	 * @return void;
	 */
	public static function createTables(){
		global $wpdb;
		$feed_sql = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix ."feed_entries (
		  id int(11) unsigned NOT NULL AUTO_INCREMENT,
		  feed_url text,
		  feed_name text,
		  feed_status text,  
		  last_file text,  
		  PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		$wpdb->query($feed_sql);
		unset($feed_sql);		
	}
	/**
	 * Drop Table
	 *
	 * @return void;
	 */
	public static function dropTables(){
		global $wpdb;
		$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "feed_entries");
	}
	/**
	 * Delete Page
	 *
	 * @return void;
	 */
	public static function deletePage(){
		$feedreader_page_id = get_option('feedreader_page_id');		
		wp_delete_post( $feedreader_page_id, true);
		
		// deactivate frontpage
		self::deActivateFrontPage();
	}
	
	/**
	 * Activate New Front Page Configuration 
	 *
	 * @param int $page_id new page id
	 * @return void;
	 */
	public static function activateFrontPage($page_id){
		// getting the old configuration of front page 
		$show_on_front = get_option('show_on_front');
		$page_on_front = get_option('page_on_front');
		
		// saving the old configuration of front page 
		update_option( 'show_on_front_old', $show_on_front );
		update_option( 'page_on_front_old', $page_on_front );
		
		// updating the new configuration of front page 
		update_option( 'page_on_front', $page_id );
		update_option( 'show_on_front', 'page' );
	}
	/**
	 * De-activate Front Page Configuration to Old
	 *
	 * @return void;
	 */
	public static function deActivateFrontPage(){
		// getting the saved old configuration of front page
		$show_on_front_old = get_option( 'show_on_front_old' );
		$page_on_front_old = get_option( 'page_on_front_old' );
		
		// setting up the saved old configuration of front page
		if($page_on_front_old == "page"){
			update_option( 'page_on_front', $page_on_front_old );
			update_option( 'show_on_front', 'page' );
		} else {
			update_option( 'show_on_front', 'posts' );
		}
	}
}
}
?>