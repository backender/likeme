<?php
namespace Likeme\SystemBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class FacebookLocation implements ContainerAwareInterface {
	
	private $container;
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}

	public function locationByFacebook($location) {
		//Common FB format is "city, region" so let's split
		if (strpos($location, ",")) {
			$location = explode(",", $location);
			$state = str_replace(' ','',$location[1]);
			$location = $location[0];
		}
		
		$em = $this->container->get('doctrine')->getEntityManager();
	
		// Get saved profile pictures
		$query = $em->createQueryBuilder()
		->from('Likeme\SystemBundle\Entity\Location', 'l')
		->select("l.id, l.postalcode, l.placename, l.statecode, l.state")
		->where("l.placename like :location")
		->setParameter('location', $location.'%');
		;
	
		$location_complete = $query->getQuery()->getResult();
		
		if (!empty($location_complete)) {
			if (count($location_complete) > 1) {
				
				/*
				 * Check if there's just 1 place that fit exaclty
				 */
				$location_exact = array();
				foreach ($location_complete as $key => $value) {
					
					$placename_select = $value['placename'];
					if ($location == $placename_select) {
						array_push($location_exact, $value['id']);
					}
				}
				if (count($location_exact) == 1) {
					return (int)implode($location_exact);
				}
				
				
				/*
				 * check if state concur
				 * example: same placename in different state (Bremgarten AG, Bremgarten BE)
				 */
				//first of all we check for space in lcoation name, otherwise $state=country!
				if(strpos($location, " ")) { //we should check here as well with coutry database -> && $state !== "Switzerland"
					if(!empty($state)) {
						$location_dif_state = $location_complete;
						foreach ($location_dif_state as $key => $value) {
							//mostly we have kanton kantonname (space between)
							$state_select = $value['state'];
							if (strpos($state_select, " ")) {
								$state_select = explode(" ", $state_select);
								$state_select = $state_select[1];
							}
							//if state doesn't fit -> unset
							if ($state_select !== $state) {
								unset($location_dif_state[$key]);
							}
						}
					}
					if (count($location_dif_state) == 1) {
						return (int)$location_dif_state[0]['id'];
					}
					
				}

				// TODO: Define default
				return false; //Zurich, Basel and all this stuff!
			
			} else {
				return $location_complete[0]['id'];
			}
		} else {
			return false;
		}
		
		return false;
	
	}

}
