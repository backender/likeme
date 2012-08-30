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
		$location = explode(",", $location);

		$em = $this->container->get('doctrine')->getEntityManager();

		// Get saved profile pictures
		$query = $em->createQueryBuilder()
				->from('Likeme\SystemBundle\Entity\Location', 'l')
				->select("l.id, l.postalcode, l.placename, l.statecode")
				->where("l.placename = :location")
				->setParameter('location', $location[0]);
		;

		$location_complete = $query->getQuery()->getResult();

		if ($location_complete !== array()) {
			if (count($location_complete) > 1) {
				$location_double = count($location_complete);
				return false;
			} else {
				return $location_complete[0]['id'];
			}
		} else {
			return false;
		}

		return false;

	}

}
