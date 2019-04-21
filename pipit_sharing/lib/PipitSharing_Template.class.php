<?php

require_once('PipitSharing_Helper.class.php');

class PipitSharing_Template extends PerchAPI_TemplateHandler
{
    public $tag_mask = 'sharing';
	
    public function render($vars, $html, $Template)
    {
		if(strpos($html, 'perch:sharing') !== false)
		{

			$Helper = new PipitSharing_Helper;
			
			if(isset($vars['sharing_item_url'])) {
				$url = $Helper->get_url($vars['sharing_item_url']);
			} else {
				$url = $Helper->get_url();
			}
			
			
			$sharing_links = array();
			$tags = $Template->find_all_tags('sharing');
            
            // $Tag is a PerchXMLTag
            foreach($tags as $Tag) {
				$tag_attributes = $Tag->get_attributes();
				$opts = array();

				foreach($tag_attributes as $key => $val) {
					if($key == 'url') {
						$url = rawurlencode($val);
					} else {

						if(strpos($Tag->id(), '_cal') !== false) {
							$key_prefix = 'sharing_cal_';
						} else {
							$key_prefix = 'sharing_' .  $Tag->id() . '_';
						}

						if(substr($val, 0, 1) === '{') {
							// variable - {variable_id}
							$var_key = trim($val, '{}');
							if(isset($vars[$var_key])) {
								$opts[$key_prefix . $key] = $vars[$var_key];
							}
						} else {
							// static content
							$opts[$key_prefix . $key] = $val;
						}
					}
				}

				$opts = array_merge($vars, $opts);

				

				switch($Tag->id()) {
					case 'facebook':
						$sharing_links['facebook'] = $Helper->generate_facebook_url($url, $opts);
						break;
		
					case 'googleplus':
						$sharing_links['googleplus'] = $Helper->generate_googleplus_url($url, $opts);
						break;
		
					case 'reddit':
						$sharing_links['reddit'] = $Helper->generate_reddit_url($url, $opts);
						break;
		
					case 'twitter':
						$sharing_links['twitter'] = $Helper->generate_twitter_url($url, $opts);
						break;
		
					case 'tumblr':
						$sharing_links['tumblr'] = $Helper->generate_tumblr_url($url, $opts);
						break;
		
					case 'linkedin':
						$sharing_links['linkedin'] = $Helper->generate_linkedin_url($url, $opts);
						break;
		
					case 'pinterest':
						$sharing_links['pinterest'] = $Helper->generate_pinterest_url($url, $opts);
						break;
		
					case 'whatsapp':
						$sharing_links['whatsapp'] = $Helper->generate_whatsapp_url($url, $opts);
						break;
		
					case 'email':
						$sharing_links['email'] = $Helper->generate_email_url($url, $opts);
						break;
		
					case 'google_cal':
						$sharing_links['google_cal'] = $Helper->generate_calendar_url('google', $opts);
						break;

					case 'yahoo_cal':
						$sharing_links['yahoo_cal'] = $Helper->generate_calendar_url('yahoo', $opts);
						break;

					case 'weboutlook_cal':
						$sharing_links['weboutlook_cal'] = $Helper->generate_calendar_url('weboutlook', $opts);
						break;

					case 'ics_cal':
						$sharing_links['ics_cal'] = $Helper->generate_calendar_url('ics', $opts);
						break;

					default:
						break;
				}
				
            }
			
			$html = $Template->replace_content_tags('sharing', $sharing_links, $html);
		}
		
        return $html;
    }

    public function render_runtime($html, $Template)
    {
        return $html;
    }
}