<?php
/**
 * @package TTFeedReader
 * @version 1.0
 */
/*
Plugin Name: JSON-Product-Importer
Plugin URI: http://coresol.com.pk
Description: The Ultimate JSON Feed Reader
Author: Mustaneer Abdullah
Version: 1.0
*/
class MyTTFeedReader {
	const BATCH_SPLIT 	= 50;
	public static $name = "tt_feed_reader";
	public $pluginLocation;
	public $fieldList =  array();
	public $fieldLists =  array();
	public $brands;
	public $currency;
	public $counter;
	public $all_posts = array();
	
	function MyTTFeedReader(){
		set_time_limit(0);
		$this->pluginLocation = WP_PLUGIN_DIR .'/ttfeedreader';
	
		$this->loadAllFiles();
		
		add_action( 'tgmpa_register', array( &$this, 'jsonfeed_register_required_plugins') );
		register_activation_hook( __FILE__, array( &$this, 'activate_feed_reader' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'de_activate_feed_reader' ) );
					
		add_action( 'init', array(&$this, 'create_jsonfeed_post') );
		add_action( 'init', array(&$this, 'feed_taxonomies_product'), 0 );
		
		add_action( 'add_meta_boxes', array(&$this, 'feed_metaboxes'));
		
		add_action( 'save_post', array(&$this, 'add_feedproduct_fields'), 10, 2 );
		
		
		add_filter('cron_schedules',array(&$this, 'feed_cron_schedules'));
		
		add_action( 'wp_ajax_feed_wp_ajax_function',array(&$this, 'feed_wp_ajax_function'));
		add_action( 'wp_ajax_nopriv_feed_wp_ajax_function' , array(&$this, 'feed_wp_ajax_function'));
		add_filter( 'http_request_timeout',array(&$this, 'timeout_extend'));
		
		if ( ! wp_next_scheduled( 'wp_feed_schedule_hook' ) ){
			wp_schedule_event(time(),'fiveminutes', 'wp_feed_schedule_hook');
		}
		add_action( 'wp_feed_schedule_hook', array(&$this, 'feed_run_product_cron'));
		if ( ! wp_next_scheduled( 'wp_feed_schedule_tenhook' ) ){
			wp_schedule_event(time(),'tenminutes', 'wp_feed_schedule_tenhook');
		}
		add_action( 'wp_feed_schedule_tenhook', array(&$this, 'feed_run_files_cron'));
		
		add_filter( 'template_include', array(&$this, 'include_template_function'), 1 );
		
		add_action('admin_menu', array(&$this, 'feed_admin_menu'));
		
		add_filter( 'json_prepare_post', array(&$this, 'json_api_prepare_jsonfeed_post'), 10, 3 ); 
		add_filter( 'rest_prepare_category', array(&$this, 'my_rest_prepare_term'), 10, 3 );
	}
	/**
	 * tgmpa Plugin Dependency check for JSON API 
	 *
	 * @return void;
	 */
	public function jsonfeed_register_required_plugins(){
		
		$plugins = array(
			array(
				'name'      => 'WP REST API',
				'slug'      => 'json-rest-api',
				'required'  => true,
				'force_activation'   => true,
				'force_deactivation' => false,
			)
		);
		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);
		tgmpa( $plugins, $config );
	}
	/**
	 * Activation of TTFeedReader 
	 *
	 * @return void;
	 */
	public function activate_feed_reader(){		
		// creating new page for ttfeedreader product display by shortcode [ttfeedreader]
		FrontpageController::createPage();		
		FrontpageController::createTables();		
	}
	/**
	 * De-activation of TTFeedReader 
	 *
	 * @return void;
	 */
	public function de_activate_feed_reader(){
		// deleting new created page for ttfeedreader product display
		FrontpageController::deletePage();
		FrontpageController::dropTables();
		wp_clear_scheduled_hook( 'feed_schedule_hook' );
	}
	/**
	 * Feed Import Admin Menu
	 *
	 * @return void;
	 */
	public function feed_admin_menu(){
		add_submenu_page('edit.php?post_type=jsonfeeds', 'Import Feed Ajax', 'Import Feed Ajax', 'edit_posts', basename(__FILE__), array(&$this, 'buildAdminAjaxPage'));
		add_submenu_page('edit.php?post_type=jsonfeeds', 'Import Background', 'Import Background', 'edit_posts',"feedcron",array(&$this,  'buildAdminCronPage'));
	}
	/**
	 * Feed Custom Taxonomies
	 *
	 * @return void;
	 */
	public function feed_taxonomies_product() {
		WpFeedsCpt::generate_custom_taxonomy("Feed Categories","Feed Category","feedproduct_category","jsonfeeds",array(&$this, "feed_edit_columns"),array(&$this, "feed_custom_columns"));
	}
	/**
	 * Feed Custom Admin Columns
	 *
	 * @return void;
	 */
	public function feed_edit_columns($columns){
		$columns = array(
			"cb" => "<input type='checkbox' >",
			"title" => "Feed Product Title",
			"description" => "Description",
			"category" => "Category",
			"brand" => "Brand",
			"price" => "Price",
		);
		return $columns;
	}
	/**
	 * Feed Custom Columns
	 *
	 * @return void;
	 */
	public function feed_custom_columns($column){
		WpFeedsCpt::generate_feed_custom_columns($column);
	}
	/**
	 * Feed Custom Metabox
	 *
	 * @return void;
	 */
	public function feed_metaboxes() {
		 WpFeedsCpt::generate_meta_box("feed_meta_box","Feed Product Details",array(&$this, "show_meta_box"),"jsonfeeds");
		 WpFeedsCpt::generate_meta_box("feed_meta_featured_box","Feed Product Featured Image",array(&$this, "show_meta_featured_box"),"jsonfeeds", "side", "low");
	}
	/**
	 * Feed Custom Metabox View
	 *
	 * @return void;
	 */
	public function show_meta_box( $feedpost ) {
		$fieldList = array ( "id", "url", "price", "currency", "brand", "type", "sku", "stock", "ean", "thumbnail", "largeimage", "images", "deliverycost", "deliverytime");
		$brands = WpFeedsCpt::get_meta_values('feed_brand', 'jsonfeeds');
		$currency = WpFeedsCpt::get_meta_values('feed_currency', 'jsonfeeds');
		foreach($fieldList as $field){
			$fieldLists[$field] = esc_html( get_post_meta( $feedpost->ID, "feed_$field", true ) );
		}
		include_once("views/admin/feed_cpt_layout.php");
	}
	/**
	 * Feed Custom Featured Image by URL
	 *
	 * @return void;
	 */
	public function show_meta_featured_box( $feedpost ){
		$margin = 'margin-top:10px;';
		$width = 'width:100%;';
		$height = 'height:266px;';
		$align = 'text-align:left;';

		$url = get_post_meta($feedpost->ID, 'feed_featuredurl', true);
		$alt = get_post_meta($feedpost->ID, 'feed_alt', true);

		if ($url) {
			$show_url = $show_button = 'display:none;';
			$show_alt = $show_image = $show_remove_button = '';
		} else {
			$show_alt = $show_image = $show_remove_button = 'display:none;';
			$show_url = $show_button = '';
		}
		include_once("views/admin/meta_featured_box.php");
	}
	/**
	 * Feed Custom Fields Save
	 *
	 * @return void;
	 */
	public function add_feedproduct_fields($feed_id, $jsonfeeds){
		if ( $jsonfeeds->post_type == 'jsonfeeds' ) {
			// Store data in post meta table if present in post data
			if ( isset( $_POST['feed'] ) && !empty($_POST['feed']) ) {
				WpFeedsCpt::save_custom_fields($feed_id,$_POST['feed']);
			}
		}
	}
	/**
	 * Feed Import By Ajax
	 *
	 * @return void;
	 */
	public function feed_wp_ajax_function(){
	
		include_once($this->pluginLocation ."/models/feed_product_structure.php");
		include_once($this->pluginLocation ."/models/feedreader_database.php");
		
		$myaction = $_REQUEST['myaction'];
		if($myaction == "files"){
			update_option( 'start_usage', round(memory_get_usage()/1048576,2));
			update_option( 'start_time', microtime(true));
			include_once($this->pluginLocation ."/controllers/classes/new_feed_importer.php");
			$feedImporter = new NewFeedImporter();
			$response = $feedImporter->FeedImporter($_REQUEST, "files");
			if($response){
				$total_entries = get_option('total_entries');
				echo json_encode( array( 'step' => 'done', 'total_entries' => $total_entries) );
			} else {
				echo json_encode( array( 'step' => 'cancel') );
			}
			die(); 
		} else {
			$counter = 0;
			if(isset($_REQUEST['counter'])){
				$counter = $_REQUEST['counter'];
			}
			
			$json_products = $json_body = array();
			$way = $_REQUEST['way'];
			$step = $_REQUEST['step'];
			if($step == 1 && $way == "ajax"){
				$counter = 0;
			} else if($step == 1 && $way == "files"){
				update_option( 'start_usage', round(memory_get_usage()/1048576,2));
				update_option( 'start_time', microtime(true));
			}
			$json_total = get_option('total_entries');
			if($way == "ajax"){
				$steps =  get_option('steps');
			} else {
				if(get_option('steps')){
					$steps =  get_option('steps');
				} else {
					$steps =  count(@scandir($this->pluginLocation ."/files")) - 2;
					update_option('steps', $steps);
				}
				$json_total = $steps; 
			}
			

			$loopVal = $step;
		
			for($start = ($step-1); $start < $step; $start++) {
				$folder = WP_PLUGIN_DIR .'/ttfeedreader/files';
				$getfileOne = array_slice(glob("$folder/*.*"), 0, 1);
				if ( 0 == filesize( $getfileOne[0] ) ) {
					@unlink($getfileOne[0]);
				}
				$getfileOne = array_slice(glob("$folder/*.*"), 0, 1);
				$getfile = @file_get_contents($getfileOne[0]);
				$ext = pathinfo($getfileOne[0], PATHINFO_EXTENSION);
				if($ext == "json"){
					$getjsoncontents = json_decode($getfile);
					// print_r($getjsoncontents);
					// die;
					if(!empty($getjsoncontents)){
						foreach($getjsoncontents as $getjsoncontent){
							JSONFeedreaderDatabase::save(JSONFeedreaderDatabase::feedproduct($getjsoncontent));
							$counter++;
						}
						@unlink($getfileOne[0]);
					}
				}
			}
			if($way == "ajax"){
				$percentage = round(($counter / $json_total) * 100, 2) ;
			} else {
				$percentage = round(($step / $json_total) * 100, 2) ;
			}
			$step++;
			if( $step > $steps ){
				$start_usage = get_option('start_usage');
				$end_usage = round(memory_get_usage()/1048576,2);
				$total_usage = round($end_usage - $start_usage,2);
				
				$start_time = get_option('start_time');
				$time_usage = (microtime(true) - $start_time) / 60;
				$time_usage = round($time_usage,2);
				
				update_option( 'steps', "");
				update_option( 'start_usage', "");
				update_option( 'start_time', "");
				echo json_encode( array( 'step' => 'done', 'total_usage' => $total_usage, 'time_usage' => $time_usage ) );
				die(); 
			} else {
				echo json_encode( array( 'step' => $step, 'counter' => $counter, 'percentage' => $percentage ,'way' => $way) );
				die();
			}
			
		}		
	}
	/**
	 * Feed Custom Cron Schedules
	 *
	 * @return void;
	 */
	public function feed_cron_schedules($schedules){
		if(!isset($schedules["fiveminutes"])){
			$schedules["fiveminutes"] = array(
				'interval' => 3*60,
				'display' => __('Once every 5 minutes'));
		}
		if(!isset($schedules["tenminutes"])){
			$schedules["tenminutes"] = array(
				'interval' => 5*60,
				'display' => __('Once every 10 minutes'));
		}
		return $schedules;
	}
	/**
	 * Feed Background Import to DB
	 *
	 * @return void;
	 */
	public function feed_run_product_cron(){
		set_time_limit(0);
		global $wpdb;
		include_once($this->pluginLocation ."/models/feed_product_structure.php");
		include_once($this->pluginLocation ."/models/feedreader_database.php");
		$result_import = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."feed_entries WHERE feed_status = '%s' order by id ASC LIMIT 1", "import"));
		if(!empty($result_import)){
			$last_file = $result_import[0]->last_file;
			$feed_id = $result_import[0]->id;
			$folder = $this->pluginLocation . '/background_files';
			$getfiles = array_slice(glob("$folder/*.*"), 0, 110);
			
			if(!empty($getfiles)){
				foreach($getfiles as $getfile){
					if ( 0 == filesize( $getfile ) ) {
						@unlink($getfile);
					} else {
						$ext = pathinfo($getfile, PATHINFO_EXTENSION);
						if($ext == "json"){
							$getfileRec = @file_get_contents($getfile);
							$getjsoncontents = json_decode($getfileRec);
							if(!empty($getjsoncontents)){
								foreach($getjsoncontents as $getjsoncontent){
									JSONFeedreaderDatabase::save(JSONFeedreaderDatabase::feedproduct($getjsoncontent));
								}
								$filename = basename($getfile);
								$result_find = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."feed_entries WHERE last_file = '%s'", $filename));
								if($result_find){
									$values_update = array("success",$result_find[0]->id);
									$sql = "UPDATE ". $wpdb->prefix ."feed_entries SET feed_status = '%s' WHERE id=%d";
									$wpdb->query($wpdb->prepare($sql, $values_update));	
								}
								@unlink($getfile);
							}
						} else {
							@unlink($getfile);
						}
					}
				}
			} else {
				$values_update = array("success",$feed_id);
				$sql = "UPDATE ". $wpdb->prefix ."feed_entries SET feed_status = '%s' WHERE id=%d";
				if($wpdb->query($wpdb->prepare($sql, $values_update)) !== false){
					die;
				}
			}
		}
	}
	/**
	 * Feed Background Files Creation by URL
	 *
	 * @return void;
	 */
	public function feed_run_files_cron(){
		set_time_limit(0);
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."feed_entries WHERE feed_status = '%s' order by id ASC LIMIT 1", "pending"));
		if(!empty($results)){
			$feed_url = $results[0]->feed_url;
			$feed_name = $results[0]->feed_name;
			$feed_status = $results[0]->feed_status;
			$feed_id = $results[0]->id;
			
			include_once( $this->pluginLocation . "/controllers/classes/new_feed_importer.php");
			
			$files['feed_url'] = $feed_url;
			$files_success = "import";
			$files_wrong = "wrong";
			$feedImporter = new NewFeedImporter();
			$response = $feedImporter->FeedImporter($files , "background_files");
			if($response){
				$values_update = array($files_success,get_option("lastfileinserted"),$feed_id);
				$sql = "UPDATE ". $wpdb->prefix ."feed_entries SET feed_status = '%s', last_file = '%s' WHERE id=%d";
				if($wpdb->query($wpdb->prepare($sql, $values_update)) !== false){
					die;
				}
			} else {
				$values_update = array($files_wrong,$feed_id);
				$sql = "UPDATE ". $wpdb->prefix ."feed_entries SET feed_status = '%s' WHERE id=%d";
				if($wpdb->query($wpdb->prepare($sql, $values_update)) !== false){
					die;
				}
			}
		}
	}
	/**
	 * Feed Load All Files in Contstructor
	 *
	 * @return void;
	 */
	public function loadAllFiles(){
		include_once("activation/class-tgm-plugin-activation.php");
		include_once("controllers/frontpage_controller.php");
		include_once("controllers/classes/wp_feeds_cpt.php");	
		include_once("cache/wp-tlc-transients/tlc-transients.php");		
		include_once("cache/feed-rest-cache.php");		
	}
	/**
	 * Feed Admin Ajax Page
	 *
	 * @return void;
	 */
	public function buildAdminAjaxPage(){
        include_once("controllers/admin_controller.php");
	}
	/**
	 * Feed Admin Background Import Page
	 *
	 * @return void;
	 */
	public function buildAdminCronPage(){
        include_once("controllers/admin_cron_controller.php");
	}
	/**
	 * Feed Custom Post Types
	 *
	 * @return void;
	 */
	public  function create_jsonfeed_post() {
		WpFeedsCpt::create_custom_post("jsonfeeds","Json Feeds","Json Feed");
	}
	/**
	 * Timeout Extend
	 *
	 * @return void;
	 */
	public function timeout_extend( $time ){
		return 100;
	}

	/**
	 * Feed Forced Home Page and Post Single Page Template
	 *
	 * @return void;
	 */
	public function include_template_function( $template_path ) {
		return WpFeedsCpt::cpt_single_template("jsonfeeds",$template_path);
	}
	/**
	 * Including Custom Metaboxes values to WP API JSON
	 *
	 * @return void;
	 */
	public function json_api_prepare_jsonfeed_post( $post_response, $post, $context ) {
		$fieldList = array ( "id", "url", "price", "currency", "brand", "type", "sku", "stock", "ean", "thumbnail", "largeimage", "images", "deliverycost", "deliverytime", "featuredurl", "alt");
		foreach($fieldList as $field){
			$post_response[$field] = get_post_meta( $post['ID'], "feed_$field", true );
		}
		// removing not necessary paramteres
		$remove_indexes = array("status","parent","format","slug","guid","menu_order","comment_status","ping_status","sticky","date_gmt","date_tz","modified_gmt","modified_tz");
		foreach($remove_indexes as $index){
			unset( $post_response[$index] );
		}
		return $post_response;
	}

}
$myttfeedreader = new MyTTFeedReader();
?>
