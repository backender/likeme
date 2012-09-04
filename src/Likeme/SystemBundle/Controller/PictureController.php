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

class PictureController extends Controller
{
    /**
     * @Route("/profile/pictures", name="pictures", options={"expose"=true})
     * @Template()
     */
    public function showAction()
    {
    	// Get Users current pictures on Amazon
    	$user = $this->container->get('security.context')->getToken()->getUser();
     	$em = $this->container->get('doctrine')->getEntityManager();
    	$curUser = $em->getRepository('LikemeSystemBundle:User')->findOneByUsername($user);

    	// Get saved profile pictures
    	$query = $em->createQueryBuilder()
            ->from('Likeme\SystemBundle\Entity\Pictures', 'p')
            ->select("p")
            ->where("p.user = :userid AND p.type = :type")
            ->setParameter('userid', $curUser->getId())
            ->setParameter('type', 'original');
    	 
    	$savedpictures = $query->getQuery()->getResult();
    	
    	
    	//Get User Profile Pictures
    	    	 
    	$fosfbservice = $this->container->get('my.facebook.user');
    	
    	// Create our Application instance (replace this with your appId and secret).
    	$facebook = $fosfbservice->getFacebookObj();
     	$user = $fosfbservice->loadUserByUsername($user->getUsername());
    	
       if ($user) {
        	try {
        		// Proceed knowing you have a logged in user who's authenticated.
        		$user_profile = $facebook->api('/me');
        	} catch (\Exception $e) {
        		$facebooktoken = $this->container->get('likeme.facebook.updatetoken');
		        if (isset($_REQUEST['code'])) {
		        	$facebooktoken->update('http://likeme.ch/likeme/web/app_dev.php/profile/pictures', $_REQUEST['code']);
		        }
       			$facebooktoken->update('http://likeme.ch/likeme/web/app_dev.php/profile/pictures');
        	//	echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
        	//	$user = null;
        	}
        } else {
			$facebooktoken = $this->container->get('likeme.facebook.updatetoken');
		        if (isset($_REQUEST['code'])) {
		        	$facebooktoken->update('http://likeme.ch/likeme/web/app_dev.php/profile/pictures', $_REQUEST['code']);
		        }
       			$facebooktoken->update('http://likeme.ch/likeme/web/app_dev.php/profile/pictures');
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
        				$contents = file_get_contents("https://graph.facebook.com/" . $row['id'] . "/photos?access_token=".$token."&limit=8");
        				$photos = json_decode($contents,true);
        				$photos = $photos['data'];
        			}
        		}
        	}
        } else {
        	echo "other error has happened";
        }  
    	    	
        return array('name' => $user_profile['name'], 'id' => $user_profile['id'], 'savedpictures' => $savedpictures, 'photos' => $photos);
       
    }
    
    /**
     * @Route("/profile/savepictures", name="save_profile_pictures")
     * @Template()
     */   
    public function savePicturesAction()
    {
    	// Get selected pictures
    	$arr = $_POST["fcbklist_values"];
    	
    	// Get links of selected pictures
    	$arr = str_replace('"', '', $arr);
    	$arr = str_replace('{', '', $arr);
    	$arr = str_replace('}', '', $arr);
    	$arr = explode(',',$arr);
    	
    	// Amazone Filesystem erstellen
    	define("AWS_CERTIFICATE_AUTHORITY", true);
    	
    	$filesystem = $this->container->get('gaufrette.filesystem.media_cache');
    	
    	// Bilder in Amazon S3 speichern
    	if ( ! $filesystem->has($_POST["fcbk_id"]."/images/profile/")) {
     		$filesystem->write($_POST["fcbk_id"]."/images/profile/","");
     	}   	
     	
     	// Save imagepaths in database
     	$em = $this->get('doctrine')->getEntityManager();
     	
     	// Get current User object
     	$username = $this->container->get('security.context')->getToken()->getUser();
     	$curUser = $em->getRepository('LikemeSystemBundle:User')->findOneByUsername($username);
     	
     	// Get timestamp of last update in database
     	$query = $em->createQueryBuilder()
     	->from('Likeme\SystemBundle\Entity\Pictures', 'p')
     	->select("p")
     	->where("p.user = :userid AND p.type = :type")
     	->setParameter('userid', $curUser->getId())
     	->setParameter('type', 'original')
     	->setMaxResults(1);
     	
     	$savedpictures = $query->getQuery()->getResult();
     		    	
     	if ($savedpictures) {
     		$lastUpdate = $savedpictures[0]->getTimestamp();
        }
    	
     	$actDateTime = new \DateTime();
     	
    	// Output all picture links
    	foreach($arr as $key){
    		// Get Facebook ID
    		$userfcbkid = $_POST["fcbk_id"];
    		
    		// Get Filename with and without filetype
    		$link = explode(':',$key,2);
    		$filenamewithtype = substr(strrchr($link[1], "/"),1);
    		$filename = strstr($filenamewithtype, '.', true);
    		
    		// Check if pictrue already defined in database
    		$result = $em->getRepository('LikemeSystemBundle:Pictures')->findOneBySrc("http://likeme.s3.amazonaws.com/" . $userfcbkid."/images/profile/".$filenamewithtype);

    		// Save image if not already on amazon
    		if ( ! $filesystem->has($userfcbkid."/images/profile/".$filenamewithtype)) {
    			$content = file_get_contents($link[1]);
    			$filesystem->write($userfcbkid."/images/profile/".$filenamewithtype, $content);
    		}
    		
    		// Make a new picture object
    		$picture = new Pictures();
    		
    		if (!$result) {
    			// Save values in object
    			$picture->setSrc("http://likeme.s3.amazonaws.com/" . $userfcbkid."/images/profile/".$filenamewithtype);
    			$picture->setTimestamp($actDateTime);
    			$picture->setType('original');
    			$picture->setUser($curUser);
    		} else {
    			// If database entry already exists => Update timestamp
    			$picture = $result; 
    			$picture->setTimestamp($actDateTime);
    		}
    		
    		// Persist object
    		$em->persist($picture);    		
    	}

    	// Save persists in Database
    	$em->flush();
    	
    	// Delete all old database entries
    	if ($savedpictures) {
    		$result = $em->getRepository('LikemeSystemBundle:Pictures')->findByTimestamp($lastUpdate);
    		
    		foreach($result as $oldentry) {
    			$em->remove($oldentry);
    		}
    	}

    	
    	// Save persists in Database
    	$em->flush();
    	
    	
    	
    	// Leere Seite mit Bilder aufrufen damit LiipImagineBundle die thumbnails erstellt

    	// Get Pictures
    	$query = $em->createQueryBuilder()
    	->from('Likeme\SystemBundle\Entity\Pictures', 'p')
    	->select("p.src")
    	->where("p.user = :userid AND p.type = :type")
    	->setParameter('userid', $curUser->getId())
    	->setParameter('type', 'original');
    	
    	$allpictures = $query->getQuery()->getResult();
    	
    	// Edit picture links for LiipImagineBundle
    	$imagineservice = $this->container->get('likeme.liipimaginebundle.getlinks');
    	$imaginelinks = $imagineservice->editLinksForGeneration($allpictures);
    	
    	// Generate thumbnails on amazon s3 for each picture
    	foreach($imaginelinks as $link) {
    		echo $link['thumb'];
    		$imagemanagerResponse = $this->container
    		->get('liip_imagine.controller')
    		->filterAction($this->getRequest(), $link['thumb'], 'thumbnails');
    	}
    	
    	// Forward to Profileview
		$response = new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
		// ... further modify the response or return it directly
		return $response;
    }
    
    /**
     * @Route("/profile/crop", name="crop_pictures", options={"expose"=true})
     */
    public function cropAction()
    {
    	if (isset($_POST['url'])) {
    		$imagelink = $_POST['url'];
    		
    		try {
    			$targ_w = $targ_h = 150;
    			$jpeg_quality = 90;
    			
    			$img_r = imagecreatefromjpeg($imagelink);
    			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
    			
    			$newthumb = imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
    					$targ_w,$targ_h,$_POST['w'],$_POST['h']);
    			
    			//header('Content-type: image/jpeg');
    			
    			ob_start();
    			imagejpeg($dst_r, null, $jpeg_quality);
    			$newthumb = ob_get_contents();
    			ob_end_clean();
    			
    			
    			// Amazone Filesystem erstellen
    			define("AWS_CERTIFICATE_AUTHORITY", true);
    			
    			$filesystem = $this->container->get('gaufrette.filesystem.media_cache');
    			
    			$thumblink = str_replace("http://likeme.s3.amazonaws.com/","",$_POST['thumburl']);
    			
    			// Bild in Amazon S3 speichern
    			if ($filesystem->has($thumblink)) {
    				$filesystem->write($thumblink, $newthumb, true);
    			}
    			
    			// In Session speichern, dass kürzlich ein Bild gecroppt wurde => Browser darf nicht mehr auf gechachte Bilder zurückgreifen
    			$session = $this->container->get('session');
    			$session->set('cropped', true);
    			
    			// Bestätigung für erfolgreiches Updaten
    			$ret = 1;
    			
			} catch (Exception $e) {
				$ret = 'Fehler: ' .  $e->getMessage();
			}	
    			
    	} else {
    		$ret = 'Fehler';
    	}
    	return new Response($ret);
    }
}
