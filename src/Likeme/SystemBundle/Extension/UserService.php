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
	
	/**
	 * Sets max allowed strangers to show (db queries!)
	 * 
	 * @return integer
	 */
	public function getDailyLikeCount()
	{
		$i=20;
		return $i;
	}
	/**
	 * Get daily limit for current User
	 * 
	 * @throws AccessDeniedException if user is not found.
	 * @return integer
	 */
	public function getMaxStranger()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		$i = self::getDailyLikeCount();
		
		$i = $i - $user->getStrangerLimitExact();
		
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
	
	
	/**
	 * First statement (StrangerAbfrage.mm) and will exclude unwanted users in random.
	 * Further statements will be based on this return.
	 * 
	 * @return array|Null
	 * @throws AccessDeniedException if user is not found.
	 */
	public function getStranger() {
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		// Get entity manager
		$em = $this->container->get('doctrine')->getEntityManager();
		
		// Get users preferences
		$prefAgeRangeDate = self::getPrefAgeRangeDate($user->getPrefAgeRange());
		$prefGender = self::getPrefGender($user->getPrefGender());
		
		
		/*
		 * Build subquery (preferences) according statement 1 (StrangerAbfrage.mm)
		 */
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
		
		
		/*
		 *  Build query according statement 1 (StrangerAbfrage.mm)
		 */
		$query = $em->createQueryBuilder()
		->select("u")
		->from('Likeme\SystemBundle\Entity\User', 'u')
		->where("u.id != :user")
		->andwhere("u.active = 1")
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
		
		
		/*
		 *  Build query according statement 2 (StrangerAbfrage.mm)
		 */
		$query = $em->createQueryBuilder()
		->select("luser.id")
		->from('Likeme\SystemBundle\Entity\Like', 'lu')
			->leftJoin('lu.user', 'luser')
		->where("lu.stranger = :user")
		->andwhere($em->createQueryBuilder()->expr()->In('lu.user', $base))
		->setParameter('minbirthday', $prefAgeRangeDate[0])
		->setParameter('maxbirthday', $prefAgeRangeDate[1])
		->setParameter('user', $user->getId())
		->setMaxResults(floor(self::getMaxStranger()/2)); //10
		if ($prefGender != 'both') {
			$query->setParameter('prefgender', $prefGender);
		}
		$likers = $query->getQuery()->getResult();	
		$likedUserCount = count($likers);
		$likedUserID = '';
		$likedUser = array();
		$i = 0;
		foreach($likers as $object) {
			if($likedUserID == '') {
				$likedUserID = $object['id'];
			} else {
				$likedUserID = $likedUserID.", ".$object['id'];;
			}
			
			if(is_array($object)) {
				$likedUser[$i] = $object['id'];
			}
			$i++;
		}
		
		
		// Get location from user
		$location = $user->getLocation();
		
		if(!empty($location)) {
				
			// Location range ermitteln
			$latitude = $location->getLat();
			$longitude = $location->getLon();
			
				
			// Add some functions to EntityManager
			$config = $em->getConfiguration();
			$config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
			$config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');
			$config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');
			
			/*
			 *  Build query according statement 3 (StrangerAbfrage.mm)
			 */
			$query = $em->createQueryBuilder()
			->select(
				'uloc.id,
				(acos(sin('.$latitude.'*'.pi().'/180)*sin(loc.lat*'.pi().'/180)+cos('.$latitude.'*'.pi().'/180)*cos(loc.lat*'.pi().'/180)*cos(('.$longitude.'-loc.lon)*'.pi().'/180))) as distance	
			')
			->from('LikemeSystemBundle:User', 'uloc')
				->leftJoin('uloc.location', 'loc')
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
			$placeUserCount = count($places);
			$placeUserID = '';
			$placeUser = array();
			$i = 0;
			foreach($places as $object) {
				if($placeUserID == '') {
					$placeUserID = $object['id'];
				} else {
					$placeUserID = $placeUserID.", ".$object['id'];
				}
				
				if(is_array($object)) {
					$placeUser[$i] = $object['id'];
				}
				$i++;
			}
			/*
			 *  Build query according statement 4 (StrangerAbfrage.mm)
			 */
			$query = $em->createQueryBuilder()
			->select("ru.id")
			->from('LikemeSystemBundle:User', 'ru')
			->where("ru.id IN (".$base.")")
			->setParameter('minbirthday', $prefAgeRangeDate[0])
			->setParameter('maxbirthday', $prefAgeRangeDate[1])
			->setParameter('user', $user->getId())
			->setMaxResults(self::getMaxStranger()-$likedUserCount-$placeUserCount); //Rest bis max.
			if(!empty($likedUserID)) {
				$query->andwhere("ru.id NOT IN (".$likedUserID.")");
			}
			if(!empty($placeUserID)) {
				$query->andwhere("ru.id NOT IN (".$placeUserID.")");
			}
			if ($prefGender != 'both') {
				$query->setParameter('prefgender', $prefGender);
			}
			$rests = $query->getQuery()->getResult();
			
			$restUser = array();
			$i = 0;
			foreach($rests as $object) {				
				if(is_array($object)) {
					$restUser[$i] = $object['id'];
				}
				$i++;
			}
		
		} else {
			
			/*
			 *  Build query according statement 5 (StrangerAbfrage.mm)
			*/
			$query = $em->createQueryBuilder()
			->select("nlu.id")
			->from('LikemeSystemBundle:User', 'nlu')
			->where("nlu.id IN (".$base.")")
			->setParameter('minbirthday', $prefAgeRangeDate[0])
			->setParameter('maxbirthday', $prefAgeRangeDate[1])
			->setParameter('user', $user->getId())
			->setMaxResults(round(self::getMaxStranger()-$likedUserCount)); //Rest bis max.
			if(!empty($likedUserID)) {
				$query->andwhere("nlu.id NOT IN (".$likedUserID.")");
			}
			if ($prefGender != 'both') {
				$query->setParameter('prefgender', $prefGender);
			}
			$rests = $query->getQuery()->getResult();
			
			$restUser = array();
			$i = 0;
			foreach($rests as $object) {
				if(is_array($object)) {
					$restUser[$i] = $object['id'];
				}
				$i++;
			}
		}
		
		// Finally return the stranger array
		if(isset($places)) {			
			return self::mergeStranger($restUser, $likedUser, $placeUser);
		} else {
			return self::mergeStranger($restUser, $likedUser);
		}
	}
	
	/**
	 * Merge every statement from getStranger() and mix
	 * 
	 * @param array $liked
	 * @param array $rest
	 * @param array $place = null
	 * @return array|null
	 */
	public function mergeStranger($rest = null, $liked = null, $place = null)
	{
		
		// Merge all statements together
		if (empty($place) && empty($liked)) {
			$array = array($rest);
		} elseif (empty($liked) && empty($rest)){
			$array = array($place);
		} elseif (empty($place) && empty($rest)){
			$array = array($liked);
		} elseif (empty($place)) {
			$array = array($liked, $rest);
		} elseif(empty($liked)) {
			$array = array($place, $rest);
		} elseif(empty($rest)) {
			$array = array($place, $liked);
		} else {
			$array = array($liked, $place, $rest);
		}
		
		$userarry = array();
		$i = 0;
		foreach($array as $stranger) {
			foreach ($stranger as $strangernew) {
				$userarry[$i] = $strangernew;
				$i++;
			}
		}
		
		// Shuffle array()
		shuffle($userarry);
		
		return $userarry;
	}
	
	/**
	 * Check if daily limit for user is already reached
	 * 
	 * @throws AccessDeniedException if user is not found.
	 * @return true|false
	 */
	public function getLimitReached()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}

		if($user->getStrangerLimitExact() == self::getMaxStranger()) {
			return true;
		} else {
			return false;
		}
	}

}
