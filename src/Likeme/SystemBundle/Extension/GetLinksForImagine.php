<?php
namespace Likeme\SystemBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class GetLinksForImagine implements ContainerAwareInterface
{
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
	
	public function editLinksForGeneration($picturearray) {
		// Edit picture paths for LiipImagineBundle
		$i = 0;
		
		foreach($picturearray as $picture) {
			foreach($picture as $key => $value) {			
				$picturearray[$i]['thumb'] = str_replace("http://likeme.s3.amazonaws.com/","",$value);
			}
			$i++;
		}
		
		return $picturearray;
	}
	
	public function editLinksForDisplay($picturearray, $cropped) {
		// Edit picture paths for LiipImagineBundle
		$i = 0;
	
		foreach($picturearray as $picture) {
			foreach($picture as $key => $value) {
				$session = $this->container->get('session');
				$cropped = $session->get('cropped');
				
				if ($cropped == true) {
					$picturearray[$i]['thumb'] = substr_replace($value, 'thumbnails/', strlen('http://likeme.s3.amazonaws.com/'), 0)."?time=".date('ymdHi');
					$session->set('cropped', false);
					echo 'Bilder neu geladen';
				} else {
					$picturearray[$i]['thumb'] = substr_replace($value, 'thumbnails/', strlen('http://likeme.s3.amazonaws.com/'), 0);
					echo 'Bilder aus cache';
				}	
			}
			$i++;
		}
	
		return $picturearray;
	}
}
