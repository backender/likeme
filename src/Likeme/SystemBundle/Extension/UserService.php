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
	
	public function getRandomUser($user) {
		
		// Settings
		$AllowedDailyLikes = 15;
	
		
		// Get EntityManager
		$em = $this->container->get('doctrine')->getEntityManager();
		
		// Get location from user
		$location = $user->getLocation();

		// Höhen- und Breitengrade für location range ermitteln
		$latitude = $location->getLat();
		$longitude = $location->getLon();
			
		// Get prefered gender from user
		$prefGender = self::getPrefGender($user->getPrefGender());
		
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
	
	
	public function getUserInRadius($user) {
	
		// Settings
		$AllowedDailyLikes = 15;
		
		$radius = 5; 
		$limit = 10;
		$earth = 6371;
		
		// Get location from user
		$location = $user->getLocation();
		
		if(!empty($location)) {
			
			// Höhen- und Breitengrade für location range ermitteln
			$latitude = $location->getLat();
			$longitude = $location->getLon();
			
			$lat = $latitude * (pi()/180);
			$lng = $longitude * (pi()/180);
			
			
			
			// Get EntityManager
			$em = $this->container->get('doctrine')->getEntityManager();
			$config = $em->getConfiguration();
			
			// Add some functions to EntityManager
			$config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
			$config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');
			$config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
			
			$query = $em->createQuery('SELECT u, (acos(sin('.$latitude.'*'.pi().'/180)*sin(l.lat*'.pi().'/180)+cos('.$latitude.'*'.pi().'/180)*cos(l.lat*'.pi().'/180)*cos(('.$longitude.'-l.lon)*'.pi().'/180))) as distance FROM LikemeSystemBundle:User u JOIN u.location l ORDER BY distance ASC')
				->setMaxResults(15);
		
			$places = $query->getResult();
			
			echo 'User aus deiner N&auml;he: ';
			foreach($places as $place) {
				echo $place[0]->getFirstname() .' aus ';
				echo $place[0]->getLocation()->getPlacename() . ', ';
			}
			
		
			return (1);
		
		}
		
		return false;
	
	}
}
