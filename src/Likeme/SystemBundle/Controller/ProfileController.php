<?php

namespace Likeme\SystemBundle\Controller;
use FOS\UserBundle\Controller\ProfileController as BaseController;

use Likeme\SystemBundle\Form\Type\ProfileFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ProfileController extends Controller {
	
	
	public function __construct() {
		$this->fos = new BaseController();
	}
	
	public function isActive() {
		$user = $this->container->get('security.context')->getToken()->getUser();
		$active = $user->getactive();

		return $active;
	}

	/**
	 * @Route("/profile", name="profile")
	 * @Template()
	 */
	public function showAction() {
		$user = $this->container->get('security.context')->getToken()->getUser();

		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		$em = $this->container->get('doctrine')->getEntityManager();
		$curUser = $em->getRepository('LikemeSystemBundle:User')->findOneByUsername($user);
		
		$form = $this->container->get('form.factory')->create(new ProfileFormType(), $curUser);
	
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
		$imaginelinks = $imagineservice->editLinks($allpictures);
		
		//FOS Form Handler
		/*$formHandler = $this->container->get('fos_user.profile.form.handler');
		
		$process = $formHandler->process($user);
		if ($process) {
			$this->setFlash('fos_user_success', 'profile.flash.updated');
		
			return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
		}*/
		
		//Embedded Form Handler
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			$validator = $this->get('validator');
			$errors = $validator->validate($user);
			
			if ($form->isValid()) {
				$em->persist($user);
				$em->flush();
								
				return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
			}			
		}
		
		return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'. $this->container->getParameter('fos_user.template.engine'),
				array('form' => $form->createView(), 'theme' => $this->container->getParameter('fos_user.template.theme'), 'user' => $user, 'active' => self::isActive(), 'pictures' => $imaginelinks)
		);

	}


}
