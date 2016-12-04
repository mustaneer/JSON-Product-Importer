<?php
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
$memory_before = $memory_after = "";
$bg_total_entries = get_option("bg_total_entries");
if(!isset($_REQUEST['cron_step'])){
	$cron_step = 1;
	$memory_before = self::convert(memory_get_peak_usage());
	update_option("memory_before",$memory_before);
} else {
	$cron_step = $_REQUEST["cron_step"];
	$memory_before = get_option("memory_before");
}
if($cron_step > $bg_total_entries){
	$percetage = 100;
	$processing_id = get_option("processing_id");
	$results = $this->get_single($processing_id);
	$feed_url = $results[0]->feed_url;
	$feed_name = $results[0]->feed_name;
	$memory_before = get_option("memory_before");
	$memory_after = self::convert(memory_get_peak_usage());
	update_option("bg_total_entries","");
} else {
	$percetage = $cron_step / $bg_total_entries * 100;
	$feed_url = "";
	$feed_name = "";
	$processing_id = 0;
}

?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?=plugin_dir_url( __FILE__ ) ?>../../public/css/bootstrap.css">
<link rel="stylesheet" href="<?=plugin_dir_url( __FILE__ ) ?>../../public/css/feedreader_cron.css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script type="text/javascript">
		var percetage_show = "<?php echo $percetage; ?>";
		var feed_url = "<?php echo $feed_url; ?>";
		var feed_name = "<?php echo $feed_name; ?>";
		var feed_id = "<?php echo $processing_id; ?>";
</script>
<script src="<?=plugin_dir_url( __FILE__ ) ?>../../public/js/progressbar.js"></script>

<div class="container-fluid"  style="margin:60px">
  <span class="import_number">Importing To DB</span>
  <div class="clear"></div>
  <input type="radio" class="radio" name="progress" value="five" id="five-percentage">
  <input type="radio" class="radio" name="progress" value="twentyfive" id="twenty-percentage">
  <input type="radio" class="radio" name="progress" value="fifty" id="sixty-percentage">
  <input type="radio" class="radio" name="progress" value="seventyfive" id="eighty-percentage">
  <input type="radio" class="radio" name="progress" value="onehundred" id="onehundred-percentage">
  <div class="myprogress">
    <div class="progress-bar"></div>
  </div>
  <span class="percentage_number">1%</span>
  <div class="clear"></div>
  <span class="memory_usage">Memory Before Import <?php echo $memory_before; ?> </span>
  <?php if($memory_after){ ?>
	  <span class="memory_usage">Memory After Import <?php echo $memory_after; ?> </span>
  <?php } ?>
</div>