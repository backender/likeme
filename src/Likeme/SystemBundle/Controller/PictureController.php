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
    
    	//Get User Profile Pictures
    	
    	// Create our Application instance (replace this with your appId and secret).
		$facebook = new \Facebook(array(
  			'appId'  => '100806270069332',
  			'secret' => '98c29a3bd105813b3308e24a404d23f1',
		));

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
        }

        //Get AccessToken
        $token = $facebook->getAccessToken();     
        
        //Receive all albums from user and list with Pictures
     	$extAlbumUrl = "https://graph.facebook.com/".$user_profile['id']."/albums?fields=id,name,type&access_token=".$token."&limit=0";
    	$albums = json_decode(file_get_contents($extAlbumUrl),true);
    	$albums = $albums['data'];
    	
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
    	    	
        return array('name' => $user_profile['name'], 'photos' => $photos);
       
    }
    
}