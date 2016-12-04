<?php

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
set_time_limit(0);
/**
 * NewFeedImporter
 *
 * @author Hassan Ali
 */
 
class NewFeedImporter {
	
    public function FeedImporter($formfields = "",$file_path){
        
        if(isset($formfields['feed_url']) && $formfields['feed_url'] != ""){
            $read = $this->feedReader($formfields['feed_url'],$file_path);
			if($read){
				return true;
			} else {
				return false;
			}
        }       
    }
    
    private function feedReader($feed_url = null ,$file_path = "files"){
		$ajaxfiles = $backgroundfiles = false;
		if($file_path == "files"){
			$ajaxfiles = true;
		} else {
			$backgroundfiles = true;
		}
		
		$filenum = 1;
		if(get_option( 'filenum')){
			$filenum = get_option( 'filenum');
			if($filenum == '999999999'){
				$filenum = 1;
			}
		}
		$filenum = $filenum +1 ;
		$feed_url = str_replace(' ','%20',$feed_url);
		if (filter_var($feed_url, FILTER_VALIDATE_URL) === FALSE) {
			return false;
		} else if($this->checkURL($feed_url) == false){
			return false;
		}
		$fd = fopen("$feed_url", "r");
		$folder = WP_PLUGIN_DIR .'/ttfeedreader/' . $file_path;
		$fileOfJson = fopen("$folder/feed_".sprintf("%09s", $filenum).".json", "w") or die("Unable to open file!");
		$flag = false;
		$starter = 0;
		$counter = 1;
		$objectPerFile = 10;
		$ajax_steps = 1;
		$numlimit = 0;
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
				$numlimit++;
				$buffer = stream_get_line($fd, 1000000, '},{"ID"');
				if($starter == 0){
					$bufferVal .= "" . $buffer . "},";
				} else {
					$bufferVal .= '{"ID"' . $buffer . "},";
				}
				$bufferVal = str_replace("}]}},", "}]",$bufferVal);
				if($counter == $objectPerFile){
					$ajax_steps++;
					$buffer = preg_replace(strrev("/},/"),strrev("}]"),strrev($bufferVal),1);
					$buffer = strrev($buffer);
					
					fwrite($fileOfJson, $buffer);
				} else {
					fwrite($fileOfJson, $bufferVal);
					$bufferVal = "";
				}
				
				$starter++;
			}
			if($counter == $objectPerFile){
				$bufferVal = "[";
				$counter = 1;
				$filenum++;
				$fileOfJson = fopen("$folder/feed_".sprintf("%09s", $filenum).".json", "w") or die("Unable to open file!");
			}
			$counter++;
			if ($filenum > 100000) {
				die();
			}
		}
		if($ajaxfiles){
			update_option( 'steps', $ajax_steps);
			update_option( 'total_entries', $numlimit);
		} else {
			update_option( 'bg_total_entries', $numlimit);
		}
		update_option( 'filenum', $filenum);
		update_option( 'lastfileinserted', "feed_".sprintf("%09s", $filenum).".json");
		unset($ajax_steps);
		unset($numlimit);
		unset($filenum);
		return true;
    }
	private function checkURL($feed_url){
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
	}
}
?>