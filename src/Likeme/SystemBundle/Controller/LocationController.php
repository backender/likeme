<?php

namespace Likeme\SystemBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LocationController extends Controller {
	


	/**
	 * @Route("/location/getlocation/{startlocation}", name="getlocation")
	 */
	public function placeCompleteAction($startlocation) {
		
		$em = $this->container->get('doctrine')->getEntityManager();

		// Get saved profile pictures
		$query = $em->createQueryBuilder()
		->from('Likeme\SystemBundle\Entity\Location', 'l')
		->select("l.id, l.postalcode, l.placename, l.statecode")
		->where("l.placename like :startlocation")
		->setParameter('startlocation', $startlocation.'%');
		;
		
		$location_complete = $query->getQuery()->getResult();
		
		return new Response(json_encode($location_complete));
	
	}
	
	
	/**
	 * @Route("/location/getlocationbyid/{id}", name="getlocationbyid")
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

}
