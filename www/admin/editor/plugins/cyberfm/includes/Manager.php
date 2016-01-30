<?php
defined('ACCESS') or die();

class Manager {
	public static $file_m;
	//public static $image_m;
	public static $sess_m;
	public static $error_m;
	public static $conf;

	public function peform($task = ''){
		if (file_exists(TASKS_PATH.$task.EXT)){
			require_once(TASKS_PATH.$task.EXT);
		}
	}	
}
?>