<?php

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

/**
 * WpFeedsCpt Custom Post Type
 *
 * @author Hassan Ali
 */
class WpFeedsCpt{
    
    
	/**
     * Registering Custom Post Type.
     *
     * @param  string   $cptName
     * @param  string  $cptLabel
     * @param  string $cptSingular
     *
     */
    public static function generate_custom_post($cptName,$cptLabel,$cptSingular){
		$labels = array(
			'name'               => _x( $cptLabel, 'post type general name' ),
			'singular_name'      => _x( $cptSingular, 'post type singular name' ),
			'add_new'            => _x( 'Add New', 'book' ),
			'add_new_item'       => __( "Add New $cptSingular" ),
			'edit_item'          => __( "Edit $cptSingular" ),
			'new_item'           => __( "New $cptSingular" ),
			'all_items'          => __( "All $cptLabel" ),
			'view_item'          => __( "View $cptSingular" ),
			'search_items'       => __( "Search $cptLabel" ),
			'not_found'          => __( "No $cptLabel found" ),
			'not_found_in_trash' => __( "No $cptLabel found in the Trash" ), 
		);
		$args = array(
			'labels'        => $labels,
			'description'   => 'Holds JSON Imported feed products and product specific data',
			'public'        => true,
			'menu_position' => 15,
			'supports'      => array( 'title', 'editor', 'thumbnail'),
			'menu_icon' 	=> 	plugin_dir_url( __FILE__ )."../../public/img/icon.png",
			'has_archive'   => true,
		);
		
		register_post_type( $cptName , $args );
		unset($args);
		unset($labels);
    }
	/**
     * Adding metabox with custom fields.
     *
     * @param  string $id
     * @param  string $metaboxName
     * @param  string $metaboxCallback
     * @param  string $customPosttype
     *
     */
    public static function generate_meta_box($id, $metaboxName, $metaboxCallback, $customPosttype, $place = "normal", $priority = "high"){
		add_meta_box( $id,
			$metaboxName,
			$metaboxCallback,
			$customPosttype, $place, $priority
		);
    }
	
	/**
     * Registering taxonomy for feed products categories
     *
     * @param  string   $taxonomyPlural
     * @param  string   $taxonomySingular
     * @param  string  $id
     * @param  string  $customPosttype
     * @param  string  $callback_func
     * @param  string  $callback_func_data
     *
     */
    public static function generate_custom_taxonomy($taxonomyPlural,$taxonomySingular,$id,$customPosttype,$callback_func,$callback_func_data){
		
		$labels = array(
			"name"              => _x( "$taxonomyPlural", "taxonomy general name" ),
			"singular_name"     => _x( "$taxonomySingular", "taxonomy singular name" ),
			"search_items"      => __( "Search $taxonomyPlural" ),
			"all_items"         => __( "All $taxonomyPlural" ),
			"parent_item"       => __( "Parent $taxonomySingular" ),
			"parent_item_colon" => __( "Parent $taxonomySingular:" ),
			"edit_item"         => __( "Edit $taxonomySingular" ), 
			"update_item"       => __( "Update $taxonomySingular" ),
			"add_new_item"      => __( "Add New $taxonomySingular" ),
			"new_item_name"     => __( "New $taxonomySingular" ),
			"menu_name"         => __( "$taxonomyPlural" ),
		);
		$args = array(
			"labels" => $labels,
			"hierarchical" => true,
		);
		register_taxonomy( $id, $customPosttype, $args );
		
		add_filter("manage_".$customPosttype."_posts_columns", $callback_func);
		add_action("manage_".$customPosttype."_posts_custom_column",  $callback_func_data);
		unset($args);
		unset($labels);
	}
	
	/**
     * Save custom meta fields values
     *
     * @param  string   $feed_id
     * @param  string|[]   $formFields
     *
     */
	public static function save_custom_fields($feed_id, $formFields = array()){
		foreach($formFields as $feedField => $formValue){
			if ( isset( $feedField ) && $feedField != '' ) {
				update_post_meta( $feed_id, "feed_$feedField", $formValue );
			}
		}
		
	}
	
	/**
     * Generate DISTINCT meta values against mera key
     *
     * @param  string   $key
     * @param  string   $type
     * @param  string   $status
     *
     */
	public static function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
		global $wpdb;
		if( empty( $key ) )
			return;
		$result = $wpdb->get_col( $wpdb->prepare( "
			SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
			LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = '%s' 
			AND p.post_status = '%s' 
			AND p.post_type = '%s'
		", $key, $status, $type ) );
		return $result;
	}
	
	/**
     * Generate Feed Admin Columns
     *
     * @param  string   $column
     *
     */
	public static function generate_feed_custom_columns($column){
		switch ($column) {
			case "description":
				the_excerpt();
				break;
			case "category":
				$speakers = get_the_terms(0, "feedproduct_category");
				$speakers_html = array();
				if(is_array($speakers)) {
				  foreach ($speakers as $speaker)
				  array_push($speakers_html, '<a href="' . get_term_link($speaker->slug, 'feedproduct_category') . '">' . $speaker->name . '</a>');
				  echo implode($speakers_html, ", ");
				}
				break;
			case "brand":
				$custom = get_post_custom();
				if(!empty($custom["feed_brand"])){
					echo $custom["feed_brand"][0];
				}
				break;
			case "price":
				$custom = get_post_custom();
				if(!empty($custom["feed_price"])){
					echo $custom["feed_price"][0];
				}
				if(!empty($custom["feed_currency"])){
					echo $custom["feed_currency"][0];
				}
				break;
		}
	}
	
	
	/* Bilal */
	public static function create_custom_post($cpt_name,$cpt_label_multiple,$cpt_label_singular){
		register_post_type( $cpt_name,
			array(
				"labels" => array(
					"name" => $cpt_label_multiple,
					"singular_name" => $cpt_label_singular,
					"add_new" => "Add New",
					"add_new_item" => "Add New $cpt_label_singular",
					"edit" => "Edit",
					"edit_item" => "Edit $cpt_label_singular",
					"new_item" => "New $cpt_label_singular",
					"view" => "View",
					"view_item" => "View $cpt_label_singular",
					"search_items" => "Search $cpt_label_singular",
					"not_found" => "No $cpt_label_singular found",
					"not_found_in_trash" => "No $cpt_label_singular found in Trash",
					"parent" => "Parent $cpt_label_singular"
				),
	 
				"public" => true,
				"menu_position" => 15,
				"supports" => array( "title", "editor", "thumbnail" ),
				"taxonomies" => array( "" ),
				"menu_icon" => plugin_dir_url( __FILE__ )."../../public/img/icon.png",
				"has_archive" => true
			)
		);
    }
	
	/**
     * connect single template for custom post type
     *
     * @param  string   $custom_post_type
     * @param  string  $template_path
     *
     */
    public static function cpt_single_template($custom_post_type,$template_path){
		if ( get_post_type() == $custom_post_type ) {
			if ( is_single() ) {
				// checks if the file exists in the theme first,
				// otherwise serve the file from the plugin
				if ( $theme_file = locate_template( array ( "single-$custom_post_type.php" ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = plugin_dir_path( __FILE__ ) . "/../../views/frontend/single-$custom_post_type.php";
				}
			}
		} else if(is_front_page()){
			$template_path = plugin_dir_path( __FILE__ ) . "/../../views/frontend/home-jsonfeeds.php";
		}
		return $template_path;
    }
}

?>