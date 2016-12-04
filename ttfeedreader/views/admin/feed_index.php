<?php
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?=plugin_dir_url( __FILE__ ) ?>../../public/css/bootstrap.css">
<link rel="stylesheet" href="<?=plugin_dir_url( __FILE__ ) ?>../../public/css/feedreader.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" />
<!-- Latest compiled and minified JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script type="text/javascript">
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<script src="<?=plugin_dir_url( __FILE__ ) ?>../../public/js/ttfeedreader.js"></script>

<div class="container-fluid">
  <div class="row">
	  <div class="page-header col-sm-11" id="feed-header">
		 <div class="informs">
			<p></p>
		</div>
		<form role="form" method="POST" action="?post_type=jsonfeeds&page=jsonfeeds.php">	
			<input type="hidden" name="action" value="newfeed" id="action">
			<div class="form-group">
				<label for="feed_url">Feed URL</label>
				<input class="form-control" id="feed_url" placeholder="Feed URL" name="feed_url" value="">
			</div>
		    <a class="btn btn-default " name="test_feedurl" href="">Test Feed URL </a> 
			<button type="submit" id="feed_submit" class="btn btn-primary">Import Feed Product</button>
			
			<?php  if ($filed > 3 ) { ?>	
				<span class="offlinebtn"> Feeds are remaining in Folder </span>
				<button type="submit" id="feed_remaining_submit" class="offlinebtn btn btn-primary">Import Remaining Feeds</button>
			<?php } ?>
			<div id="progressBar" class="default-feed"><div></div></div>
		</form>
				
	  </div>
	  <div class="direcory_show clearboth">
		<span class="spinner is-active"></span>
		<img src="<?=plugin_dir_url( __FILE__ ) ?>../../public/img/directory.png" alt="directory" />
		<h2> Reading Your JSON Files  </h2>
	  </div>
	  <h2 class="valuesShowentries col-sm-11"><small> Total <span id="total_entries"></span> JSON entities have been found</small></h2>
	  <div class="clearboth col-sm-11">
			<h2 class="valuesShow"><small>Total Memory Usage = <span id="total_usage"></span> MB</small></h2>
			<h2 class="valuesShow"><small>Total Time Consumed = <span id="time_usage"></span> Mins</small></h2>
	  </div>
  </div>
</div>