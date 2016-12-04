function removeImage() {
	jQuery("#feed_alt").hide();
	jQuery("#feed_featuredimage").hide();
	jQuery("#feed_remove_button").hide();

	jQuery("#feed_alt").val("");
	jQuery("#feed_featuredurl").val("");

	jQuery("#feed_featuredurl").show();
	jQuery("#feed_button").show();
}

function previewImage() {
	var $url = jQuery("#feed_featuredurl").val();

	if ($url) {
		jQuery("#feed_featuredurl").hide();
		jQuery("#feed_button").hide();

		jQuery("#feed_featuredimage").css('background-image', "url('" + $url + "')");

		jQuery("#feed_alt").show();
		jQuery("#feed_featuredimage").show();
	}
}
