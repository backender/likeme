<?php

namespace likeme\SpamBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use likeme\SpamBundle\Entity\Spam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('likemeSpamBundle:Default:index.html.twig', array('name' => $name));
    }
	
	public function newSpamAction(Request $request)
    {
    	$spam = new Spam();
    	$spam->setprofileIdFrom('1');
    	$spam->setprofileIdTo('2');
    	$spam->settimestamp(new \DateTime());
     	
     	$form = $this->createFormBuilder($spam)
     		->add('profileIdFrom', 'text')
     		->add('profileIdTo', 'text')
     		->add('reason', 'text')
     		->getForm();
     	
     	$validator = $this->get('validator');
     	$errors = $validator->validate($spam);
     	
     	if ($request->getMethod() == 'POST') {
     		$form->bindRequest($request);
     	
     		if ($form->isValid()) {
     			$em = $this->getDoctrine()->getEntityManager();
   				$em->persist($spam);
    			$em->flush();
    			return new Response('Eintrag mit ID #'.$spam->getId().'<br />'.$spam->getReason());
     			//return $this->redirect($this->generateUrl('task_success'));
     		}
     	}
     	return $this->render('likemeSpamBundle:Default:newSpam.html.twig', array('form' => $form->createView()));
    }
    
    public function listSpamAction()
    {
    	$spam = $this->getDoctrine()
    		->getRepository('likemeSpamBundle:Spam')
    		->findAll();

    	if (!$spam) {
    		throw $this->createNotFoundException('No spam found');
    	}
    	return $this->render('likemeSpamBundle:Default:listSpam.html.twig', array('spam' => $spam)); 
    }
    
}
