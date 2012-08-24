<?php

namespace Likeme\SystemBundle\Controller;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

use Likeme\SystemBundle\Form\Type\ProfileFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ProfileController extends Controller {
	
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

		//Build form anyway
		$form = $this->container->get('fos_user.profile.form');
		$formHandler = $this->container->get('fos_user.profile.form.handler');

		$process = $formHandler->process($user);
		if ($process) {
			$this->setFlash('fos_user_success', 'profile.flash.updated');

			return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
		}

		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('LikemeSystemBundle:User')->findOneByUsername($user);
		$profileForm = $this->createForm(new ProfileFormType(), $entity);

		return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'. $this->container->getParameter('fos_user.template.engine'),
				array('form' => $profileForm->createView(),	'theme' => $this->container->getParameter('fos_user.template.theme'), 'user' => $user, 'active' => self::isActive())
				);

	}
	
	public function editAction() {
		return array();
	}

}
