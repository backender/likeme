<?php

namespace Likeme\SystemBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MatchedController extends Controller {

	/**
	 * @Secure(roles="ROLE_USER")
	 * @Route("/matched", name="matched")
	 * @Template()
	 */
	public function showAction() {
		$user = $this->container->get('security.context')->getToken()->getUser();
		$em = $this->container->get('doctrine')->getEntityManager();
    	$UserService = $this->container->get('likeme.user.userservice');
    	
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		// Get current User object
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	// Get user matches
    	$userService = $this->container->get('likeme.user.userservice');
    	$userMatches = $userService->getMatches($user);
		
		return array('userMatches' => $userMatches);
    	
	}


}
