<?php
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


class JSONFeedreaderDatabase{
	public $categoryIds = array();
	public $varField;
	public $fieldList;
	const POST_TYPE = 'jsonfeeds';
	const CATEGORY_TYPE = 'feedproduct_category';
	
	public static function save(\FeedProductStructure $feedproduct){
		global $wpdb;

		$categoryIds = array();
		foreach($feedproduct->categories as $category){
			$categoryIds[] = $category;
			self::createCategory($category);
		}
        $feedproduct_post = array(
          'post_title'      => $feedproduct->name(),
          'post_content'    => $feedproduct->description(),
          'post_status'     => 'publish',
          'comment_status'  => 'closed',
          'ping_status'     => 'closed',
          'post_author'     => 1,
		  'post_category'   => $categoryIds,
          'post_type'       => self::POST_TYPE,
		  'tax_input' => array(
                self::CATEGORY_TYPE => $categoryIds
            )
        );
        // Insert the post into the database and get the resulting id back... 
        $post_id = wp_insert_post( $feedproduct_post, false );
		wp_set_object_terms($post_id, $categoryIds, self::CATEGORY_TYPE, true);
		$fieldList = array ( "id", "url", "price", "currency", "brand", "type", "sku", "stock", "ean", "thumbnail", "largeimage", "images", "deliverycost", "deliverytime","featuredurl","alt");
		foreach($fieldList as $field){
			if(is_array ($feedproduct->$field())){
				$varField = implode(",",$feedproduct->$field());
			} else {
				$varField = $feedproduct->$field();
			}
			update_post_meta( $post_id, "feed_$field" , $varField );
		}
		unset($fieldList);
		unset($categoryIds);
		unset($feedproduct_post);
		unset($fieldList);
		return false;
	}
	
	public static function createCategory($category){
		if($category == ""){
			return false;
		}
		$parent_term = term_exists( $category , self::CATEGORY_TYPE ); // array is returned if taxonomy is given
		// print_r($parent_term);
		$taxonomy_term_id = $parent_term['term_id']; // get numeric term id
		if($taxonomy_term_id){
			return true;
		} else {
			$cat_id = wp_insert_term(
			  $category, // the term 
			  self::CATEGORY_TYPE, // the taxonomy
			  array(
				'description'=> $category,
				'slug' => str_replace(" ","-",strtolower($category)),
			  )
			);
			return true;
		}
		
	}
	
	/**
     * Returns feedproduct by json object.
     *
     * @param string json.
     * @return FeedProduct.
     */
    public static function feedproduct($jsonObj) {
        $result = new FeedProductStructure;
        $result->id(trim((string)$jsonObj->ID));
        $result->name(trim((string)$jsonObj->name));
        $result->currency(trim((string)$jsonObj->price->currency));
        $result->price(trim((string)$jsonObj->price->amount));
        $result->url(trim((string)$jsonObj->URL));
        $result->description(trim((string)$jsonObj->description));
        $result->brand(self::generateValues($jsonObj->properties->brand));
        $result->type(self::generateValues($jsonObj->properties->producttype));
        $result->deliverycost(self::generateValues($jsonObj->properties->deliveryCosts));
        $result->sku(self::generateValues($jsonObj->properties->SKU));
        $result->stock(self::generateValues($jsonObj->properties->stock));
        $result->thumbnail(self::generateValues($jsonObj->properties->thumbnailURL));
        $result->deliverytime(self::generateValues($jsonObj->properties->deliveryTime));
        $result->largeimage(self::generateValues($jsonObj->properties->imageURLlarge));
        $result->featuredurl(self::generateValues($jsonObj->properties->imageURLlarge));
        $result->ean(self::generateValues($jsonObj->properties->EAN));
        $result->images(self::generateValues($jsonObj->images));
        $result->categories(self::generateValues($jsonObj->categories));
		
        return $result;
    }
	
	public static function generateValues($jsonvalue){
		$values = [];
        foreach ($jsonvalue as $value) {
            $values[] = trim((string)$value);
        }
		return $values;
	}
	
}
?>