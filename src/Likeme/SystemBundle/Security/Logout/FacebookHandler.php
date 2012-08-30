<?php
namespace Likeme\SystemBundle\Security\Logout;
//This is basically extending facebook handler - but dont need to implement this anymore
//use FOS\FacebookBundle\Security\Logout\FacebookHandler as BaseHandler;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

/**
 * Listener for the logout action
 *
 * This handler will clear the application's Facebook cookie.
 */
class FacebookHandler implements LogoutHandlerInterface, ContainerAwareInterface {

	private $container;

	private $facebook;

	public function __construct(\BaseFacebook $facebook) {
		$this->facebook = $facebook;
	}
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function setfbcheck() {
		//Set fbcheck back to 0 before logout, so profile will be checked next login for fbdata
		$user = $this->container->get('security.context')->getToken()->getUser();
		$em = $this->container->get('doctrine')->getEntityManager();
		$curUser = $em->getRepository('LikemeSystemBundle:User')->findOneById($user);
		$curUser->setFbcheck(1);
		
	}

	public function logout(Request $request, Response $response, TokenInterface $token) {
		self::setfbcheck();
		
		$response->headers->clearCookie('fbsr_' . $this->facebook->getAppId());
	}

}
