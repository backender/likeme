<?php

namespace Likeme\SystemBundle\Controller;

use Likeme\SystemBundle\Form\Type\NextFormType;

use Likeme\SystemBundle\Form\Type\LikeFormType;

use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Likeme\SystemBundle\Entity\Like;
use Likeme\SystemBundle\Entity\Next;

class StrangerController extends Controller
{   
    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/stranger/{strangerId}", name="stranger_view")
     * @Template()
     */
    public function showAction($strangerId)
    {
    	// Get EntityManager
    	$em = $this->get('doctrine')->getEntityManager();
    	
    	//Get Request
    	$request = $this->getRequest();
    	
    	// Form names
    	$unlikeFormName = "likeme_user_unlike";
    	
    	// Get current User object
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	// Get UserService
    	$userService = $this->container->get('likeme.user.userservice');
    	
    	// Get user matches
    	$userMatches = $userService->getMatches($user);
    	
		// Fetch stranger from GET
		$stranger = $this->getDoctrine()
			->getRepository('LikemeSystemBundle:User')
			->find($strangerId);
		
		
			// Get pictures from stranger
			$textExtension = $this->container->get('likeme.twig.extension');
			$strangerPictures = $textExtension->stranger_pictures($stranger);
			
			
	    	
	    	// Return view with form
	    	return array('stranger' => $stranger, 
	    				 'stranger_pictures' => $strangerPictures, 
	    				 //'unlikeForm' => $unlikeForm->createView(),
	    				 'userMatches' => $userMatches
	    				 );
		

    }

    
}
