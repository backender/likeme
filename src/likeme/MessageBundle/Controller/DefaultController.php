<?php

namespace likeme\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use likeme\MessageBundle\Entity\messages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('likemeMessageBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function newMessageAction(Request $request)
    {
    	$message = new messages();
    	$message->setprofileIdFrom('1');
    	$message->setprofileIdTo('2');
    	$message->settimestamp(new \DateTime());
     	
     	$form = $this->createFormBuilder($message)
     		->add('profileIdFrom', 'text')
     		->add('profileIdTo', 'text')
     		->add('message', 'text')
     		->getForm();
     	
     	$validator = $this->get('validator');
     	$errors = $validator->validate($message);
     	
     	if ($request->getMethod() == 'POST') {
     		$form->bindRequest($request);
     	
     		if ($form->isValid()) {
     			$em = $this->getDoctrine()->getEntityManager();
   				$em->persist($message);
    			$em->flush();
    			//return new Response('Eintrag mit ID #'.$spam->getId().'<br />'.$spam->getReason());
     			return $this->redirect($this->generateUrl('_listMessage'));
     		}
     	}
     	return $this->render('likemeMessageBundle:Default:newMessage.html.twig', array('form' => $form->createView()));
    }
    
    public function listMessageAction()
    {
    	$spam = $this->getDoctrine()
    		->getRepository('likemeMessageBundle:Spam')
    		->findAll();

    	if (!$spam) {
    		throw $this->createNotFoundException('No spam found');
    	}
    	return $this->render('likemeMessageBundle:Default:listMessage.html.twig', array('spam' => $spam)); 
    }
    
    
}
