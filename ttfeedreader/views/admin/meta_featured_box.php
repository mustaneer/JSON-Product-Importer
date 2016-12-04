<script type="text/javascript" src="<?=plugin_dir_url( __FILE__ ) ?>../../public/js/meta_featured_box.js"></script>

<!-- show alt field, image and link if URL was already provided -->

<input id="feed_alt" 
	   type="text" 
	   name="feed[alt]" 
	   placeholder="alt attribute (optional)" 
	   value="<?php echo $alt; ?>" 
	   style="<?php echo $width, $margin, $show_alt ?>" />

<div id="feed_featuredimage"
	 style="<?php echo $height, $margin, $show_image ?> 
	 background:url('<?php echo $url; ?>') no-repeat center center; 
	 background-size:cover;" >
</div>
<a id="feed_remove_button" 
   class="button" 
   onClick="removeImage();" 
   style="<?php echo $align, $margin, $show_remove_button ?>" >Remove</a>

<!-- show URL field and preview button if URL was not provided yet -->

<input id="feed_featuredurl" 
	   type="text" 
	   name="feed[featuredurl]" 
	   placeholder="URL" 
	   value="<?php echo $url; ?>"
	   style="<?php echo $width, $margin, $show_url ?>" />

<a id="feed_button" 
   class="button" 
   onClick="previewImage();" 
   style="<?php echo $align, $margin, $show_button ?>" >Preview</a>
