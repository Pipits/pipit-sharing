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
			

			$googleplus_url = $Helper->generate_googleplus_url($url, $vars);
			$facebook_url = $Helper->generate_facebook_url($url, $vars);
			$twitter_url = $Helper->generate_twitter_url($url, $vars);

			$email_url = $Helper->generate_email_url($url, $vars);
			$whatsapp_url = $Helper->generate_whatsapp_url($url, $vars);
			$reddit_url = $Helper->generate_reddit_url($url, $vars);

			$linkedin_url = $Helper->generate_linkedin_url($url, $vars);
			$tumblr_url = $Helper->generate_tumblr_url($url, $vars);
			$pinterest_url = $Helper->generate_pinterest_url($url, $vars);
			

			$sharing_links = [
				'facebook' => $facebook_url,
				'twitter' => $twitter_url,
				'googleplus' => $googleplus_url,
				'reddit' => $reddit_url,
				'linkedin' => $linkedin_url,
				'pinterest' => $pinterest_url,
				'tumblr' => $tumblr_url,
				'email' => $email_url,
				'whatsapp' => $whatsapp_url,
			];
			
			$html = $Template->replace_content_tags('sharing', $sharing_links, $html);
		}
		
        return $html;
    }

    public function render_runtime($html, $Template)
    {
        return $html;
    }
}