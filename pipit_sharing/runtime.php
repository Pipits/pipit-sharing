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



	function pipit_sharing_link($type, $opts = array(), $return = false) {
		$Helper = new PipitSharing_Helper;
		$page_url = false;
		
		if(isset($opts['page-url'])) {
			$page_url = rawurlencode($opts['page-url']);
			unset($opts['page-url']);
		}

		$prefixed_opts = array();
		foreach($opts as $key => $val) {
			$prefixed_opts["sharing_{$type}_{$key}"] = $val;
		}

		$link = $Helper->generate_url_for($type, $page_url, $prefixed_opts);
		if($return) return $link;
		echo $link;
	}