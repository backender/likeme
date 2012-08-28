<?php

namespace Likeme\SystemBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LocationController extends Controller {
	
	
	/**
	 * @Route("/location/get/{input}", name="location_get")
	 */
	public function getLocationAction($input) {
		if (is_numeric($input)){
			return self::locationByPostalcodeAction($input);
		} else {
			return self::locationByNameAction($input);
		}
	}

	/**
	 * @Route("/location/name/{name}", name="location_by_name")
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
	 * @Route("/location/id/{id}", name="location_by_id")
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
	 * @Route("/location/postalcode/{postalcode}", name="location_by_postalcode")
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

}
