<?php
namespace Likeme\SystemBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class PreferenceService implements ContainerAwareInterface
{
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function getPrefGender($pref_gender) {
		// Get prefered gender from user
		switch ($pref_gender) {
			case 0:
				$gender = 'both';
				break;
			case 1:
				$gender = 'male';
				break;
			case 2:
				$gender = 'female';
				break;
		}
		
		return $gender;
	}
}
