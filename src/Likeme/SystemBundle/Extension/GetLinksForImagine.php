<?php
namespace Likeme\SystemBundle\Extension;

class GetLinksForImagine 
{
	public function editLinks($picturearray) {
		// Edit picture paths for LiipImagineBundle
		$i = 0;
		
		foreach($picturearray as $picture) {
			foreach($picture as $key => $value) {			
				$picturearray[$i]['thumb'] = str_replace("http://likeme.s3.amazonaws.com","",$value);
			}
			$i++;
		}
		
		return $picturearray;
	}
}
