<?php

namespace likeme\SpamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use likeme\SpamBundle\Entity\Spam;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('likemeSpamBundle:Default:index.html.twig', array('name' => $name));
    }
	
	public function newSpamAction()
    {
    	$spam = new Spam();
    	$spam->setprofileIdFrom('1');
    	$spam->setprofileIdTo('2');
     	
     	$form = $this->createFormBuilder($spam)
     		->add('profileIdFrom', 'text')
     		->add('profileIdTo', 'text')
     		->add('reason', 'text')
     		->getForm();
     	$validator = $this->get('validator');
     	$errors = $validator->validate($spam);
     	
     	if (count($errors) > 0) {
     		return $this->render('likemeSpamBundle:Default:newSpam.html.twig', array('form' => $form->createView(), 'errors' => $errors));
     	} else {
     		return $this->render('likemeSpamBundle:Default:newSpam.html.twig', array('form' => $form->createView()));
     	}
     	
        //return $this->render('likemeSpamBundle:Default:newSpam.html.twig', array('name' => $name));
    }
}
