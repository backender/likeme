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
    	
    	// UserService abrufen  	 
    	$UserService = $this->container->get('likeme.user.userservice');
    	
    	// Strange statement 1
    	$strangers = $UserService->getStranger();
    	echo "<br />";
    	foreach($strangers as $stranger) {
    		foreach ($stranger as $strangernew) {
    			
    			try {
    				echo $strangernew->getFirstname().", ";
    			}
    			catch (Exception $e)
    			{
    			//	throw new Exception( 'Something really gone wrong', 0, $e);
    			}
    		}
    		
    	}
    	 
    	// Get random user for current user ($curUser)
    	//$rndUser = $UserService->getUserInRadius($user);
    	
    	return array();
    }
    
}
