<?php

class PipitSharing_Template extends PerchAPI_TemplateHandler
{
    public $tag_mask = 'sharing';
	
    public function render($vars, $html, $Template)
    {
		if(strpos($html, 'perch:sharing') !== false)
		{

			$API  = new PerchAPI(1.0, 'pipit_sharing');
			$Settings = $API->get('Settings');

			if(PERCH_SSL)
			{
				$protocol = 'https://';
			}
			else
			{
				$protocol = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://';
			}
			
			
			if($Settings->get('pipit_sharing_domain')->val() && $Settings->get('pipit_sharing_domain')->val() == '2')
			{
				$site_url = $protocol . $_SERVER['HTTP_HOST'];
			}
			else
			{
				$site_url = $Settings->get('siteURL')->val();
				if(substr($site_url, 0, 4) !== "http")
				{
					$site_url = $protocol . $site_url;
				}
			}

			$url = rawurlencode("{$site_url}{$_SERVER['REQUEST_URI']}");
			$twitter_args = $pinterest_args = $linkedin_args = $tumblr_args = $reddit_args = '';
			


			/* only take URL */
			$googleplus_url = 'https://plus.google.com/share?url='.$url;
			$facebook_url = 'https://www.facebook.com/sharer.php?u='.$url;

			

			/* Twitter */
			$twitter_desc_length = $twitter_via_length = $twitter_tags_length = 0;
			if(isset($vars['sharing_twitter_tags']))
			{
				$pattern = '/[#| ]/';
				$twitter_tags = preg_replace($pattern,"",$vars['sharing_twitter_tags']);
				$twitter_stripped_tags = explode(",", $twitter_tags);
				
				$twitter_tags_length = count($twitter_stripped_tags) * 2; //space and # before each tag
				foreach($twitter_stripped_tags as $tag)
				{
					$twitter_tags_length += strlen($tag);
				}
				$twitter_args .= '&hashtags='.$twitter_tags;
			}

			if(isset($vars['sharing_twitter_via']))
			{
				$pattern = '/[@| ]/';
				$twitter_via = preg_replace($pattern,"",$vars['sharing_twitter_via']);
				$twitter_via_length = strlen($twitter_via) + 5; //+3 space+@+space+twitter_via

				$twitter_args .= '&via='.$twitter_via;
			}

			if(isset($vars['sharing_twitter_desc']))
			{
				if(is_array($vars['sharing_twitter_desc']) && isset($vars['sharing_twitter_desc']['raw']))
				{
					$twitter_desc = $vars['sharing_twitter_desc']['raw'];
				}
				else
				{
					$twitter_desc = $vars['sharing_twitter_desc'];
				}
				$twitter_desc_length = strlen($twitter_desc);
				$twitter_args .= '&text='.rawurlencode($twitter_desc);
			}

			// URL of any length will be altered to 23 characters
			$tweet_length = 23 + $twitter_desc_length + $twitter_via_length + $twitter_tags_length;
			if($tweet_length > 230)
			{
				$twitter_url = 'https://twitter.com/intent/tweet?url='.$url;	
			}
			else
			{
				$twitter_url = 'https://twitter.com/intent/tweet?url='.$url.$twitter_args;
			}
			


			
			/* Reddit */
			if(isset($vars['sharing_reddit_title']))
			{
				$reddit_args .= '&title='.rawurlencode($vars['sharing_reddit_title']);
			}
			$reddit_url = 'https://reddit.com/submit/?url='.$url.$reddit_args;




			/* Pinterest */
			if(isset($vars['sharing_pinterest_desc']))
			{
				if(is_array($vars['sharing_pinterest_desc']) && isset($vars['sharing_pinterest_desc']['raw']))
				{
					$pin_desc = $vars['sharing_pinterest_desc']['raw'];
				}
				else
				{
					$pin_desc = $vars['sharing_pinterest_desc'];
				}
				$pinterest_args .= '&description='.rawurlencode($pin_desc);
			}
			if(isset($vars['sharing_pinterest_media']))
			{
				if(is_array($vars['sharing_pinterest_media']) && isset($vars['sharing_pinterest_media']['_default']))
				{
					$pin_media =  $site_url . $vars['sharing_pinterest_media']['_default'];
				}
				else
				{
					$pin_media = $vars['sharing_pinterest_media'];
				}
				$pinterest_args .= '&media='.rawurlencode($pin_media);
			}
			if(isset($vars['sharing_pinterest_video']))
			{
				$pinterest_args .= '&is_video='.$vars['sharing_pinterest_video'];
			}
			$pinterest_url = 'https://pinterest.com/pin/create/button/?url='.$url.$pinterest_args;
			
			


			/* LinkedIn */
			if(isset($vars['sharing_linkedin_title']))
			{
				/* max length 200 */
				$linkedin_args .= '&title='.rawurlencode($vars['sharing_linkedin_title']);
			}
			if(isset($vars['sharing_linkedin_desc']))
			{
				if(is_array($vars['sharing_linkedin_desc']) && isset($vars['sharing_linkedin_desc']['raw']))
				{
					$linkedin_desc = $vars['sharing_linkedin_desc']['raw'];
				}
				else
				{
					$linkedin_desc = $vars['sharing_linkedin_desc'];
				}
				$linkedin_args .= '&summary='.rawurlencode($linkedin_desc);
			}
			if(isset($vars['sharing_linkedin_source']))
			{
				/* max length 200 */
				$linkedin_args .= '&source='.rawurlencode($vars['sharing_linkedin_source']);
			}
			$linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url='.$url.$linkedin_args;
			
			


			/* Tumblr */
			if(isset($vars['sharing_tumblr_posttype']))
			{
				//  text, photo, link, quote, chat, or video
				$tumblr_args .= '&posttype='.$vars['sharing_tumblr_posttype'];
			}
			if(isset($vars['sharing_tumblr_title']))
			{
				$tumblr_args .= '&title='.rawurlencode($vars['sharing_tumblr_title']);
			}
			if(isset($vars['sharing_tumblr_tags']))
			{
				$pattern = '/[#| ]/';
				$tumblr_args .= '&hashtags='.preg_replace($pattern,"",$vars['sharing_tumblr_tags']);
			}
			if(isset($vars['sharing_tumblr_desc']))
			{
				if(is_array($vars['sharing_tumblr_desc']) && isset($vars['sharing_tumblr_desc']['raw']))
				{
					$tumblr_caption = $vars['sharing_tumblr_desc']['raw'];
				}
				else
				{
					$tumblr_caption = $vars['sharing_tumblr_desc'];
				}
				$tumblr_args .= '&caption='.rawurlencode($tumblr_caption);
			}
			$tumblr_url = 'https://tumblr.com/widgets/share/tool?canonicalUrl='.$url.$tumblr_args;
			



			$sharing_links = [
				'facebook'=>$facebook_url,
				'twitter'=>$twitter_url,
				'googleplus'=>$googleplus_url,
				'reddit'=>$reddit_url,
				'linkedin'=>$linkedin_url,
				'pinterest'=>$pinterest_url,
				'tumblr'=>$tumblr_url,
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