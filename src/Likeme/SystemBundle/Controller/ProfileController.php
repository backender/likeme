<?php

namespace Likeme\SystemBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
//use Likeme\SystemBundle\Form\Type\PreferenceFormType;
//use FOS\UserBundle\Controller\ProfileController as BaseController;
use Likeme\SystemBundle\Form\Type\ProfileFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProfileController extends Controller {

	/**
	 * @Route("/profile", name="profile")
	 * @Template()
	 */
	public function showAction() {
		$user = $this->container->get('security.context')->getToken()->getUser();
		$em = $this->container->get('doctrine')->getEntityManager();
    	$UserService = $this->container->get('likeme.user.userservice');
    	
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		//Check if user is active
		$user->setFBData();
		
		// Get current users location
		if($user->getLocation() !== NULL) {
			$userLocation = $user->getLocation();
		} else {
			$userLocation = false;
		}
		// Build form
		//$form = $this->container->get('form.factory')->create(new ProfileFormType(), $user, );
		$form = $this->createForm(new ProfileFormType(array('em' => $em,)), $user);
		
		//Embedded Form Handler
		$request = $this->getRequest();
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			$validator = $this->get('validator');
			$errors = $validator->validate($user);
			
			if ($form->isValid()) {
				
				$em->persist($user);
				$em->flush();
				
				//Update Stranger array using new criteria
				$strangers = $UserService->getStranger();
				if($UserService->checkStrangerSessionEmpty($strangers) == false){
					$UserService->setStrangers($strangers);
				}
				
				//Check if user is active
				$user->setFBData();
				
				
				//ajax form success
				if ($this->container->get('request')->isXmlHttpRequest())
				{
					$return = 1;
					return new Response($return);
				}
				
				return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
			}			
		}
		
		//ajax form failed
		if ($this->container->get('request')->isXmlHttpRequest())
		{
			$return = $errors;
			return new Response($return);
		}
		
		
		return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'. $this->container->getParameter('fos_user.template.engine'),
				array('form' => $form->createView(), 'theme' => $this->container->getParameter('fos_user.template.theme'), 'location' => $userLocation)
		);

	}


}
