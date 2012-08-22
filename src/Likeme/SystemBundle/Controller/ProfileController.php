<?php

namespace Likeme\SystemBundle\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\UserBundle\Controller\ProfileController as BaseController;

class ProfileController extends BaseController
{	
    /**
     * @Route("/", name="profile")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    
    /**
     * Show the user
     */
    public function showAction()
    {
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	if (!is_object($user) || !$user instanceof UserInterface) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    
    	return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'), array('user' => $user));
    }
}