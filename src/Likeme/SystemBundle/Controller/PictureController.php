<?php

namespace Likeme\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PictureController extends Controller
{
    /**
     * @Route("/pictures", name="pictures")
     * @Template()
     */
    public function showAction()
    {
    
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

	
        $token = $facebook->getAccessToken();     
          
     	$extAlbumUrl = "https://graph.facebook.com/".$user_profile['id']."/albums?fields=id,name,type&access_token=".$token."&limit=0";
       
    	$albums = json_decode(file_get_contents($extAlbumUrl),true);
    	$albums = $albums['data'];
    	
    	foreach ($albums as $row)
    	{
    		//if ($row['type'] == 'normal')
    		//{
    			echo '<a href="photos.php?albumid=' . $row['id'] . '">';
    			echo $row['name'];
    			echo '</a><br>';
    			
    			$contents = file_get_contents("https://graph.facebook.com/" . $row['id'] . "/photos?access_token=".$token."&limit=30");
    			$photos = json_decode($contents,true);
    			$photos = $photos['data'];
    			 
    			foreach ($photos as $row)
    			{
    				echo '<img src="' . $row['images'][1]['source'] . '" /><br>';
    			}
    		//}
    	}
    	
    	
    	var_dump($albums);

    	
        return array('name' => $user_profile['name']);
       
    }
    
}