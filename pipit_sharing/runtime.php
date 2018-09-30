<?php
include(PERCH_PATH.'/addons/apps/pipit_sharing/lib/vendor/autoload.php');
	spl_autoload_register(function($class_name){
		if (strpos($class_name, 'PipitSharing_')===0) {
			include(PERCH_PATH.'/addons/apps/pipit_sharing/lib/'.$class_name.'.class.php');
			return true;
		}
		return false;
	});
	
	PerchSystem::register_template_handler('PipitSharing_Template');