<?php
namespace Likeme\SystemBundle\Security\Logout;
//This is basically extending facebook handler - but dont need to implement this anymore
//use FOS\FacebookBundle\Security\Logout\FacebookHandler as BaseHandler;

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
class FacebookHandler implements LogoutHandlerInterface {

	private $container;

	private $facebook;

	public function __construct(\BaseFacebook $facebook, ContainerInterface $container) {
		$this->facebook = $facebook;
		$this->container = $container;
	}
	
	public function closeVisited()
	{
		$session = $this->container->get('session');
		$visited = $session->get('visited', array());
		if(in_array('visited', $visited)) {
				
			$visited[] = '';
			$session->set('visited', $visited);
			return false;
				
		}
	}

	public function logout(Request $request, Response $response, TokenInterface $token) {
		self::closeVisited(); //close session var "visited"
		$response->headers->clearCookie('fbsr_' . $this->facebook->getAppId());
	}

}
