<?php

namespace Likeme\SystemBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		$response = new RedirectResponse($this->container->get('router')->generate('user_home'));
    		return $response;
    	}
    	
        return array();
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/home", name="user_home")
     * @Template()
     */
    public function showAction()
    {
    	// Get EntityManager
    	$em = $this->get('doctrine')->getEntityManager();
    	
    	// Get current User object
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	$session = $this->container->get('session');
    	$strangers = $session->get('strangers');
    	
    	var_dump($strangers);  	
    	
    	$stranger = $this->getDoctrine()
    	->getRepository('LikemeSystemBundle:User')
    	->find($strangers[0]); //TODO: set session array count

    	// Get pictures from stranger
    	$textExtension = $this->container->get('likeme.twig.extension');
    	$strangerPictures = $textExtension->stranger_pictures($stranger);
    	
    	
    	return array('stranger' => $stranger, 'stranger_pictures' => $strangerPictures);
    }
    
}
