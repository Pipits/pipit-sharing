<?php
	if ($CurrentUser->logged_in() && $CurrentUser->has_priv('pipit_sharing')) {
		$this->register_app('pipit_sharing', 'Pipit Sharing', 99, 'Social Sharing App', '1.2.0', true);
		$this->require_version('pipit_sharing', '3.0');
		
		$opts = array();
		$opts[] = array('label'=>'Settings', 'value'=>'1');
		$opts[] = array('label'=>'$_SERVER[\'HTTP_HOST\']', 'value'=>'2');
		$opts[] = array('label'=>'Perch config (recommended)', 'value'=>'3');
		$this->add_setting('pipit_sharing_domain', 'Get website URL from:', 'select', '3', $opts);
		
		include(PERCH_PATH.'/addons/apps/pipit_sharing/lib/vendor/autoload.php');
		spl_autoload_register(function($class_name){
			if (strpos($class_name, 'PipitSharing_')===0) {
				include(PERCH_PATH.'/addons/apps/pipit_sharing/lib/'.$class_name.'.class.php');
				return true;
			}
			return false;
		});
		
		PerchSystem::register_template_handler('PipitSharing_Template');

		$API  = new PerchAPI(1.0, 'pipit_sharing');
		$Settings = $API->get('Settings');
	}
	