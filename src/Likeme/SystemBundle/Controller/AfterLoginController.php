<?php

namespace Likeme\SystemBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Likeme\SystemBundle\Extension\GetLinksForImagine;

use Likeme\SystemBundle\Entity\Pictures;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Gaufrette\Filesystem;

class AfterLoginController extends Controller
{
    
    /**
     * @Route("/afterlogin", name="after_login", options={"expose"=true})
     */
    public function afterloginAction()
    {
    	$em = $this->get('doctrine')->getEntityManager();
    	
    	// Get Users current pictures on Amazon
    	$user = $this->container->get('security.context')->getToken()->getUser();

    	
    	// Check if user has already an image
    	$query = $em->createQueryBuilder()
    	->from('Likeme\SystemBundle\Entity\Pictures', 'p')
    	->select("p")
    	->where("p.user = :userid AND p.type = :type")
    	->setParameter('userid', $user->getId())
    	->setParameter('type', 'original')
    	->setMaxResults(1);
    	
    	$savedpictures = $query->getQuery()->getResult();
    	
    	// If not likeme automaticly takes one profile picture form facebook
    	if (!$savedpictures) {
    		
    		// Get User Profile Pictures
 
    		// Get Facebook Service
    		$fosfbservice = $this->container->get('my.facebook.user');
    		 
    		// Create our Application instance (replace this with your appId and secret).
    		$facebook = $fosfbservice->getFacebookObj();
    		$user = $fosfbservice->loadUserByUsername($user->getUsername());
    		 
    		if ($user) {
    			try {
    				// Proceed knowing you have a logged in user who's authenticated.
    				$user_profile = $facebook->api('/me');
    			} catch (\Exception $e) {

    			}
    		} else {

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
    		
    		$actDateTime = new \DateTime();
    		
    		// Check if link correct
    		if ($content) {
    			// Save content in array
    			$response = json_decode($content, true);
    			
    			// Check for errors
    			if (isset($response['error'])) {
    				if ($response['error']['type'] == "OAuthException") {
    		
    				} else {
    					echo "other error has happened";
    				}
    			} else {
    				// Loop trough albums and receive photos
    				$albums = $response['data'];
    		
    				$foundonephoto = false;
    				
    				foreach ($albums as $row)
    				{
    					// Only if Album is ProfilePictures
    					if ($row['type'] == 'profile')
    					{

    						// Print out the pictures from album
    						$contents = file_get_contents("https://graph.facebook.com/" . $row['id'] . "/photos?access_token=".$token."&limit=1");
    						
    						$photos = json_decode($contents,true);
    						
    						// Saving profile photo
    						$photo = $photos['data'];
    							
    						if ($photo) {	
    							
    							// Min. 1 profile picture was found
    							$foundonephoto = true;
    							
    							// Amazone Filesystem erstellen
    							define("AWS_CERTIFICATE_AUTHORITY", true);
    							$filesystem = $this->container->get('gaufrette.filesystem.media_cache');
    								
    							// Get Facebook ID
    							$userfcbkid = $user_profile['id'];
    							
    							// Bilderordner in Amazon S3 erstellen falls er noch nicht existiert
    							if ( ! $filesystem->has($userfcbkid."/images/profile/")) {
    								$filesystem->write($userfcbkid."/images/profile/","");
    							}
    							
    							
    							// Get Filename with and without filetype
    							$link = $photo[0]['source'];
    							$filenamewithtype = substr(strrchr($link, "/"),1);
    							$filename = strstr($filenamewithtype, '.', true);
    							
    							// Save image if not already on amazon
    							if ( ! $filesystem->has($userfcbkid."/images/profile/".$filenamewithtype)) {
    								$content = file_get_contents($link);
    								$filesystem->write($userfcbkid."/images/profile/".$filenamewithtype, $content);
    							}
    							
    							// Make a new picture object
    							$picture = new Pictures();
    							
    							// Save values in object
    							$picture->setSrc("http://likeme.s3.amazonaws.com/" . $userfcbkid."/images/profile/".$filenamewithtype);
    							$picture->setTimestamp($actDateTime);
    							$picture->setType('original');
    							$picture->setUser($user);
    							$picture->setPosition(1);
    							
    							// Persist object
    							$em->persist($picture);
    							
    							// Save persists in Database
    							$em->flush();
    							
    								
    							// Get Pictures
    							$query = $em->createQueryBuilder()
    							->from('Likeme\SystemBundle\Entity\Pictures', 'p')
    							->select("p.src")
    							->where("p.user = :userid AND p.type = :type")
    							->setParameter('userid', $user->getId())
    							->setParameter('type', 'original');
    							
    							$allpictures = $query->getQuery()->getResult();
    							
    							// Edit picture links for LiipImagineBundle
    							$imagineservice = $this->container->get('likeme.liipimaginebundle.getlinks');
    							$imaginelinks = $imagineservice->editLinksForGeneration($allpictures);
    								
    							
    							// Generate thumbnails on amazon s3 for each picture
    							foreach($imaginelinks as $link) {
    								$this->container
    								->get('liip_imagine.controller')
    								->filterAction($this->getRequest(), $link['thumb'], 'thumbnails');
    							}				
    						}
    					}
    				}
    				
    				if ($foundonephoto == false) {
    					//No profile photo has been found => deactivate User
    					$user->setActive(false);
    					
    					// Persist object
    					$em->persist($user);
    						
    					// Save persists in Database
    					$em->flush();
    				}
    			}
    		} else {
    			echo "other error has happened";
    		}
    		
    		$funcresponse = new Response('Fehler');
    	}
    	
    	
    	
    	/**
    	 * Save daily stranger array in session
    	 */

    	// UserService abrufen
    	$UserService = $this->container->get('likeme.user.userservice');
    	$strangers = $UserService->getStranger();
    	
    	//update session
    	if($UserService->checkStrangerSessionEmpty($strangers) == false){
    		$UserService->setStrangers($strangers);
    	}
    	
    	// Forward to Home
    	$funcresponse = new RedirectResponse($this->container->get('router')->generate('home'));

    	return $funcresponse;
    }    
    
}
