<?php

namespace Likeme\SystemBundle\Controller;

use Likeme\SystemBundle\Extension\FacebookToken;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LocationController extends Controller {
	
	
	/**
	 * @Route("/location/get/{input}", name="location_get", options={"expose"=true})
	 */
	public function getLocationAction($input) {
		if (is_numeric($input)){
			return self::locationByPostalcodeAction($input);
		} else {
			return self::locationByNameAction($input);
		}
	}

	/**
	 * @Route("/location/name/{name}", name="location_by_name", options={"expose"=true})
	 */
	public function locationByNameAction($name) {
		
		$em = $this->container->get('doctrine')->getEntityManager();

		// Get saved profile pictures
		$query = $em->createQueryBuilder()
		->from('Likeme\SystemBundle\Entity\Location', 'l')
		->select("l.id, l.postalcode, l.placename, l.statecode")
		->where("l.placename like :name")
		->setParameter('name', $name.'%');
		;
		
		$location_complete = $query->getQuery()->getResult();
		
		return new Response(json_encode($location_complete));
	
	}
	
	
	/**
	 * @Route("/location/id/{id}", name="location_by_id", options={"expose"=true})
	 */
	public function locationByIdAction($id) {
	
		$em = $this->container->get('doctrine')->getEntityManager();
	
		// Get saved profile pictures
		$query = $em->createQueryBuilder()
		->from('Likeme\SystemBundle\Entity\Location', 'l')
		->select("l.id, l.postalcode, l.placename, l.statecode")
		->where("l.id = :id")
		->setParameter('id', $id);
		;
	
		$location_complete = $query->getQuery()->getResult();
	
		return new Response(json_encode($location_complete));
	
	}
	
	/**
	 * @Route("/location/postalcode/{postalcode}", name="location_by_postalcode", options={"expose"=true})
	 */
	public function locationByPostalcodeAction($postalcode) {
	
		$em = $this->container->get('doctrine')->getEntityManager();
	
		// Get saved profile pictures
		$query = $em->createQueryBuilder()
		->from('Likeme\SystemBundle\Entity\Location', 'l')
		->select("l.id, l.postalcode, l.placename, l.statecode")
		->where("l.postalcode like :postalcode")
		->setParameter('postalcode', $postalcode.'%');
		;
	
		$location_complete = $query->getQuery()->getResult();
	
		return new Response(json_encode($location_complete));
	
	}
	
	
	/**
	 * @Route("/location/facebook/{location}", name="location_by_facebook", options={"expose"=true})
	 */
	public function locationByFacebookAction($location) {
		
		//this is more or less just for debugging in browser
		$facebook_location = $this->container->get("likeme.facebook.location");
		$locationByFacebook = $facebook_location->locationByFacebook($location);
		
		return $locationByFacebook;
	
	}
	
}
