<?php

class PipitSharing_Helper {

    /**
     * Get site URL from settings, config or current page
     */
    function get_site_url() {
        $API  = new PerchAPI(1.0, 'pipit_sharing');
        $Settings = $API->get('Settings');

        if(PERCH_SSL) {
            $protocol = 'https://';
        } else {
            $protocol = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://';
        }
        
        if($Settings->get('pipit_sharing_domain')->val() && $Settings->get('pipit_sharing_domain')->val() == '2') {
            $site_url = $protocol . $_SERVER['HTTP_HOST'];
        } else {
            $site_url = $Settings->get('siteURL')->val();
            if(substr($site_url, 0, 4) !== "http") {
                $site_url = $protocol . $site_url;
            }
        }


        return $site_url;
    }





    /**
     * Get encoded page url
     */
    function get_url($page_url = false) {
        $site_url = $this->get_site_url();

        if($page_url) {
            return rawurlencode("{$site_url}{$page_url}");
        } else {
            return rawurlencode("{$site_url}{$_SERVER['REQUEST_URI']}");
        }
    }



    /**
     * Google Plus sharing URL. Only takes a URL as a parameter
     */
    function generate_googleplus_url($page_url, $opts = []) {
        return 'https://plus.google.com/share?url=' . $page_url;
    }





    /**
     * Facebook sharing URL. Only takes a URL as a parameter
     */
    function generate_facebook_url($page_url, $opts = []) {
        return 'https://www.facebook.com/sharer.php?u=' . $page_url;
    }





    /**
     * Reddit sharing links
     */
    function generate_reddit_url($page_url, $opts = []) {
        $args = '';

        if(isset($opts['sharing_reddit_title'])) {
            $args .= '&title='.rawurlencode($opts['sharing_reddit_title']);
        }

        return 'https://reddit.com/submit/?url='.$page_url.$args;
    }





    /**
     * LinkedIn sharing links
     */
    function generate_linkedin_url($page_url, $opts = []) {
        $args = '';

        if(isset($opts['sharing_linkedin_title'])) {
            /* max length 200 */
            $args .= '&title='.rawurlencode($opts['sharing_linkedin_title']);
        } 
        
        if(isset($opts['sharing_linkedin_desc'])) {
            if(is_array($opts['sharing_linkedin_desc']) && isset($opts['sharing_linkedin_desc']['raw'])) {
                $linkedin_desc = $opts['sharing_linkedin_desc']['raw'];
            } else {
                $linkedin_desc = $opts['sharing_linkedin_desc'];
            }
            $args .= '&summary='.rawurlencode($linkedin_desc);
        }


        if(isset($opts['sharing_linkedin_source'])) {
            /* max length 200 */
            $args .= '&source='.rawurlencode($opts['sharing_linkedin_source']);
        }


        return 'https://www.linkedin.com/shareArticle?mini=true&url='.$page_url.$args;
    }





    /**
     * Pinterest sharing URL
     */
    function generate_pinterest_url($page_url, $opts = []) {
        $args = '';

        if(isset($opts['sharing_pinterest_desc'])) {
            if(is_array($opts['sharing_pinterest_desc']) && isset($opts['sharing_pinterest_desc']['raw'])) {
                $pin_desc = $opts['sharing_pinterest_desc']['raw'];
            } else {
                $pin_desc = $opts['sharing_pinterest_desc'];
            }

            $args .= '&description='.rawurlencode($pin_desc);
        }


        if(isset($opts['sharing_pinterest_media'])) {
            
            if(is_array($opts['sharing_pinterest_media']) && isset($opts['sharing_pinterest_media']['_default'])) {
                $pin_media =  $site_url . $opts['sharing_pinterest_media']['_default'];
            } else {
                $pin_media = $opts['sharing_pinterest_media'];
            }

            $args .= '&media='.rawurlencode($pin_media);
        }
            

        if(isset($opts['sharing_pinterest_video'])) {
            $args .= '&is_video='.$opts['sharing_pinterest_video'];
        }

        $pinterest_url = 'https://pinterest.com/pin/create/button/?url='.$page_url.$args;
        
        return $pinterest_url;
    }





    /**
     * Twitter sharing URL.
     * Can take: url, tags, via (author), description
     */
    function generate_twitter_url($page_url, $opts = []) {
        $args = '';
        $twitter_desc_length = $twitter_via_length = $twitter_tags_length = 0;

        if(isset($opts['sharing_twitter_tags'])) {
            $pattern = '/[#| ]/';
            $twitter_tags = preg_replace($pattern, "", $opts['sharing_twitter_tags']);
            $twitter_stripped_tags = explode(",", $twitter_tags);
            
            $twitter_tags_length = count($twitter_stripped_tags) * 2; //space and # before each tag
            
            foreach($twitter_stripped_tags as $tag) {
                $twitter_tags_length += strlen($tag);
            }
            
            $args .= '&hashtags='.$twitter_tags;
        }
        

        if(isset($opts['sharing_twitter_via'])) {
            $pattern = '/[@| ]/';
            $twitter_via = preg_replace($pattern,"",$opts['sharing_twitter_via']);
            $twitter_via_length = strlen($twitter_via) + 5; //+3 space+@+space+twitter_via

            $args .= '&via='.$twitter_via;
        }
        


        if(isset($opts['sharing_twitter_desc'])) {
            if(is_array($opts['sharing_twitter_desc']) && isset($opts['sharing_twitter_desc']['raw'])) {
                $twitter_desc = $opts['sharing_twitter_desc']['raw'];
            } else {
                $twitter_desc = $opts['sharing_twitter_desc'];
            }
            $twitter_desc_length = strlen($twitter_desc);
            $args .= '&text='.rawurlencode($twitter_desc);
        }
        

        // URL of any length will be altered to 23 characters
        $tweet_length = 23 + $twitter_desc_length + $twitter_via_length + $twitter_tags_length;
        if($tweet_length > 230) {
            $twitter_url = 'https://twitter.com/intent/tweet?url='.$page_url;	
        } else {
            $twitter_url = 'https://twitter.com/intent/tweet?url='.$page_url.$args;
        }
        


        return $twitter_url;
    }






    
    /**
     * Tumblr sharing URL
     */
    function generate_tumblr_url($page_url, $opts) {
        $args = '';

        if(isset($opts['sharing_tumblr_posttype'])) {
            //  text, photo, link, quote, chat, or video
            $args .= '&posttype='.$opts['sharing_tumblr_posttype'];
        }
        
        if(isset($opts['sharing_tumblr_title'])) {
            $args .= '&title='.rawurlencode($opts['sharing_tumblr_title']);
        }


        if(isset($opts['sharing_tumblr_tags'])) {
            $pattern = '/[#| ]/';
            $args .= '&hashtags='.preg_replace($pattern,"",$opts['sharing_tumblr_tags']);
        }


        if(isset($opts['sharing_tumblr_desc'])) {

            if(is_array($opts['sharing_tumblr_desc']) && isset($opts['sharing_tumblr_desc']['raw'])) {
                $tumblr_caption = $opts['sharing_tumblr_desc']['raw'];
            } else {
                $tumblr_caption = $opts['sharing_tumblr_desc'];
            }

            $args .= '&caption='.rawurlencode($tumblr_caption);
        }


        $tumblr_url = 'https://tumblr.com/widgets/share/tool?canonicalUrl='.$page_url.$args;

        return $tumblr_url;
    }







    /**
     * Email mailto URL
     */
    function generate_email_url($page_url, $opts = []) {
        $email_body = $args = '';

        if(isset($opts['sharing_email_subject'])) {
            if($args != '') { $args .= '&'; }
            $args .= 'subject='.rawurlencode($opts['sharing_email_subject']);
        }


        if(isset($opts['sharing_email_desc'])) {
            if($args != '') { $args .= '&'; }
            if(is_array($opts['sharing_email_desc']) && isset($opts['sharing_email_desc']['raw']))
            {
                $email_body = $opts['sharing_email_desc']['raw'];
            }
            else
            {
                $email_body = $opts['sharing_email_desc'];
            }
        }
            $args .= '&body='.rawurlencode($email_body);
            $args .= '%0D%0A%0D%0A'.$page_url;
        $email_url = 'mailto:?'.$args;


        return $email_url;
    }






    

    /**
     * Whatsapp sharing URL
     */
    function generate_whatsapp_url($page_url, $opts = []) {
        $whatsapp_phone = '';
        $whatsapp_text = '';
        if(isset($opts['sharing_whatsapp_desc']))
        {
            if(is_array($opts['sharing_whatsapp_desc']) && isset($opts['sharing_whatsapp_desc']['raw']))
            {
                $whatsapp_text .= $opts['sharing_whatsapp_desc']['raw'];
            }
            else
            {
                $whatsapp_text = $opts['sharing_whatsapp_desc'];
            }
            $whatsapp_text = rawurlencode($whatsapp_text);
        }

        
        if(isset($opts['sharing_whatsapp_phone']))
        {
            $whatsapp_phone = $opts['sharing_whatsapp_phone'];
        }

        $whatsapp_url = "https://wa.me/$whatsapp_phone?text=".$whatsapp_text.'%0D%0A%0D%0A'.$page_url;

        return $whatsapp_url;
    }


}