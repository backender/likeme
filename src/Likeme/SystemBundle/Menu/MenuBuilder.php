<?php
namespace Likeme\SystemBundle\Menu;

use Symfony\Component\Security\Core\SecurityContextInterface;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder { 

	private $factory;
	
    protected $securityContext;
	
	/**
	 * @param FactoryInterface $factory
	 */
	public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext) {
		$this->factory = $factory;
		$this->securityContext = $securityContext;
	}

	public function createMainMenu(Request $request) {
		
		$menu = $this->factory->createItem('root');

		$menu->addChild('Home', array('route' => 'home'));
		$menu->addChild('Profil', array('route' => 'fos_user_profile_show'));

		if( $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY') ){
			$menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
		} else {
			$menu->addChild('Login', array('route' => 'fos_user_security_login'));
		}
		
		return $menu;
	}

}
