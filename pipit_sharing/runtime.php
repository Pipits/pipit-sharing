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





	function pipit_sharing_facebook($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('facebook', $page_url, $opts);
		if($return) return $link;
		echo $link;
	}

	

	function pipit_sharing_googleplus($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('googleplus', $page_url, $opts, $return);
		if($return) return $link;
		echo $link;
	}



	function pipit_sharing_twitter($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('twitter', $page_url, $opts, $return);
		if($return) return $link;
		echo $link;
	}



	function pipit_sharing_tumblr($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('tumblr', $page_url, $opts, $return);
		if($return) return $link;
		echo $link;
	}



	function pipit_sharing_linkedin($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('linkedin', $page_url, $opts, $return);
		if($return) return $link;
		echo $link;
	}



	function pipit_sharing_whatsapp($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('whatsapp', $page_url, $opts, $return);
		if($return) return $link;
		echo $link;
	}



	function pipit_sharing_email($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('email', $page_url, $opts, $return);
		if($return) return $link;
		echo $link;
	}



	function pipit_sharing_reddit($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('reddit', $page_url, $opts, $return);
		if($return) return $link;
		echo $link;
	}



	function pipit_sharing_pinterest($opts = array(), $page_url = false, $return = false) {
		$Helper = new PipitSharing_Helper;
		$link = $Helper->generate_url_for('pinterest', $page_url, $opts, $return);
		if($return) return $link;
		echo $link;
	}