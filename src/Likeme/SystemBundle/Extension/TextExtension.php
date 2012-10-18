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
			'state' => new \Twig_Filter_Method($this, 'state'),
			'thumb' => new \Twig_Filter_Method($this, 'thumb')
		);
	}
	
	public function getGlobals()
	{
		return array(
			//'user_pictures' => self::user_pictures()
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
	 * Get thumbnail src from original src
	 * @param string $src
	 * @return string
	 */
	public function thumb($src)
	{
		$thumb_src = substr_replace($src, 'thumbnails/', strlen('http://likeme.s3.amazonaws.com/'), 0)."?".date('ymdHi');
		return $thumb_src;
	}
	
}
