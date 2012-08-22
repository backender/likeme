<?php

namespace Likeme\SystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

require_once '../vendor/facebook/src/facebook.php';

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
        
        return array('name' => $user_profile['albums']);
       
    }
    
}