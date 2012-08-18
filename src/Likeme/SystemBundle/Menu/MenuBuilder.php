<?php
namespace Likeme\SystemBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MenuBuilder {
	private $factory;
	
	/**
	 * @param FactoryInterface $factory
	 */
	public function __construct(FactoryInterface $factory) {
		$this->factory = $factory;
	}

	public function createMainMenu(Request $request) {
		$menu = $this->factory->createItem('root');

		$menu->addChild('Home', array('route' => 'home'));
		$menu->addChild('Profil', array('route' => 'fos_user_profile_show'));
		$menu->addChild('Registrieren', array('route' => 'fos_user_registration_register'));

			$menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
			$menu->addChild('Login', array('route' => 'fos_user_security_login'));

		return $menu;
	}

}
