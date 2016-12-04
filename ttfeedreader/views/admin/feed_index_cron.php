<?php
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

if( empty($updated) ){
	 $updated = '';
}
?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?=plugin_dir_url( __FILE__ ) ?>../../public/css/bootstrap.css">
<link rel="stylesheet" href="<?=plugin_dir_url( __FILE__ ) ?>../../public/css/feedreader_cron.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" />

<!-- Latest compiled and minified JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>'; 
		var home_url = "<?php echo esc_url( home_url( '/' ) ) ?>"; 
</script>
<script src="<?=plugin_dir_url( __FILE__ ) ?>../../public/js/ttfeedreader_cron.js"></script>
<div class="container-fluid">
  <div class="row">
	<div class="col-sm-11">
	  <div class="page-header" id="feed-header">
		<form role="form" method="POST" action="?post_type=jsonfeeds&page=feedcron">	
		<input type="hidden" name="action" value="add_url" id="action">
		<input type="hidden" name="feed_status" value="pending" id="feed_status">
		<input type="hidden" name="feed_id" value="<?php if(isset($updated[0]->id)) echo $updated[0]->id; ?>" id="action">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="feed_url">Feed JSON URL</label>
						<input class="form-control" id="feed_url" placeholder="Feed URL" name="feed_url" value="<?php if(isset($updated[0]->feed_url)) { echo $updated[0]->feed_url; } ?>">
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label for="feed_url">Feed Name</label>
						<input class="form-control" id="feed_name" placeholder="Feed Name" name="feed_name" value="<?php if(isset($updated[0]->feed_name)) echo $updated[0]->feed_name; ?>">
					</div>
				</div>
				<div class="col-sm-2">
					<button type="submit" id="feed_submit" class="btn btn-primary" style=" margin-top: 22px; "><?php if(isset($updated[0]->id)){ echo "Update Feed URL"; } else { echo "Add Feed URL"; } ?></button>
				</div>
			</div>
		</form>		
	  </div>
	</div>
  </div>
</div>
<div class="container col-sm-11">
	<h2>All JSON Feeds</h2>
	<div class="row">
		<div class="col-sm-11 tablehead">
		  <table class="table table-bordered">
			<thead>
			  <tr>
				<th>Feed JSON Name</th>
				<th>Feed JSON URL</th>
				<th>Check JSON URL</th>
				<th>Actions</th>
			  </tr>
			</thead>
			<tbody>
			<?php $get_all = self::get_all();
				if($get_all){
				foreach($get_all as $result){
				?>
			
			  <tr>
				<td><?= $result->feed_name; ?></td>
				<td><button type="button" class="btn btn-default btn-sm" name="embed_jsonfeed" data-feed-url="<?= $result->feed_url; ?>" data-feed-name="feed JSON">Show Feed's JSON URL </button></td>
				<td><a class="btn btn-default btn-sm" name="show_jsonfeed" href="?post_type=jsonfeeds&page=feedcron&action=test_url&feed_id=<?= $result->id; ?>">Test Feed URL </a></td>
				<td>
					<?php if($result->feed_status == "pending") { ?>
						<button type="button" class="btn btn-default btn-sm" name="edit_jsonfeed" data-feed-id="<?= $result->id; ?>">Edit Feed URL </button>
						<button type="button" name="delete_button" class="btn btn-danger btn-sm" data-feed-id="<?= $result->id; ?>">Delete</button>
						<button type="button" name="process_button" class="btn btn-info btn-sm" data-feed-id="<?= $result->id; ?>">(Pending) Process Now</button>
					<?php } else if($result->feed_status == "import") { ?>
						<button type="button" name="import_button" class="btn btn-warning btn-sm" data-feed-id="<?= $result->id; ?>">(Files Created) Import Now to DB</button>
					<?php } else if($result->feed_status == "wrong") { ?>
					<button type="button" class="btn btn-default btn-sm" name="edit_jsonfeed" data-feed-id="<?= $result->id; ?>">Edit Feed URL </button>
						<button type="button" name="delete_button" class="btn btn-danger btn-sm" data-feed-id="<?= $result->id; ?>">Delete</button>
						<button type="button" name="" class="btn btn-danger btn-sm" >Something Wrong Not Processed</button>
					<?php } else { ?>
						<button type="button" name="delete_button" class="btn btn-danger btn-sm" data-feed-id="<?= $result->id; ?>">Delete</button>
						<button type="button" name="" class="btn btn-success btn-sm" >Successfully Processed</button>
					<?php } ?>
				</td>
			 </tr>
			  <form id="delete_<?= $result->id ?>_form" action="#" method="POST">
				<input type="hidden" value="delete_url" name="action">					
				<input type="hidden" value="<?= $result->id; ?>" name="feed_id">
			  </form>
			  <?php } }?>
			</tbody>
		  </table>
		</div>	
	</div>	
</div>
<div id="embed" class="modal fade">
	 <div class="modal-dialog">
		<div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title"><span id="feed_embed_name">feed_name</span> URL </h4>
	      </div>			
			<div class="modal-body" id="embed_url">
	    	  embed_url
		  	</div>
		    <div class="modal-footer">
		      <button type="button" data-dismiss="modal" class="btn btn-primary">Okay</button>
		    </div>
		</div>
	</div>
</div>
<div id="confirm" class="modal fade">
	 <div class="modal-dialog modal-sm">
		<div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title">Confirm</h4>
	      </div>			
			<div class="modal-body">
	    	  Are you sure you want to delete this Feed URL?
		  	</div>
		    <div class="modal-footer">
		      <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
		      <button type="button" data-dismiss="modal" class="btn">Cancel</button>
		    </div>
		</div>
	</div>
</div>
<div class="fancycontainer">
</div>