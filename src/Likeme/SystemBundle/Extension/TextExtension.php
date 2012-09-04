<?php
namespace Likeme\SystemBundle\Extension;

class TextExtension extends \Twig_Extension
{
	public function getName()
	{
		return 'text_twig_extension';
	}
	
	public function getFilters()
	{
		return array(
			'truncate' => new \Twig_Filter_Method($this, 'truncate'),
			'comment_date' => new \Twig_Filter_Method($this, 'comment_date'),
			'age' => new \Twig_Filter_Method($this, 'age'),
			'fb_date' => new \Twig_Filter_Method($this, 'fb_date')
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
		//explode the date to get month, day and year (facebook standard)
		$birthDate = explode("/", $birthDate);
		//get age from date or birthdate
		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y")-$birthDate[2])-1):(date("Y")-$birthDate[2]));
		return $age;
	}
	
	public function fb_date($date)
	{
		$date = explode("/", $date);
		$date = $date[1].".".$date[0].".".$date[2];
		return $date;
	}
}
