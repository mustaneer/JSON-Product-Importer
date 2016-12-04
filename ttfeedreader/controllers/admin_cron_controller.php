<?php

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

/**
 * AdminCronController
 *
 * @author Hassan Ali
 */
class AdminCronController{
    
    public $pluginLocation;
    
    function AdminCronController($location = ""){
        $this->pluginLocation = $location;
		if(!isset($_REQUEST['action']) || $_REQUEST['action'] == "index"){
            $this->render('index');
        } else{
            $this->render($_REQUEST['action']);
        }
		
    }
	public function render($action = null){
        switch($action){            
            //Feed  Actions
            case "files_create":
				$this->files_create($_REQUEST);
				break;
            case "process_now":
				$cron_step = 0;
				include_once($this->pluginLocation ."/views/admin/process_show.php");
				if($cron_step > $bg_total_entries){
					?>
					<script type="text/javascript">
						setTimeout(function(){ 
							window.location.href="?post_type=jsonfeeds&page=feedcron&action=add_url&feed_id="+feed_id+"&feed_url="+feed_url+"&feed_name="+feed_name+"&feed_status=sucsess";
						}, 4000);
					</script>
					<?php
				} else {
					$this->import_to_db($cron_step);
				}
				break;
            case "add_url":
				$this->add_url($_REQUEST);
				break;
			case "update_url":
				$feed_id = $_REQUEST['feed_id'];
				$updated = $this->get_single($feed_id);
				include_once($this->pluginLocation ."/views/admin/feed_index_cron.php"); 
				break;
			case "delete_url":
				$this->delete_url($_REQUEST);
				$this->render('index');
				break; 
			case "test_url":
				if(isset($_REQUEST['feed_id'])){
					$feed_id = $_REQUEST['feed_id'];
					$result = $this->get_single($feed_id);
					$feed_url = $result[0]->feed_url;
				} else if(isset($_REQUEST['feed_url'])){
					$feed_url = $_REQUEST['feed_url'];
				}
				$this->test_url($feed_url);
				break;                        
            default:
				include_once($this->pluginLocation ."/views/admin/feed_index_cron.php");                                
                break;
        }
    }
	private function import_to_db($cron_step){
		set_time_limit(0);
		global $wpdb;
		include_once($this->pluginLocation ."/models/feed_product_structure.php");
		include_once($this->pluginLocation ."/models/feedreader_database.php");
		$counter = $cron_step;
		$folder = $this->pluginLocation . '/direct_files';
		$getfiles = array();
		$nofiles = false;
		if(!empty(glob("$folder/*.*"))){
			$getfiles = @array_slice(glob("$folder/*.*"), 0, 10);
		}
		if(empty($getfiles)){
			$folder = $this->pluginLocation . '/background_files';
			if(!empty(glob("$folder/*.*"))){
				$getfiles = @array_slice(glob("$folder/*.*"), 0, 10);
				if(get_option("bg_total_entries") == ""){
					update_option("bg_total_entries",count(glob("$folder/*.*")));
				}
				
			} else {
				$nofiles = true;
			}
		}
		if(!empty($getfiles)){
			
			foreach($getfiles as $getfile){
				if ( 0 == @filesize( $getfile ) ) {
					@unlink($getfile);
				} else {
					$ext = pathinfo($getfile, PATHINFO_EXTENSION);
					if($ext == "json"){
						$getfileRec = @file_get_contents($getfile);
						$getjsoncontents = json_decode($getfileRec);
						if(!empty($getjsoncontents)){
							foreach($getjsoncontents as $getjsoncontent){
								JSONFeedreaderDatabase::save(JSONFeedreaderDatabase::feedproduct($getjsoncontent));
								$counter++;
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
			if($nofiles){
				?>
				<script type="text/javascript">
					window.location.href="?post_type=jsonfeeds&page=feedcron";
				</script>
				<?php
			} else {			
				$counter++;
			}
		}
		?>
		
		<script type="text/javascript">
				var cron_step = "<?php echo $counter; ?>";
				window.location.href="?post_type=jsonfeeds&page=feedcron&action=process_now&cron_step="+cron_step;
		</script>
		<?php
	}
	private function files_create($files = array()){
		global $wpdb;
		include_once( $this->pluginLocation . "/controllers/classes/new_feed_importer.php");
		$feed_id = $files['feed_id'];
		$file_db_res = $this->get_single($feed_id);
		$files['feed_url'] = $file_db_res[0]->feed_url;
		$files['feed_name'] = $file_db_res[0]->feed_name;
		$feed_status = $file_db_res[0]->feed_status;
		if($feed_status == "pending"){
			$files['feed_status'] = "import";
			$feedImporter = new NewFeedImporter();
			$response = $feedImporter->FeedImporter($files , "direct_files");
			if($response){
				$values_update = array(get_option("lastfileinserted"),$feed_id);
				$sql = "UPDATE ". $wpdb->prefix ."feed_entries SET last_file = '%s' WHERE id=%d";
				if($wpdb->query($wpdb->prepare($sql, $values_update)) !== false){
					$this->add_url($files ,"process_now");
				}
			} else {
				$values_update = array("wrong",$feed_id);
				$sql = "UPDATE ". $wpdb->prefix ."feed_entries SET feed_status = '%s' WHERE id=%d";
				if($wpdb->query($wpdb->prepare($sql, $values_update)) !== false){
					$this->render('index');
				}
			}
		} else {
			$this->render('index');
		}
	}
	
	private function add_url($parameters , $redirect = "index"){
		global $wpdb;
		$feed_url = $parameters['feed_url'];
		$feed_name = $parameters['feed_name'];
		$feed_id = $parameters['feed_id'];
		$feed_status = $parameters['feed_status'];
		
		$values = array($feed_url, $feed_name, $feed_status);
		if($feed_id && intval($feed_id) !== 0){
			$sql = "UPDATE ". $wpdb->prefix ."feed_entries SET feed_url='%s', feed_name='%s', feed_status='%s' WHERE id=%d";		
			$values[] = $feed_id;
			update_option("processing_id",$feed_id);
		}
		else{
			$sql = "INSERT INTO ". $wpdb->prefix ."feed_entries (feed_url, feed_name, feed_status) VALUES ('%s', '%s', '%s')";			
		}
		if($wpdb->query($wpdb->prepare($sql, $values)) !== false){
			$this->render($redirect);
		} else {
			$this->render("index");
		}
		unset($feed_url);
		unset($feed_name);
		unset($feed_id);
		unset($feed_status);
	}
	private function delete_url($feed){
		global $wpdb;
		$feed_id = $feed['feed_id'];
		return $wpdb->query($wpdb->prepare("DELETE FROM ". $wpdb->prefix ."feed_entries WHERE id=%d",$feed_id));
	}
	public static function get_all(){
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."feed_entries Order By id Desc");
		return $results;
	}
	private function get_single($item_id){
		global $wpdb;
		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."feed_entries WHERE id=%d LIMIT 1", $item_id));
		return $results;
	}
	private function test_url($feed_url){
		$feed_url = str_replace(' ','%20',$feed_url);
		echo "<div style='min-width:500px;min-height:500px;'>";
		if (filter_var($feed_url, FILTER_VALIDATE_URL) === FALSE) {
			echo 'Not a valid URL';
			echo "</div>";
			die;
		} else if($this->checkURL($feed_url) == false){
			echo "URL don't have json entries :(";
			die;
		}
				echo "<h3> Feed URL : ". $feed_url. "</h3>";
		echo "<h4> Approx 2 json object of your feed </h4>";
		$fd = fopen("$feed_url", "r");
		$flag = false;
		$starter = 0;
		$counter = 0;
		$objectPerFile = 2;
		$bufferVal = "";
		while ( !feof($fd) )
		{	
			$buffer = "";
			if($flag == false){
				$char = fread($fd, 1);
				if ($char == '[') {
					$flag = true;
				} 
			} else {
				$buffer = stream_get_line($fd, 1000000, '},{"ID"');
				if($starter == 0){
					$bufferVal .= "" . $buffer . "},";
				} else {
					$bufferVal .= '{"ID"' . $buffer . "},";
				}
				$bufferVal .= str_replace("}]}},", "}]",$bufferVal);
				
				if($counter == $objectPerFile){
					$buffer = preg_replace(strrev("/},/"),strrev("}]"),strrev($bufferVal),1);
					$buffer = strrev($buffer);
					echo $bufferVal = $buffer . "<br /><br />";
				}
				echo $bufferVal . "<br /><br />";
				$starter++;
				$counter++;
			}
			if($counter == $objectPerFile){
				break;
			}
			
		}
		echo "</div>";
		die;
	}
	private function checkURL($feed_url){
		$buffers = "";
		$sitecheck = false;
		$fds = fopen("$feed_url", "r");
		while ( !feof($fds) )
		{
			if($sitecheck == false){
				$sitecheck = true;
				$buffers = stream_get_line($fds, 1000000, '},{"ID"');
				if($buffers == null || $buffers == ""){
					return false;
				}
			} else {
				return true;
			}
		}
		unset($buffers);
	}
	public static function convert($size){
		$unit=array('B','KB','MB','GB','TB','PB');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}
}


$admincroncontroller = new AdminCronController($this->pluginLocation);
?>