<?php
namespace Likeme\SystemBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class UserService implements ContainerAwareInterface
{
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function getRandomUser($user) {
		
		// Settings
		$AllowedDailyLikes = 15;
	
		
		
		// Get EntityManager
		$em = $this->container->get('doctrine')->getEntityManager();
		
		// Get Location Repository
		$locrepository = $this->container->get('doctrine')->getRepository('LikemeSystemBundle:Location');
		
		// Get location from user
		$location = $user->getLocation();
		
		// Höhen- und Breitengrade für location range ermitteln
		$latitude = $location->getLat();
		$longitude = $location->getLon();
		
		// Get prefered gender from user
		switch ($user->getPrefGender()) {
			case 0:
				$prefGender = 'both';
				break;
			case 1:
				$prefGender = 'male';
				break;
			case 2:
				$prefGender = 'female';
				break;
		}
		
		// Get prefered age range from
		$prefAgeRange = explode("-", $user->getPrefAgeRange());
		$prefMinAge = $prefAgeRange[0];
		$prefMaxAge = $prefAgeRange[1];
		
		$prefMaxBirthYear = date(date('Y') - $prefMinAge);
		$prefMinBirthYear = date(date('Y') - $prefMaxAge);
		
		// Get timestamp of last update in database
		$query = $em->createQueryBuilder()
		->select('u')
		->from('Likeme\SystemBundle\Entity\User', 'u')
		->leftJoin('u.location', 'ul')
		->where("u.id != :myid AND (u.birthday >= :minbirthyear OR u.birthday <= :maxbirthyear)")
		->setParameter('myid', $user->getID())
		->setParameter('minbirthyear', $prefMinBirthYear)
		->setParameter('maxbirthyear', $prefMaxBirthYear);
		
		if ($prefGender != 'both') {
			$query->andWhere("u.gender = :gender")
				->setParameter('gender', $prefGender);
		}
		
		$randPrefUser = $query->getQuery()->getResult();
		
		echo var_dump($randPrefUser[0]->getFirstname());
		
		return ($randPrefUser);
		
		//$savedpictures = $query->getQuery()->getResult();
		
		
	}
}
