<?php
namespace Likeme\SystemBundle\Extension;

use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Tests\Common\Annotations\True;
use Doctrine\Tests\Common\Annotations\Null;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Doctrine\Tests\Common\Annotations\False;
use Likeme\SystemBundle\Entity\Like;
use Likeme\SystemBundle\Entity\Next;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class UserService implements ContainerAwareInterface
{
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function getMaxStranger()
	{
		$i=20;
		return $i;
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
	
	/**
	 * Gives the users prefered age range of strangers
	 *
	 * @param string $prefAgeRange
	 * @return array|Null
	 */
	public function getPrefAgeRangeDate($prefAgeRange)
	{
		if (!$prefAgeRange) {
			return null;
		}
		$prefAgeRange = self::getPrefAgeRange($prefAgeRange);
		$prefAgeRangeMin = $prefAgeRange[1]+1;
		$prefAgeRangeMax = $prefAgeRange[0];
		
		$min = new \DateTime();
		$max = new \DateTime();
		$min = $min->sub(date_interval_create_from_date_string($prefAgeRangeMin." year"));
		$max = $max->sub(date_interval_create_from_date_string($prefAgeRangeMax." year")); 
		
		$prefAgeRangeDate = array($min, $max);
		
		return $prefAgeRangeDate;
	}
	
	/**
	 * Gives the users prefered age range of strangers
	 * 
	 * @param string $prefAgeRange
	 * @return array|Null
	 */
	public function getPrefAgeRange($prefAgeRange)
	{
		if (!$prefAgeRange) {
			return null;
		}
		
		$prefAgeRange = explode("-", $prefAgeRange);
		
		return $prefAgeRange;
	}
	
	/**
	 * User likes stranger
	 * 
	 * @param object $stranger
	 * @return True
	 * @throws AccessDeniedException if user is not found.
	 */
	public function setLike($stranger)
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		$em = $this->container->get('doctrine')->getEntityManager();
		
		$like = new Like();
		
		$like->setUser($user);
		$like->setStranger($stranger);
		$like->setCreatedAt(new \DateTime());
		
		$em->persist($like);
		$em->flush();
		
		return true;
	}
	
	/**
	 * User nexts stranger (do not like)
	 * 
	 * @param object $stranger
	 * @return True
	 * @throws AccessDeniedException if user is not found.
	 */
	public function setNext($stranger)
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		$em = $this->container->get('doctrine')->getEntityManager();
		
		$next = new Next();
		
		$next->setUser($user);
		$next->setStranger($stranger);
		$next->setCreatedAt(new \DateTime());
		
		$em->persist($next);
		$em->flush();
		
		return true;
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
		$prefAgeRange = self::getPrefAgeRange($user->getPrefAgeRange());
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
	
	
	/**
	 * First statement (StrangerAbfrage.mm) and will exclude unwanted users in random.
	 * Further statements will be based on this return.
	 * 
	 * @return array|Null
	 * @throws AccessDeniedException if user is not found.
	 */
	public function getUserMandatory() {
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		// Get entity manager
		$em = $this->container->get('doctrine')->getEntityManager();
		$config = $em->getConfiguration();
		$config->addCustomNumericFunction('STR_TO_DATE', 'DoctrineExtensions\Query\Mysql\StrToDate');
		
		// Get users preferences
		$prefAgeRange = self::getPrefAgeRange($user->getPrefAgeRange());
		$prefAgeRangeDate = self::getPrefAgeRangeDate($user->getPrefAgeRange());
		$prefGender = self::getPrefGender($user->getPrefGender());
		
		
		// Subquery: Excludes liked users
		$liked = $em->createQueryBuilder()
		->select('ul.id')
		->from('Likeme\SystemBundle\Entity\Like', 'l')
			->innerJoin("l.stranger", "ul")
		->where('l.user = :user');
		$liked = $liked->getDQL();
		
		// Subquery: Excludes nexted users
		$nexted = $em->createQueryBuilder()
		->select('un.id')
		->from('Likeme\SystemBundle\Entity\Next', 'n')
			->innerJoin("n.stranger", "un")
		->where('n.user = :user');
		$nexted = $nexted->getDQL();
		
		
		// Build query according statement 1 (StrangerAbfrage.mm)
		$query = $em->createQueryBuilder()
		->select("u")
		->from('Likeme\SystemBundle\Entity\User', 'u')
		->where("u.id != :user")
		->andwhere("u.birthday >= :minbirthday")
		->andwhere("u.birthday <= :maxbirthday")
		->andwhere($em->createQueryBuilder()->expr()->notIn('u.id', $liked))
		->andwhere($em->createQueryBuilder()->expr()->notIn('u.id', $nexted))
		->setParameter('minbirthday', $prefAgeRangeDate[0])
		->setParameter('maxbirthday', $prefAgeRangeDate[1])
		->setParameter('user', $user->getId());
		if ($prefGender != 'both') {
			$query->andWhere("u.gender = :prefgender")
			->setParameter('prefgender', $prefGender);
		}
		$base = $query->getDQL();
		
		
		$query = $em->createQueryBuilder()
		->select("lu")
		->from('Likeme\SystemBundle\Entity\Like', 'lu')
		->where("lu.stranger = :user")
		->andwhere($em->createQueryBuilder()->expr()->In('lu.user', $base))
		->setParameter('minbirthday', $prefAgeRangeDate[0])
		->setParameter('maxbirthday', $prefAgeRangeDate[1])
		->setParameter('user', $user->getId())
		->setMaxResults(round(self::getMaxStranger()/2)); //10
		if ($prefGender != 'both') {
			$query->setParameter('prefgender', $prefGender);
		}
		$likedUser_DQL = $query->getDQL();
		$likedUser = $query->getQuery()->getResult();
		$likedUserCount = count($likedUser);
		$likedUserID = '';
		foreach($likedUser as $object) {
			if($likedUserID == '') {
				$likedUserID = $object->getUser()->getId();
			} else {
				$likedUserID = $likedUserID.", ".$object->getUser()->getId();
			}
		}
		
		// Get location from user
		$location = $user->getLocation();
		
		if(!empty($location)) {
				
			// Location range ermitteln
			$latitude = $location->getLat();
			$longitude = $location->getLon();
			
				
			// Add some functions to EntityManager
			$config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
			$config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');
			$config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
			
			
			$query = $em->createQueryBuilder()
			->select(
				'uloc,
				(acos(sin('.$latitude.'*'.pi().'/180)*sin(loc.lat*'.pi().'/180)+cos('.$latitude.'*'.pi().'/180)*cos(loc.lat*'.pi().'/180)*cos(('.$longitude.'-loc.lon)*'.pi().'/180))) as distance	
			')
			->from('LikemeSystemBundle:User', 'uloc')
				->innerJoin('uloc.location', 'loc')
			->where("uloc.id IN (".$base.")")
			->orderby('distance', 'ASC')
			->setParameter('minbirthday', $prefAgeRangeDate[0])
			->setParameter('maxbirthday', $prefAgeRangeDate[1])
			->setParameter('user', $user->getId());
			if(!empty($likedUserID)) {
				$query->andwhere("uloc.id NOT IN (".$likedUserID.")");
			}
			if ($prefGender != 'both') {
				$query->setParameter('prefgender', $prefGender);
			}
			$query->setMaxResults(round((self::getMaxStranger()-$likedUserCount)/100*75)); //min. 8
			$places = $query->getQuery()->getResult();
						
			echo 'User aus deiner N&auml;he: ';
			foreach($places as $place) {
				echo $place[0]->getFirstname() .' aus ';
				echo $place[0]->getLocation()->getPlacename() . ', ';
			}
		
		}
		
	}
}
