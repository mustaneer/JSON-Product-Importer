<?php

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

/**
 * AdminController
 *
 * @author Hassan Ali
 */
class AdminController{
    
    public $pluginLocation;
    
    function AdminController($location = ""){
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
            case "newfeed":
                include_once($this->pluginLocation ."/controllers/classes/new_feed_importer.php");
                break;                        
            default:
				$filed = count($this->isEmptyDir($this->pluginLocation ."/files"));
                include_once($this->pluginLocation ."/views/admin/feed_index.php");                                
                break;
        }
    }
	public function isEmptyDir($dir){ 
		 return $files = @scandir($dir);
	}
	
}


$controller = new AdminController(WP_PLUGIN_DIR .'/ttfeedreader');
?>