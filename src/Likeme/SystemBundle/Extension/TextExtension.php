<?php
namespace Likeme\SystemBundle\Extension;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use FOS\UserBundle\Model\UserInterface;

class TextExtension extends \Twig_Extension implements ContainerAwareInterface
{
	public function getName()
	{
		return 'text_twig_extension';
	}
	
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function getFilters()
	{
		return array(
			'truncate' => new \Twig_Filter_Method($this, 'truncate'),
			'comment_date' => new \Twig_Filter_Method($this, 'comment_date'),
			'age' => new \Twig_Filter_Method($this, 'age'),
			'fb_date' => new \Twig_Filter_Method($this, 'fb_date'),
			'state' => new \Twig_Filter_Method($this, 'state')
		);
	}
	
	public function getGlobals()
	{
		return array(
			'user_pictures' => self::user_pictures()
		);
	}
	
	public function truncate($text, $limit = 150)
	{
		if(mb_strlen($text, 'UTF-8') > $limit) {
			$text = mb_strcut($text, 0, $limit, 'UTF-8');
			$lastWhitespace = mb_strrpos($text, ' ', 0, 'UTF-8');
			$text = mb_strcut($text, 0, $lastWhitespace, 'UTF-8') . '...';
		}
		
		return $text;
		
	}
	
	public function comment_date(\DateTime $datetime)
	{		
		$yesterday = new \DateTime('yesterday');		
		$date = $datetime->format('d.m.Y');
		$time = $datetime->format('H:i');
		
		if($datetime > $yesterday)
			$date = 'Heute';
	
		return "{$date} at {$time}";;
	}
	
	public function age($birthDate)
	{
		$date = $birthDate;
 		$now = new \DateTime();
 		$interval = $now->diff($date);
 		$age = $interval->y;
		
 		return $age;
	}
	
	public function fb_date($date)
	{
		$date = $date->format("d.m.Y");
		return $date;
	}
	
	public function state($state)
	{
		$state = explode(" ", $state);
		return $state[1];
	}
	
	
	/**
	 * Get all images from user
	 * 
	 * @throws AccessDeniedException
	 * @return array|false
	 */
	public function user_pictures()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
		
		$em = $this->container->get('doctrine')->getEntityManager();
			
		// Get Pictures
		$query = $em->createQueryBuilder()
		->from('Likeme\SystemBundle\Entity\Pictures', 'p')
		->select("p.id, p.src, p.position")
		->where("p.user = :userid AND p.type = :type")
		->setParameter('userid', $user->getId())
		->setParameter('type', 'original')
		->orderBy('p.position', 'ASC');
			
		$allpictures = $query->getQuery()->getResult();
			
		// Edit picture links for LiipImagineBundle
		$imagineservice = $this->container->get('likeme.liipimaginebundle.getlinks');
		$imaginelinks = $imagineservice->editLinksForDisplay($allpictures);
			
		if(!empty($imaginelinks)) {
			return $imaginelinks;
		}
		
		return false;
	}
	
	
	/**
	 * Get all images from stranger
	 * 
	 * @param object $stranger
	 * @return array|false
	 */
	public function stranger_pictures($stranger)
	{
			$em = $this->container->get('doctrine')->getEntityManager();
				
			// Get Pictures
			$query = $em->createQueryBuilder()
			->from('Likeme\SystemBundle\Entity\Pictures', 'p')
			->select("p.id, p.src, p.position")
			->where("p.user = :userid AND p.type = :type")
			->setParameter('userid', $stranger->getId())
			->setParameter('type', 'original')
			->orderBy('p.position', 'ASC');
			
			$allpictures = $query->getQuery()->getResult();
				
			// Edit picture links for LiipImagineBundle
			$imagineservice = $this->container->get('likeme.liipimaginebundle.getlinks');
			$imaginelinks = $imagineservice->editLinksForDisplay($allpictures);

			if(!empty($imaginelinks)) {
				return $imaginelinks;
			}
			
			return false;
	}
}
