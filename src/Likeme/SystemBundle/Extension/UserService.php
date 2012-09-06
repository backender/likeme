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
	
	public function setUpExtentedEM()
	{
		$config = new \Doctrine\ORM\Configuration();
		$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
		$config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
		$config->setProxyDir($GLOBALS['doctrine2-proxies-path']);
		$config->setProxyNamespace($GLOBALS['doctrine2-proxies-namespace']);
		$config->setAutoGenerateProxyClasses(true);
	
		$driver = $config->newDefaultAnnotationDriver($GLOBALS['doctrine2-entities-path']);
		$config->setMetadataDriverImpl($driver);
	
		$conn = array(
				'driver' => 'pdo_sqlite',
				'memory' => true,
		);
	
		$config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
		$config->addCustomNumericFunction('ASIN', 'DoctrineExtensions\Query\Mysql\Asin');
		$config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');
		$config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
		$config->addCustomNumericFunction('COT', 'DoctrineExtensions\Query\Mysql\Cot');
		$config->addCustomNumericFunction('TAN', 'DoctrineExtensions\Query\Mysql\Tan');
		$config->addCustomNumericFunction('ATAN', 'DoctrineExtensions\Query\Mysql\Atan');
		$config->addCustomNumericFunction('ATAN2', 'DoctrineExtensions\Query\Mysql\Atan2');
	
		$config->addCustomNumericFunction('DEGREES', 'DoctrineExtensions\Query\Mysql\Degrees');
		$config->addCustomNumericFunction('RADIANS', 'DoctrineExtensions\Query\Mysql\Radians');
	
		$em = \Doctrine\ORM\EntityManager::create($conn, $config);
		
		return $em;
	}
	
	public function getUserInRadius($user) {
	
		// Settings
		$AllowedDailyLikes = 15;
		
		$radius = 5; 
		$limit = 10;
		$earth = 6371;
		
		// Get location from user
		$location = $user->getLocation();
		
		// Höhen- und Breitengrade für location range ermitteln
		$latitude = $location->getLat();
		$longitude = $location->getLon();
		
		$lat = $latitude * (pi()/180);
		$lng = $longitude * (pi()/180);
		
		
		
		// Get EntityManager
		$em = $this->container->get('doctrine')->getEntityManager();
		$config = $em->getConfiguration();
		
		$config->addCustomNumericFunction('DEGREES', 'DoctrineExtensions\Query\Mysql\Degrees');
		$config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
		$config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
		$config->addCustomNumericFunction('ASIN', 'DoctrineExtensions\Query\Mysql\Asin');
		$config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');
		$config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
		
		$query = $em->createQuery('SELECT l, (acos(sin('.$latitude.'*'.pi().'/180)*sin(l.lat*'.pi().'/180)+cos('.$latitude.'*'.pi().'/180)*cos(l.lat*'.pi().'/180)*cos(('.$longitude.'-l.lon)*'.pi().'/180))) as distance FROM LikemeSystemBundle:Location l ORDER BY distance ASC')
			->setMaxResults(15);
	
		$places = $query->getResult();
		
		foreach($places as $place) {
			echo $place[0]->getPlacename();
		}
		
	
		return (1);
	
	}
}
