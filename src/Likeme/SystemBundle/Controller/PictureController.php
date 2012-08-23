<?php

namespace Likeme\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PictureController extends Controller
{
    /**
     * @Route("/profile/pictures", name="pictures")
     * @Template()
     */
    public function showAction()
    {
    	$app_id = '100806270069332';
    	$app_secret = '98c29a3bd105813b3308e24a404d23f1';
    	$my_url = 'http://likeme.ch/likeme/web/app_dev.php/profile/pictures';
    	
    	//Get User Profile Pictures
    	
    	// Create our Application instance (replace this with your appId and secret).
		$facebook = new \Facebook(array(
  			'appId'  => $app_id,
  			'secret' => $app_secret
		));

		// If we requested a new access token from Facebbok we get a code
		if (isset($_REQUEST["code"])) {
			$code = $_REQUEST["code"];
			
			// If we get a code, it means that we have re-authed the user
			//and can get a valid access_token.
			if (isset($code)) {
				$token_url="https://graph.facebook.com/oauth/access_token?client_id="
				. $app_id . "&redirect_uri=" . urlencode($my_url)
				. "&client_secret=" . $app_secret
				. "&code=" . $code . "&display=popup";
				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);
				$access_token = $params['access_token'];
				$facebook->setAccessToken($access_token);
			}
		}
			
    	// See if there is a user from a cookie
        $user = $facebook->getUser();
        
       if ($user) {
        	try {
        		// Proceed knowing you have a logged in user who's authenticated.
        		$user_profile = $facebook->api('/me');
        	} catch (FacebookApiException $e) {
        		echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
        		$user = null;
        	}
        } else {
        	// Retrieving a valid access token.
        	$dialog_url= "http://likeme.ch/likeme/web/app_dev.php/login";
        	echo("<script> top.location.href='" . $dialog_url
        			. "'</script>");
        }
                
        //Get AccessToken
        $token = $facebook->getAccessToken();    
        
        //Receive a list with all albums from user 
        $extAlbumUrl = "https://graph.facebook.com/".$user_profile['id']."/albums?fields=id,name,type&access_token=".$token."&limit=0";
        
        // Get Content also if there is a HTTP error code (ex. 400)
        
        //Set stream options
        $opts = array	(
        		'http' => array('ignore_errors' => true)
        );
        //Create the stream context
        $context = stream_context_create($opts);
        
        //Open the file using the defined context
        $content = file_get_contents($extAlbumUrl, false, $context);


        // Check if link correct
        if ($content) {
        	// Save content in array
        	$response = json_decode($content, true);
        	
        	// Check for errors
        	if (isset($response['error'])) {
        		if ($response['error']['type'] == "OAuthException") {
        			// Retrieving a valid access token.
        			$dialog_url= "https://www.facebook.com/dialog/oauth?"
        			. "client_id=". $app_id
        			. "&redirect_uri=" . urlencode($my_url);
        			echo("<script> top.location.href='" . $dialog_url
        					. "'</script>");
        		} else {
        			echo "other error has happened";
        		}
        	} else {
        		// Loop trough albums and receive photos
        		$albums = $response['data'];
        		
        		foreach ($albums as $row)
        		{
        			// Only if Album is ProfilePictures
        			if ($row['type'] == 'profile')
        			{
        				// Print out the pictures from album
        				$contents = file_get_contents("https://graph.facebook.com/" . $row['id'] . "/photos?access_token=".$token."&limit=1");
        				$photos = json_decode($contents,true);
        				$photos = $photos['data'];
        			}
        		}
        	}
        } else {
        	echo "other error has happened";
        }  
    	    	
        return array('name' => $user_profile['name'], 'photos' => $photos);
       
    }
    
}